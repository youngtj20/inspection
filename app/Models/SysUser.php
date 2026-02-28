<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysUser extends Model
{
    protected $table = 'sys_user';
    public $timestamps = false;

    protected $fillable = [
        'username', 'nickname', 'password', 'salt', 'dept_id',
        'picture', 'sex', 'email', 'phone', 'remark', 'status'
    ];

    protected $dates = ['create_date', 'update_date', 'createDate', 'updateDate'];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }

    public function roles()
    {
        return $this->belongsToMany(SysRole::class, 'sys_user_role', 'user_id', 'role_id');
    }

    public function actionLogs()
    {
        return $this->hasMany(SysActionLog::class, 'oper_by');
    }
}
