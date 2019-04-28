<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\Categoria;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $productos = Producto::orderBy('id','desc')->paginate(3);
        }else{
            $productos = Producto::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(3);
        }

        

        return [
            'pagination' => [
                'total'             => $productos->total(),
                'current_page'      => $productos->currentPage(),
                'per_page'          => $productos->perPage(),
                'last_page'         => $productos->lastPage(),
                'from'              => $productos->firstItem(),
                'to'                => $productos->lastItem(),
            ],

            'productos' => $productos
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $producto = New Producto();
        $producto->idcateogria = $request->idcateogria;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->condicion = '1';
        $producto->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->idcateogria = $request->idcateogria;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->condicion = '1';
        $producto->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->condicion = '1';
        $producto->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->condicion = '0';
        $producto->save();
    }
}
