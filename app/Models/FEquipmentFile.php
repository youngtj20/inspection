<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FEquipmentFile extends Model
{
    protected $table = 'f_equipment_files';
    public $timestamps = false;

    protected $fillable = [
        'manufacturer', 'model', 'name', 'producerCountry', 'productDate',
        'type', 'dept_id', 'certificationDate'
    ];

    protected $dates = ['createDate', 'updateDate', 'create_date', 'update_date'];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
