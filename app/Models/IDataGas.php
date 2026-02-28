<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataGas extends Model
{
    protected $table = 'i_data_gas';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'idlhcmax', 'idlhcmin', 'idlhcaverage', 'stsidlhc',
        'hghhcmax', 'hghhcmin', 'hghhcaverage', 'stshghhc', 'idlcomax', 'idlcomin',
        'idlcoaverage', 'stsidlco', 'hghcomax', 'hghcomin', 'hghcoaverage', 'stshghco',
        'idllambdamax', 'idllambdamin', 'idllambdaaverage', 'stsidllambda',
        'hghlambdamax', 'hghlambdamin', 'hghlambdaaverage', 'stshghlambda',
        'idlco2max', 'idlco2min', 'idlco2average', 'stsidlco2',
        'hghco2max', 'hghco2min', 'hghco2average', 'stshghco2',
        'idlo2max', 'idlo2min', 'idlo2average', 'stsidlo2',
        'hgho2max', 'hgho2min', 'hgho2average', 'stshgho2',
        'idlnomax', 'idlnomin', 'idlnoaverage', 'stsidlno',
        'hghnomax', 'hghnomin', 'hghnoaverage', 'stshghno', 'dept_id'
    ];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
