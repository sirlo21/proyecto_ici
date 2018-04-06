<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateIciRequest;
use App\Http\Requests\UpdateIciRequest;
use App\Repositories\IciRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Establecimiento;
use App\Models\Farmacia;
use App\Models\Year;
use App\Models\Ici;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Excel;
use PHPExcel_Worksheet_Drawing;


class IciController extends AppBaseController
{
    private $iciRepository;

    public function __construct(IciRepository $iciRepo)
    {
        $this->iciRepository = $iciRepo;
    }

    public function index(Request $request)
    {
        $this->iciRepository->pushCriteria(new RequestCriteria($request));
        $icis = $this->iciRepository->all();

        return view('admin.icis.index')
            ->with('icis', $icis);
    }

    public function create()
    {   
        $valor=1; //creacion 1 edicion 2
        $mes = DB::table('mes')->pluck('descripcion','id');
        $ano = DB::table('years')->pluck('descripcion','id');
        $establecimientos=Establecimiento::pluck('nombre_establecimiento','id');
        return view('admin.icis.create',compact(["mes"],["ano"],["establecimientos"],["valor"]));
    }

    public function store(CreateIciRequest $request)
    {

        $year=Year::find($request->year_id);
        $mouth=DB::table('mes')->find($request->mes_id);
        $ano=$year->descripcion;
        $mes=$mouth->cod_mes;
        $anomes=$ano.$mes;

        $repetido=DB::table('icis')
                    ->where('anomes',$anomes)
                    ->count();

        if ($repetido>0) {
            Flash::error('Ya se encuentra registrado');
            
        }
        else
        {
            switch ($mes) {
                case '01':$meses='Enero';break;
                case '02':$meses='Febrero';break;
                case '03':$meses='Marzo';break;
                case '04':$meses='Abril';break;
                case '05':$meses='Mayo';break;
                case '06':$meses='Junio';break;
                case '07':$meses='Julio';break;
                case '08':$meses='Agosto';break;
                case '09':$meses='Setiembre';break;
                case '10':$meses='Octubre';break;
                case '11':$meses='Noviembre';break;
                case '12':$meses='Diciembre';break;
        }
            DB::table('icis')
                    ->insert([
                            'anomes' => $anomes,
                            'year_id' => $request->year_id,
                            'mes_id' => $request->mes_id,
                            'desc_mes' => $meses,
                            'mes'=>$mes,
                            'ano'=>$ano,
                        ]);
        $icis=DB::table('icis')->orderBy('id', 'desc')->first();
        $ici = $this->iciRepository->findWithoutFail($icis->id);        
        $ici->establecimientos()->sync($request->establecimientos); //attach        
        $farmacias = DB::table('farmacias')->select('id','establecimiento_id')->get();
        foreach ($farmacias as $key => $farmacia) {
            DB::table('farmacia_ici')
                    ->insert([
                            'farmacia_id' => $farmacia->id,
                            'ici_id' => $icis->id,
                            'establecimiento_id' => $farmacia->establecimiento_id,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ]);  
        }
        
        Flash::success('Guardado correctamente.');

        }    
        return redirect(route('icis.index'));
    }

    public function show($id)
    {
        $ici = $this->iciRepository->findWithoutFail($id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.index'));
        }

        $consulta = DB::table('establecimiento_ici')
                ->join('establecimientos', 'establecimientos.id', '=', 'establecimiento_ici.establecimiento_id')
                ->join('icis', 'icis.id', '=', 'establecimiento_ici.ici_id')
                ->where('icis.id', '=', $id)
                ->get();
        //dd($consulta);
        $establecimientos=Establecimiento::pluck('nombre_establecimiento','id');

        return view('admin.icis.show')->with('ici', $ici)
                                      ->with('establecimientos', $establecimientos)
                                      ->with('consulta', $consulta);
    }

    public function mostrar_servicios($id,$establecimiento_id)
    {
        $ici = $this->iciRepository->findWithoutFail($id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.index'));
        }

        $consulta = DB::table('farmacia_ici')
                ->join('farmacias', 'farmacias.id', '=', 'farmacia_ici.farmacia_id')
                ->join('icis', 'icis.id', '=', 'farmacia_ici.ici_id')
                ->where('icis.id', '=', $id)
                ->where('farmacia_ici.establecimiento_id', '=', $establecimiento_id)
                ->get();
        //dd($consulta);
        $establecimientos=Establecimiento::find($establecimiento_id);
        //dd($establecimientos->nombre_establecimiento);
        return view('admin.icis.mostrar_servicios')->with('ici', $ici)
                                      ->with('establecimientos', $establecimientos)
                                      ->with('consulta', $consulta);
    }

