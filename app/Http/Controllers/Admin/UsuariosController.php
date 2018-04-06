<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;
use App\Models\Establecimiento;
use App\Models\Farmacia;
use App\Models\Grado;

//class UsuariosController extends Controller
class UsuariosController extends AppBaseController
{
  public function form_nuevo_usuario(){
    //carga el formulario para agregar un nuevo usuario
    $roles=Role::all();
    //Cargamos todos los establecimientos
    $establecimiento_id=Establecimiento::pluck('nombre_establecimiento','id');
    //Cargamos los grados
    $grado_id=Grado::pluck('descripcion','id');

    $farmacia_id=Farmacia::pluck('descripcion','id');
    
    //enviamos la vista
    return view("admin.usuarios.formularios.form_nuevo_usuario")->with("establecimiento_id",$establecimiento_id)->with("grado_id",$grado_id)->with("roles",$roles)->with("farmacia_id",$farmacia_id);
  }


  public function form_nuevo_rol(){
    //Cargamos los roles
    $roles=Role::all();
    //Enviamos la vista
    return view("admin.usuarios.formularios.form_nuevo_rol")->with("roles",$roles);
  }

  public function form_nuevo_permiso(){
    //carga el formulario para agregar un nuevo rol
     $roles=Role::all();
     //carga el formulario para agregar un nuevo permiso
     $permisos=Permission::all();
     //Enviamos la vista
    return view("admin.usuarios.formularios.form_nuevo_permiso")->with("roles",$roles)->with("permisos", $permisos);
  }

  public function listado_usuarios(){
    //presenta un listado de usuarios paginados de 100 en 100
    $usuarios=User::paginate(100);
    //enviamos la vista
    return view("admin.usuarios.listados.listado_usuarios")->with("usuarios",$usuarios);
  }

  public function crear_usuario(Request $request){
    //crea un nuevo usuario en el sistema
    //reglas de validación
    $reglas=[ 'password' => 'required|min:8',
              'email' => 'required|email|unique:users',
              'telefono'=>'required|min:9',
              'grado_id'=>'required',
              'dni'=>'required|min:8|unique:users',
              'establecimiento_id'=>'required'
                  ];
    //mensaje de error
    $mensajes=[  'password.min' => 'El password debe tener al menos 8 caracteres',
                 'email.unique' => 'El email ya se encuentra registrado en la base de datos',
                 'dni.unique' => 'El dni ya se encuentra registrado en la base de datos',
               ];
    //validamos las reglas
    $validator = Validator::make( $request->all(),$reglas,$mensajes );
    //Si hay errores retornamos mensaje
    if( $validator->fails() ){ 
        return view("admin.usuarios.mensajes.mensaje_error")->with("msj","...Existen errores...")
                                            ->withErrors($validator->errors());         
    }

    

    //Creamos un nuevo usuario
    $usuario=new User;
    //Concatenamos  el nombre y apellido en mayuscula
    $usuario->name=strtoupper( $request->input("nombres")." ".$request->input("apellidos") ) ;
    //Asignamos el dni
    $usuario->dni=$request->input("dni") ;
    //Asignamos el nombre
    $usuario->nombres=strtoupper( $request->input("nombres") ) ;
    //Asignamos el apellido
    $usuario->apellidos=strtoupper( $request->input("apellidos") ) ;
    //Asignamos el telefono
    $usuario->telefono=$request->input("telefono");
    //Asignamos el establecimiento
    $usuario->establecimiento_id=$request->input("establecimiento_id");
    //Buscamos la descripcion del grado
    $grado = Grado::findOrFail($usuario->grado_id);
    //Buscamos el nombre del establecimiento
    $establecimiento = Establecimiento::findOrFail($usuario->establecimiento_id);
    //asignamos el nombre del establecimiento
    $usuario->nombre_establecimiento=$establecimiento->nombre_establecimiento;
    //Buscamos el nombre del establecimiento
    //Asignamos el grado
    $usuario->grado_id=$request->input("grado_id");
    //Buscamos el nombre del establecimiento
    $grado = Grado::findOrFail($usuario->grado_id);
    //asignamos el nombre del establecimiento
    $usuario->grado=$grado->descripcion;
    //Asignamos el email
    $usuario->email=$request->input("email");
    //Asignamos el password
    $usuario->password= bcrypt( $request->input("password") ); 
    //Guardamos el usuario
    

    if($usuario->save())
    {
      return view("admin.usuarios.mensajes.msj_usuario_creado")->with("msj","Usuario agregado correctamente") ;
    }
    else
    {
        return view("admin.usuarios.mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ;...") ;
    }
  }

  public function crear_rol(Request $request){

    //Creamos un nuevo Rol
    $rol=new Role;
    //Asignamos un nombre al rol
    $rol->name=$request->input("rol_nombre") ;
    //Asignamos un slug al rol
    $rol->slug=$request->input("rol_slug") ;
    //Asignamos una descripcion
    $rol->description=$request->input("rol_descripcion") ;
    //Guardamos el rol
    if($rol->save())
    {
        return view("admin.usuarios.mensajes.msj_rol_creado")->with("msj","Rol agregado correctamente") ;
    }
    else
    {
        return view("admin.usuarios.mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ;...") ;
    }
  }

  public function crear_permiso(Request $request){
  
    //Creamos un nuevo permiso
    $permiso=new Permission;
    //Asignamos el nombre al permiso
    $permiso->name=$request->input("permiso_nombre") ;
    //Asignamos el slug al permiso
    $permiso->slug=$request->input("permiso_slug") ;
    //Asignamos la descripcion
    $permiso->description=$request->input("permiso_descripcion") ;
    //Guardamos el permiso
    if($permiso->save())
      {
        return view("admin.usuarios.mensajes.msj_permiso_creado")->with("msj","Permiso creado correctamente") ;
      }
      else
      {
        return view("admin.usuarios.mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ;...") ;
      }
  }

