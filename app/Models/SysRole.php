<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysRole extends Model
{
    protected $table = 'sys_role';
    public $timestamps = false;

    protected $fillable = [
        'title', 'name', 'remark', 'status'
    ];

    protected $dates = ['create_date', 'update_date', 'createDate', 'updateDate'];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(SysUser::class, 'sys_user_role', 'role_id', 'user_id');
    }

    public function menus()
    {
        return $this->belongsToMany(SysMenu::class, 'sys_role_menu', 'role_id', 'menu_id');
    }
}
