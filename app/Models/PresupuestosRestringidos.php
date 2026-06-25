<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class PresupuestosRestringidos extends Model
{
    protected $table = 'presupuestosrestringidos';
    protected $fillable = [
        'id',
        'presupuesto_id',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsto(User::class, 'user_id');
    }
}
