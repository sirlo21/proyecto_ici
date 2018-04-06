<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Http\Requests\CreatePetitorioRequest;
use App\Http\Requests\UpdatePetitorioRequest;
use App\Repositories\PetitorioRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Nivel;
use App\Models\TipoDispositivoMedico;
use App\Models\TipoUso;
use App\Models\UnidadMedida;
use App\Models\Petitorio;


use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PetitorioController extends AppBaseController
{
    private $petitorioRepository;

    public function __construct(petitorioRepository $petitorioRepo)
    {
        $this->petitorioRepository = $petitorioRepo;
    }

    public function index(Request $request)
    { 
        
        //inicializamos los petitorios
        $this->petitorioRepository->pushCriteria(new RequestCriteria($request));
        
        //Consultamos todos los medicamentos y dispositivos medicos que hay en el petitorio
        $petitorios = $this->petitorioRepository->all();    
        
        //dd($petitorios);
        //mostramos la vista con la consulta realizada anteriormente
        return view('admin.petitorios.index')
            ->with('petitorios', $petitorios);
    }

//medicamentos por nivel
//    public function medicamentos(Request $request)
//    {
//        $petitorios = Petitorio::where('tipo_dispositivo_medico_id',1);    
//        return view('admin.establecimiento.medicamentos.index')
//            ->with('petitorios', $petitorios);
//    }

///dispositivo por nivel
//    public function dispositivo(Request $request,$nivel,$tipo)
//    {
//        $petitorios=DB::table('petitorios')
//            ->where('nivel_id',$nivel)
//            ->where('tipo_dispositivo_medicos_id',$tipo)
//            ->get();
//   
//        return view('admin.petitorios.dispositivo.index')
//            ->with('petitorios', $petitorios);
//    }


//dispositivo por nivel por dispositivo medico
//    public function dispositivos(Request $request,$nivel)
///    {
//        $petitorios=DB::table('petitorios')
//            ->where('nivel_id',$nivel)
//            ->where('tipo_dispositivo_medicos_id','<>',1)
//            ->get();
//    
//        return view('admin.petitorios.dispositivo.index')
//            ->with('petitorios', $petitorios);
//    }

    public function create()
    {
        //cargamos los niveles
        $nivel_id=Nivel::pluck('descripcion','id');
        //cargamos las unidades de medida
        $unidad_medida_id=UnidadMedida::pluck('descripcion','id');
        //cargamos las unidades de medida
        $unidad_descripcion=UnidadMedida::pluck('descripcion','descripcion');
        //cargamos tipo de uso
        $tipo_uso_id=TipoUso::pluck('descripcion','id');
        //cargamos los tipos de dispositvos medicos
        $tipo_dispositivo_id=TipoDispositivoMedico::pluck('descripcion','id');
        
        //mostramos la vista
        return view('admin.petitorios.create',compact(["nivel_id","unidad_medida_id","tipo_uso_id","tipo_dispositivo_id","unidad_descripcion"]));
    }

    public function store(CreatePetitorioRequest $request)
    {
        
        //recogemos los datos enviados por el formulario
        $codigo_petitorio = $request->input("codigo_petitorio");  
        $principio_activo = $request->input("principio_activo"); 
        $concentracion = $request->input("concentracion");
        $form_farm = $request->input("form_farm");  
        $presentacion = $request->input("presentacion");  
        $unidad_medida_id = $request->input("unidad_medida_id");  
        $tipo_uso_id = $request->input("tipo_uso_id");
        $nivel_id = $request->input("nivel_id");                
        $tipo_dispositivo_id = $request->input("tipo_dispositivo_id");

        //$tipo = TipoDispositivoMedico::where('id',$tipo_dispositivo_id);
        $tipo = TipoDispositivoMedico::findOrFail($tipo_dispositivo_id);
        $unidad = UnidadMedida::findOrFail($unidad_medida_id);
        $uso = TipoUso::findOrFail($tipo_uso_id);
        $nivel = Nivel::findOrFail($nivel_id);

        //dd($tipo->descripcion);
        
        //En la descripcion concatenamos el principio activo + concentracion + formmula farmaceutica + presentacion 
        //si unidad de medida es 1 'N/A' 
        if   ($unidad_medida_id === 1)
            $descripcion = $principio_activo.' '.$concentracion.' '.$form_farm.' '.$presentacion;
        else
            $descripcion = $principio_activo.' '.$concentracion.' '.$form_farm.' '.$presentacion.' '.$unidad->descripcion;
        
        //guardamos la informacion en la tabla de petitorios
        DB::table('petitorios')->insert([
            "tipo_dispositivo_medicos_id"=> $request->input("tipo_dispositivo_id"),
            "descripcion_tipo_dispositivo"=>$tipo->descripcion,
            "codigo_petitorio"=>$codigo_petitorio,
            "principio_activo"=>$principio_activo,
            "concentracion"=>$concentracion,
            "form_farm"=>$form_farm,
            "presentacion"=>$presentacion,
            "unidad_medida_id"=>$unidad_medida_id,
            "descripcion_unidad_medida"=>$unidad->descripcion,
            "nivel_id"=>$nivel_id,
            "descripcion_tipo_uso"=>$uso->descripcion,
            "tipo_uso_id"=>$tipo_uso_id,
            "descripcion_nivel"=>$nivel->descripcion,
            "descripcion"=>$descripcion,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);
        
       //sino hay ningun inconveniente mostramos mensaje de exito 
       Flash::success('Medicamento/Dispositivo guardado correctamente.');

       //redireccionamos al index 
        return redirect(route('petitorios.index'));
    
    }

    
    public function show($id)
    {
        $petitorio = $this->petitorioRepository->findWithoutFail($id);

        if (empty($petitorio)) {
            Flash::error('Medicamento/Petitorio no encontrado');

            return redirect(route('petitorios.index',$id));
        }

        return view('admin.petitorios.show')->with('petitorio', $petitorio)
                                            ->with('id',$id);

        
    }

    /**
     * Display the specified petitorio.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function petitorio_medicamento($id)
    {
        $petitorio = $this->petitorioRepository->findWithoutFail($id);

        if (empty($petitorio)) {
            Flash::error('Medicamento/Petitorio no encontrado');

            return redirect(route('petitorios.medicamentos1'));
        }

        return view('admin.petitorios.show')->with('petitorio', $petitorio);

        
    }

    /**
     * Show the form for editing the specified petitorio.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $petitorio = $this->petitorioRepository->findWithoutFail($id);

        if (empty($petitorio)) {
            Flash::error('Medicamento o Dispositivo no encontrado');

            return redirect(route('petitorios.index'));
        }

        $nivel_id=Nivel::pluck('descripcion','id');
        $unidad_medida_id=UnidadMedida::pluck('descripcion','id');
        $tipo_uso_id=TipoUso::pluck('descripcion','id');
        $tipo_dispositivo_id=TipoDispositivoMedico::pluck('descripcion','id');

        return view('admin.petitorios.edit')->with('petitorio', $petitorio)->with('nivel_id', $nivel_id)->with('unidad_medida_id', $unidad_medida_id)->with('tipo_uso_id', $tipo_uso_id)->with('tipo_dispositivo_id', $tipo_dispositivo_id);
    }

    /**
     * Update the specified petitorio in storage.
     *
     * @param  int              $id
     * @param UpdatepetitorioRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePetitorioRequest $request)
    {
        $petitorio = $this->petitorioRepository->findWithoutFail($id);

        if (empty($petitorio)) {
            Flash::error('Medicamento/Petitorio no encontrado');

            return redirect(route('petitorios.index'));
        }

        $petitorio = $this->petitorioRepository->update($request->all(), $id);

        Flash::success('Medicamento/Petitorio actualizado.');

        return redirect(route('petitorios.index'));
    }

    /**
     * Remove the specified petitorio from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $petitorio = $this->petitorioRepository->findWithoutFail($id);

        if (empty($petitorio)) {
            Flash::error('Medicamento/Petitorio no encontrado');

            return redirect(route('petitorios.index'));
        }

        $this->petitorioRepository->delete($id);

        Flash::success('Medicamento/Petitorio eliminado.');

        return redirect(route('petitorios.index'));
    }

    ///dispositivo por nivel
    public function buscarenpetitorio(Request $request,$nivel,$tipo)
    {
        $petitorios=DB::table('petitorios')
            ->where('nivel_id',$nivel)
            ->where('tipo_dispositivo_medicos_id',$tipo)
            ->get();
   
        return view('admin.petitorios.dispositivo.index')
            ->with('petitorios', $petitorios);
    }


}
