<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysMenu extends Model
{
    protected $table = 'sys_menu';
    public $timestamps = false;

    protected $fillable = [
        'title', 'pid', 'pids', 'url', 'perms', 'icon', 'type', 'sort', 'remark', 'status'
    ];

    protected $dates = ['create_date', 'update_date', 'createDate', 'updateDate'];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(SysRole::class, 'sys_role_menu', 'menu_id', 'role_id');
    }
}
