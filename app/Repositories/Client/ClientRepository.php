<?php

namespace App\Repositories\Client;

use App\Models\Client;
use App\Models\ClientFile;
use App\Models\ClientMessage;
use App\Models\ClientMessageArgument;
use App\Models\ClientRules;
use App\Models\Property;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    protected $model;
    protected $client_rules;
    protected $client_files;

    public function __construct(Client $model, ClientRules $client_rules, ClientFile $client_files)
    {
        parent::__construct($model);
        $this->client_rules = $client_rules;
        $this->client_files = $client_files;
    }


    //?name=&lastname=&phone=&email

    public function getDataTable()
    {

        $query = $this->model->latest();

        if (request()->filled('name')) {
            $name = request()->input('name');
            $query->where('name', 'like', '%' . $name . '%');
        }

        if (request()->filled('lastname')) {
            $lastname = request()->input('lastname');
            $query->where('lastname', 'like', '%' . $lastname . '%');
        }

        if (request()->filled('phone')) {
            $phone = request()->input('phone');
            $query->where(function ($q) use ($phone) {
                $q->where('phone', 'like', '%' . $phone . '%')
                    ->orWhere('phone2', 'like', '%' . $phone . '%');
            });
        }

        if (request()->filled('email')) {
            $email = request()->input('email');
            $query->where(function ($q) use ($email) {
                $q->where('mail', 'like', '%' . $email . '%')
                    ->orWhere('mail2', 'like', '%' . $email . '%');
            });
        }

        $list = $query->get();
        return Datatables::of($list)
            ->addColumn('name', function ($row) {
                return '<a href="' . route('admin.crm.clients.show', $row) . '">' . $row->name . '</a>';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.crm.client.actions', ['row' => $row]);
            })
            ->editColumn('created_at', function ($row) {
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $now = Carbon::now()->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();

                if ($date >= $now) {
                    return '<span>' . $date . '</span>';
                } else {
                    return '<span>' . $date . '</span><div class="form-text mt-0">' . $diffForHumans . '</div>';
                }
            })
            ->rawColumns(['name', 'actions', 'created_at'])
            ->make();
    }

    public function getUserRodo($client, $attributes = null): object
    {
        return $this->client_rules->where('client_id', $client->id)
            ->when(isset($attributes['status']), function ($query) use ($attributes) {
                $query->where('status', $attributes['status']);
            })
            ->get();
    }

    public function getUserFiles($client): object
    {
        return $this->client_files->where('client_id', $client->id)
            ->when($user_id = auth()->id(), function ($query) use ($user_id) {
                $query->where("user_id", $user_id);
            })
            ->get(['id', 'user_id', 'name', 'description', 'file', 'mime', 'size', 'created_at', 'updated_at']);
    }

    /**
     * Zakłada albo aktualizuje klienta (po e-mailu) i zapisuje jego wiadomość.
     *
     * Wołane z: formularza na stronie (Request), panelu (Request), API oraz zadania ProcessLeads (tablica).
     * Zgody RODO zapisuje ClientObserver na podstawie pól `rule_{id}` z bieżącego żądania.
     *
     * Wzorzec: poligonowa (2026-08) - bez UTM, ścieżki kampanii i przypisywania handlowca.
     */
    public function createClient($attributes, $property = null, $status = 1, $source = null)
    {
        $email = trim((string) ($attributes['email'] ?? $attributes['mail'] ?? ''));
        $lastname = $attributes['lastname'] ?? $attributes['surname'] ?? null;

        // Formularz nie wymaga e-maila (wymagany jest telefon), a clients.mail jest NOT NULL i służy do
        // rozpoznania klienta. Bez e-maila - adres zastępczy, oznaczony flagą, na który niczego nie wysyłamy.
        $isRandomEmail = $email === '';
        if ($isRandomEmail) {
            $email = 'noemail_' . Str::uuid() . '@example.com';
        }

        $client = null;

        try {
            $client = $this->model->firstOrNew(['mail' => $email]);

            $client->phone = $attributes['phone'] ?? $client->phone;
            $client->name = $attributes['name'];
            if ($lastname) {
                $client->lastname = $lastname;
            }
            $client->status = $status;
            $client->is_random_email = $isRandomEmail;
            if (!$client->exists) {
                $client->created_at = now();
            }
            $client->updated_at = now();

            // save() odpala ClientObserver (created / updated) - tam zapis zgód RODO
            $client->save();

            Log::info(($client->wasRecentlyCreated ? 'Client was created: ' : 'Client was updated: ') . $client->id);
        } catch (\Exception $e) {
            Log::error('Error during createClient: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return null;
        }

        if (!empty($attributes['message'])) {
            $ip = $attributes['ip'] ?? null;
            if (!$ip && $attributes instanceof Request) {
                $ip = $attributes->ip();
            }

            $msg = ClientMessage::create([
                'client_id' => $client->id,
                // 0, nie NULL - Skrzynka leadów szuka wiadomości bez opiekuna po user_id = 0
                'user_id' => 0,
                'message' => $attributes['message'],
                'ip' => $ip ?? '127.0.0.1',
                'source' => $source ?? ($attributes['page'] ?? 'Formularz kontaktowy'),
            ]);

            $arguments = [];

            // Formularz przy lokalu: dane bierzemy z modelu, nie z formularza (nie da się ich podmienić w przeglądarce)
            if ($property) {
                $arguments = [
                    'investment_id' => $property->investment_id,
                    'building_id' => $property->building_id,
                    'floor_id' => $property->floor_id,
                    'property_id' => $property->id,
                    'rooms' => $property->rooms,
                    'area' => $property->area,
                ];
            }

            // Formularz przy inwestycji (bez lokalu)
            if (!isset($arguments['investment_id']) && !empty($attributes['investment_id'])) {
                $arguments['investment_id'] = (int) $attributes['investment_id'];
            }

            // Leady z portali (ProcessLeads)
            if ($source && isset($attributes['is_external_source'])) {
                $arguments['is_external'] = $attributes['is_external_source'];
            }
            if (!empty($attributes['investment_name'])) {
                $arguments['investment_name'] = $attributes['investment_name'];
            }
            if (!empty($attributes['property_name'])) {
                $arguments['property_name'] = $attributes['property_name'];
            }

            if (!empty($arguments)) {
                $msg->arguments = json_encode($arguments);
                $msg->save();
            }
        } else {
            ClientMessage::create([
                'client_id' => $client->id,
                'user_id' => 0,
                'message' => 'Klient dodany w systemie',
                'ip' => '127.0.0.1',
                'source' => 'Formularz w systemie',
            ]);
        }

        return $client;
    }
}
