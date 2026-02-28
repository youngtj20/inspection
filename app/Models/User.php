<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'sys_user';

    protected $fillable = [
        'username',
        'nickname',
        'email',
        'password',
        'dept_id',
        'phone',
        'sex',
        'status',
    ];

    protected $hidden = [
        'password',
        'salt',
    ];

    protected $casts = [
        'create_date' => 'datetime',
        'update_date' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function hasRole($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $userRoles = DB::table('sys_user_role')
            ->join('sys_role', 'sys_user_role.role_id', '=', 'sys_role.id')
            ->where('sys_user_role.user_id', $this->id)
            ->pluck('sys_role.name')
            ->toArray();

        return count(array_intersect($roles, $userRoles)) > 0;
    }
}
