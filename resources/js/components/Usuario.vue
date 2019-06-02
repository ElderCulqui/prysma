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

               <h2>Listado de Usuarios</h2><br/>
              
                <button class="btn btn-primary btn-lg" type="button" @click="abrirModal('usuario','registrar')">
                    <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Agregar Usuario
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Nombre o Rol</option>
                                <option value="num_documento">Número Documento</option>
                                <option value="email">Email</option>
                            </select>
                            <input type="text" @keyup.enter="listarUsuario(1,buscar,criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                            <button type="submit" @click="listarUsuario(1,buscar,criterio);"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr class="bg-primary">
                           
                            <th>Nombre</th>
                            <th>Tipo Documento</th>
                            <th>Número Documento</th>
                            <th>Rol</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Editar</th>
                            <th>Cambiar Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        <tr v-for="usuario in arrayUsuario" :key="usuario.id">
                            
                            <td v-text="usuario.nombre"></td>
                            <td v-text="usuario.tipo_documento"></td>
                            <td v-text="usuario.num_documento"></td>
                            <td v-text="usuario.rol.nombre"></td>
                            <td v-text="usuario.telefono"></td>
                            <td v-text="usuario.email"></td>
                            <td v-text="usuario.usuario"></td>
                            <td>
                                <button type="button" class="btn btn-success btn-md" v-if="usuario.condicion">
                                  <i class="fa fa-check fa-2x"></i> Activo
                                </button>
                                <button type="button" class="btn btn-danger btn-md" v-else>
                                  <i class="fa fa-check fa-2x"></i> Desactivado
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-md" @click="abrirModal('usuario','actualizar',usuario)">

                                  <i class="fa fa-edit fa-2x"></i> Editar
                                </button> &nbsp;
                            </td>
                            <td>

                                <template v-if="usuario.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(usuario.id)">
                                        <i class="fa fa-lock fa-2x"></i> Desactivar
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-success btn-sm" @click="activarUsuario(usuario.id)">
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
                    
                    <div v-show="errorUsuario" class="form-group row div-error">
                        
                        <div class="text-center text-error">
                            
                            <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error"></div>

                        </div>
                    
                    </div>
                     

                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                            <div class="col-md-9">
                                <select class="form-control" v-model="idrol">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre(*)</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Tipo de Documento</label>
                            <div class="col-md-9">
                                <select v-model="tipo_documento" class="form-control">
                                    <option value="DNI">DNI</option>
                                    <option value="CEDULA">CEDULA</option>
                                    <option value="PASS">PASS</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Número de Documento</label>
                            <div class="col-md-9">
                                <input type="text" v-model="num_documento" class="form-control" placeholder="Número">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                            <div class="col-md-9">
                                <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                            <div class="col-md-9">
                                <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Email</label>
                            <div class="col-md-9">
                                <input type="text" v-model="email" class="form-control" placeholder="Email">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                            <div class="col-md-9">
                                <input type="text" v-model="usuario" class="form-control" placeholder="Usuario">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Password</label>
                            <div class="col-md-9">
                                <input type="password" v-model="password" class="form-control" placeholder="Password">
                               
                            </div>
                        </div>
                    </form>
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
    export default {
        
        data(){
            return{
                usuario_id:0,
                idrol:0,
                nombre:'',
                tipo_documento:'',
                num_documento:'',
                direccion:'',
                telefono:'',
                email:'',
                usuario:'',
                password:'',
                tituloModal:'',
                arrayUsuario:[],
                arrayRol:[],
                modal:0,
                tipoAccion:0,
                errorUsuario:0,
                errorMostrarMsjUsuario:[],
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
                buscar: ''

            }    
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
            listarUsuario(page,buscar,criterio){

                let me=this;

                var url = 'usuario?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

                axios.get(url).then(function (response) {
                    // handle success
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayUsuario = respuesta.usuarios.data;
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

                me.listarUsuario(page,buscar,criterio);
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

            selectRol(){
                let me=this;

                var url = 'rol/selectRol';

                axios.get(url).then(function (response) {
                    // handle success
                    // console.log(response);
                    var respuesta = response.data;
                    me.arrayRol = respuesta.roles;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
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
            this.listarUsuario(1,this.buscar,this.criterio);
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
</style>

