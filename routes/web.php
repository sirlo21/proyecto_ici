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
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();




Route::group(['middleware' => 'auth'], function () {
  
    //Ruta del Home de Administración
    Route::get('/home', 'HomeController@index');

    //Rutas del Usuario
    Route::resource('users', 'Admin\UserController');
    Route::get('/listado_usuarios', 'Admin\UsuariosController@listado_usuarios');
    Route::post('crear_usuario', 'Admin\UsuariosController@crear_usuario');
    Route::post('editar_usuario', 'Admin\UsuariosController@editar_usuario');
    Route::post('editar_acceso', 'Admin\UsuariosController@editar_acceso');
    Route::post('buscar_usuario', 'Admin\UsuariosController@buscar_usuario');
    Route::post('borrar_usuario', 'Admin\UsuariosController@borrar_usuario');
    Route::get('confirmacion_borrado_usuario/{idusuario}', 'Admin\UsuariosController@confirmacion_borrado_usuario');
  
    //Rutas deRoles
    Route::post('crear_rol', 'Admin\UsuariosController@crear_rol');
    Route::get('asignar_rol/{idusu}/{idrol}', 'Admin\UsuariosController@asignar_rol');
    Route::get('quitar_rol/{idusu}/{idrol}', 'Admin\UsuariosController@quitar_rol');
    Route::get('borrar_rol/{idrol}', 'Admin\UsuariosController@borrar_rol');
    
    //Rutas de Permiso
    Route::post('crear_permiso', 'Admin\UsuariosController@crear_permiso');
    Route::post('asignar_permiso', 'Admin\UsuariosController@asignar_permiso');
    Route::get('quitar_permiso/{idrol}/{idper}', 'Admin\UsuariosController@quitar_permiso');

    //Rutas de Formulario de Usuario
    Route::get('form_nuevo_usuario', 'Admin\UsuariosController@form_nuevo_usuario');
    Route::get('form_nuevo_rol', 'Admin\UsuariosController@form_nuevo_rol');
    Route::get('form_nuevo_permiso', 'Admin\UsuariosController@form_nuevo_permiso');
    Route::get('form_editar_usuario/{id}', 'Admin\UsuariosController@form_editar_usuario');
    Route::get('form_borrado_usuario/{idusu}', 'Admin\UsuariosController@form_borrado_usuario');

    ///Rutas para establecimiento
    Route::resource('establecimientos', 'Admin\EstablecimientoController');
    Route::get('establecimiento/medicamentos/{establecimiento_id}', 'Admin\EstablecimientoController@ver_medicamentos')->name('establecimientos.ver_medicamentos');
    Route::get('establecimiento/asignar_medicamentos/{establecimiento_id}', 'Admin\EstablecimientoController@asignar_medicamentos')->name('establecimientos.asignar_medicamentos');
    Route::put('establecimiento/asignar_medicamentos/{establecimiento_id}', 'Admin\EstablecimientoController@guardar_medicamentos')->name('establecimientos.guardar_medicamentos');
    Route::patch('establecimiento/asignar_medicamentos/{establecimiento_id}', 'Admin\EstablecimientoController@guardar_medicamentos')->name('establecimientos.guardar_medicamentos');
    Route::put('establecimiento/cargar_datos/{establecimiento_id}', 'Admin\EstablecimientoController@cargar_datos_medicamentos')->name('establecimientos.cargar_datos_medicamentos');
    Route::patch('establecimiento/cargar_datos/{establecimiento_id}', 'Admin\EstablecimientoController@cargar_datos_medicamentos')->name('establecimientos.cargar_datos_medicamentos');
    Route::get('establecimiento/dispositivos/{establecimiento_id}', 'Admin\EstablecimientoController@ver_dispositivos')->name('establecimientos.ver_dispositivos');
    Route::get('establecimiento/asignar_dispositivos/{establecimiento_id}', 'Admin\EstablecimientoController@asignar_dispositivos')->name('establecimientos.asignar_dispositivos');
    Route::put('establecimiento/asignar_dispositivos/{establecimiento_id}', 'Admin\EstablecimientoController@guardar_dispositivos')->name('establecimientos.guardar_dispositivos');
    Route::patch('establecimiento/asignar_dispositivos/{establecimiento_id}', 'Admin\EstablecimientoController@guardar_dispositivos')->name('establecimientos.guardar_dispositivos');

    //Rutas ICI - Admin
    Route::resource('icis', 'Admin\IciController');
    Route::get('icis/establecimientos/mostrar_servicios/{ici_id}/{establecimiento_id}', 'Admin\IciController@mostrar_servicios')->name('icis.mostrar_servicios');
    Route::get('icis/activar_servicio/{ici_id}/{establecimiento_id}/{farmacia_id}', 'Admin\IciController@activar_servicio')->name('icis.activar_servicio');
    Route::patch('icis/establecimiento/activar_servicio/{ici_id}/{establecimiento_id}/{farmacia_id}', 'Admin\IciController@update_petitorio_servicio')->name('icis.update_petitorio_servicio');

    Route::get('icis/activar/{ici_id}/{establecimiento_id}', 'Admin\IciController@activar')->name('icis.activar');
    Route::patch('icis/activar/{ici_id}/{establecimiento_id}', 'Admin\IciController@update_petitorio')->name('icis.update_petitorio');
    Route::get('icis/medicamentos/{ici_id}/{establecimiento_id}', 'Admin\IciController@medicamentos')->name('icis.medicamentos');
    Route::get('icis/dispositivos/{ici_id}/{establecimiento_id}', 'Admin\IciController@dispositivos')->name('icis.dispositivos');
    //Route::get('icis/medicamentos/{establecimiento_id}', 'Admin\IciController@cargar_medicamentos')->name('icis.cargar_medicamentos');
    //Route::get('icis/dispositivos/{establecimiento_id}', 'Admin\IciController@cargar_dispositivos')->name('icis.cargar_dispositivos');
    Route::get('icis/abastecimiento/medicamentos/{ici_id}/{establecimiento_id}', 'Admin\IciController@medicamentos_abastecimientos')->name('icis.medicamentos_abastecimientos');
    Route::get('icis/abastecimiento/dispositivos/{ici_id}/{establecimiento_id}', 'Admin\IciController@dispositivos_abastecimientos')->name('icis.dispositivos_abastecimientos');

    //Rutas ICI - Site
    Route::resource('abastecimiento', 'Site\IciController');
    Route::get('abastecimiento/cerrar_medicamento/{ici_id}/{establecimiento_id}', 'Site\IciController@cerrar_medicamento')->name('abastecimiento.cerrar_medicamento');
    Route::get('abastecimiento/establecimiento/cerrar_medicamento_servicio/{ici_id}/{establecimiento_id}/{farmacia_id}', 'Site\IciController@cerrar_medicamento_servicio')->name('abastecimiento.cerrar_medicamento_servicio');
    //Route::get('abastecimiento/medicamentos/{ici_id}/{establecimiento_id}', 'Site\IciController@medicamentos')->name('abastecimiento.medicamentos');
    //Route::get('abastecimiento/dispositivos/{ici_id}/{establecimiento_id}', 'Site\IciController@dispositivos')->name('abastecimiento.dispositivos');
    Route::get('abastecimiento/medicamentos/{ici_id}/{establecimiento_id}', 'Site\IciController@cargar_medicamentos')->name('abastecimiento.cargar_medicamentos');
    Route::get('abastecimiento/medicamentos/descargar/{ici_id}/{establecimiento_id}', 'Site\IciController@descargar_medicamentos')->name('abastecimiento.descargar_medicamentos');
    Route::get('abastecimiento/dispositivos/{ici_id}/{establecimiento_id}', 'Site\IciController@cargar_dispositivos')->name('abastecimiento.cargar_dispositivos');
    Route::get('abastecimiento/cerrar_dispositivos/{ici_id}/{establecimiento_id}', 'Site\IciController@cerrar_dispositivos')->name('abastecimiento.cerrar_dispositivos');
    //Route::get('abastecimiento/medicamentos/{ici_id}/{establecimiento_id}', 'Site\IciController@medicamentos_abastecimientos')->name('abastecimiento.medicamentos_abastecimientos');
    //Route::get('abastecimiento/abastecimiento/dispositivos/{establecimiento_id}', 'Site\IciController@dispositivos_abastecimientos')->name('abastecimiento.dispositivos_abastecimientos');
    Route::get('abastecimiento/dispositivos/descargar/{ici_id}/{establecimiento_id}', 'Site\IciController@descargar_dispositivos')->name('abastecimiento.descargar_dispositivos');
    Route::get('abastecimiento/editar_abastecimiento/{abastecimiento_id}', 'Site\IciController@editar_abastecimiento')->name('abastecimiento.editar_abastecimiento');
    Route::patch('abastecimiento/editar_abastecimiento/{abastecimiento_id}', 'Site\IciController@update_abastecimiento')->name('abastecimiento.update_abastecimiento');

    //Otras Rutas
    Route::resource('regions', 'Admin\RegionController');
    Route::resource('nivels', 'Admin\NivelController');
    Route::resource('categorias', 'Admin\CategoriaController');
    Route::resource('tipoEstablecimientos', 'Admin\TipoEstablecimientoController');
    Route::resource('tipoInternamientos', 'Admin\TipoInternamientoController');
    Route::resource('disas', 'Admin\DisaController');
    Route::resource('departamentos','Admin\DepartamentoController');
    Route::resource('provincias', 'Admin\ProvinciaController');
    Route::get('provincias/{id}','Admin\DistritoController@getProvincias')->name('distritos.getProvincias');
    Route::resource('distritos', 'Admin\DistritoController');
    Route::resource('tipoDispositivoMedicos', 'Admin\TipoDispositivoMedicoController');
    Route::resource('unidadMedidas', 'Admin\UnidadMedidaController');
    Route::resource('tipoUsos', 'Admin\TipoUsoController');
    Route::resource('petitorios', 'Admin\PetitorioController');
    Route::resource('grados', 'Admin\GradoController');

    // Route::resource('icis/abastecimientos', 'Admin\AbastecimientoController');
    Route::get('abastecimiento-export/{ici_id}/{establecimiento_id}/{opt}/{type}', 'Site\IciController@exportAbastecimientoData')->name('abastecimiento.exportAbastecimientoData');

    Route::get('api/contact/{ici_id}/{establecimiento_id}/{anomes}/{tipo}', 'Site\IciController@apiContact')->name('api.contact');

    Route::resource('years', 'Admin\YearController');

    Route::resource('farmacias', 'Admin\FarmaciaController');
    Route::get('ver_farmacia/{establecimiento_id}/{nivel_id}', 'Admin\FarmaciaController@ver_farmacia')->name('farmacias.ver_farmacia');
    Route::get('crear_farmacia/{establecimiento_id}/{nivel_id}', 'Admin\FarmaciaController@crear_farmacia')->name('farmacias.crear_farmacia');
    Route::get('editar/{id}/{establecimiento_id}/{nivel_id}', 'Admin\FarmaciaController@editar')->name('farmacias.editar');
    Route::delete('eliminar/{id}/{establecimiento_id}/{nivel_id}', 'Admin\FarmaciaController@eliminar')->name('farmacias.eliminar');

    Route::get('establecimiento/farmacia/medicamentos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@ver_medicamentos')->name('farmacias.ver_medicamentos');
    Route::get('establecimiento/farmacia/asignar_medicamentos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@asignar_medicamentos')->name('farmacias.asignar_medicamentos');
    Route::put('establecimiento/farmacia/asignar_medicamentos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@guardar_medicamentos')->name('farmacias.guardar_medicamentos');
    Route::patch('establecimiento/farmacia/asignar_medicamentos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@guardar_medicamentos')->name('farmacias.guardar_medicamentos');
    Route::put('establecimiento/farmacia/cargar_datos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@cargar_datos_medicamentos')->name('farmacias.cargar_datos_medicamentos');
    Route::patch('establecimiento/farmacia/cargar_datos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@cargar_datos_medicamentos')->name('farmacias.cargar_datos_medicamentos');
    Route::get('establecimiento/farmacia/dispositivos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@ver_dispositivos')->name('farmacias.ver_dispositivos');
    Route::get('establecimiento/farmacia/asignar_dispositivos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@asignar_dispositivos')->name('farmacias.asignar_dispositivos');
    Route::put('establecimiento/farmacia/asignar_dispositivos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@guardar_dispositivos')->name('farmacias.guardar_dispositivos');
    Route::patch('establecimiento/farmacia/asignar_dispositivos/{establecimiento_id}/{farmacia_id}', 'Admin\FarmaciaController@guardar_dispositivos')->name('farmacias.guardar_dispositivos');

    Route::get('abastecimiento/asignar_medicamentos/{ici_id}/{establecimiento_id}', 'Site\IciController@asignar_medicamentos')->name('abastecimiento.asignar_medicamentos');
    Route::put('abastecimiento/asignar_medicamentos/{establecimiento_id}', 'Site\IciController@guardar_medicamentos')->name('abastecimiento.guardar_medicamentos');
    Route::patch('abastecimiento/asignar_medicamentos/{establecimiento_id}', 'Site\IciController@guardar_medicamentos')->name('abastecimiento.guardar_medicamentos');

   /* Route::get('GetEstablecimientos','Admin\UserController@GetEstablecimientos')->name('GetEstablecimientos'); 
    Route::get('GetFarmacias/{establecimiento_id}','Admin\UserController@GetFarmacias')->name('GetFarmacias'); 
    */
});

// Route::get('api/contact', 'Admin\AbastecimientoController@apiContact')->name('api.contact');
