<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataSpeedometer extends Model
{
    protected $table = 'i_data_speedometer';
    public $timestamps = false;

    protected $fillable = [
        'inspectTimes', 'seriesNo', 'speed', 'stsspeed', 'deptId', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
