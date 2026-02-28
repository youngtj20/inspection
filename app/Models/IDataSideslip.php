<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataSideslip extends Model
{
    protected $table = 'i_data_sideslip';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'slide', 'stsslide', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