    public function edit($id)
    {
        $valor=2; //creacion 1 edicion 2
        $ici = $this->iciRepository->findWithoutFail($id);
        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.index'));
        }

        $mes = DB::table('mes')->pluck('descripcion','id');
        $ano = DB::table('years')->pluck('descripcion','id');
        $establecimientos=Establecimiento::pluck('nombre_establecimiento','id');
        return view('admin.icis.edit')->with('ici', $ici)
                                      ->with('mes', $mes)
                                      ->with('ano', $ano)
                                      ->with('valor', $valor)
                                      ->with('establecimientos', $establecimientos);
        
    }

    public function activar($ici_id, $establecimiento_id)
    {
        $ici = $this->iciRepository->findWithoutFail($ici_id);
        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.index'));
        }

        $establecimiento=Establecimiento::find($establecimiento_id);
        if (empty($establecimiento)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.show',$ici_id));
        }

        $establecimiento_ici = DB::table('establecimiento_ici')
                                ->join('establecimientos', 'establecimientos.id', '=', 'establecimiento_ici.establecimiento_id')
                                ->where('establecimiento_ici.ici_id',$ici_id)
                                ->where('establecimiento_ici.establecimiento_id',$establecimiento_id)
                                ->get();
        //dd($establecimiento_ici->get(0)->medicamento_cerrado);
        $cerrado_medicamento=$establecimiento_ici->get(0)->medicamento_cerrado;
        $cerrado_dispositivo=$establecimiento_ici->get(0)->dispositivo_cerrado;

        return view('admin.icis.activar')->with('cerrado_dispositivo', $cerrado_dispositivo)
                                         ->with('cerrado_medicamento', $cerrado_medicamento)
                                         ->with('ici',$ici)
                                         ->with('establecimiento_id',$establecimiento_id);
        
    }

    public function activar_servicio($ici_id, $establecimiento_id,$farmacia_id )
    {
        $ici = $this->iciRepository->findWithoutFail($ici_id);
        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.index'));
        }

        $establecimiento=Establecimiento::find($establecimiento_id);
        if (empty($establecimiento)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.show',$ici_id));
        }

        $farmacia_ici = DB::table('farmacia_ici')
                                ->join('farmacias', 'farmacias.id', '=', 'farmacia_ici.farmacia_id')
                                ->where('farmacia_ici.ici_id',$ici_id)
                                ->where('farmacia_ici.farmacia_id',$farmacia_id)
                                ->get();
        //dd($establecimiento_ici->get(0)->medicamento_cerrado);
        $cerrado_medicamento=$farmacia_ici->get(0)->medicamento_cerrado;
        
        $cerrado_dispositivo=$farmacia_ici->get(0)->dispositivo_cerrado;

        return view('admin.icis.activar_servicio')->with('cerrado_dispositivo', $cerrado_dispositivo)
                                         ->with('cerrado_medicamento', $cerrado_medicamento)
                                         ->with('ici',$ici)
                                         ->with('farmacia_id',$farmacia_id)
                                         ->with('establecimiento_id',$establecimiento_id);
    }

    public function update_petitorio(Request $request,$ici_id,$establecimiento_id)
    {
        $ici = $this->iciRepository->findWithoutFail($ici_id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.index'));
        }

        $establecimiento=Establecimiento::find($establecimiento_id);
        if (empty($establecimiento)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.show',$ici_id));
        }
        
        $medicamento=$request->input('cerrado_medicamento');
        if($medicamento==null)
            $medicamento=1;
        else
            $medicamento=2;
        
        $dispositivo=$request->input('cerrado_dispositivo');

        if($dispositivo==null)
            $dispositivo=1;
        else
            $dispositivo=2;
        
        //dd($medicamento);
        DB::table('establecimiento_ici')
            ->where('ici_id', $ici_id)
            ->where('establecimiento_id', $establecimiento_id)
            ->update([
                        'medicamento_cerrado' => $medicamento,
                        'dispositivo_cerrado' => $dispositivo,                        
                    ]);
        //$ici->establecimientos()->sync($request->establecimientos); //attach
        Flash::success('Actualizado satisfactoriamente.');

        return redirect(route('icis.show',$ici_id));
    }

    public function update_petitorio_servicio(Request $request,$ici_id,$establecimiento_id,$farmacia_id)
    {
        $ici = $this->iciRepository->findWithoutFail($ici_id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.index'));
        }

        $establecimiento=Establecimiento::find($establecimiento_id);
        if (empty($establecimiento)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.show',$ici_id));
        }

        $farmacia=Farmacia::find($farmacia_id);
        if (empty($farmacia)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.show',$ici_id));
        }

        
        $medicamento=$request->input('cerrado_medicamento');
        if($medicamento==null)
            $medicamento=1;
        else
            $medicamento=2;
        
        $dispositivo=$request->input('cerrado_dispositivo');

        if($dispositivo==null)
            $dispositivo=1;
        else
            $dispositivo=2;

        //$ici = $this->iciRepository->update($request->all(), $id);

        DB::table('farmacia_ici')
            ->where('ici_id', $ici_id)
            ->where('farmacia_id', $farmacia_id)
            ->where('establecimiento_id', $establecimiento_id)
            ->update([
                        'medicamento_cerrado' => $medicamento,
                        'dispositivo_cerrado' => $dispositivo,                        
                    ]);
        //$ici->establecimientos()->sync($request->establecimientos); //attach
        Flash::success('Actualizado satisfactoriamente.');

        return redirect(route('icis.mostrar_servicios',[$ici_id,$establecimiento_id]));
    }

    public function update($id, UpdateIciRequest $request)
    {
        $ici = $this->iciRepository->findWithoutFail($id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('icis.index'));
        }

        $year=Year::find($request->year_id);
        $mouth=DB::table('mes')->find($request->mes_id);
        $ano=$year->descripcion;
        $mes=$mouth->cod_mes;
        $anomes=$ano.$mes;

        $repetido=DB::table('icis')
                    ->where('anomes',$anomes)
                    ->where('id','<>',$id)
                    ->count();

        if ($repetido>0) {
            Flash::error('Ya se encuentra registrado');
        }
        else
        {
            switch ($mes) {
                case '01':$meses='Enero';break;
                case '02':$meses='Febrero';break;
                case '03':$meses='Marzo';break;
                case '04':$meses='Abril';break;
                case '05':$meses='Mayo';break;
                case '06':$meses='Junio';break;
                case '07':$meses='Julio';break;
                case '08':$meses='Agosto';break;
                case '09':$meses='Setiembre';break;
                case '10':$meses='Octubre';break;
                case '11':$meses='Noviembre';break;
                case '12':$meses='Diciembre';break;
            }

            //$ici = $this->iciRepository->update($request->all(), $id);

            DB::table('icis')
                ->where('id', $id)
                ->update([
                            'anomes' => $anomes,
                            'year_id' => $request->year_id,
                            'mes_id' => $request->mes_id,
                            'mes' =>$mes,
                            'desc_mes' =>$meses,
                            'ano'=>$ano,
                        ]);

            $ici->establecimientos()->sync($request->establecimientos); //attach
            $farmacias = DB::table('farmacias')->select('id','establecimiento_id')->get();
            foreach ($farmacias as $key => $farmacia) {
                DB::table('farmacia_ici')
                    ->insert([
                            'farmacia_id' => $farmacia->id,
                            'ici_id' => $ici->id,
                            'establecimiento_id' => $farmacia->establecimiento_id,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ]);  
            }

            Flash::success('Actualizado satisfactoriamente.');
        }    
        return redirect(route('icis.index'));
    }

    //////////////////////////////////
    public function dispositivos(Request $request,$ici_id, $establecimiento_id)
    {
        
        //buscamos el establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id);
        //si encuentra o no el establecimiento
        if (empty($establecimiento)) {
            Flash::error('Establecimientos ICI con esas caracteristicas');
            return redirect(route('abastecimiento.index'));
        }

        $ici = Ici::find($ici_id);
        if (empty($ici)) {
            Flash::error('No se tiene un ICI con esas caracteristicas');
            return redirect(route('abastecimiento.index'));
        }
    
        //Cargamos los datos a mostrar
        $abastecimientos=DB::table('abastecimientos')
                            ->where('establecimiento_id',$establecimiento_id)
                            ->where('anomes',$ici->anomes)
                            ->where('ici_id',$ici_id)
                            ->where('tipo_dispositivo_id','>',1)
                            ->get();
    
        //Si existe medicamento con valores negativos
        $valor_negativo = DB::table('abastecimientos')
                                ->where('stock_final', '<', 0)
                                ->where('anomes',$ici->anomes)
                                ->where('establecimiento_id',$establecimiento_id)
                                ->where('ici_id', '=', $ici_id)
                                ->count();
        
        //dd($valor_negativo); 
        return view('admin.icis.dispositivos.dispositivos')
                ->with('abastecimientos', $abastecimientos)
                ->with('establecimiento_id', $establecimiento_id)
                ->with('ici_id', $ici_id)
                ->with('valor_negativo',$valor_negativo);

    }

    public function medicamentos(Request $request,$ici_id, $establecimiento_id)
    {
            //buscamos el establecimiento
            $establecimiento = Establecimiento::find($establecimiento_id);
            //si encuentra o no el establecimiento
            if (empty($establecimiento)) {
                Flash::error('Establecimientos ICI con esas caracteristicas');
                return redirect(route('abastecimiento.index'));
            }
            $ici = Ici::find($ici_id);
            if (empty($ici)) {
                Flash::error('No se tiene un ICI con esas caracteristicas');
                return redirect(route('abastecimiento.index'));
            }
        
            //Cargamos los datos a mostrar
                $abastecimientos=DB::table('abastecimientos')
                                    ->where('establecimiento_id',$establecimiento_id)
                                    ->where('anomes',$ici->anomes)
                                    ->where('ici_id',$ici_id)
                                    ->where('tipo_dispositivo_id',1)
                                    ->get();
            
            //dd($abastecimientos);

            //Si existe medicamento con valores negativos
            $valor_negativo = DB::table('abastecimientos')
                                    ->where('stock_final', '<', 0)
                                    ->where('anomes',$ici->anomes)
                                    ->where('establecimiento_id',$establecimiento_id)
                                    ->where('ici_id', '=', $ici_id)
                                    ->count();

            //dd($valor_negativo); 
            return view('admin.icis.medicamentos.medicamentos')
                ->with('abastecimientos', $abastecimientos)
                ->with('establecimiento_id', $establecimiento_id)
                ->with('ici_id', $ici_id)
                ->with('valor_negativo',$valor_negativo);
        
    }

    public function medicamentos_abastecimientos(Request $request,$ici_id, $establecimiento_id)
    {       

        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');
            return redirect(route('abastecimiento.index'));
        }

        $ici = Ici::find($ici_id);

        if (empty($ici)) {
            Flash::error('ICI no encontrada');
            return redirect(route('abastecimiento.index'));
        }

        
        //SI LOS DATOS NO SON CARGADOS
            $abastecimientos=DB::table('abastecimientos')->
                                where( function ( $query )
                                {
                                    $query->orWhere('unidad_ingreso','>',0)
                                        ->orWhere('valor_ingreso','>',0)
                                        ->orWhere('stock_final','>',0)                                        
                                        ->orWhere('total_salidas','>',0);
                                })
                                ->where('anomes',$ici->anomes)
                                ->where('ici_id',$ici_id)
                                ->where('tipo_dispositivo_id',1)
                                ->where('establecimiento_id',$establecimiento_id)
                                ->get();
