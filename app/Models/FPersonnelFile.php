<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FPersonnelFile extends Model
{
    protected $table = 'f_personnel_files';
    public $timestamps = false;

    protected $fillable = [
        'age', 'education', 'email', 'gender', 'jobTitle', 'name',
        'phone', 'dept_id'
    ];

    protected $dates = ['createDate', 'updateDate', 'create_date', 'update_date'];

    // Relationships
    public function dept()
    {
        return $this->belongsTo(SysDept::class, 'dept_id');
    }
}
