<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $proveedores = Proveedor::orderBy('id','desc')->paginate(3);
        }else{
            $proveedores = Proveedor::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(3);
        }

        

        return [
            'pagination' => [
                'total'             => $proveedores->total(),
                'current_page'      => $proveedores->currentPage(),
                'per_page'          => $proveedores->perPage(),
                'last_page'         => $proveedores->lastPage(),
                'from'              => $proveedores->firstItem(),
                'to'                => $proveedores->lastItem(),
            ],

            'proveedores' => $proveedores
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $proveedor = New Proveedor();
        $proveedor->nombre = $request->nombre;
        $proveedor->tipo_documento = $request->tipo_documento;
        $proveedor->num_documento = $request->num_documento;
        $proveedor->direccion = $request->direccion;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->nombre = $request->nombre;
        $proveedor->tipo_documento = $request->tipo_documento;
        $proveedor->num_documento = $request->num_documento;
        $proveedor->direccion = $request->direccion;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->save();
    }
}
