<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataSuspensionRear extends Model
{
    protected $table = 'i_data_suspension_rear';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'lftweight', 'rgtweight', 'lftsuspension',
        'rgtsuspension', 'suspensiondiff', 'suspensioneff', 'stssuspension',
        'stssuspensiondiff', 'stssuspensioneff', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
