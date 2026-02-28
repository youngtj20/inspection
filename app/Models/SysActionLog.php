<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysActionLog extends Model
{
    protected $table = 'sys_action_log';
    public $timestamps = false;

    protected $fillable = [
        'name', 'type', 'ipaddr', 'clazz', 'method', 'model', 'record_id',
        'message', 'oper_name', 'oper_by'
    ];

    protected $dates = ['create_date', 'createDate'];

    // Relationships
    public function operator()
    {
        return $this->belongsTo(SysUser::class, 'oper_by');
    }
}
