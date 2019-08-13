<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';

    protected $fillable = [
        'idventa',
        'idproducto',
        'cantidad',
        'precio',
        'descuento'
    ];

    public function venta(){
        return $this->belongTo('App\Venta','idventa');
    }

    public function productos(){
        return $this->belongsTo('App\Producto','idproducto');
    }
}
