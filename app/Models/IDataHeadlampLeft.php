<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataHeadlampLeft extends Model
{
    protected $table = 'i_data_headlamp_left';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'height', 'lightintensity',
        'offsetlrfar', 'offsetlrnear', 'offsetudfar', 'offsetudnear',
        'stslightintensity', 'stsoffsetlrfar', 'stsoffsetlrnear',
        'stsoffsetudfar', 'stsoffsetudnear', 'stsheight', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
