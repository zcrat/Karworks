<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DetallesGenerales;
use App\User;
class TrabajosParciales extends Model
{
    use softDeletes;
    protected $table = 'trabajos_parciales';
    protected $fillable = [
        'Detalles_Generales_Id',
        'user_id',
        'descripcion',
        'horas',
    ];

    public function detallesGenerales()
    {
        return $this->belongsTo(DetallesGenerales::class, 'Detalles_Generales_Id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function OrdenServicio()
    {
        return $this->belongsTo(DetallesGenerales::class, 'Detalles_Generales_Id');
    }

}
