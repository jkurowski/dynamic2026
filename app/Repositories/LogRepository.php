<?php namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class LogRepository
{
    public function getDataTable(User $user = null, string $minDate = null, string $maxDate = null){

        $query = Activity::orderByDesc("id");

        if ($user) {
            $query->where('causer_id', $user->id);
        }

        if ($minDate) {
            $minDateCarbon = Carbon::parse($minDate)->startOfDay();
            $query->where('created_at', '>=', $minDateCarbon);
        }

        if ($maxDate) {
            $maxDateCarbon = Carbon::parse($maxDate)->endOfDay();
            $query->where('created_at', '<=', $maxDateCarbon);
        }

        $list = $query->get();

        return Datatables::of($list)
            ->editColumn('name', function ($row) {
                $causerType = $row->causer_type;
                if ($causerType === 'App\Models\User') {
                    return '<span data-filter="'. $row->causer->email .'">'
                        . $row->causer->name
                        . '<br>' . $row->causer->email
                        . '<br><strong>Typ: Użytkownik</strong></span>';
                } elseif ($causerType === 'App\Models\Client') {
                    return '<span data-filter="'. $row->causer->mail .'">'
                        . $row->causer->name.' - <a href="'.route('admin.crm.clients.show', $row->causer->id).'">Zobacz profil</a>'
                        . '<br>' . $row->causer->mail
                        . '<br><strong>Rola: Klient</strong></span>';
                } else {
                    return '<span data-filter="">-</span>';
                }
            })
            ->editColumn('method', function ($row){
                return '<span data-filter="'. $row->properties['methodType'].'"><div class="badge badge-method badge-method-'.strtolower($row->properties['methodType']).'">'. $row->properties['methodType'].'</div></span>';
            })
            ->editColumn('route', function ($row){
                return $row->properties['route'];
            })
            ->editColumn('referer', function ($row){
                return $row->properties['referer'];
            })
            ->editColumn('ip', function ($row){
                return $row->properties['ipAddress'];
            })
            ->editColumn('created_at', function ($row){
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $now = Carbon::now()->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();

                if($date >= $now){
                    return '<span>'.$date.'</span>';
                } else {
                    return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
                }
            })
            ->rawColumns(['method', 'name', 'created_at'])
            ->make();
    }
}
