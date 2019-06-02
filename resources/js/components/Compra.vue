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
            <template v-if="listado">
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
                                            <i class="fa fa-lock fa-2x"></i> Anular Compra
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-success btn-sm">
                                            <i class="fa fa-lock fa-2x"></i> Cambiado
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
            <template v-else>
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
                                <select class="form-control" v-model="tipo_identificaion">
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
                                    <button @click="abriModal()" class="btn btn-primary">
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
                    
                    <div v-show="errorUsuario" class="form-group row div-error">
                        
                        <div class="text-center text-error">
                            
                            <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error"></div>

                        </div>
                    
                    </div>
                     
                </div>
                <div class="modal-footer">
                    <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
                    <button type="button" @click="registrarUsuario()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
                    <button type="button" @click="actualizarUsuario()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Actualizar</button>
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

            cambiarPagina(page,buscar,criterio){
                let me = this;

                me.pagination.current_page = page;

                me.listarCompra(page,buscar,criterio);
            },

            mostrarDetalle(){
                this.listado=0;
            },

            ocultarDetalle(){
                this.listado=1;
            },

            registrarUsuario(){

                if(this.validarUsuario()){
                    return;
                }

                let me=this;

                axios.post('usuario/registrar',{
                    'idrol':this.idrol,
                    'nombre':this.nombre,
                    'tipo_documento':this.tipo_documento,
                    'num_documento':this.num_documento,
                    'direccion':this.direccion,
                    'telefono':this.telefono,
                    'email':this.email,
                    'usuario':this.usuario,
                    'password':this.password,
                }).then(function (response) {
                    // handle success
                    // console.log(response);
                    me.cerrarModal();
                    me.listarUsuario(1,'','nombre');  
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            actualizarUsuario(){
                if(this.validarUsuario()){
                    return;
                }

                let me=this;

                axios.put('usuario/actualizar',{
                    'idrol':this.idrol,
                    'nombre':this.nombre,
                    'tipo_documento':this.tipo_documento,
                    'num_documento':this.num_documento,
                    'direccion':this.direccion,
                    'telefono':this.telefono,
                    'email':this.email,
                    'usuario':this.usuario,
                    'password':this.password,
                    'id':this.proveedor_id,
                }).then(function (response) {
                    // handle success
                    // console.log(response);
                    me.cerrarModal();
                    me.listarUsuario(1,'','nombre');  
                })
                .catch(function (error) {
                    // handle error
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

            desactivarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                    })

                    swalWithBootstrapButtons({
                    title: '¿Estás seguro de desactivar el usuario?',
                    
                    // type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                    cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('usuario/desactivar',{
                            'id':id
                        }).then(function (response) {
                            // handle success   
                            me.listarUsuario(1,'','nombre'); 
                            swalWithBootstrapButtons.fire(
                                '¡Desactivado!',
                                'El registro a sido desactivado con éxito',
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

            activarUsuario(id){
                const swalWithBootstrapButtons1 = Swal.mixin({
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                    })

                    swalWithBootstrapButtons1({
                    title: '¿Estás seguro de activar el usuario?',
                    
                    // type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                    cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('usuario/activar',{
                            'id':id
                        }).then(function (response) {
                            // handle success   
                            me.listarUsuario(1,'','nombre'); 
                            swalWithBootstrapButtons1(
                                '¡Activado!',
                                'El registro a sido activado con éxito',
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

            validarUsuario(){
                this.errorUsuario=0;
                this.errorMostrarMsjUsuario=[];

                if(!this.nombre) this.errorMostrarMsjUsuario.push("(*)El nombre no puede estar vacío");
                if(!this.idrol) this.errorMostrarMsjUsuario.push("(*)El rol no puede estar vacío");
                if(!this.tipo_documento) this.errorMostrarMsjUsuario.push("(*)El tipo de documento del Usuario no puede estar vacío");
                if(!this.num_documento) this.errorMostrarMsjUsuario.push("(*)El numero de documento del Usuario no puede estar vacío");
                if(!this.usuario) this.errorMostrarMsjUsuario.push("(*)El usuario no puede estar vacío");
                if(this.errorMostrarMsjUsuario.length) this.errorUsuario=1;

                return this.errorUsuario;
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal="";
                this.nombre="";
                this.tipo_documento="DNI";
                this.nun_documento=""; 
                this.direccion="";
                this.telefono="";
                this.email="";
                this.password="";
                this.usuario="";
                this.idrol="";
                this.errorUsuario=0;
            },

            abrirModal(modelo,accion,data=[]){
                switch(modelo){
                    case "usuario":
                    {
                        switch(accion){
                            case "registrar":
                            {
                                this.modal=1;
                                this.tituloModal="Registrar Usuario";
                                this.tipoAccion=1;
                                this.idrol="";
                                this.nombre="";
                                this.tipo_documento="DNI";
                                this.num_documento="";
                                this.direccion="";
                                this.telefono="";
                                this.email="";
                                this.usuario="";
                                this.password="";
                                break;
                            }

                            case "actualizar":
                            {
                                // console.log(data);
                                this.modal=1;
                                this.tituloModal="Editar Usuario";
                                this.tipoAccion=2;
                                this.proveedor_id=data['id'];
                                this.idrol=data['idrol'];
                                this.nombre=data['nombre'];
                                this.tipo_documento=data['tipo_documento'];
                                this.num_documento=data['num_documento'];
                                this.direccion=data['direccion'];
                                this.telefono=data['telefono'];
                                this.email=data['email'];
                                this.usuario=data['usuario'];
                                this.password=data['password'];
                                break;
                            }   
                        }
                    }
                }
                this.selectRol();
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

