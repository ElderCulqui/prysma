<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Compra;
use App\DetalleCompra;
use Carbon\Carbon;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $compras = Compra::with('proveedor')
                                 ->with('usuario')
                                 ->orderBy('id','desc')
                                 ->paginate(3);
        }else{
            $compras = Compra::with('proveedor')
                                 ->with('usuario')
                                 ->where($criterio,'like','%'.$buscar.'%')
                                 ->orWhereHas('proveedor',function($query) use ($criterio,$buscar){
                                     $query->where($criterio,'like','%'.$buscar.'%');
                                 })
                                 ->orWhereHas('usuario',function($query) use ($criterio,$buscar){
                                    $query->where($criterio,'like','%'.$buscar.'%');
                                 })
                                 ->orderBy('id','desc')
                                 ->paginate(3);
        }

        

        return [
            'pagination' => [
                'total'             => $compras->total(),
                'current_page'      => $compras->currentPage(),
                'per_page'          => $compras->perPage(),
                'last_page'         => $compras->lastPage(),
                'from'              => $compras->firstItem(),
                'to'                => $compras->lastItem(),
            ],

            'compras' => $compras
        ];
    }

    public function obtenerCompra(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;
        $compra = Compra::with('detalle_compra')
                           ->with('detalle_compra.productos') 
                           ->with('proveedor')
                           ->where('id',$id)
                           ->orderBy('id','desc')
                           ->get();
                                 
        return [ 'compra' => $compra];                            
    }

    public function obtenerDetalles(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = DetalleCompra::where('idcompra',$id)
                                 ->orderBy('id','desc')
                                 ->get();

        return [ 'detalles' => $detalles];                            
    }

    public function store(Request $request){

        if(!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();
                $myTime = Carbon::now('America/Lima');
                $compra = new Compra();
                $compra->idproveedor = $request->idproveedor;
                $compra->idusuario = Auth::user()->id;
                $compra->tipo_identificacion = $request->tipo_identificacion;
                $compra->num_compra = $request->num_compra;
                $compra->fecha_compra = $myTime->toDateString();
                $compra->impuesto = $request->impuesto;
                $compra->total = $request->total;
                $compra->estado = 'Registrado';
                $compra->save();

                $detalles = $request->data;
                foreach ($detalles as $key => $value) {
                    $detalle = new DetalleCompra();
                    $detalle->idcompra = $compra->id;
                    $detalle->idproducto = $value['idproducto'];
                    $detalle->cantidad = $value['cantidad'];
                    $detalle->precio = $value['precio'];
                    $detalle->save();
                }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function desactivar(Request $request){

        if(!$request->ajax()) return redirect('/');

        $compra = Compra::findOrFail($request->id);
        $compra->estado = 'Anulado';
        $compra->save();
    }
}
