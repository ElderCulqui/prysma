<template>
   <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="/">BACKEND - SISTEMA DE COMPRAS - VENTAS</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <!-- Listado de Ventas -->
            <template v-if="listado==1">
                <div class="card-header">
                <h2>Listado de Ventas</h2><br/>
                    <button class="btn btn-primary btn-lg" type="button" @click="mostrarDetalle()">
                        <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Nueva Venta
                    </button>
                
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="tipo_identificacion">Tipo de Identificación</option>
                                    <option value="num_venta">Número de Venta</option>
                                    <option value="fecha_venta">Fecha de Venta</option>
                                </select>
                                <input type="text" @keyup.enter="listarVenta(1,buscar,criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                                <button type="submit" @click="listarVenta(1,buscar,criterio);"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr class="bg-primary">
                                    <th>Ver Detalle</th>
                                    <th>Fecha Venta</th>
                                    <th>Número Venta</th>
                                    <th>Cliente</th>
                                    <th>Tipo Identificación</th>
                                    <th>Vendedor</th>
                                    <th>Total (S/.)</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>
                                    <th>Cambiar Estado</th>
                                    <th>Descargar Reporte</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                <tr v-for="venta in arrayVenta" :key="venta.id">
                                    
                                    <td>
                                        <button type="button" @click="verVenta(venta.id)" class="btn btn-warning btn-md">
                                            <i class="fa fa-eye fa-2x"></i> Ver Detalle
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="venta.fecha_venta"></td>
                                    <td v-text="venta.num_venta"></td>
                                    <td v-text="venta.cliente.nombre"></td>
                                    <td v-text="venta.tipo_identificacion"></td>
                                    <td v-text="venta.usuario.nombre"></td>
                                    <td v-text="venta.total"></td>
                                    <td v-text="venta.impuesto"></td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-md" v-if="venta.estado=='Registrado'">
                                        <i class="fa fa-check fa-2x"></i> Registrado
                                        </button>
                                        <button type="button" class="btn btn-danger btn-md" v-else>
                                        <i class="fa fa-check fa-2x"></i> Anulado
                                        </button>
                                    </td>
                                    <td>
                                        <template v-if="venta.estado=='Registrado'">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                <i class="fa fa-check fa-2x"></i> Anular Venta
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="fa fa-check fa-2x"></i> Cambiado
                                            </button>
                                        </template>
                                    </td>
                                    <td>
                                        <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                            <i class="fa fa-file fa-2x"></i> Descargar PDF
                                        </button> &nbsp;
                                    </td>

                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </template>
            <!-- Fin Listado Compras-->
            
            <!-- Detalle Compras -->
            <template v-else-if="listado==0">
                <span><strong>(*) Campo Obligatorio</strong></span><br>
                <h3 class="text-center">Llenar el Formulario</h3>
                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="text-uppercase"><strong>Nueva Venta(*)</strong></label>
                                <input type="text" class="form-control" v-model="num_venta" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="text-uppercas"><strong>Cliente(*)</strong></label>
                            
                                <v-select
                                    :on-search="selectCliente"
                                    label="nombre"
                                    :options="arrayCliente"
                                    placeholder="Buscar Clientes..."
                                    :onChange="getDatosCliente"
                                >
                                </v-select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="text-uppercase"><strong>Tipo Identificacion(*)</strong></label>
                                <select class="form-control" v-model="tipo_identificacion">
                                    <option value="0">Seleccione</option>
                                    <option value="FACTURA">Factura</option>
                                    <option value="TICKET">Ticket</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="text-uppercase"><strong>Impuesto(*)</strong></label>
                                <input type="text" class="form-control" v-model="impuesto">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border"> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Producto <span class="text-error" v-show="idproducto==0">(*Ingrese código del producto)</span></label>
                                <div class="form-inline">
                                    <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarProducto()" placeholder="Ingrese Código">
                                    <button @click="abrirModal()" class="btn btn-primary">
                                        <i class="fa fa-plus"></i>&nbsp; Agregar Productos
                                    </button>
                                    <input type="text" readonly class="form-control" v-model="producto">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Precio <span class="text-error" v-show="precio==0">(*Ingrese un valor)</span></label>
                                <input type="number" value="0" step="any" class="form-control" v-model="precio">
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cantidad <span class="text-error" v-show="cantidad==0">(*Ingrese un valor)</span></label>
                                <input type="number" value="0" step="any" class="form-control" v-model="cantidad">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Descuento</label>
                                <input type="number" value="0" step="any" class="form-control" v-model="descuento">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button @click="agregarDetalle()" class="btn btn-primary form-control btnagregar">
                                    <i class="fa fa-plus fa-2x"></i>Agregar Detalle
                                </button>
                            </div>
                        </div> 
                    </div>
                    <!-- <br><br> -->
                    <div class="form-grop row border">
                        <h3>Lista de Productos</h3>
                        
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="bg-success">
                                        <th>Eliminar</th>
                                        <th>Producto</th>
                                        <th>Precio (S/.)</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Total (S/.)</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                        <td>
                                            <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                <i class="fa fa-times fa-2x"></i>
                                            </button>
                                        </td>
                                        <td v-text="detalle.producto"></td>
                                        <td>
                                            <input v-model="detalle.precio" type="number" class="form-control">
                                        </td>
                                        <td>
                                            <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                                            <input v-model="detalle.cantidad" type="number" class="form-control">
                                        </td>
                                        <td>
                                            <span style="color:red;" v-show="detalle.descuento>(detalle.precio*detalle.cantidad)">Descuento superior al total</span>
                                            <input v-model="detalle.descuento" type="number" class="form-control">
                                        </td>
                                        <td>
                                            {{ detalle.precio * detalle.cantidad - detalle.descuento }}
                                        </td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="5" align="right"><strong>Sub-Total:</strong></td>
                                        <td><strong>S/. {{subTotal=(total-subTotalImpuesto).toFixed(2)}}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="5" align="right"><strong>Impuesto:</strong></td>
                                        <td><strong>S/. {{subTotalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="5" align="right"><strong>Descuento:</strong></td>
                                        <td><strong>S/. {{subTotalDescuento=calcularDescuento}}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="5" align="right"><strong>Total:</strong></td>
                                        <td><strong>S/. {{total=calcularTotal}}</strong></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="6">
                                            No se han agregado productos
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <button type="button" class="btn btn-danger" @click="ocultarDetalle()"><i class="fa fa-times fa-2x"></i>Cerrar</button>
                            <button type="button" class="btn btn-success" @click="registrarVenta()"><i class="fa fa-save fa-2x"></i>Registrar Venta</button>
                        </div>
                    </div>
                </div>    
            </template>
            <!-- Fin Detalle Compras -->

            <!-- Ver Compra -->
            <template v-else-if="listado==2">
                <h2 class="text-center">Detalle Venta</h2>
                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="text-uppercase"><strong>Cliente</strong></label>
                                <p v-text="cliente"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="text-uppercase"><strong>Tipo Documento</strong></label>
                                <p v-text="tipo_identificacion"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="text-uppercase"><strong>Número Venta</strong></label>
                                <p v-text="num_venta"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="text-uppercase"><strong>Impuesto</strong></label>
                                <p v-text="impuesto"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr class="bg-success">
                                        <th>Producto</th>
                                        <th>Precio (S/.)</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Total (S/.)</th>   
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <td v-text="detalle.productos.nombre"></td>
                                        <td v-text="detalle.precio"></td>
                                        <td v-text="detalle.cantidad"></td>
                                        <td v-text="detalle.descuento"></td>
                                        <td>
                                            {{ detalle.precio * detalle.cantidad }}
                                        </td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="4" align="right"><strong>Sub-Total:</strong></td>
                                        <td><strong>S/.{{ subTotal=(total-subTotalImpuesto).toFixed(2) }}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="4" align="right"><strong>Impuesto:</strong></td>
                                        <td><strong>S/.{{ subTotalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2) }}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="4" align="right"><strong>Descuento:</strong></td>
                                        <td><strong>S/.{{ subTotalDescuento }}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="4" align="right"><strong>Total:</strong></td>
                                        <td><strong>S/.{{total}}</strong></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="5">
                                            No se han agregado productos
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div align="right">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-danger">
                                    <i class="fa fa-times fa-2x"></i>Cerrar
                                </button>
                            </div>
                       </div>
                    </div>
                </div>
            </template>
            <!-- Fin Ver Compra -->
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" :class="{'mostrar':modal}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" @click="cerrarModal()" class="close" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
               
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <!-- <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Producto</option>
                                    <option value="idcategoria">Categoría</option>
                                </select> -->
                                <input type="text" @keyup.enter="listarProducto(buscarP,criterioP);" v-model="buscarP" class="form-control" placeholder="Buscar por Producto o Categoría">
                                <button type="submit" @click="listarProducto(buscarP,criterioP);"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr class="bg-primary">
                            <th>Categoria</th>
                            <th>Producto</th>
                            <th>Codigo</th>
                            <th>Precio Venta (USD$)</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        <tr v-for="producto in arrayProducto" :key="producto.id">
                            
                            <td v-text="producto.categoria.nombre"></td>
                            <td v-text="producto.nombre"></td>
                            <td v-text="producto.codigo"></td>
                            <td v-text="producto.precio_venta"></td>
                            <td v-text="producto.stock"></td>
                            <td>
                                <button type="button" class="btn btn-success btn-md" v-if="producto.condicion">
                                  <i class="fa fa-check fa-2x"></i> Activo
                                </button>
                                <button type="button" class="btn btn-danger btn-md" v-else>
                                  <i class="fa fa-check fa-2x"></i> Desactivado
                                </button>
                            </td>

                            <td>
                                <button type="button" class="btn btn-info btn-md" @click="agregarDetalleModal(producto)">

                                  <i class="fa fa-edit fa-2x"></i> Agregar
                                </button> &nbsp;
                            </td>

                        </tr>
                       
                    </tbody>
                </table>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
</main>
</template>

<script>

    import vSelect from 'vue-select';

    export default {
        
        data(){
            return{
                venta_id:0,
                idcliente:0,
                cliente:'',
                nombre:'',
                tipo_identificacion:'FACTURA',
                num_venta:'',
                impuesto:0.18,
                total:0.00,
                subTotalImpuesto:0.00,
                subTotalDescuento:0.00,
                subTotal:0.00,
                arrayVenta:[],
                arrayCliente:[],
                arrayDetalle:[],
                listado:1,
                tituloModal:'',
                modal:0,
                tipoAccion:0,
                errorVenta:0,
                errorMostrarMsjVenta:[],
                pagination:{
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset:3,
                criterio: 'num_venta',
                buscar: '',
                criterioP: 'nombre',
                buscarP: '',
                arrayProducto:[],
                idproducto:0,
                codigo:'',
                producto:'',
                precio:0,
                cantidad:0,
                descuento:0,
                stock:0,

            }    
        },

        components:{
            vSelect
        },

        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },

            pagesNumber: function(){
                if(!this.pagination.to){
                    return[];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;

            },

            calcularDescuento: function(){
                var total_descuento = 0.0;
                for(var i=0; i<this.arrayDetalle.length; i++){
                    total_descuento = total_descuento + parseInt(this.arrayDetalle[i].descuento);  
                }
                return parseInt(total_descuento);
            },
            calcularTotal: function(){
                var resultado = 0.0;
                for(var i=0; i<this.arrayDetalle.length; i++){
                    resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento);  
                }
                return resultado;
            },

        },

        methods:{
            listarVenta(page,buscar,criterio){

                let me=this;

                var url = 'venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

                axios.get(url).then(function (response) {
                    // handle success
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;

                me.pagination.current_page = page;

                me.listarVenta(page,buscar,criterio);
            },

            mostrarDetalle(){

                let me = this;

                me.listado=0;
                me.idcliente=0;
                me.tipo_identificacion = 'FACTURA';
                me.num_venta = '';
                me.impuesto = 0.18;  
                me.total = 0.0;
                me.idproducto = 0;
                me.producto = '';
                me.cantidad = 0;
                me.precio = 0;
                me.descuento = 0;
                me.arrayDetalle = [];
            },

            ocultarDetalle(){

                let me = this; 
                this.listado = 1;
                me.cliente = '';
                me.tipo_identificacion = 'FACTURA';
                me.num_compra = '';
                me.impuesto = 0.18;
                me.total = 0.0;
                me.arrayDetalle = [];
            },

            verVenta(id){
                let me = this;
                me.listado=2;
                var venta
                var url = 'venta/obtenerVenta?id='+id;
                
                axios.get(url).then(function (response) {
                    // handle success
                    //console.log(compra);
                    var respuesta = response.data;
                    venta = respuesta.venta[0];

                    me.cliente = venta.cliente.nombre;
                    me.num_venta = venta.num_venta;
                    me.tipo_identificacion = venta.tipo_identificacion;
                    me.impuesto = venta.impuesto;
                    me.total = venta.total;
                    me.arrayDetalle = venta.detalle_venta;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            registrarVenta(){

                if(this.validarVenta()){
                    return;
                }

                let me=this;

                axios.post('venta/registrar',{
                    'idcliente':this.idcliente,
                    'tipo_identificacion':this.tipo_identificacion,
                    'num_venta':this.num_venta,
                    'impuesto':this.impuesto,
                    'total':this.total,
                    'data':this.arrayDetalle
                }).then(function (response) {
                    me.listado = 1;
                    me.listarVenta(1,'','num_venta');
                    me.idcliente=0;
                    me.tipo_identificacion = 'FACTURA';
                    me.num_venta = '';
                    me.impuesto = 0.18;  
                    me.total = 0.0;
                    me.idproducto = 0;
                    me.producto = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.arrayDetalle = [];
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            selectCliente(search,loading){
                let me=this;
                loading(true)
                var url = 'cliente/selectCliente?filtro='+search;

                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    // q:searchstock
                    me.arrayCliente = respuesta.clientes;
                    console.log(respuesta)
                    loading(false)
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            getDatosCliente(val1){
                let me = this;
                me.loading = true;
                me.idcliente = val1.id;
            },

            buscarProducto(){
                let me=this;
                var url = 'producto/buscarProducto?filtro='+ me.codigo;

                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayProducto = respuesta.productos;

                    if(me.arrayProducto.length > 0){
                        me.producto = me.arrayProducto[0]['nombre'];
                        me.idproducto = me.arrayProducto[0]['id'];
                        me.precio = me.arrayProducto[0]['precio_venta'];
                        me.stock = me.arrayProducto[0]['stock'];
                    }else{
                        me.producto = 'No existe el producto';
                        me.idproducto = 0;
                    }
                })
                .catch(function (error){
                    console.log(error);
                })
            },

            encuentra(id){
                var sw = 0;
                for(var i=0; i<this.arrayDetalle.length; i++){
                    if(this.arrayDetalle[i].idproducto == id){
                        sw = true;
                    }
                }
                return sw;
            },

            agregarDetalle(){
                let me = this;
                if(me.idproducto == 0 || me.cantidad == 0 || me.precio == 0){

                }
                else{
                    if(me.encuentra(me.idproducto)){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Este producto ya fue agregado'
                        })
                    }
                    else{
                        if(me.cantidad>me.stock){
                            swal({
                                type: 'error',
                                title: 'Error...',
                                text: 'Stock insuficiente'
                            })
                        }
                        else{
                                me.arrayDetalle.push({
                                idproducto: me.idproducto,
                                producto: me.producto,
                                cantidad: me.cantidad,
                                descuento: me.descuento,
                                precio: me.precio
                            });
                            me.codigo="";
                            me.idproducto=0;
                            me.producto="";
                            me.cantidad=0;
                            me.descuento=0;
                            me.precio=0;
                            me.stock=0;
                        }
                    }
                }
            },

            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },

            agregarDetalleModal(data=[]){
                let me = this;

                if(me.encuentra(data['id'])){
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Ese producto ya fue agregado'
                    })
                }else{
                    me.arrayDetalle.push({
                        idproducto: data['id'],
                        producto: data['nombre'],
                        cantidad: 1,
                        precio: data['precio_venta'],
                        descuento: 0,
                        stock: data['stock'],
                    })
                }
            },

            listarProducto(buscar,criterio){

                let me=this;

                var url = 'producto/listarProducto?buscar=' + buscar + '&criterio=' + criterio;

                axios.get(url).then(function (response) {
                    // handle success
                    // console.log(response);
                    var respuesta = response.data;
                    me.arrayProducto = respuesta.productos.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            desactivarVenta(id){
                const swalWithBootstrapButtons = Swal.mixin({
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                    })

                    swalWithBootstrapButtons({
                    title: '¿Estás seguro de anular la venta?',
                    
                    // type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                    cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('venta/desactivar',{
                            'id':id
                        }).then(function (response) {
                            // handle success   
                            me.listarVenta(1,'','num_venta'); 
                            swalWithBootstrapButtons.fire(
                                '¡Desactivado!',
                                'La venta a sido anulada con éxito',
                                'success'
                            )
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                        
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        
                    }
                })
            },

            validarVenta(){
                this.errorVenta=0;
                this.errorMostrarMsjVenta=[];

                if(this.idcliente == 0) this.errorMostrarMsjVenta.push("(*)El proveedor no puede estar vacío");
                if(this.tipo_identificacion == 0) this.errorMostrarMsjVenta.push("(*)El tipo de identificacion no puede estar vacío");
                if(!this.num_venta) this.errorMostrarMsjVenta.push("(*)El número de la Venta no puede estar vacío");
                if(!this.impuesto) this.errorMostrarMsjVenta.push("(*)El impuesto de la Venta no puede estar vacío");
                if(this.arrayDetalle.length <= 0) this.errorMostrarMsjVenta.push("(*)El detalle de la Venta no puede estar vacío");
                
                if(this.errorMostrarMsjVenta.length) this.errorVenta=1;

                return this.errorVenta;
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal="";
            },

            abrirModal(){
                    this.arrayProducto = [];
                    this.buscarP = '';
                    this.modal=1;
                    this.tituloModal="Seleccione uno o varios productos";
                    
            }
        },

        mounted() {
            // console.log('Component mounted.')
            this.listarVenta(1,this.buscar,this.criterio);
        }
    }
</script>

<style>
    .modal-content{
        width:100% !important;
        position:absolute !important;
    }

    .mostrar{
        display:list-item !important;
        opacity:1 !important;
        position:absolute !important;
        background-color: #3c29297a !important;
    }

    .div-error{
        display: flex;
        justify-content: center;
    }

    .text-error{
        color: red !important;
        font-weight: bold;
        font-size: 20px;
    }

    @media (min-width: 600px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>

