<template>
   <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="/">BACKEND - SISTEMA DE COMPRAS - VENTAS</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">

               <h2>Listado de Productos</h2><br/>
              
                <button class="btn btn-primary btn-lg" type="button" @click="abrirModal('producto','registrar')">
                    <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Agregar Producto
                </button>
                <button type="button" class="btn btn-success btn-lg" @click="cargarPdf()">
                    <i class="fa fa-file fa-2x"></i>&nbsp;&nbsp;Reporte PDF
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <!-- <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Producto</option>
                                <option value="idcategoria">Categoría</option>
                            </select> -->
                            <input type="text" @keyup.enter="listarProducto(1,buscar,criterio);" v-model="buscar" class="form-control" placeholder="Buscar por Producto o Categoría">
                            <button type="submit" @click="listarProducto(1,buscar,criterio);"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
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
                            <th>Editar</th>
                            <th>Cambiar Estado</th>
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
                                <button type="button" class="btn btn-info btn-md" @click="abrirModal('producto','actualizar',producto)">

                                  <i class="fa fa-edit fa-2x"></i> Editar
                                </button> &nbsp;
                            </td>

                            <td>

                                <template v-if="producto.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarProducto(producto.id)">
                                        <i class="fa fa-lock fa-2x"></i> Desactivar
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-success btn-sm" @click="activarProducto(producto.id)">
                                        <i class="fa fa-lock fa-2x"></i> Activar
                                    </button>
                                </template>
                               
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
                    
                    <div v-show="errorProducto" class="form-group row div-error">
                        
                        <div class="text-center text-error">
                            
                            <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error"></div>

                        </div>
                    
                    </div>
                     

                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                            <div class="col-md-9">
                                <select class="form-control" v-model="idcategoria">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Codigo</label>
                            <div class="col-md-9">
                            <input type="text" v-model="codigo" class="form-control" placeholder="Ingrese código">
                            <barcode :value="codigo" :option="{format:'EAN-13'}">
                                Creando código de barras
                            </barcode>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Producto</label>
                            <div class="col-md-9">
                            <input type="text" v-model="nombre" class="form-control" placeholder="Ingrese nombre del producto">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Precio de Venta</label>
                            <div class="col-md-9">
                            <input type="number" v-model="precio_venta" class="form-control" placeholder="Ingrese precio de venta">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Stock</label>
                            <div class="col-md-9">
                            <input type="number" v-model="stock" class="form-control" placeholder="Ingrese stock">
                            </div>
                        </div>

                        <div class="form group row">
                            <label class="col-md-3 form-control-label" for="imagen-input">Imagen</label>
                            <div class="col-md-9">

                                <div v-if="tipoAccion==1">
                                    <input type="file" @change="subirImagen" class="form-control">
                                    <img :src="imagen" class="image-responsive" width="100px" height="100px">
                                </div>

                                <div v-if="tipoAccion==2">
                                    <input type="file" @change="subirImagen" class="form-control">
                                    <img :src="imagen" class="image-responsive" width="100px" height="100px">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
                    <button type="button" @click="registrarProducto()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
                    <button type="button" @click="actualizarProducto()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Actualizar</button>
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
    import VueBarcode from 'vue-barcode';
    export default {
        
        data(){
            return{
                producto_id:0,
                idcategoria:0,
                nombre:'',
                codigo:'',
                precio_venta:0,
                stock:0,
                imagen:'',
                tituloModal:'',
                arrayProducto:[],
                modal:0,
                tipoAccion:0,
                errorProducto:0,
                errorMostrarMsjProducto:[],
                pagination:{
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset:3,
                criterio: 'nombre',
                buscar: '',
                arrayCategoria:[]

            }    
        },
        components: {
            'barcode': VueBarcode
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

            }
        },

        methods:{
            listarProducto(page,buscar,criterio){

                let me=this;

                var url = 'producto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

                axios.get(url).then(function (response) {
                    // handle success
                    // console.log(response);
                    var respuesta = response.data;
                    me.arrayProducto = respuesta.productos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            cargarPdf()
            {
                window.open('/producto/listarPDF','_blank');
            },

            selectCategoria(){
                let me=this;

                var url = 'categoria/selectCategoria';

                axios.get(url).then(function (response) {
                    // handle success
                    // console.log(response);
                    var respuesta = response.data;
                    me.arrayCategoria = respuesta.categorias;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;

                me.pagination.current_page = page;

                me.listarProducto(page,buscar,criterio);
            },

            subirImagen(e) {
                let me = this;
                let file = e.target.files[0];
                let reader = new FileReader();

                reader.onloadend = (file) => {
                    me.imagen = reader.result;
                }
                reader.readAsDataURL(file);
            },

            registrarProducto(){

                if(this.validarProducto()){
                    return;
                }

                let me=this;

                axios.post('producto/registrar',{
                    'idcategoria':this.idcategoria,
                    'codigo':this.codigo,
                    'nombre':this.nombre,
                    'precio_venta':this.precio_venta,
                    'stock':this.stock,
                    'imagen':this.imagen,
                }).then(function (response) {
                    // handle success
                    // console.log(response);
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');  
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            actualizarProducto(){
                if(this.validarProducto()){
                    return;
                }

                let me=this;

                axios.put('producto/actualizar',{
                    'idcategoria':this.idcategoria,
                    'codigo':this.codigo,
                    'nombre':this.nombre,
                    'precio_venta':this.precio_venta,
                    'stock':this.stock,
                    'imagen':this.imagen,
                    'id':this.producto_id
                }).then(function (response) {
                    // handle success
                    // console.log(response);
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');  
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },

            desactivarProducto(id){
                const swalWithBootstrapButtons = Swal.mixin({
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                    })

                    swalWithBootstrapButtons({
                    title: '¿Estás seguro de desactivar el producto?',
                    
                    // type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                    cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('producto/desactivar',{
                            'id':id
                        }).then(function (response) {
                            // handle success   
                            me.listarProducto(1,'','nombre'); 
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

            activarProducto(id){
                const swalWithBootstrapButtons = Swal.mixin({
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                    })

                    swalWithBootstrapButtons({
                    title: '¿Estás seguro de activar el producto?',
                    
                    // type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
                    cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('producto/activar',{
                            'id':id
                        }).then(function (response) {
                            // handle success   
                            me.listarProducto(1,'','nombre'); 
                            swalWithBootstrapButtons(
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

            validarProducto(){
                this.errorProducto=0;
                this.errorMostrarMsjProducto=[];

                if(!this.idcategoria) this.errorMostrarMsjProducto.push("(*)Debe seleccionar una categoría");
                if(!this.nombre) this.errorMostrarMsjProducto.push("(*)El nombre del producto no puede estar vacío");
                if(!this.codigo) this.errorMostrarMsjProducto.push("(*)El codigo del producto no puede estar vacío");
                if(!this.precio_venta) this.errorMostrarMsjProducto.push("(*)El precio de venta del producto no puede estar vacío");
                if(!this.stock) this.errorMostrarMsjProducto.push("(*)El stock del producto no puede estar vacío");
                if(!this.imagen) this.errorMostrarMsjProducto.push("(*)La imagen del producto no puede estar vacío");
                if(this.errorMostrarMsjProducto.length) this.errorProducto=1;

                return this.errorProducto;
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal="";
                this.nombre="";
                this.idcategoria="";
                this.codigo="";
                this.precio_venta="";
                this.stock="";
                this.imagen="";
                this.errorProducto=0;
            },

            abrirModal(modelo,accion,data=[]){
                switch(modelo){
                    case "producto":
                    {
                        switch(accion){
                            case "registrar":
                            {
                                this.modal=1;
                                this.tituloModal="Registrar Producto";
                                this.tipoAccion=1;
                                this.idcategoria="";
                                this.codigo="";
                                this.nombre="";
                                this.precio_venta="";
                                this.stock="";
                                break;
                            }

                            case "actualizar":
                            {
                                // console.log(data);
                                this.modal=1;
                                this.tituloModal="Editar Producto";
                                this.tipoAccion=2;
                                this.producto_id=data['id'];
                                this.idcategoria=data['idcategoria'];
                                this.codigo=data['codigo'];
                                this.nombre=data['nombre'];
                                this.precio_venta=data['precio_venta'];
                                this.stock=data['stock'];
                                break;
                            }   
                        }
                    }
                }
                this.selectCategoria();
            }
        },

        mounted() {
            // console.log('Component mounted.')
            this.listarProducto(1,this.buscar,this.criterio);
        }
    }
</script>

<style>
    .modal-content{
        width:100% !important;
        position:absolute !important;
    }

    .mostrar{
        height: 1000px;
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
</style>

