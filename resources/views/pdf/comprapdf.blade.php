<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de venta</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }
 
 
        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }
 
        #encabezado{
        text-align: center;
        margin-left: 35%;
        margin-right: 35%;
        font-size: 15px;
        }
 
        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        background:#D2691E;
        }
 
        section{
        clear: left;
        }
 
        #cliente{
        text-align: left;
        }
 
        #facliente{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }
 
        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }
 
        #facliente thead{
        padding: 20px;
        background:#D2691E;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }
 
        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }
 
        #facvendedor thead{
        padding: 20px;
        background: #D2691E;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }
 
        #facproducto{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }
 
        #facproducto thead{
        padding: 20px;
        background: #D2691E;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }
 
       
    </style>
    <body>
        {{-- @foreach ($compra as $v) --}}
        <header>
            <!--<div id="logo">
                <img src="img/logo.png" alt="" id="imagen">
            </div>-->

            <div>
                
                <table id="datos">
                    <thead>                        
                        <tr>
                            <th id="">DATOS DEL PROVEEDOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p id="proveedor">Nombre: {{$compra->proveedor->nombre ?? '-'}}<br>
                            {{$compra->proveedor->tipo_documento}}: {{$compra->proveedor->num_documento}}<br>
                            Dirección: {{$compra->proveedor->direccion}}<br>
                            Teléfono: {{$compra->proveedor->telefono}}<br>
                            Email: {{$compra->proveedor->email}}</</p></th>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div id="fact">
                <p>{{$compra->tipo_identificacion}}-COMPRA<br>
                  {{$compra->num_compra}}</p>
            </div>
        </header>
        <br>
       
        {{-- @endforeach --}}
        <br>
       
        <section>
            <div>
                <table id="facproducto">
                    <thead>
                        <tr id="fa">
                            <th>COMPRADOR</th>
                            <th>FECHA COMPRA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$compra->usuario->nombre}}</td>
                            <td>{{$compra->created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <table id="facproducto">
                    <thead>
                        <tr id="fa">
                            <th>CANTIDAD</th>
                            <th>PRODUCTO</th>
                            <th>PRECIO COMPRA (USD$)</th>
                            {{-- <th>DESCUENTO</th> --}}
                            <th>PRECIO TOTAL (USD$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compra->detalle_compra as $det)
                        <tr>
                            <td>{{$det->cantidad}}</td>
                            <td>{{$det->producto->nombre}}</td>
                            <td>{{$det->precio}}</td>
                            {{-- <td>{{$det->descuento}}</td> --}}
                            <td>{{$det->cantidad*$det->precio}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        {{-- @foreach ($compra as $v) --}}
                        <tr>
                            <th></th>
                            <th></th>
                            {{-- <th></th> --}}
                            <th>SUBTOTAL</th>
                            <td>USD$ {{round($compra->total-($compra->total*$compra->impuesto),2)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            {{-- <th></th> --}}
                            <th>Impuesto</th>
                            <td>USD$ {{round($compra->total*$compra->impuesto,2)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            {{-- <th></th> --}}
                            <th>TOTAL</th>
                            <td>USD$ {{$compra->total}}</td>
                        </tr>
                        {{-- @endforeach --}}
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <!--puedes poner un mensaje aqui-->
            <div id="datos">
                <p id="encabezado">
                    <b>webtraining-it.com</b><br>Eyter Higuera<br>Telefono:(+00)123456799<br>Email:webdeveloperphpwordpress@gmail.com
                </p>
            </div>
        </footer>
    </body>
</html>