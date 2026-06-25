<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    public $timestamps =false;
    protected $table = 'detalle_facturas';
    protected $fillable = [
        'factura_id',
        'idarticulo',
        'cantidad',
        'precio'
    ];

}
