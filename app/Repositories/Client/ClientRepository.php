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

    public function createClient($attributes, $property = null, $status = 1, $source = null)
    {
        Log::info('Call createClient');

        $utm_array = []; // Initialize as an empty array

        if (isset($attributes['cookie']) && is_array($attributes['cookie'])) {
            $utm_array = array_filter($attributes->cookie()); // Set only if cookies exist
            unset($utm_array['XSRF-TOKEN'], $utm_array['laravel_session']);
        }

        Log::info('Request: ' . $attributes['email']);
        Log::info('Request: ' . $attributes['phone']);
        Log::info('Request: ' . $attributes['name']);
        Log::info('Request: ' . $status);

        try {
            // Additional logging before updateOrCreate
            Log::info('Attempting to updateOrCreate client');

            //            $client = $this->model->updateOrCreate(
            //                ['mail' => $attributes['email']],
            //                [
            //                    'phone' => $attributes['phone'] ?? NULL,
            //                    'name' => $attributes['name'],
            //                    'status' => $status,
            //                    'updated_at' => now()
            //                ]
            //            );

            // Find the record by email or create a new instance
            $client = $this->model->firstOrNew(['mail' => $attributes['email']]);

            // Check if the client already exists
            if ($client->exists) {
                // Client exists, update attributes
                $client->phone = $attributes['phone'] ?? null;
                $client->name = $attributes['name'];
                $client->status = $status;
                $client->updated_at = now();

                // Save and trigger the 'updated' event
                $client->save();
            } else {
                // Client does not exist, set attributes
                $client->phone = $attributes['phone'] ?? null;
                $client->name = $attributes['name'];
                $client->status = $status;
                $client->created_at = now(); // Optional: set created_at manually if needed
                $client->updated_at = now();

                // Save and trigger the 'created' event
                $client->save();
            }

            if ($client->wasRecentlyCreated) {
                Log::info('Client was created: ' . $client->id);
            } else {
                Log::info('Client was updated: ' . $client->id);
            }
        } catch (\Exception $e) {
            Log::error('Error during updateOrCreate: ' . $e->getMessage());
            Log::error('Error during updateOrCreate: ' . $e->getTraceAsString());
        }

        if (isset($attributes['message']) && $client->id) {

            //$source = strtok($attributes->headers->get('referer'), '?');

            $msg = ClientMessage::create([
                'client_id' => $client->id,
                'message' => $attributes['message'],
                'ip' => $attributes['ip'] ?   $attributes['ip'] : $attributes->ip(),
                'source' => $source ?? $attributes['page'],
            ]);

            $arguments = [];
            if ($property) {
                $propertyMappings = [
                    'investment_id' => $property->investment_id,
                    'building_id' => $property->building_id,
                    'floor_id' => $property->floor_id,
                    'property_id' => $property->id,
                    'rooms' => $property->rooms,
                    'area' => $property->area,
                ];
                $arguments = array_merge($propertyMappings, $utm_array);
            }

            if ($source && isset($attributes['is_external_source'])) {
                $arguments['is_external'] = $attributes['is_external_source'];
            }

            if (isset($attributes['investment_id']) && isset($attributes['investment_name'])) {
                $arguments = array_merge(
                    $arguments,
                    ['investment_id' => $attributes['investment_id']],
                    ['investment_name' => $attributes['investment_name']]
                );
            }

            if (isset($attributes['property_name'])) {
                $arguments = array_merge($arguments, ['property_name' => $attributes['property_name']]);
            }

            if (!empty($arguments)) {
                $msg->arguments = json_encode($arguments);
            }

            $msg->save();
        } else {
            $msg = ClientMessage::create([
                'client_id' => $client->id,
                'message' => 'Klient dodany w systemie',
                'ip' => '127.0.0.1',
                'source' => 'Formularz w systemie',
            ]);

            $msg->save();
        }

        return $client;
    }
}
