<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Venta;
use App\DetalleVenta;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $ventas = Venta::with('cliente')
                                 ->with('usuario')
                                 ->orderBy('id','desc')
                                 ->paginate(3);
        }else{
            $ventas = Venta::with('cliente')
                                 ->with('usuario')
                                 ->where($criterio,'like','%'.$buscar.'%')
                                 ->orWhereHas('cliente',function($query) use ($criterio,$buscar){
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
                'total'             => $ventas->total(),
                'current_page'      => $ventas->currentPage(),
                'per_page'          => $ventas->perPage(),
                'last_page'         => $ventas->lastPage(),
                'from'              => $ventas->firstItem(),
                'to'                => $ventas->lastItem(),
            ],

            'ventas' => $ventas
        ];
    }

    public function obtenerVenta(Request $request){
        //if(!$request->ajax()) return redirect('/');

        $id = $request->id;
        $venta = Venta::with('detalle_venta')
                           ->with('detalle_venta.productos') 
                           ->with('cliente')
                           ->where('id',$id)
                           ->orderBy('id','desc')
                           ->get();
                                 
        return [ 'venta' => $venta];                            
    }

    public function store(Request $request){

        if(!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();
                $myTime = Carbon::now('America/Lima');
                $venta = new Venta();
                $venta->idcliente = $request->idcliente;
                $venta->idusuario = Auth::user()->id;
                $venta->tipo_identificacion = $request->tipo_identificacion;
                $venta->num_venta = $request->num_venta;
                $venta->fecha_venta = $myTime->toDateString();
                $venta->impuesto = $request->impuesto;
                $venta->total = $request->total;
                $venta->estado = 'Registrado';
                $venta->save();

                $detalles = $request->data;
                foreach ($detalles as $key => $value) {
                    $detalle = new DetalleVenta();
                    $detalle->idventa = $venta->id;
                    $detalle->idproducto = $value['idproducto'];
                    $detalle->cantidad = $value['cantidad'];
                    $detalle->precio = $value['precio'];
                    $detalle->descuento = 1;
                    $detalle->save();
                }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function desactivar(Request $request){

        if(!$request->ajax()) return redirect('/');

        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }
}
