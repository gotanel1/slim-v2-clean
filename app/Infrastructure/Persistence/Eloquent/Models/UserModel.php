<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * User Eloquent Model
 * Infrastructure concern - database mapping
 */
class UserModel extends Model
{
    protected $table = 'users';
    
    protected $fillable = [
        'email',
        'password',
        'name',
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = true;
}
