<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

// CMS
use App\Models\Investment;

class CheckInvestmentPermission
{
    public function handle($request, Closure $next)
    {
        $investment = $request->route('investment');

        if ($investment instanceof Investment) {
            $investmentId = $investment->id;
        } elseif (is_int($investment)) {
            $investmentId = $investment;
        } elseif (is_numeric($investment)) {
            $investmentId = (int) $investment;
        } else {
            // Handle the case where the route parameter is neither an instance nor a valid ID
            abort(404, 'Investment not found');
        }

        $role = Role::find(13);

        if(!Auth::user()->hasRole($role)){
            if (!Auth::check() || !Auth::user()->hasPermissionTo('view-investment-' . $investmentId)) {
                return redirect()->route('admin.developro.investment.index');
            }
        }

        return $next($request);
    }
}
