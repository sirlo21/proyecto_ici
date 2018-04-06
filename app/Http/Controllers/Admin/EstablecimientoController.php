<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Http\Requests\CreateEstablecimientoRequest;
use App\Http\Requests\UpdateEstablecimientoRequest;
use App\Repositories\EstablecimientoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Abastecimiento;
use App\Models\Petitorio;
use App\Models\Categoria;
use App\Models\Disa;
use App\Models\Distrito;
use App\Models\Nivel;
use App\Models\Provincia;
use App\Models\Departamento;
use App\Models\Region;
use App\Models\TipoEstablecimiento;
use App\Models\TipoInternamiento;
use App\Models\Establecimiento;

use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class EstablecimientoController extends AppBaseController
{
    /** @var  EstablecimientosRepository */
    private $establecimientoRepository;

    public function __construct(EstablecimientoRepository $establecimientoRepo)
    {
        $this->establecimientoRepository = $establecimientoRepo;
    }

    /**
     * Display a listing of the Establecimientos.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->establecimientoRepository->pushCriteria(new RequestCriteria($request));
        $establecimientos = $this->establecimientoRepository->all();

        return view('admin.establecimientos.index')
            ->with('establecimientos', $establecimientos);
    }

    /**
     * Show the form for creating a new Establecimientos.
     *
     * @return Response
     */
    public function create()
    {
        $nivel_id=Nivel::pluck('descripcion','id');
        $region_id=Region::pluck('descripcion','id');
        $categoria_id=Categoria::pluck('descripcion','id');
        $tipo_establecimiento_id=TipoEstablecimiento::pluck('descripcion','id');
        $tipo_internamiento_id=TipoInternamiento::pluck('descripcion','id');
        $departamento_id=Departamento::pluck('nombre_dpto','id');
        $provincia_id=Provincia::pluck('nombre_prov','id');
        $distrito_id=Distrito::pluck('nombre_dist','id');
        $disa_id=Disa::pluck('descripcion','id');

        return view('admin.establecimientos.create',compact(["nivel_id","region_id","categoria_id","tipo_establecimiento_id","tipo_internamiento_id","departamento_id","provincia_id","distrito_id","disa_id"]));

        
    }

    /**
     * Store a newly created Establecimientos in storage.
     *
     * @param CreateEstablecimientosRequest $request
     *
     * @return Response
     */
    public function store(CreateEstablecimientoRequest $request)
    {
        $input = $request->all();

        $establecimientos = $this->establecimientoRepository->create($input);

        Flash::success('Establecimientos guardado correctamente.');

        return redirect(route('establecimiento.index'));
    }

    /**
     * Display the specified Establecimientos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $establecimientos = $this->establecimientoRepository->findWithoutFail($id);

        if (empty($establecimientos)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimiento.index'));
        }

        return view('admin.establecimientos.show')->with('establecimientos', $establecimientos);
    }

    //////mostrar medicamentos 
    public function ver_medicamentos(Request $request,$establecimiento_id)
    {

        ///mostrar todos los petitorios del establecimiento
        $establecimiento = $this->establecimientoRepository->findWithoutFail($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimientos.index'));
        }

        $petitorios = Establecimiento::find($establecimiento_id)->petitorios
                        ->where('tipo_dispositivo_medicos_id',1)
                        ->where('nivel_id','<=',$establecimiento->nivel_id);
            

        //dd($petitorios); 
        return view('admin.establecimientos.medicamentos.ver_medicamentos')
            ->with('petitorios', $petitorios)
            ->with('nombre_establecimiento', $establecimiento->nombre_establecimiento)
            ->with('establecimiento_id', $establecimiento_id);
            
    }

    ////////////////////
    public function asignar_medicamentos($establecimiento_id)
    {
        //mostrar todos los petitorios que sean del nivel del establecimiento
        $establecimiento = $this->establecimientoRepository->findWithoutFail($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimientos.index'));
        }


        $petitorios=Petitorio::where('tipo_dispositivo_medicos_id',1)
                        ->where('nivel_id','<=',$establecimiento->nivel_id)
                        ->pluck('descripcion','id');

        return view('admin.establecimientos.medicamentos.asignar_medicamentos',compact('petitorios','establecimiento'));     
    }

    ////////////////
    public function guardar_medicamentos(UpdateEstablecimientoRequest $request, $establecimiento_id)
    {
        
        $establecimiento = $this->establecimientoRepository->findWithoutFail($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimiento.index'));
        }

        //Buscar todos los medicamentos
        $num_medicamentos=DB::table('establecimiento_petitorio')
                            ->where('establecimiento_id',$establecimiento_id)
                            ->where('tipo_dispositivo_medico_id',1)
                            ->orwhere('tipo_dispositivo_medico_id','>',1)
                            ->count();
        
        //Si esta vacio y no ha sido asignado ningun valor aun a la bd
        if($num_medicamentos==0){
            $establecimiento->petitorios()->sync($request->petitorios); 
        }
        else
        {
            $medicamentos_bd=DB::table('establecimiento_petitorio')
                            ->where('establecimiento_id',$establecimiento_id)
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
                $establecimiento->petitorios()->attach($request->petitorios); //attach       
            }
            else
            {
                ///eliminamos los que no estan en los checkbox
                $establecimiento->petitorios()->detach($medicamentos_desmarcado); //attach       
                ///Insertamos los nuevos
                $establecimiento->petitorios()->attach($medicamentos_nuevos); //attach       

            }    

        }    

        Flash::success('Establecimientos guardado correctamente.');

        return redirect(route('establecimientos.ver_medicamentos',[$establecimiento_id]));
    }

    //////mostrar medicamentos 
    public function ver_dispositivos(Request $request,$establecimiento_id)
    {

        ///mostrar todos los petitorios del establecimiento
        $establecimiento = $this->establecimientoRepository->findWithoutFail($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimientos.index'));
        }

        $petitorios = Establecimiento::find($establecimiento_id)->petitorios
                        ->where('tipo_dispositivo_medicos_id','>',1)
                        ->where('nivel_id','<=',$establecimiento->nivel_id);
        
        //dd($petitorios); 
        return view('admin.establecimientos.dispositivos.ver_dispositivos')
            ->with('petitorios', $petitorios)
            ->with('nombre_establecimiento', $establecimiento->nombre_establecimiento)
            ->with('establecimiento_id', $establecimiento_id);
            
    }

    ////////////////////
    public function asignar_dispositivos($establecimiento_id)
    {
        //encontrar el establecimiento que se encuentra para asignar
        $establecimiento = $this->establecimientoRepository->findWithoutFail($establecimiento_id);

        //si encontramos el establecimiento buscado
        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimientos.index'));
        }

        //Mostramos los petitorios que son dispositivos medicos con el nivel del establecimiento
        $petitorios=Petitorio::select('descripcion','id','descripcion_tipo_dispositivo')->where('tipo_dispositivo_medicos_id','>',1)
                        ->where('nivel_id','<=',$establecimiento->nivel_id)
                        ->get();
                        //->pluck('descripcion','id');

        return view('admin.establecimientos.dispositivos.asignar_dispositivos',compact('petitorios','establecimiento'));     
    }

    ////////////////
    public function guardar_dispositivos(UpdateEstablecimientoRequest $request, $establecimiento_id)
    {
        
        //Busco el establecimiento
        $establecimiento = $this->establecimientoRepository->findWithoutFail($establecimiento_id);

        //Si se ha digitado en la url un establecimiento que no corresponde
        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimiento.index'));
        }

        //Buscar todos los medicamentos
        $num_medicamentos=DB::table('establecimiento_petitorio')
                            ->where('establecimiento_id',$establecimiento_id)
                            ->where('tipo_dispositivo_medico_id',1)
                            ->orwhere('tipo_dispositivo_medico_id','>',1)
                            ->count();
        
        //Si esta vacio y no ha sido asignado ningun valor aun a la bd
        if($num_medicamentos==0){
            $establecimiento->petitorios()->sync($request->petitorios); 

        }
        else
        {
            $dispositivos_bd=DB::table('establecimiento_petitorio')
                            ->where('establecimiento_id',$establecimiento_id)
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
                $establecimiento->petitorios()->attach($request->petitorios); //attach       
            }
            else
            {
                ///eliminamos los que no estan en los checkbox
                $establecimiento->petitorios()->detach($dispositivos_desmarcado); //attach       
                ///Insertamos los nuevos
                $establecimiento->petitorios()->attach($dispositivos_nuevos); //attach       
                

            }    

        }    

        ///Actualizamos los tipos de dispositivos a 2
        foreach ($request->petitorios as $key => $petitorio) {
            DB::table('establecimiento_petitorio')
                    ->where('petitorio_id', $petitorio )
                    ->update(['tipo_dispositivo_medico_id' => 2]);
        }
        

        Flash::success('Dispositivos guardado correctamente.');

        return redirect(route('establecimientos.ver_dispositivos',[$establecimiento_id]));
    }


///////////////////////////////  
/*    public function cargar_datos_medicamentos($establecimiento_id)
    {
        $abastecimientos = DB::table('establecimientos')
                ->join('establecimiento_petitorio', 'establecimiento_petitorio.establecimiento_id', '=', 'establecimientos.id')
                ->join('petitorios', 'establecimiento_petitorio.petitorio_id', '=', 'petitorios.id')
                ->where('establecimiento_petitorio.establecimiento_id',$establecimiento_id)
                ->get();
        
        //dd($abastecimientos);

        foreach($abastecimientos as $key => $abastecimiento){
                 DB::table('abastecimientos')
                     ->insert([
                        'anomes' => '201802',
                        'establecimiento_id' => $abastecimiento->establecimiento_id,
                        'cod_establecimiento' => $abastecimiento->codigo_establecimiento,
                        'nombre_establecimiento' => $abastecimiento->nombre_establecimiento,
                        'tipo_dispositivo_id' => $abastecimiento->tipo_dispositivo_medico_id,
                        'petitorio_id' => $abastecimiento->petitorio_id,
                        'cod_petitorio' => $abastecimiento->codigo_petitorio,
                        'descripcion' => $abastecimiento->descripcion,
                        'precio' => $abastecimiento->precio,
                        
             ]);
        }


    }
*/

    /**
     * Show the form for editing the specified Establecimientos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $establecimientos = $this->establecimientoRepository->findWithoutFail($id);

        if (empty($establecimientos)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimiento.index'));
        }

        $nivel_id=Nivel::pluck('descripcion','id');
        $region_id=Region::pluck('descripcion','id');
        $categoria_id=Categoria::pluck('descripcion','id');
        $tipo_establecimiento_id=TipoEstablecimiento::pluck('descripcion','id');
        $tipo_internamiento_id=TipoInternamiento::pluck('descripcion','id');
        $departamento_id=Departamento::pluck('nombre_dpto','id');
        $provincia_id=Provincia::pluck('nombre_prov','id');
        $distrito_id=Distrito::pluck('nombre_dist','id');
        $disa_id=Disa::pluck('descripcion','id');

        return view('admin.establecimientos.edit')->with('establecimientos', $establecimientos)->with('nivel_id', $nivel_id)->with('region_id', $region_id)->with('categoria_id', $categoria_id)->with('tipo_establecimiento_id', $tipo_establecimiento_id)->with('tipo_internamiento_id', $tipo_internamiento_id)->with('departamento_id', $departamento_id)->with('provincia_id', $provincia_id)->with('distrito_id', $distrito_id)->with('disa_id', $disa_id);


    }

    /**
     * Update the specified Establecimientos in storage.
     *
     * @param  int              $id
     * @param UpdateEstablecimientoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEstablecimientoRequest $request)
    {
        $establecimientos = $this->establecimientoRepository->findWithoutFail($id);

        if (empty($establecimientos)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimiento.index'));
        }

        $establecimientos = $this->establecimientoRepository->update($request->all(), $id);

        Flash::success('Establecimientos actualizado satisfactoriamente.');

        return redirect(route('establecimiento.index'));
    }

    /**
     * Remove the specified Establecimientos from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $establecimiento = $this->establecimientoRepository->findWithoutFail($id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('establecimiento.index'));
        }

        $this->establecimientoRepository->delete($id);

        Flash::success('Establecimientos eliminado.');

        return redirect(route('establecimiento.index'));
    }
}
