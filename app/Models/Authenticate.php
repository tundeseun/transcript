<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;

class Authenticate extends AuthenticatableUser implements Authenticatable
{
    use HasFactory;

    protected $table = 'authenticate';
    
    protected $fillable = ['matric', 'password'];
}
