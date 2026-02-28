<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $userRoles = DB::table('sys_user_role')
            ->join('sys_role', 'sys_user_role.role_id', '=', 'sys_role.id')
            ->where('sys_user_role.user_id', $user->id)
            ->pluck('sys_role.name')
            ->toArray();
        
        if (count(array_intersect($roles, $userRoles)) > 0) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized action.');
    }
}
