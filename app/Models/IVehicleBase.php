<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IVehicleBase extends Model
{
    protected $table = 'i_vehicle_base';
    public $timestamps = false;

    protected $fillable = [
        'plateno', 'vehicletype', 'engineno', 'makeofvehicle', 'model',
        'licencetype', 'owner', 'identificationmark', 'address', 'phoneno',
        'netweight', 'authorizedtocarry', 'grossweight', 'personstocarry',
        'fueltype', 'headlampsystem', 'drivemethod', 'axisnumber', 'handbrake',
        'registerdate', 'productdate', 'heavyorlight', 'chassisno', 'odmeter'
    ];

    protected $dates = ['createDate', 'create_date'];
}
