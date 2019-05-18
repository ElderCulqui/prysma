<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $usuarios = User::with('rol')
                            ->orderBy('id','desc')
                            ->paginate(3);
        }else{
            $usuarios = User::with('rol')
                            ->where($criterio,'like','%'.$buscar.'%')
                            ->orWhereHas('rol',function($query) use ($criterio,$buscar){
                                $query->where($criterio,'like','%'.$buscar.'%');
                            })
                            ->orderBy('id','desc')
                            ->paginate(3);
        }

        

        return [
            'pagination' => [
                'total'             => $usuarios->total(),
                'current_page'      => $usuarios->currentPage(),
                'per_page'          => $usuarios->perPage(),
                'last_page'         => $usuarios->lastPage(),
                'from'              => $usuarios->firstItem(),
                'to'                => $usuarios->lastItem(),
            ],

            'usuarios' => $usuarios
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $usuario = New User();
        $usuario->idrol = $request->idrol;
        $usuario->nombre = $request->nombre;
        $usuario->tipo_documento = $request->tipo_documento;
        $usuario->num_documento = $request->num_documento;
        $usuario->direccion = $request->direccion;
        $usuario->telefono = $request->telefono;
        $usuario->email = $request->email;
        $usuario->usuario = $request->usuario;
        $usuario->password = bcrypt($request->password);
        $usuario->condicion = 1;
        $usuario->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $usuario = User::findOrFail($request->id);
        $usuario->idrol = $request->idrol;
        $usuario->nombre = $request->nombre;
        $usuario->tipo_documento = $request->tipo_documento;
        $usuario->num_documento = $request->num_documento;
        $usuario->direccion = $request->direccion;
        $usuario->telefono = $request->telefono;
        $usuario->email = $request->email;
        $usuario->usuario = $request->usuario;
        $usuario->password = bcrypt($request->password);
        $usuario->condicion = 1;
        $usuario->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $usuario = User::findOrFail($request->id);
        $usuario->condicion = '1';
        $usuario->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $usuario = User::findOrFail($request->id);
        $usuario->condicion = '0';
        $usuario->save();
    }
}
