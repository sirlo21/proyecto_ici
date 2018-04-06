<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Establecimiento;
use App\Models\Farmacia;
use App\Models\Grado;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;


class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $users = $this->userRepository->all();

        return view('admin.users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $establecimiento_id=Establecimiento::pluck('nombre_establecimiento','id');
        $grado_id=Grado::pluck('descripcion','id');
        $farmacia_id=Farmacia::pluck('descripcion','id');
        return view('admin.users.create',compact(["establecimiento_id","grado_id","farmacia_id"]));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        //$input = $request->all();

        
        //$user = $this->userRepository->create($input);

    //Concatenamos  el nombre y apellido en mayuscula
    $name=strtoupper( $request->input("nombres")." ".$request->input("apellidos") ) ;
    //Asignamos el dni
    $dni=$request->input("dni") ;
    //Asignamos el nombre
    $nombres=strtoupper( $request->input("nombres") ) ;
    //Asignamos el apellido
    $apellidos=strtoupper( $request->input("apellidos") ) ;
    //Asignamos el telefono
    $telefono=$request->input("telefono");
    //Asignamos el establecimiento
    $establecimiento_id=$request->input("establecimiento_id");
    //Buscamos la descripcion del grado
    $establecimiento = Establecimiento::findOrFail($establecimiento_id);
    //asignamos el nombre del establecimiento
    $nombre_establecimiento=$establecimiento->nombre_establecimiento;
    //Buscamos el nombre del establecimiento
    //Asignamos el grado
    $grado_id=$request->input("grado_id");
    //Buscamos el nombre del establecimiento
    $grado = Grado::findOrFail($grado_id);
    //asignamos el nombre del establecimiento
    $grado=$grado->descripcion;
    //Asignamos el email
    $email=$request->input("email");
    //Asignamos el password
    $password= bcrypt( $request->input("password") ); 
    //Guardamos el usuario
    
    

    $nivel=$establecimiento->nivel_id;

        if ($nivel==1)
        {
            $farmacia='';
            $farmacia_id=0;
        }
        else
        {
            $farmacia_id=$request->input("farmacia_id");        
            $farmacia = Farmacia::findOrFail($farmacia_id);
            //asignamos el nombre del establecimiento
            $farmacia=$farmacia->descripcion;
        }


    




    DB::table('users')
                    ->insert([
                            'name' => $name,
                            'dni' => $dni,
                            'nombres' => $nombres,
                            'apellidos'=>$apellidos,
                            'telefono'=>$telefono,
                            'establecimiento_id'=>$establecimiento_id,
                            'nombre_establecimiento'=>$nombre_establecimiento,
                            'grado_id'=>$grado_id,
                            'email'=>$email,
                            'grado'=>$grado,
                            'password'=>$password,
                            'farmacia_id'=>$farmacia_id,
                            'nombre_farmacia'=>$farmacia,
                            "created_at"=>Carbon::now(),
                            "updated_at"=>Carbon::now()

                        ]);


    Flash::success('Usuario grabado con exito.');

    return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        
        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        
        $establecimiento_id=Establecimiento::pluck('nombre_establecimiento','id');
        $grado_id=Grado::pluck('descripcion','id');
        $farmacia_id=Farmacia::pluck('descripcion','id');
        return view('admin.users.edit')->with('user', $user)->with('establecimiento_id',$establecimiento_id)->with('grado_id',$grado_id)->with('farmacia_id',$farmacia_id);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuario no encontrado');

            return redirect(route('users.index'));
        }

        //$user = $this->userRepository->update($request->all(), $id);

            //Concatenamos  el nombre y apellido en mayuscula
        $name=strtoupper( $request->input("nombres")." ".$request->input("apellidos") ) ;
        //Asignamos el dni
        $dni=$request->input("dni") ;
        //Asignamos el nombre
        $nombres=strtoupper( $request->input("nombres") ) ;
        //Asignamos el apellido
        $apellidos=strtoupper( $request->input("apellidos") ) ;
        //Asignamos el telefono
        $telefono=$request->input("telefono");
        //Asignamos el establecimiento
        $establecimiento_id=$request->input("establecimiento_id");
        //Buscamos la descripcion del grado
        $establecimiento = Establecimiento::findOrFail($establecimiento_id);
        //asignamos el nombre del establecimiento
        $nombre_establecimiento=$establecimiento->nombre_establecimiento;
        //Buscamos el nombre del establecimiento
        //Asignamos el grado
        $grado_id=$request->input("grado_id");
        //Buscamos el nombre del establecimiento
        $grado = Grado::findOrFail($grado_id);
        //asignamos el nombre del establecimiento
        $grado=$grado->descripcion;
        //Asignamos el email
        $email=$request->input("email");
        //Asignamos el password
        $password= bcrypt( $request->input("password") ); 
        //Guardamos el usuario
        $farmacia_id=$request->input("farmacia_id");
        //Buscamos el nombre del establecimiento
        $farmacia = Farmacia::findOrFail($farmacia_id);
        //asignamos el nombre del establecimiento
        $farmacia=$farmacia->descripcion;


        DB::table('users')
                ->where('id', $id )
                        ->update([
                                'name' => $name,
                                'dni' => $dni,
                                'nombres' => $nombres,
                                'apellidos'=>$apellidos,
                                'telefono'=>$telefono,
                                'establecimiento_id'=>$establecimiento_id,
                                'nombre_establecimiento'=>$nombre_establecimiento,
                                'grado_id'=>$grado_id,
                                'email'=>$email,
                                'grado'=>$grado,
                                'password'=>$password,
                                'farmacia_id'=>$farmacia_id,
                                'nombre_farmacia'=>$farmacia,
                            ]);

        Flash::success('Usuario actualizado correctamente.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

   /* public function GetEstablecimientos()
    {   
        $Establecimientos = Establecimiento::all();
        $data = [];
        $data[0] = [
            'id'=>0,
            'text'=>'Seleccione',

        ]; 
        foreach ($Establecimientos as $key => $value) {
            $data[$key+1]= [
                'id'=>$value->id,
                'text'=>$value->nombre_establecimiento,
            ];
        }
        return response()->json($data);
    }

    public function GetFarmacias($id)
    {   
        $Farmacias = Farmacia::where('establecimiento_id',$id)->get();
        $data = [];
        $data[0] = [
            'id'=>0,
            'text'=>'Seleccione',

        ]; 
        foreach ($Farmacias as $key => $value) {
            $data[$key+1]= [
                'id'=>$value->id,
                'text'=>$value->descripcion,
            ];
        }
        return response()->json($data);
    }

*/
}
