<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateFarmaciaRequest;
use App\Http\Requests\UpdateFarmaciaRequest;
use App\Repositories\FarmaciaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use App\Models\Abastecimiento;
use App\Models\Petitorio;
use Flash;
use DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class FarmaciaController extends AppBaseController
{
    /** @var  FarmaciaRepository */
    private $farmaciaRepository;

    public function __construct(FarmaciaRepository $farmaciaRepo)
    {
        $this->farmaciaRepository = $farmaciaRepo;
    }

    /**
     * Display a listing of the Farmacia.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->farmaciaRepository->pushCriteria(new RequestCriteria($request));
        $farmacias = $this->farmaciaRepository->all();

        return view('admin.farmacias.index')
            ->with('farmacias', $farmacias);


    }

    public function ver_farmacia(Request $request,$establecimiento_id,$nivel_id)
    {
        if ($nivel_id == 2 or $nivel_id == 3){

            //$farmacias = $this->farmaciaRepository->find($id_establecimiento);
            $establecimiento = Establecimiento::find($establecimiento_id);

            if (empty($establecimiento)) {
                Flash::error('Establecimiento no encontrada');

                return redirect(route('establecimientos.index'));
            }
            
            $farmacias = $this->farmaciaRepository->all()->where('establecimiento_id',$establecimiento_id);
            
            return view('admin.farmacias.ver_farmacia')
                    ->with('farmacias', $farmacias)
                    ->with('establecimiento_id', $establecimiento_id)
                    ->with('nivel_id', $nivel_id);
        }
        else
        {
                Flash::error('No hay Farmacias para esta IPRESS');

                return redirect(route('establecimientos.index'));
        }    
        
    }
    /**
     * Show the form for creating a new Farmacia.
     *
     * @return Response
     */
    public function create()
    {
        $establecimiento_id=Establecimiento::pluck('nombre_establecimiento','id');
        return view('admin.farmacias.create', compact(["establecimiento_id"]));
    }

    public function crear_farmacia($establecimiento_id,$nivel_id)
    {
        return view('admin.farmacias.create')
                ->with('establecimiento_id', $establecimiento_id)
                ->with('nivel_id', $nivel_id);
    }

    /**
     * Store a newly created Farmacia in storage.
     *
     * @param CreateFarmaciaRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmaciaRequest $request)
    {
        //$input = $request->all();
        //dd($request->nivel);

        $farmacia =DB::table('farmacias')
                    ->insert([
                        'descripcion'=>$request->descripcion,
                        'establecimiento_id'=>$request->id

                    ]);

        Flash::success('Farmacia creada satisfactoriamente.');

        return redirect(route('farmacias.ver_farmacia',['establecimiento_id'=>$request->id,'nivel_id'=>$request->nivel]));


    }

    /**
     * Display the specified Farmacia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farmacia = $this->farmaciaRepository->findWithoutFail($id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrada');

            return redirect(route('farmacias.index'));
        }

        return view('admin.farmacias.show')->with('farmacia', $farmacia);
    }

    /**
     * Show the form for editing the specified Farmacia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id,$nivel_id)
    {
        $farmacia = $this->farmaciaRepository->findWithoutFail($id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrada');

            return redirect(route('farmacias.index'));
        }

        //$establecimiento_id=Establecimiento::pluck('nombre_establecimiento','id');

        return view('admin.farmacias.edit') ->with('farmacia', $farmacia)
                                            ->with('nivel_id', $nivel_id);
    }

    public function editar($id,$establecimiento_id,$nivel_id)
    {
        $farmacia = $this->farmaciaRepository->findWithoutFail($id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrada');

            return redirect(route('farmacias.index'));
        }

        //$establecimiento_id=Establecimiento::pluck('nombre_establecimiento','id');

        return view('admin.farmacias.edit') ->with('farmacia', $farmacia)
                                            ->with('establecimiento_id', $establecimiento_id)
                                            ->with('nivel_id', $nivel_id);
    }

    /**
     * Update the specified Farmacia in storage.
     *
     * @param  int              $id
     * @param UpdateFarmaciaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmaciaRequest $request)
    {
        $farmacia = $this->farmaciaRepository->findWithoutFail($id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrada');

            return redirect(route('farmacias.index'));
        }

        $farmacia = $this->farmaciaRepository->update($request->all(), $id);

        Flash::success('Farmacia actualizada satisfactoriamente.');

        return redirect(route('farmacias.ver_farmacia',['establecimiento_id'=>$request->id,'nivel_id'=>$request->nivel]));
    }

    /**
     * Remove the specified Farmacia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farmacia = $this->farmaciaRepository->findWithoutFail($id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrada');

            return redirect(route('farmacias.index'));
        }

        $this->farmaciaRepository->delete($id);

        Flash::success('Farmacia borrada.');

        return Redirect::back();
        //return redirect(route('farmacias.ver_farmacia',['establecimiento_id'=>$request->id,'nivel_id'=>$request->nivel]));
    }

    public function eliminar($id,$establecimiento_id,$nivel_id)
    {
        
        //dd($establecimiento_id);
        $farmacia = $this->farmaciaRepository->findWithoutFail($id);

        

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrada');

            return redirect(route('farmacias.index'));
        }

        $this->farmaciaRepository->delete($id);

        Flash::success('Farmacia borrada.');


        return redirect(route('farmacias.ver_farmacia',['establecimiento_id'=>$establecimiento_id,'nivel_id'=>$nivel_id]));
    }


    //////mostrar medicamentos 
    public function ver_medicamentos(Request $request,$establecimiento_id,$farmacia_id)
    {

        ///mostrar todos los petitorios del establecimiento
        $farmacia = $this->farmaciaRepository->findWithoutFail($farmacia_id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrada');

            return redirect(route('establecimientos.index'));
        }

        ///mostrar todos los petitorios del establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimientos.index'));
        }

        $petitorios = DB::table('farmacias')
                ->join('farmacia_petitorio', 'farmacia_petitorio.farmacia_id', '=', 'farmacias.id')
                  ->join('petitorios', 'farmacia_petitorio.petitorio_id', '=', 'petitorios.id')
                  ->where('farmacia_petitorio.farmacia_id',$farmacia_id)
                  ->where('tipo_dispositivo_medicos_id',1)
                  ->get();
        
        return view('admin.farmacias.medicamentos.ver_medicamentos')
            ->with('petitorios', $petitorios)
            ->with('nombre_establecimiento', $establecimiento->nombre_establecimiento)
            ->with('establecimiento_id', $establecimiento_id)
            ->with('farmacia_id', $farmacia_id);
            
    }

    ////////////////////
    public function asignar_medicamentos($establecimiento_id,$farmacia_id)
    {
        //mostrar todos los petitorios que sean del nivel del establecimiento
        $farmacia = $this->farmaciaRepository->findWithoutFail($farmacia_id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrado');

            return redirect(route('establecimientos.index'));
        }

        //mostrar todos los petitorios que sean del nivel del establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimientos.index'));
        }

        $petitorios=Petitorio::where('tipo_dispositivo_medicos_id',1)
                        ->where('nivel_id','<=',$establecimiento->nivel_id)
                        ->pluck('descripcion','id');

        
        

        return view('admin.farmacias.medicamentos.asignar_medicamentos',compact('petitorios','establecimiento','farmacia'));     
    }

    ////////////////
    public function guardar_medicamentos(UpdateFarmaciaRequest $request, $establecimiento_id,$farmacia_id)
    {
        
        //mostrar todos los petitorios que sean del nivel del establecimiento
        $farmacia = $this->farmaciaRepository->findWithoutFail($farmacia_id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrado');

            return redirect(route('establecimientos.index'));
        }

        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimiento.index'));
        }

        //Buscar todos los medicamentos
        $num_medicamentos=DB::table('farmacia_petitorio')
                            ->where('farmacia_id',$farmacia_id)
                            ->where('tipo_dispositivo_medico_id',1)
                            ->orwhere('tipo_dispositivo_medico_id','>',1)
                            ->count();
        
        //Si esta vacio y no ha sido asignado ningun valor aun a la bd
        if($num_medicamentos==0){
            $farmacia->petitorios()->sync($request->petitorios); 
        }
        else
        {
            $medicamentos_bd=DB::table('farmacia_petitorio')
                            ->where('farmacia_id',$farmacia_id)
                            ->where('tipo_dispositivo_medico_id',1)
                            ->get();
            //checkbox marcados
            $medicamentos_iguales=$medicamentos_bd->pluck('petitorio_id')->intersect($request->petitorios)->count();
            
            //checkbox desmarcados
            $medicamentos_desmarcado=$medicamentos_bd->pluck('petitorio_id')->diff($request->petitorios);

            //convertimos a arreglo
            $arreglo = $medicamentos_bd->pluck('petitorio_id')->toArray();

            //los nuevos checkbox
            $medicamentos_nuevos=array_diff($request->petitorios,$arreglo);

            //dd($medicamentos_diferentes);            
            if($medicamentos_iguales==0)
            {
                //insertamos todos
                $farmacia->petitorios()->attach($request->petitorios); //attach                      
            }
            else
            {
                ///eliminamos los que no estan en los checkbox
                $farmacia->petitorios()->detach($medicamentos_desmarcado); //attach       
                ///Insertamos los nuevos
                $farmacia->petitorios()->attach($medicamentos_nuevos); //attach       

            }    

            ///Actualizamos los tipos de dispositivos a 2
            foreach ($request->petitorios as $key => $petitorio) {
                DB::table('farmacia_petitorio')
                        ->where('petitorio_id', $petitorio )
                        ->update(['tipo_dispositivo_medico_id' => 1]);
            }
            
        

        }    

        Flash::success('Medicamentos guardado correctamente.');

        return redirect(route('farmacias.ver_medicamentos',[$establecimiento_id,$farmacia_id]));
    }

    //////mostrar medicamentos 
    public function ver_dispositivos(Request $request,$establecimiento_id,$farmacia_id)
    {

        ///mostrar todos los petitorios del establecimiento
        $farmacia = $this->farmaciaRepository->findWithoutFail($farmacia_id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrada');

            return redirect(route('establecimientos.index'));
        }

        //mostrar todos los petitorios que sean del nivel del establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimientos.index'));
        }

        $petitorios = DB::table('farmacias')
                  ->join('farmacia_petitorio', 'farmacia_petitorio.farmacia_id', '=', 'farmacias.id')
                  ->join('petitorios', 'farmacia_petitorio.petitorio_id', '=', 'petitorios.id')
                  ->where('farmacia_petitorio.farmacia_id',$farmacia_id)
                  ->where('tipo_dispositivo_medicos_id','>',1)
                  ->get();
        

        return view('admin.farmacias.dispositivos.ver_dispositivos')
            ->with('petitorios', $petitorios)
            ->with('nombre_establecimiento', $establecimiento->nombre_establecimiento)
            ->with('establecimiento_id', $establecimiento_id)
            ->with('farmacia_id', $farmacia_id);
    }

    ////////////////////
    public function asignar_dispositivos($establecimiento_id,$farmacia_id)
    {
        
        $farmacia = $this->farmaciaRepository->findWithoutFail($farmacia_id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrado');

            return redirect(route('establecimientos.index'));
        }


       //encontrar el establecimiento que se encuentra para asignar            
        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
    
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimientos.index'));
        }

        //Mostramos los petitorios que son dispositivos medicos con el nivel del establecimiento
        $petitorios=Petitorio::select('descripcion','id','descripcion_tipo_dispositivo')->where('tipo_dispositivo_medicos_id','>',1)
                        ->where('nivel_id','<=',$establecimiento->nivel_id)
                        ->get();
                        //->pluck('descripcion','id');

        return view('admin.farmacias.dispositivos.asignar_dispositivos',compact('petitorios','establecimiento','farmacia'));     
    }

        
    ////////////////
    public function guardar_dispositivos(UpdateFarmaciaRequest $request, $establecimiento_id,$farmacia_id)
    {
        
        //mostrar todos los petitorios que sean del nivel del establecimiento
        $farmacia = $this->farmaciaRepository->findWithoutFail($farmacia_id);

        if (empty($farmacia)) {
            Flash::error('Farmacia no encontrado');

            return redirect(route('establecimientos.index'));
        }

        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
             Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimiento.index'));
        }

        //Buscar todos los medicamentos
        $num_medicamentos=DB::table('farmacia_petitorio')
                            ->where('farmacia_id',$farmacia_id)
                            ->where('tipo_dispositivo_medico_id',1)
                            ->orwhere('tipo_dispositivo_medico_id','>',1)
                            ->count();
        
        //Si esta vacio y no ha sido asignado ningun valor aun a la bd
        if($num_medicamentos==0){
            $establecimiento->petitorios()->sync($request->petitorios); 

        }
        else
        {
            $dispositivos_bd=DB::table('farmacia_petitorio')
                            ->where('farmacia_id',$farmacia_id)
                            ->where('tipo_dispositivo_medico_id','>',1)
                            ->get();
        
            //checkbox marcados
            $dispositivos_iguales=$dispositivos_bd->pluck('petitorio_id')->intersect($request->petitorios)->count();
            
            //checkbox desmarcados
            $dispositivos_desmarcado=$dispositivos_bd->pluck('petitorio_id')->diff($request->petitorios);

            //convertimos a arreglo
            $arreglo = $dispositivos_bd->pluck('petitorio_id')->toArray();

            //los nuevos checkbox
            $dispositivos_nuevos=array_diff($request->petitorios,$arreglo);

            //dd($medicamentos_diferentes);            
            if($dispositivos_iguales==0)
            {
                $farmacia->petitorios()->attach($request->petitorios); //attach       
            }
            else
            {
                ///eliminamos los que no estan en los checkbox
                $farmacia->petitorios()->detach($dispositivos_desmarcado); //attach       
                ///Insertamos los nuevos
                $farmacia->petitorios()->attach($dispositivos_nuevos); //attach       
                

            }    

        }    

        ///Actualizamos los tipos de dispositivos a 2
        foreach ($request->petitorios as $key => $petitorio) {
            DB::table('farmacia_petitorio')
                    ->where('petitorio_id', $petitorio )
                    ->update(['tipo_dispositivo_medico_id' => 2]);
        }
        

        Flash::success('Dispositivos guardado correctamente.');

        return redirect(route('farmacias.ver_dispositivos',[$establecimiento_id,$farmacia_id]));
    }
}