<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'idcliente',
        'idusuario',
        'tipo_identificacion',
        'num_venta',
        'fecha_venta',
        'impuesto',
        'total',
        'estado'
    ];

    public function detalle_venta(){
        return $this->hasMany('App\DetalleVenta','idventa','id');
    }

    public function cliente(){
        return $this->belongsTo('App\Cliente','idcliente');
    }
    
    public function usuario(){
        return $this->belongsTo('App\User','idusuario');
    }
}
