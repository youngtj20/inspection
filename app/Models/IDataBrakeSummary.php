<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataBrakeSummary extends Model
{
    protected $table = 'i_data_brake_summary';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'tolbrakeeff', 'tolhandbrakeeff',
        'tolload', 'stsbrakeeff', 'stshandbrakeeff', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
