<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataVisual extends Model
{
    protected $table = 'i_data_visual';
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