//            dd($abastecimientos);
        return view('admin.icis.medicamentos.descargar_medicamentos')
            ->with('abastecimientos', $abastecimientos)
            ->with('establecimiento_id', $establecimiento_id)
            ->with('ici_id', $ici_id);
            
    }

    public function dispositivos_abastecimientos(Request $request,$ici_id, $establecimiento_id)
        {       

            $establecimiento = Establecimiento::find($establecimiento_id);

            if (empty($establecimiento)) {
                Flash::error('Establecimientos no encontrado');
                return redirect(route('abastecimiento.index'));
            }

            $ici = Ici::find($ici_id);

            if (empty($ici)) {
                Flash::error('ICI no encontrada');
                return redirect(route('abastecimiento.index'));
            }

            
            //SI LOS DATOS NO SON CARGADOS
                $abastecimientos=DB::table('abastecimientos')->
                                    where( function ( $query )
                                    {
                                        $query->orWhere('unidad_ingreso','>',0)
                                            ->orWhere('valor_ingreso','>',0)
                                            ->orWhere('stock_final','>',0)                                        
                                            ->orWhere('total_salidas','>',0);
                                    })
                                    ->where('anomes',$ici->anomes)
                                    ->where('ici_id',$ici_id)
                                    ->where('tipo_dispositivo_id','>',1)
                                    ->where('establecimiento_id',$establecimiento_id)
                                    ->get();
    //            dd($abastecimientos);
            return view('admin.icis.dispositivos.descargar_dispositivos')
                ->with('abastecimientos', $abastecimientos)
                ->with('establecimiento_id', $establecimiento_id)
                ->with('ici_id', $ici_id);
                
        }
    

    /**
     * Remove the specified Ici from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ici = $this->iciRepository->findWithoutFail($id);

        if (empty($ici)) {
            Flash::error('No encontrado');

            return redirect(route('icis.index'));
        }

        $this->iciRepository->delete($id);

        Flash::success('Borrado correctamente.');

        return redirect(route('icis.index'));
    }
}
