<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTaller extends Model
{
    protected $table = 'users_taller';
    protected $fillable = [
        'nombre',
        'tipo_user_taller_id'
    ];


}
