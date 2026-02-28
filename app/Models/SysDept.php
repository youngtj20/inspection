<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysDept extends Model
{
    protected $table = 'sys_dept';
    public $timestamps = false;

    protected $fillable = [
        'title', 'pid', 'pids', 'sort', 'remark', 'status',
        'address', 'area', 'contactnumber', 'contacts', 'employees', 'state', 'deptno'
    ];

    protected $dates = ['create_date', 'update_date', 'createDate', 'updateDate'];

    // Relationships
    public function users()
    {
        return $this->hasMany(SysUser::class, 'dept_id');
    }

    public function equipment()
    {
        return $this->hasMany(FEquipmentFile::class, 'dept_id');
    }

    public function personnel()
    {
        return $this->hasMany(FPersonnelFile::class, 'dept_id');
    }

    public function inspectionData()
    {
        return $this->hasMany(IDataBase::class, 'dept_id');
    }
}
