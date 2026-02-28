<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysFile extends Model
{
    protected $table = 'sys_file';
    public $timestamps = false;

    protected $fillable = [
        'name', 'path', 'mime', 'size', 'md5', 'sha1', 'create_by'
    ];

    protected $dates = ['create_date', 'createDate'];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(SysUser::class, 'create_by');
    }
}
