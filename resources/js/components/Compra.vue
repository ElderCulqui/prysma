<template>
   <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="/">BACKEND - SISTEMA DE COMPRAS - VENTAS</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <!-- Listado de Compras -->
            <template v-if="listado==1">
                <div class="card-header">
                <h2>Listado de Compras</h2><br/>
                    <button class="btn btn-primary btn-lg" type="button" @click="mostrarDetalle()">
                        <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Nueva Compra
                    </button>
                
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="tipo_identificacion">Tipo de Identificación</option>
                                    <option value="num_compra">Número de Compra</option>
                                    <option value="fecha_compra">Fecha de Compra</option>
                                </select>
                                <input type="text" @keyup.enter="listarCompra(1,buscar,criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                                <button type="submit" @click="listarCompra(1,buscar,criterio);"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr class="bg-primary">
                                    <th>Ver Detalle</th>
                                    <th>Fecha Compra</th>
                                    <th>Número Compra</th>
                                    <th>Proveedor</th>
                                    <th>Tipo Identificación</th>
                                    <th>Comprador</th>
                                    <th>Total (S/.)</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>
                                    <th>Cambiar Estado</th>
                                    <th>Descargar Reporte</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                <tr v-for="compra in arrayCompra" :key="compra.id">
                                    
                                    <td>
                                        <button type="button" @click="verCompra(compra.id)" class="btn btn-warning btn-md">
                                            <i class="fa fa-eye fa-2x"></i> Ver Detalle
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="compra.fecha_compra"></td>
                                    <td v-text="compra.num_compra"></td>
                                    <td v-text="compra.proveedor.nombre"></td>
                                    <td v-text="compra.tipo_identificacion"></td>
                                    <td v-text="compra.usuario.nombre"></td>
                                    <td v-text="compra.total"></td>
                                    <td v-text="compra.impuesto"></td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-md" v-if="compra.estado=='Registrado'">
                                        <i class="fa fa-check fa-2x"></i> Registrado
                                        </button>
                                        <button type="button" class="btn btn-danger btn-md" v-else>
                                        <i class="fa fa-check fa-2x"></i> Anulado
                                        </button>
                                    </td>
                                    <td>
                                        <template v-if="compra.estado=='Registrado'">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCompra(compra.id)">
                                                <i class="fa fa-check fa-2x"></i> Anular Compra
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="fa fa-check fa-2x"></i> Cambiado
                                            </button>
                                        </template>
                                    </td>
                                    <td>
                                        <button type="button" @click="pdfCompra(compra.id)" class="btn btn-info btn-sm">
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
                                <label class="text-uppercase"><strong>Nueva Compra(*)</strong></label>
                                <input type="text" class="form-control" v-model="num_compra" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="text-uppercas"><strong>Proveedor(*)</strong></label>
                            
                                <v-select
                                    :on-search="selectProveedor"
                                    label="nombre"
                                    :options="arrayProveedor"
                                    placeholder="Buscar Proveedores..."
                                    :onChange="getDatosProveedor"
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
                                <div v-show="errorCompra" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCompra" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border"> 
                        <div class="col-md-6">
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
                                <button @click="agregarDetalle()" class="btn btn-primary form-control btnagregar">
                                    <i class="fa fa-plus fa-2x"></i>Agregar Detalle
                                </button>
                            </div>
                        </div> 
                    </div>
                    <!-- <br><br> -->
                    <div class="form-grop row border">
                        <h3>Lista de Compras a Proveedores</h3>
                        
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="bg-success">
                                        <th>Eliminar</th>
                                        <th>Producto</th>
                                        <th>Precio (S/.)</th>
                                        <th>Cantidad</th>
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
                                            <input v-model="detalle.precio" type="number" value="3" class="form-control">
                                        </td>
                                        <td>
                                            <input v-model="detalle.cantidad" type="number" value="2" class="form-control">
                                        </td>
                                        <td>
                                            {{ detalle.precio * detalle.cantidad }}
                                        </td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="4" align="right"><strong>Sub-Total:</strong></td>
                                        <td><strong>S/. {{subTotal=(total-subTotalImpuesto).toFixed(2)}}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="4" align="right"><strong>Impuesto:</strong></td>
                                        <td><strong>S/. {{subTotalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="4" align="right"><strong>Total:</strong></td>
                                        <td><strong>S/. {{total=calcularTotal}}</strong></td>
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
                    <div class="col-md-12">
                        <div class="form-group row">
                            <button type="button" class="btn btn-danger" @click="ocultarDetalle()"><i class="fa fa-times fa-2x"></i>Cerrar</button>
                            <button type="button" class="btn btn-success" @click="registrarCompra()"><i class="fa fa-save fa-2x"></i>Registrar Compra</button>
                        </div>
                    </div>
                </div>    
            </template>
            <!-- Fin Detalle Compras -->

            <!-- Ver Compra -->
            <template v-else-if="listado==2">
                <h2 class="text-center">Detalle Compra</h2>
                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="text-uppercase"><strong>Proveedor</strong></label>
                                <p v-text="proveedor"></p>
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
                                <label for="" class="text-uppercase"><strong>Número Compra</strong></label>
                                <p v-text="num_compra"></p>
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
                                        <th>Total (S/.)</th>   
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <td v-text="detalle.productos.nombre"></td>
                                        <td v-text="detalle.precio"></td>
                                        <td v-text="detalle.cantidad"></td>
                                        <td>
                                            {{ detalle.precio * detalle.cantidad }}
                                        </td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="3" align="right"><strong>Sub-Total:</strong></td>
                                        <td><strong>S/.{{ subTotal=(total-subTotalImpuesto).toFixed(2) }}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="3" align="right"><strong>Impuesto:</strong></td>
                                        <td><strong>S/.{{ subTotalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2) }}</strong></td>
                                    </tr>
                                    <tr style="background-color: grey;">
                                        <td colspan="3" align="right"><strong>Total:</strong></td>
                                        <td><strong>S/.{{total}}</strong></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="4">
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
                            <th>Imagen</th>
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
                                <img :src="'img/producto/' + producto.imagen" class="img-responsive" width="100px" height="100px">
                            </td>
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
                compra_id:0,
                idproveedor:0,
                proveedor:'',
                nombre:'',
                tipo_identificacion:'FACTURA',
                num_compra:'',
                impuesto:0.18,
                total:0.00,
                subTotalImpuesto:0.00,
                subTotal:0.00,
                arrayCompra:[],
                arrayProveedor:[],
                arrayDetalle:[],
                listado:1,
                tituloModal:'',
                modal:0,
                tipoAccion:0,
                errorCompra:0,
                errorMostrarMsjCompra:[],
                pagination:{
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset:3,
                criterio: 'num_compra',
                buscar: '',
                criterioP: 'nombre',
                buscarP: '',
                arrayProducto:[],
                idproducto:0,
                codigo:'',
                producto:'',
                precio:0,
                cantidad:0

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

            calcularTotal: function(){
                var resultado = 0.0;
                for(var i=0; i<this.arrayDetalle.length; i++){
                    resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad);  
                }
                return resultado;
            },
        },

        methods:{
            listarCompra(page,buscar,criterio){

                let me=this;

                var url = 'compra?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

                axios.get(url).then(function (response) {
                    // handle success
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayCompra = respuesta.compras.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            pdfCompra(id){
                window.open('/compra/pdf/'+id+','+'_blank');
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;

                me.pagination.current_page = page;

                me.listarCompra(page,buscar,criterio);
            },

            mostrarDetalle(){

                let me = this;

                me.listado=0;
                me.idproveedor=0;
                me.tipo_identificacion = 'FACTURA';
                me.num_compra = '';
                me.impuesto = 0.18;  
                me.total = 0.0;
                me.idproducto = 0;
                me.producto = '';
                me.cantidad = 0;
                me.precio = 0;
                me.arrayDetalle = [];
            },

            ocultarDetalle(){
                this.listado = 1;
                me.proveedor = '';
                me.tipo_identificacion = 'FACTURA';
                me.num_compra = '';
                me.impuesto = 0.18;
                me.total = 0.0;
                me.arrayDetalle = [];
            },

            verCompra(id){
                let me = this;
                me.listado=2;
                var compra
                var url = 'compra/obtenerCompra?id='+id;
                
                axios.get(url).then(function (response) {
                    // handle success
                    //console.log(compra);
                    var respuesta = response.data;
                    compra = respuesta.compra[0];

                    me.proveedor = compra.proveedor.nombre;
                    me.num_compra = compra.num_compra;
                    me.tipo_identificacion = compra.tipo_identificacion;
                    me.impuesto = compra.impuesto;
                    me.total = compra.total;
                    me.arrayDetalle = compra.detalle_compra;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            registrarCompra(){

                if(this.validarCompra()){
                    return;
                }

                let me=this;

                axios.post('compra/registrar',{
                    'idproveedor':this.idproveedor,
                    'tipo_identificacion':this.tipo_identificacion,
                    'num_compra':this.num_compra,
                    'impuesto':this.impuesto,
                    'total':this.total,
                    'data':this.arrayDetalle
                }).then(function (response) {
                    me.listado = 1;
                    me.listarCompra(1,'','num_compra');
                    me.idproveedor=0;
                    me.tipo_identificacion = 'FACTURA';
                    me.num_compra = '';
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

            selectProveedor(search,loading){
                let me=this;
                loading(true)
                var url = 'proveedor/selectProveedor?filtro='+search;

                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q:search
                    me.arrayProveedor = respuesta.proveedores;
                    console.log(respuesta)
                    loading(false)
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
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
                        me.arrayDetalle.push({
                            idproducto: me.idproducto,
                            producto: me.producto,
                            cantidad: me.cantidad,
                            precio: me.precio
                        });
                        me.codigo="";
                        me.idproducto=0;
                        me.producto="";
                        me.cantidad=0;
                        me.precio=0;
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
                        precio: data['precio_venta']
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

            desactivarCompra(id){
                const swalWithBootstrapButtons = Swal.mixin({
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                    })

                    swalWithBootstrapButtons({
                    title: '¿Estás seguro de anular la compra?',
                    
                    // type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                    cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('compra/desactivar',{
                            'id':id
                        }).then(function (response) {
                            // handle success   
                            me.listarCompra(1,'','num_comra'); 
                            swalWithBootstrapButtons.fire(
                                '¡Desactivado!',
                                'La compra a sido anulada con éxito',
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

            validarCompra(){
                this.errorCompra=0;
                this.errorMostrarMsjCompra=[];

                if(this.idproveedor == 0) this.errorMostrarMsjCompra.push("(*)El proveedor no puede estar vacío");
                if(this.tipo_identificacion == 0) this.errorMostrarMsjCompra.push("(*)El tipo de identificacion no puede estar vacío");
                if(!this.num_compra) this.errorMostrarMsjCompra.push("(*)El número de la Compra no puede estar vacío");
                if(!this.impuesto) this.errorMostrarMsjCompra.push("(*)El impuesto de la Compra no puede estar vacío");
                if(this.arrayDetalle.length <= 0) this.errorMostrarMsjCompra.push("(*)El detalle de la Compra no puede estar vacío");
                
                if(this.errorMostrarMsjCompra.length) this.errorCompra=1;

                return this.errorCompra;
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
            this.listarCompra(1,this.buscar,this.criterio);
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

