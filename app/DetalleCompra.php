<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compras';

    protected $fillable = [
        'idcompra', 
        'idproducto', 
        'cantidad', 
        'precio'
    ];

    public function compra(){
        return $this->belongsTo('App\Compra','idcompra');
    }

    public function productos(){
        return $this->belongsTo('App\Producto','idproducto');
    }
}
