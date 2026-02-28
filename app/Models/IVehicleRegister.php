<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IVehicleRegister extends Model
{
    protected $table = 'i_vehicle_register';
    public $timestamps = false;

    protected $fillable = [
        'seriesno', 'inspecttimes', 'stationno', 'inspectdate', 'registertime',
        'plateno', 'inspecttype', 'vehicletype', 'engineno', 'makeofvehicle',
        'model', 'licencetype', 'owner', 'identificationmark', 'address', 'phoneno',
        'netweight', 'authorizedtocarry', 'grossweight', 'personstocarry',
        'fueltype', 'headlampsystem', 'drivemethod', 'axisnumber', 'handbrake',
        'registerdate', 'productdate', 'heavyorlight', 'chassisno', 'acceptmember',
        'odmeter', 'inspectitems', 'presentor', 'invoiceno', 'position', 'dept_id'
    ];

    protected $dates = ['createDate'];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
