<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataBrakeRear extends Model
{
    protected $table = 'i_data_brake_rear';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'lftaxleload', 'rgtaxleload', 'axleload',
        'lftbrakeforce', 'rgtbrakeforce', 'lfthandbrake', 'rgthandbrake',
        'lftfrictioneff', 'rgtfrictioneff', 'lftbrakeeff', 'rgtbrakeeff',
        'brakeeff', 'lftbrakediff', 'rgtbrakediff', 'brakediff',
        'lfthandbrakeeff', 'rgthandbrakeeff', 'handbrakeeff',
        'lfthandbrakediff', 'rgthandbrakediff', 'handbrakediff',
        'stsfrictioneff', 'stsbrakeforce', 'stshandbrakeforce', 'stsaxleload',
        'stsbrakeeff', 'stsbrakediff', 'stshandbrakeeff', 'stshandbrakediff', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
