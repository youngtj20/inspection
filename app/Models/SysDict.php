<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysDict extends Model
{
    protected $table = 'sys_dict';
    public $timestamps = false;

    protected $fillable = [
        'title', 'name', 'type', 'value', 'remark', 'status'
    ];

    protected $dates = ['create_date', 'update_date', 'createDate', 'updateDate'];
}
