<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function () {
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout','Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard','DashboardController');

    Route::get('/main', function () {
        return view('contenido.contenido');
    })->name('main');

    Route::group(['middleware' => ['Comprador', 'Vendedor', 'Administrador']], function (){
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria','CategoriaController@selectCategoria');
        
        Route::get('/producto', 'ProductoController@index');
        Route::post('/producto/registrar', 'ProductoController@store');
        Route::put('/producto/actualizar', 'ProductoController@update');
        Route::put('/producto/desactivar', 'ProductoController@desactivar');
        Route::put('/producto/activar', 'ProductoController@activar');
        Route::get('/producto/buscarProducto', 'ProductoController@buscarProducto');
        Route::get('/producto/listarProducto', 'ProductoController@listarProducto');
        Route::get('/producto/listarPDF', 'ProductoController@listarPDF')->name('productos_pdf');
    });
    
    Route::group(['middleware' => ['Comprador','Administrador']], function () {
        
        
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/compra', 'CompraController@index');
        Route::post('/compra/registrar', 'CompraController@store');
        Route::put('/compra/desactivar', 'CompraController@desactivar');
        Route::get('/compra/obtenerCompra', 'CompraController@obtenerCompra');
        Route::get('/compra/pdf/{id}', 'CompraController@pdf')->name('compra_pdf');
    });

    Route::group(['middleware' => ['Vendedor','Administrador']], function () {
        
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerVenta', 'VentaController@obtenerVenta');
        Route::get('/venta/pdf/{id}', 'VentaController@pdf')->name('venta_pdf');
    });

    Route::group(['middleware' => ['Administrador']], function () {
        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');
        
        Route::get('/usuario', 'UserController@index');
        Route::post('/usuario/registrar', 'UserController@store');
        Route::put('/usuario/actualizar', 'UserController@update');
        Route::put('/usuario/desactivar', 'UserController@desactivar');
        Route::put('/usuario/activar', 'UserController@activar'); 
    });
});

// Route::get('/home', 'HomeController@index')->name('home');
