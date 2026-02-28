<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataSmoke extends Model
{
    protected $table = 'i_data_smoke';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'n1', 'n2', 'n3', 'n4', 'naverage', 'stsn',
        'k1', 'k2', 'k3', 'k4', 'kaverage', 'stsk', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
