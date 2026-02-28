<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataPit extends Model
{
    protected $table = 'i_data_pit';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'defectcode', 'category', 'description', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
