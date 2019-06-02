<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = [
        'idproveedor', 
        'id_usuario', 
        'tipo_identificacion', 
        'num_compra', 
        'fecha_compra', 
        'impuesto', 
        'total', 
        'estado'
    ];

    public function detalle_compra(){
        return $this->hasMany('App\DetalleCompra','idcompra','id');
    }

    public function proveedor(){
        return $this->belongsTo('App\Proveedor','idproveedor');
    }

    public function usuario(){
        return $this->belongsTo('App\User','idusuario');
    }
}
