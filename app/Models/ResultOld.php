<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultOld extends Model
{
    use HasFactory;
    protected $table = 'result_old';

    public function course()
    {
        return $this->belongsTo(CourseOnline::class, 'code', 'course');
    }

}
