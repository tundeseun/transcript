<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldofInterest5 extends Model
{
    use HasFactory;

    protected $table = 'fieldofinterest5';

    public function facultyNew()
    {
        return $this->belongsTo(FacNew::class, 'faculty', 'id');
    }

    public function departmentNew()
    {
        return $this->belongsTo(DeptNew::class, 'department', 'id');
    }

    public function degreeNew()
    {
        return $this->belongsTo(DegreeNew::class, 'degree', 'id');
    }

    public function fieldNew()
    {
        return $this->belongsTo(FieldNew::class, 'field_title', 'id');
    }
}
