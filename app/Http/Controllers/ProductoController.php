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
            $productos = Producto::with('categoria')
                                 ->orderBy('id','desc')
                                 ->paginate(3);
        }else{
            $productos = Producto::with('categoria')
                                 ->where($criterio,'like','%'.$buscar.'%')
                                 ->orWhereHas('categoria',function($query) use ($criterio,$buscar){
                                     $query->where($criterio,'like','%'.$buscar.'%');
                                 })
                                 ->orderBy('id','desc')
                                 ->paginate(3);
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
        $producto->idcategoria = $request->idcategoria;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
        $producto->condicion = '1';
        $producto->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->idcategoria = $request->idcategoria;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
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

    public function buscarProducto(Request $request){
        // return $request;
        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $productos = Producto::with('categoria')
                            ->where('codigo',$filtro)
                            ->orderBy('nombre','asc')
                            ->take(1)
                            ->get();
        
        return [ 'productos' => $productos ];
    }

    public function listarProducto(Request $request){
        
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $productos = Producto::with('categoria')
                                 ->orderBy('id','desc')
                                 ->paginate(10);
        }else{
            $productos = Producto::with('categoria')
                                 ->where($criterio,'like','%'.$buscar.'%')
                                 ->orWhereHas('categoria',function($query) use ($criterio,$buscar){
                                     $query->where($criterio,'like','%'.$buscar.'%');
                                 })
                                 ->orderBy('id','desc')
                                 ->paginate(10);
        }

        return ['productos' => $productos];
    }
}
