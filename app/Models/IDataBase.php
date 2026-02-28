<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDataBase extends Model
{
    protected $table = 'i_data_base';
    public $timestamps = false;

    protected $fillable = [
        'plateno', 'vehicletype', 'licencetype', 'seriesno', 'inspectdate',
        'inspecttimes', 'inspecttype', 'starttime', 'endTime', 'conclusion',
        'workerline', 'register', 'inspector', 'appearanceinspector', 'pitinspector',
        'owner', 'testresult', 'dept_id', 'isupload'
    ];

    protected $dates = ['createDate', 'updateDate'];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }

    public function brakeFront()
    {
        return $this->hasOne(IDataBrakeFront::class, 'seriesno', 'seriesno');
    }

    public function brakeRear()
    {
        return $this->hasOne(IDataBrakeRear::class, 'seriesno', 'seriesno');
    }

    public function gas()
    {
        return $this->hasOne(IDataGas::class, 'seriesno', 'seriesno');
    }

    public function smoke()
    {
        return $this->hasOne(IDataSmoke::class, 'seriesno', 'seriesno');
    }
}