  public function asignar_permiso(Request $request){

    //Recupermos el rol seleccionado
    $roleid=$request->input("rol_sel");
    //Recuperamos el permiso
    $idper=$request->input("permiso_rol");
    //Recuperamos el id del rol
    $rol=Role::find($roleid);
    //Asignamos permiso 
    $rol->assignPermission($idper);
      
    //Guardamos el rol  
    if($rol->save())
    {
      return view("admin.usuarios.mensajes.msj_permiso_creado")->with("msj","Permiso asignado correctamente") ;
    }
    else
    {
      return view("admin.usuarios.mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ;...") ;
    }
  }

  public function form_editar_usuario($id){
  
    //Buscamos el usuario
    $usuario=User::find($id);
    //Consultamos la relacion de roles
    $roles=Role::all();
    //Enviamos los grados
    $grados=Grado::all();
    //Enviamos los establecimientos
    $establecimientos=Establecimiento::all();
    

    return view("admin.usuarios.formularios.form_editar_usuario")->with("usuario",$usuario)
                                                    ->with("roles",$roles)
                                                    ->with('grados',$grados)
                                                    ->with('establecimientos',$establecimientos);       
  }

  public function editar_usuario(Request $request){
            
    //Recuperamos el id del usuario
    $idusuario=$request->input("id_usuario");
    //Buscamos al usuario
    $usuario=User::find($idusuario);
    //Asignamos el nombre ingresado
    $usuario->dni=$request->input("dni") ;
    //Asignamos el nombre ingresado
    $usuario->nombres=strtoupper( $request->input("nombres") ) ;
    //Asignamos el apellido ingresado
    $usuario->apellidos=strtoupper( $request->input("apellidos") ) ;
    //Concatenamos el nombre y apellido
    $usuario->name=$usuario->nombres.' '.$usuario->apellidos  ;
    //Asignamos el telefono
    $usuario->telefono=$request->input("telefono");
    //Asignamos el grado
    $usuario->grado_id=$request->input("grado_id");
    //Asignamos la establecimiento
    $usuario->establecimiento_id=$request->input("establecimiento_id");
    //Buscamos el nombre del establecimiento
    $grado=Grado::find($usuario->grado_id);
    //Asignamos el  grado
    $usuario->grado=$grado->descripcion;
    //Buscamos el nombre del establecimiento
    $establecimiento=Establecimiento::find($usuario->establecimiento_id);
    //Asignamos el establecimiento
    $usuario->nombre_establecimiento=$establecimiento->nombre_establecimiento;
    
     
    //Verificamos los roles
    if($request->has("rol")){
        $rol=$request->input("rol");
        $usuario->revokeAllRoles();
        $usuario->assignRole($rol);
     }
     
    //Guardamos los usuarios
    if( $usuario->save()){
        return view("admin.usuarios.mensajes.msj_usuario_actualizado")->with("msj","Usuario actualizado correctamente")
                                                       ->with("idusuario",$idusuario) ;
    }
    else
    {
        return view("admin.usuarios.mensajes.mensaje_error")->with("msj","..Hubo un error al agregar ; intentarlo nuevamente..");
    }
  }


  public function buscar_usuario(Request $request){
    $dato=$request->input("dato_buscado");
    $usuarios=User::where("name","like","%".$dato."%")->orwhere("apellidos","like","%".$dato."%")                                              ->paginate(100);
    return view('admin.usuarios.listados.listado_usuarios')->with("usuarios",$usuarios);
  }

  public function borrar_usuario(Request $request){

    $idusuario=$request->input("id_usuario");
    $usuario=User::find($idusuario);

    if($usuario->delete()){
         return view("admin.usuarios.mensajes.msj_usuario_borrado")->with("msj","Usuario borrado correctamente") ;
    }
    else
    {
        return view("admin.usuarios.mensajes.mensaje_error")->with("msj","..Hubo un error al agregar ; intentarlo nuevamente..");
    }  
  }

  public function editar_acceso(Request $request){
    $idusuario=$request->input("id_usuario");
    $usuario=User::find($idusuario);
    $usuario->email=$request->input("email");
    $usuario->password= bcrypt( $request->input("password") ); 
      
    if( $usuario->save()){
      return view("admin.usuarios.mensajes.msj_usuario_actualizado")->with("msj","Usuario actualizado correctamente")->with("idusuario",$idusuario) ;
    }
    else
    {
      return view("admin.usuarios.mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ; intentarlo nuevamente ...") ;
    }
  }

  public function asignar_rol($idusu,$idrol){

    $usuario=User::find($idusu);
    $usuario->assignRole($idrol);
    $usuario=User::find($idusu);
    $rolesasignados=$usuario->getRoles();
        
    return json_encode ($rolesasignados);
  }

  public function quitar_rol($idusu,$idrol){

    $usuario=User::find($idusu);
    $usuario->revokeRole($idrol);
    $rolesasignados=$usuario->getRoles();
    return json_encode ($rolesasignados);
  }

  public function form_borrado_usuario($id){
    $usuario=User::find($id);
    return view("admin.usuarios.confirmaciones.form_borrado_usuario")->with("usuario",$usuario);

  }

  public function quitar_permiso($idrole,$idper){ 
      
    $role = Role::find($idrole);
    $role->revokePermission($idper);
    $role->save();

    return "ok";
  }

  public function borrar_rol($idrole){
    $role = Role::find($idrole);
    $role->delete();
    return "ok";
  }
}
