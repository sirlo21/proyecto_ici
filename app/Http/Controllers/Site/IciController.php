<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\CreateIciRequest;
use App\Http\Requests\UpdateIciRequest;
use App\Repositories\IciRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Abastecimiento;
use App\Models\Abastecimiento2;
use App\Models\Petitorio;
use DB;
use App\Models\Establecimiento;
use App\Models\Ici;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\Auth;
use Excel;
use PHPExcel_Worksheet_Drawing;
use Yajra\DataTables\DataTables;

class IciController extends AppBaseController
{
    /** @var  IciRepository */
    private $iciRepository;

    public function __construct(IciRepository $iciRepo)
    {
        $this->iciRepository = $iciRepo;
    }

    public function index(Request $request)
    {
        $establecimiento_id=Auth::user()->establecimiento_id;
        $farmacia_id=Auth::user()->farmacia_id;

        $establecimiento = Establecimiento::find($establecimiento_id);
            
        $nivel=$establecimiento->nivel_id;

        if ($nivel==1)
        {
            $nombre_farmacia='';
            $icis = DB::table('icis')
                ->join('establecimiento_ici', 'establecimiento_ici.ici_id', '=', 'icis.id')
                ->join('establecimientos', 'establecimiento_ici.establecimiento_id', '=', 'establecimientos.id')
                ->where('establecimiento_ici.establecimiento_id',$establecimiento_id)
                ->get();
        
        }
        else
        {
            $nombre_farmacia=Auth::user()->nombre_farmacia;
            $icis = DB::table('icis')
                ->join('farmacia_ici', 'farmacia_ici.ici_id', '=', 'icis.id')
                ->join('farmacias', 'farmacia_ici.farmacia_id', '=', 'farmacias.id')
                ->where('farmacia_ici.farmacia_id',$farmacia_id)
                ->where('farmacia_ici.establecimiento_id',$establecimiento_id)
                ->get();
        
        }

        return view('site.icis.index')
            ->with('nombre_farmacia', $nombre_farmacia)
            ->with('icis', $icis);       

    }

    
    public function show($id)
    {
        $ici = $this->iciRepository->findWithoutFail($id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('abastecimiento.index'));
        }

        $establecimientos=Establecimiento::pluck('nombre_establecimiento','id');

    }

    public function edit($id)
    {
        $establecimiento_id=Auth::user()->establecimiento_id;

        $establecimiento = Establecimiento::find($establecimiento_id);
            
        $nivel=$establecimiento->nivel_id;

        if ($nivel==1)
        {
            $contact = Abastecimiento::findOrFail($id);
        }
        else
        {
            $contact = Abastecimiento2::findOrFail($id);
            //dd($contact);
        }

        
        return $contact;
        
    }

    public function update($id, UpdateIciRequest $request)
    {
       
        $establecimiento_id=Auth::user()->establecimiento_id;

        $establecimiento = Establecimiento::find($establecimiento_id);
            
        $nivel=$establecimiento->nivel_id;

        if ($nivel==1)
        {

            $abastecimiento = Abastecimiento::find($id);
        }
        else
        {
            $abastecimiento = Abastecimiento2::find($id);
            //dd($contact);
        }

        if (empty($abastecimiento)) {
            Flash::error('Abastecimiento no encontrado');

            return redirect(route('abastecimientos.index'));
        }
    
        $precio=$request->input("precio");
        $cpma = $request->input("cpma");
        $stock_inicial = $request->input("stock_inicial");        
        $almacen_central = $request->input("almacen_central");
        $ingreso_proveedor = $request->input("ingreso_proveedor");
        $ingreso_transferencia = $request->input("ingreso_transferencia");

        $unidad_ingreso = $almacen_central+$ingreso_proveedor+$ingreso_transferencia;
        $valor_ingreso=$precio*$unidad_ingreso;

        
        $unidad_consumo = $request->input("unidad_consumo");  
        $valor_consumo=$unidad_consumo*$precio;

        $salida_transferencia = $request->input("salida_transferencia");
        $merma = $request->input("merma");  

        $total_salidas = $unidad_consumo+$salida_transferencia+$merma;

        $stock_final=($stock_inicial+$unidad_ingreso)-$total_salidas;        
        $fecha_vencimiento = $request->input("fecha_vencimiento");     

        //variable oculta
        $establecimiento_id = $request->input("establecimiento_id");
        $tipo_dispositivo_id = $request->input("tipo_dispositivo_id");
        
        if ($cpma>0) { 
            $disponibilidad=$stock_final/$cpma;
        } 
        else
        {
            $disponibilidad=$stock_final;
        }

        
        $unidades_sobrestock=$stock_final-($cpma*6);

        if ($unidades_sobrestock<=0)
            $unidades_sobrestock=0;
                
        $valor_sobrestock=$precio*$unidades_sobrestock;
        
        if ($nivel==1)
        {

            DB::table('abastecimientos')
            ->where('id', $id)
            ->update([
                        'cpma' => $cpma,
                        'stock_inicial' => $stock_inicial,
                        'almacen_central' => $almacen_central,
                        'ingreso_proveedor' => $ingreso_proveedor,
                        'ingreso_transferencia' => $ingreso_transferencia,
                        'unidad_ingreso' => $unidad_ingreso,
                        'valor_ingreso' => $valor_ingreso,
                        'unidad_consumo' => $unidad_consumo,
                        'valor_consumo' => $valor_consumo,
                        'salida_transferencia' => $salida_transferencia,
                        'merma' => $merma,                        
                        'total_salidas' => $total_salidas,
                        'stock_final' => $stock_final,
                        'fecha_vencimiento' => $fecha_vencimiento,
                        'disponibilidad' => $disponibilidad,
                        'unidades_sobrestock' => $unidades_sobrestock,
                        'valor_sobrestock' => $valor_sobrestock,
             ]);
        
        }
        else
        {
            DB::table('abastecimientos_servicios')
            ->where('id', $id)
            ->update([
                        'cpma' => $cpma,
                        'stock_inicial' => $stock_inicial,
                        'almacen_central' => $almacen_central,
                        'ingreso_proveedor' => $ingreso_proveedor,
                        'ingreso_transferencia' => $ingreso_transferencia,
                        'unidad_ingreso' => $unidad_ingreso,
                        'valor_ingreso' => $valor_ingreso,
                        'unidad_consumo' => $unidad_consumo,
                        'valor_consumo' => $valor_consumo,
                        'salida_transferencia' => $salida_transferencia,
                        'merma' => $merma,                        
                        'total_salidas' => $total_salidas,
                        'stock_final' => $stock_final,
                        'fecha_vencimiento' => $fecha_vencimiento,
                        'disponibilidad' => $disponibilidad,
                        'unidades_sobrestock' => $unidades_sobrestock,
                        'valor_sobrestock' => $valor_sobrestock,
             ]);
        
        }
            
        return response()->json([
            'success' => true,
            'message' => 'Datos Actualizado'
        ]); 
    }

   
    //////////////////////////////////
    public function medicamentos_abastecimientos(Request $request,$ici_id,$establecimiento_id)
    {


        ///mostrar todos los petitorios del establecimiento
        $ici = Ici::find($ici_id);

        if (empty($ici)) {
            Flash::error('Ici no encontrada');
            return redirect(route('abastecimiento.index'));
        }

        //dd($ici->anomes);

        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');

            return redirect(route('abastecimiento.index'));
        }
        
        //SI LOS DATOS NO SON CARGADOS
        $abastecimientos=DB::table('abastecimientos')
            ->where('establecimiento_id',$establecimiento_id)
            ->where('anomes',$ici->anomes)
            ->where('tipo_dispositivo_id',1)
            ->get();
       
        //dd($abastecimientos); 
        return view('site.icis.medicamentos.medicamentos')
            ->with('abastecimientos', $abastecimientos)
            ->with('establecimiento_id', $establecimiento_id)
            ->with('ici_id', $ici_id);
            
    }

    
    protected function crear_petitorio_abastecimientos_servicios($anomes, $ici_id,$establecimiento_id, $farmacia_id)
    {

        //buscamos si existen datos ya grabados
        $numero_abastecimiento=DB::table('abastecimientos_servicios')
                                    ->where('farmacia_id',$farmacia_id)
                                    ->where('anomes',$anomes)
                                    ->count();   

        //Si numero de abastecimiento es 0 significa que aun no se ha cargado datos y empezara a llenar informacion
        
        if ($numero_abastecimiento==0){
            $sw=0;
            
            // Hacemos la consulta de los medicamentos que han sido asignado a cada establecimiento
                $abastecimientos = DB::table('farmacias')
                ->join('farmacia_petitorio', 'farmacia_petitorio.farmacia_id', '=', 'farmacias.id')
                ->join('petitorios', 'farmacia_petitorio.petitorio_id', '=', 'petitorios.id')
                ->where('farmacias.id', '=', $farmacia_id)
                ->get();

            //buscamos si existe registro del mes anterior
            $num_ici=DB::table('icis')->orderBy('id', 'desc')->count();

            
            //si hay registro entonces cargamos llenamos los valores con el stock_inicial
            if ($num_ici>3){
                
                //buscamos el ici anterior
                $icis = DB::table('icis')->orderBy('id', 'desc')->get();
                $anomes_ici=$icis->get(1)->anomes;

                //BUSCAMOS LOS ABASTECIMIENTO ANTERIOR
                $abastecimientos_anterior=DB::table('abastecimientos_servicios')
                        ->where( function ( $query )
                            {
                                $query->orWhere('unidad_ingreso','>',0)
                                    ->orWhere('valor_ingreso','>',0)
                                    ->orWhere('stock_final','>',0)
                                    ->orWhere('total_salidas','>',0);
                            })
                            ->where('anomes',$anomes_ici)
                            ->where('establecimiento_id',$establecimiento_id)
                            ->where('farmacia_id',$farmacia_id)
                            ->get();

                foreach($abastecimientos as $key => $abastecimiento){
                
                    DB::table('abastecimientos_servicios')
                         ->insert([
                            'ici_id' => $ici_id,
                            'anomes' => $anomes,
                            'establecimiento_id' => $abastecimiento->establecimiento_id,
                            'farmacia_id' => $abastecimiento->farmacia_id,
                            'cod_establecimiento' => $abastecimiento->codigo_establecimiento,
                            'nombre_establecimiento' => $abastecimiento->nombre_establecimiento,
                            'tipo_dispositivo_id' => $abastecimiento->tipo_dispositivo_medicos_id,
                            'petitorio_id' => $abastecimiento->petitorio_id,
                            'cod_petitorio' => $abastecimiento->codigo_petitorio,
                            'descripcion' => $abastecimiento->descripcion,
                            'precio' => $abastecimiento->precio
                    ]);     
                } 
                
                //recorremos los datos para saber  si han ingresado con el stock_inicial
                foreach($abastecimientos as $key => $abastecimiento){
                
                    
                    foreach($abastecimientos_anterior as $id => $abastecimiento_anterior){     

                        if($abastecimiento_anterior->petitorio_id == $abastecimiento->petitorio_id){
                            $stock_final=$abastecimiento_anterior->stock_final;
                            $cpma_anterior=$abastecimiento_anterior->cpma;
                            $sw=1;

                            DB::table('abastecimientos_servicios')
                              ->where('ici_id',$ici_id)
                              ->where('establecimiento_id',$establecimiento_id)
                              ->where('farmacia_id',$farmacia_id)
                              ->where('petitorio_id',$abastecimiento->petitorio_id)
                              ->update([
                                        'cpma'=> $cpma_anterior,
                                        'stock_inicial' => $stock_final
                                ]);

                            break;
                        }
                    }

                    $sw=0;
                    
                }
            }//sino existe entonces llenamos solo los datos
            else
            {
               
                // Hacemos la consulta de los medicamentos que han sido asignado a cada establecimiento
                $abastecimientos = DB::table('farmacias')
                ->join('farmacia_petitorio', 'farmacia_petitorio.farmacia_id', '=', 'farmacias.id')
                ->join('establecimientos', 'establecimientos.id', '=', 'farmacias.establecimiento_id')
                ->join('petitorios', 'farmacia_petitorio.petitorio_id', '=', 'petitorios.id')
                ->where('farmacias.id', '=', $farmacia_id)
                ->get();

               foreach($abastecimientos as $key => $abastecimiento){
                
                    DB::table('abastecimientos_servicios')
                         ->insert([
                            'ici_id' => $ici_id,
                            'anomes' => $anomes,
                            'establecimiento_id' => $abastecimiento->establecimiento_id,
                            'farmacia_id' => $abastecimiento->farmacia_id,
                            'cod_establecimiento' => $abastecimiento->codigo_establecimiento,
                            'nombre_establecimiento' => $abastecimiento->nombre_establecimiento,
                            'tipo_dispositivo_id' => $abastecimiento->tipo_dispositivo_medico_id,
                            'petitorio_id' => $abastecimiento->petitorio_id,
                            'cod_petitorio' => $abastecimiento->codigo_petitorio,
                            'descripcion' => $abastecimiento->descripcion,
                            'precio' => $abastecimiento->precio
                    ]);     
                }     
            }    
            
        }    
    }    

    protected function crear_petitorio_abastecimientos($anomes, $ici_id,$establecimiento_id)
    {

        //buscamos si existen datos ya grabados
        $numero_abastecimiento=DB::table('abastecimientos')
                                    ->where('establecimiento_id',$establecimiento_id)
                                    ->where('anomes',$anomes)
                                    ->count();   

        //Si numero de abastecimiento es 0 significa que aun no se ha cargado datos y empezara a llenar informacion
        if ($numero_abastecimiento==0){
            $sw=0;
            
            // Hacemos la consulta de los medicamentos que han sido asignado a cada establecimiento
                $abastecimientos = DB::table('establecimientos')
                ->join('establecimiento_petitorio', 'establecimiento_petitorio.establecimiento_id', '=', 'establecimientos.id')
                ->join('petitorios', 'establecimiento_petitorio.petitorio_id', '=', 'petitorios.id')
                ->where('establecimientos.id', '=', $establecimiento_id)
                ->get();

            //buscamos si existe registro del mes anterior
            $num_ici=DB::table('icis')->orderBy('id', 'desc')->count();

            //si hay registro entonces cargamos llenamos los valores con el stock_inicial
            if ($num_ici>1){
                
                //buscamos el ici anterior
                $icis = DB::table('icis')->orderBy('id', 'desc')->get();
                $anomes_ici=$icis->get(1)->anomes;

                //BUSCAMOS LOS ABASTECIMIENTO ANTERIOR
                $abastecimientos_anterior=DB::table('abastecimientos')
                        ->where( function ( $query )
                            {
                                $query->orWhere('unidad_ingreso','>',0)
                                    ->orWhere('valor_ingreso','>',0)
                                    ->orWhere('stock_final','>',0)
                                    ->orWhere('total_salidas','>',0);
                            })
                            ->where('anomes',$anomes_ici)
                            ->where('establecimiento_id',$establecimiento_id)
                            ->get();

                foreach($abastecimientos as $key => $abastecimiento){
                
                    DB::table('abastecimientos')
                         ->insert([
                            'ici_id' => $ici_id,
                            'anomes' => $anomes,
                            'establecimiento_id' => $abastecimiento->establecimiento_id,
                            'cod_establecimiento' => $abastecimiento->codigo_establecimiento,
                            'nombre_establecimiento' => $abastecimiento->nombre_establecimiento,
                            'tipo_dispositivo_id' => $abastecimiento->tipo_dispositivo_medicos_id,
                            'petitorio_id' => $abastecimiento->petitorio_id,
                            'cod_petitorio' => $abastecimiento->codigo_petitorio,
                            'descripcion' => $abastecimiento->descripcion,
                            'precio' => $abastecimiento->precio
                    ]);     
                } 
                
                //recorremos los datos para saber  si han ingresado con el stock_inicial
                foreach($abastecimientos as $key => $abastecimiento){
                
                    
                    foreach($abastecimientos_anterior as $id => $abastecimiento_anterior){     

                        if($abastecimiento_anterior->petitorio_id == $abastecimiento->petitorio_id){
                            $stock_final=$abastecimiento_anterior->stock_final;
                            $cpma_anterior=$abastecimiento_anterior->cpma;
                            $sw=1;

                            DB::table('abastecimientos')
                              ->where('ici_id',$ici_id)
                              ->where('establecimiento_id',$establecimiento_id)
                              ->where('petitorio_id',$abastecimiento->petitorio_id)
                              ->update([
                                        'cpma'=> $cpma_anterior,
                                        'stock_inicial' => $stock_final
                                ]);

                            break;
                        }
                    }

                    $sw=0;
                    
                }
            }//sino existe entonces llenamos solo los datos
            else
            {
               foreach($abastecimientos as $key => $abastecimiento){
                
                    DB::table('abastecimientos')
                         ->insert([
                            'ici_id' => $ici_id,
                            'anomes' => $anomes,
                            'establecimiento_id' => $abastecimiento->establecimiento_id,
                            'cod_establecimiento' => $abastecimiento->codigo_establecimiento,
                            'nombre_establecimiento' => $abastecimiento->nombre_establecimiento,
                            'tipo_dispositivo_id' => $abastecimiento->tipo_dispositivo_medicos_id,
                            'petitorio_id' => $abastecimiento->petitorio_id,
                            'cod_petitorio' => $abastecimiento->codigo_petitorio,
                            'descripcion' => $abastecimiento->descripcion,
                            'precio' => $abastecimiento->precio
                    ]);     
                }     
            }    
            
        }    
    }    
    
    ///////////////////Cerramos los medicamentos
    public function cerrar_medicamento(Request $request,$ici_id,$establecimiento_id)
    {
        
        $ici = $this->iciRepository->findWithoutFail($ici_id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('abastecimiento.index'));
        }

        $establecimiento=Establecimiento::find($establecimiento_id);
        if (empty($establecimiento)) {
            Flash::error('No se ha encontrado');

            return redirect(route('abastecimiento.show',$ici_id));
        }
            
        $nivel=$establecimiento->nivel_id;

        if ($nivel==1)
        {

            DB::table('establecimiento_ici')
                ->where('ici_id', $ici_id)
                ->where('establecimiento_id', $establecimiento_id)
                ->update([
                        'medicamento_cerrado' => 2,
            ]);

        }
        else
        {
            $farmacia_id=Auth::user()->farmacia_id;
            DB::table('farmacia_ici')
                ->where('ici_id', $ici_id)
                ->where('establecimiento_id', $establecimiento_id)
                ->where('farmacia_id', $farmacia_id)
                ->update([
                        'medicamento_cerrado' => 2,
            ]);
            //dd($contact);
        }




        Flash::success('Petitorio de Medicamento Cerrado.');

        return redirect(route('abastecimiento.cargar_medicamentos',[$ici_id,$establecimiento_id]));
    }

    ///////////////////Cerramos los medicamentos
    public function cerrar_medicamento_servicio(Request $request,$ici_id,$establecimiento_id,$farmacia_id)
    {
        
        $ici = $this->iciRepository->findWithoutFail($ici_id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('abastecimiento.index'));
        }

        $establecimiento=Establecimiento::find($establecimiento_id);
        if (empty($establecimiento)) {
            Flash::error('No se ha encontrado');

            return redirect(route('abastecimiento.show',$ici_id));
        }

        $nivel=$establecimiento->nivel_id;

        if ($nivel==1)
        {

            DB::table('establecimiento_ici')
                ->where('ici_id', $ici_id)
                ->where('establecimiento_id', $establecimiento_id)
                ->update([
                        'medicamento_cerrado' => 2,
            ]);
        }
        else
        {
            $farmacia_id=Auth::user()->farmacia_id;
            DB::table('farmacia_ici')
                ->where('ici_id', $ici_id)
                ->where('establecimiento_id', $establecimiento_id)
                ->where('farmacia_id', $farmacia_id)
                ->update([
                        'medicamento_cerrado' => 2,
            ]);
            //dd($contact);
        }
        Flash::success('Petitorio de Medicamento Cerrado.');

        return redirect(route('abastecimiento.cargar_medicamentos',[$ici_id,$establecimiento_id]));


    }

    public function apiContact($ici_id,$establecimiento_id,$anomes,$tipo)
    {

        //verificamos la farmacia del usuario
        $farmacia_id=Auth::user()->farmacia_id;
        
        //Cargamos los datos a mostrar
        $petitorio_cerrado = DB::table('establecimiento_ici')
                                    ->where('establecimiento_id', '=', $establecimiento_id)
                                    ->where('ici_id', '=', $ici_id)
                                    ->get();

        //averiguamos el nivel del establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id);
        $nivel=$establecimiento->nivel_id;
        
        if($nivel==1){

            if($tipo==1){  //medicamento

                    $contact=DB::table('abastecimientos')
                                    ->where('establecimiento_id',$establecimiento_id)
                                    ->where('ici_id',$ici_id)
                                    ->where('anomes',$anomes)
                                    ->where('tipo_dispositivo_id',1)
                                    ->get();
                    $cerrado=$petitorio_cerrado->get(0)->medicamento_cerrado;
            }
            else
            {
                        $contact=DB::table('abastecimientos')
                                        ->where('establecimiento_id',$establecimiento_id)
                                        ->where('anomes',$anomes)
                                        ->where('ici_id',$ici_id)
                                        ->where('tipo_dispositivo_id','>',1)
                                        ->get();
                        $cerrado=$petitorio_cerrado->get(0)->dispositivo_cerrado;
            }        

        }
        else
        {
            if($tipo==1){  //medicamento

                    $contact=DB::table('abastecimientos_servicios')
                                    ->where('establecimiento_id',$establecimiento_id)
                                    ->where('farmacia_id',$farmacia_id)
                                    ->where('ici_id',$ici_id)
                                    ->where('anomes',$anomes)
                                    ->where('tipo_dispositivo_id',1)
                                    ->get();
                    $cerrado=$petitorio_cerrado->get(0)->medicamento_cerrado;
            }
            else
            {
                        $contact=DB::table('abastecimientos_servicios')
                                        ->where('establecimiento_id',$establecimiento_id)
                                        ->where('farmacia_id',$farmacia_id)
                                        ->where('anomes',$anomes)
                                        ->where('ici_id',$ici_id)
                                        ->where('tipo_dispositivo_id','>',1)
                                        ->get();
                    $cerrado=$petitorio_cerrado->get(0)->dispositivo_cerrado;
            }              
        }    

                
            
        if($cerrado==2){

                return Datatables::of($contact)
                ->addColumn('action', function($contact){
                    return '<a href="#" disabled class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>' ;                
                })
                ->rawColumns(['valor_sobrestock', 'action'])->make(true);    

        }
        else
        {

                return Datatables::of($contact)
                ->addColumn('action', function($contact){
                  return '<a onclick="editForm('. $contact->id .')" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>' ;                
                })
                ->rawColumns(['valor_sobrestock', 'action'])->make(true);    
        }
    }

    //////////////////////////////////
    public function cargar_medicamentos(Request $request,$ici_id, $establecimiento_id)
    {
        //Verificamos si el usuario es el mismo
        if (Auth::user()->establecimiento_id == $establecimiento_id ){

            $farmacia_id=Auth::user()->farmacia_id;
            //buscamos el establecimiento
            $establecimiento = Establecimiento::find($establecimiento_id);
            //si encuentra o no el establecimiento
            if (empty($establecimiento)) {
                Flash::error('Establecimientos ICI con esas caracteristicas');
                return redirect(route('abastecimiento.index'));
            }

            $nivel=$establecimiento->nivel_id;
            //si viene del menu
            if($ici_id==0){
                $icis=DB::table('icis')->orderBy('id', 'desc')->first();
                $ici = Ici::find($icis->id);
                $ici_id=$icis->id;
            }
            else
            {
                $ici = Ici::find($ici_id);
                if (empty($ici)) {
                    Flash::error('No se tiene un ICI con esas caracteristicas');
                    return redirect(route('abastecimiento.index'));
                }
            }    
            
            
            if ($nivel==1 ){            
                $this->crear_petitorio_abastecimientos($ici->anomes, $ici->id,$establecimiento_id);
            }
            else
            {
                $this->crear_petitorio_abastecimientos_servicios($ici->anomes, $ici->id,$establecimiento_id, $farmacia_id);   
            }    

            if ($nivel==1 ){
            //Verificamos si petitorio esta cerrado
            $petitorio_cerrado = DB::table('establecimiento_ici')
                                    ->where('establecimiento_id', '=', $establecimiento_id)
                                    ->where('ici_id', '=', $ici->id)
                                    ->get();

            //como existe aunque sea 1 recogemos si esta cerrado o no
            $medicamento_cerrado=$petitorio_cerrado->get(0)->medicamento_cerrado;

            //Si existe medicamento con valores negativos
            $valor_negativo = DB::table('abastecimientos')
                                    ->where('stock_final', '<', 0)
                                    ->where('anomes',$ici->anomes)
                                    ->where('establecimiento_id',$establecimiento_id)
                                    ->where('ici_id', '=', $ici->id)
                                    ->where('tipo_dispositivo_id',1)
                                    ->count();
            }
            else
            {
                
                //Verificamos si petitorio esta cerrado
                $petitorio_cerrado = DB::table('farmacia_ici')
                                        ->where('establecimiento_id', '=', $establecimiento_id)
                                        ->where('farmacia_id', '=', $farmacia_id)
                                        ->where('ici_id', '=', $ici->id)
                                        ->get();

                //como existe aunque sea 1 recogemos si esta cerrado o no
                $medicamento_cerrado=$petitorio_cerrado->get(0)->medicamento_cerrado;

                //Si existe medicamento con valores negativos
                $valor_negativo = DB::table('abastecimientos_servicios')
                                        ->where('stock_final', '<', 0)
                                        ->where('anomes',$ici->anomes)
                                        ->where('farmacia_id', '=', $farmacia_id)
                                        ->where('establecimiento_id',$establecimiento_id)
                                        ->where('ici_id', '=', $ici->id)
                                        ->where('tipo_dispositivo_id',1)
                                        ->count();   
            }    
            $tipo=1;


            return view('site.icis.medicamentos.medicamentos')
                           ->with('establecimiento_id', $establecimiento_id)
                           ->with('farmacia_id', $farmacia_id)
                           ->with('nivel', $nivel)
                           ->with('ici_id', $ici->id)
                           ->with('anomes', $ici->anomes)
                           ->with('tipo', $tipo)
                           ->with('medicamento_cerrado', $medicamento_cerrado)
                           ->with('valor_negativo',$valor_negativo);
        }
        else
        {
            Flash::error('No se tiene un ICI con esas caracteristicas');
            return redirect(route('abastecimiento.index'));
        }    
        
            
    }
    
    //////////////////////////////////
    public function descargar_medicamentos(Request $request,$ici_id, $establecimiento_id)
    {       
        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');
            return redirect(route('abastecimiento.index'));
        }
        
        $farmacia_id=Auth::user()->farmacia_id;

        $nivel=$establecimiento->nivel_id;

        $ici = Ici::find($ici_id);

        if (empty($ici)) {
            Flash::error('ICI no encontrada');
            return redirect(route('abastecimiento.index'));
        }

        if($nivel==1){
            $abastecimientos=DB::table('abastecimientos')
                        ->where( function ( $query )
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

        }
        else
        {
            $abastecimientos=DB::table('abastecimientos_servicios')
                        ->where( function ( $query )
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
                            ->where('farmacia_id',$farmacia_id)
                            ->get();

        }    
        
        return view('site.icis.medicamentos.descargar_medicamentos')
            ->with('abastecimientos', $abastecimientos)
            ->with('establecimiento_id', $establecimiento_id)
            ->with('farmacia_id', $farmacia_id)
            ->with('ici_id', $ici_id);
    }
    
    
    //////////////////////////////////

    public function cargar_dispositivos(Request $request,$ici_id, $establecimiento_id)
    {
        //Verificamos si el usuario es el mismo
        if (Auth::user()->establecimiento_id == $establecimiento_id ){

            $farmacia_id=Auth::user()->farmacia_id;

            //buscamos el establecimiento
            $establecimiento = Establecimiento::find($establecimiento_id);
            //si encuentra o no el establecimiento
            if (empty($establecimiento)) {
                Flash::error('Establecimientos ICI con esas caracteristicas');
                return redirect(route('abastecimiento.index'));
            }

            $nivel=$establecimiento->nivel_id;

            //si viene del menu
            $sw=0;
            if($ici_id==0){
                $icis=DB::table('icis')->orderBy('id', 'desc')->first();
                $ici = Ici::find($icis->id);
                $ici_id=$icis->id;
                $sw=1;
            }
            else
            {
                $ici = Ici::find($ici_id);
                if (empty($ici)) {
                    Flash::error('No se tiene un ICI con esas caracteristicas');
                    return redirect(route('abastecimiento.index'));
                }
            }    
            
            
            if ($nivel==1 ){            
                
                    $this->crear_petitorio_abastecimientos($ici->anomes, $ici->id,$establecimiento_id);    
                
            }
            else
            {
                $this->crear_petitorio_abastecimientos_servicios($ici->anomes, $ici->id,$establecimiento_id, $farmacia_id);   
            }    
            
            //Verificamos si petitorio esta cerrado
            // Hacemos la consulta de los medicamentos que han sido asignado a cada establecimiento
            if ($nivel==1 ){            
                
                $petitorio_cerrado = DB::table('establecimiento_ici')
                                        ->where('establecimiento_id', '=', $establecimiento_id)
                                        ->where('ici_id', '=', $ici_id)
                                        ->get();

                //como existe aunque sea 1 recogemos si esta cerrado o no
                $dispositivo_cerrado=$petitorio_cerrado->get(0)->dispositivo_cerrado;

                //Si existe medicamento con valores negativos
                $valor_negativo = DB::table('abastecimientos')
                                        ->where('stock_final', '<', 0)
                                        ->where('anomes',$ici->anomes)
                                        ->where('establecimiento_id',$establecimiento_id)
                                        ->where('ici_id', '=', $ici_id)
                                        ->where('tipo_dispositivo_id','>',1)
                                        ->count();

            }
            else
            {
                
                //Verificamos si petitorio esta cerrado
                $petitorio_cerrado = DB::table('farmacia_ici')
                                        ->where('establecimiento_id', '=', $establecimiento_id)
                                        ->where('farmacia_id', '=', $farmacia_id)
                                        ->where('ici_id', '=', $ici_id)
                                        ->get();

                //como existe aunque sea 1 recogemos si esta cerrado o no
                $dispositivo_cerrado=$petitorio_cerrado->get(0)->dispositivo_cerrado;

                //Si existe medicamento con valores negativos
                $valor_negativo = DB::table('abastecimientos_servicios')
                                        ->where('stock_final', '<', 0)
                                        ->where('anomes',$ici->anomes)
                                        ->where('farmacia_id', '=', $farmacia_id)
                                        ->where('establecimiento_id',$establecimiento_id)
                                        ->where('ici_id', '=', $ici->id)
                                        ->where('tipo_dispositivo_id','>',1)
                                        ->count();   
            }    

            $tipo=2;

            //dd($establecimiento_id); 
            return view('site.icis.dispositivos.dispositivos')
                ->with('establecimiento_id', $establecimiento_id)
                ->with('ici_id', $ici_id)
                ->with('nivel', $nivel)
                ->with('farmacia_id', $farmacia_id)
                ->with('tipo', $tipo)
                ->with('anomes', $ici->anomes)
                ->with('medicamento_cerrado', $dispositivo_cerrado)
                ->with('valor_negativo',$valor_negativo);
        }
        else
        {
            Flash::error('No se tiene un ICI con esas caracteristicas');
            return redirect(route('abastecimiento.index'));
        }    
        
            
    }


    ///////////////////////////////    
    //////////////////////////////////
        public function descargar_dispositivos(Request $request,$ici_id, $establecimiento_id)
        {       

            $establecimiento = Establecimiento::find($establecimiento_id);

            if (empty($establecimiento)) {
                Flash::error('Establecimientos no encontrado');
                return redirect(route('abastecimiento.index'));
            }

            $nivel=$establecimiento->nivel_id;

            $ici = Ici::find($ici_id);

            if (empty($ici)) {
                Flash::error('ICI no encontrada');
                return redirect(route('abastecimiento.index'));
            }

            if($nivel==1){
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
            }
            else
            {
                //SI LOS DATOS NO SON CARGADOS
                $abastecimientos=DB::table('abastecimientos_servicios')->
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
                                    ->where('farmacia_id',$farmacia_id)
                                    ->get();
            }    
            

    //            dd($abastecimientos);
            return view('site.icis.dispositivos.descargar_dispositivos')
                ->with('abastecimientos', $abastecimientos)
                ->with('farmacia_id', $farmacia_id)
                ->with('establecimiento_id', $establecimiento_id)
                ->with('ici_id', $ici_id);
                
        }

    public function cerrar_dispositivos(Request $request,$ici_id,$establecimiento_id)
    {
        

        $ici = $this->iciRepository->findWithoutFail($ici_id);

        if (empty($ici)) {
            Flash::error('No se ha encontrado');

            return redirect(route('abastecimiento.index'));
        }

        $establecimiento=Establecimiento::find($establecimiento_id);
        if (empty($establecimiento)) {
            Flash::error('No se ha encontrado');

            return redirect(route('abastecimiento.show',$ici_id));
        }

        $nivel=$establecimiento->nivel_id;
        $farmacia_id=Auth::user()->farmacia_id;

        if ($nivel==1)
        {

            DB::table('establecimiento_ici')
                  ->where('ici_id', $ici_id)
                  ->where('establecimiento_id', $establecimiento_id)
                  ->update([
                        'dispositivo_cerrado' => 2,
            ]);
        }
        else
        {
            DB::table('farmacia_ici')
                  ->where('ici_id', $ici_id)
                  ->where('farmacia_id', $farmacia_id)
                  ->where('establecimiento_id', $establecimiento_id)
                  ->update([
                        'dispositivo_cerrado' => 2,
            ]);
        }
        

        Flash::success('Petitorio de Dispositivo Cerrado.');

        return redirect(route('abastecimiento.cargar_dispositivos',[$ici_id,$establecimiento_id]));
    }

    public function exportAbastecimientoData($ici_id,$establecimiento_id,$opt,$type)
    {
        
        $establecimiento = Establecimiento::find($establecimiento_id);

        if (empty($establecimiento)) {
            Flash::error('Establecimientos no encontrado');
            return redirect(route('abastecimiento.index'));
        }

        $nivel=$establecimiento->nivel_id;

        $farmacia_id=Auth::user()->farmacia_id;
        

        $ici = Ici::find($ici_id);

        if (empty($ici)) {
            Flash::error('ICI no encontrada');
            return redirect(route('abastecimiento.index'));
        }

        if ($nivel==1)
        {

            if($opt==1){
                $data=DB::table('abastecimientos')->
                        where( function ( $query )
                        {
                            $query->orWhere('unidad_ingreso','>',0)
                                ->orWhere('valor_ingreso','>',0)
                                ->orWhere('stock_final','>',0)                                        
                                ->orWhere('total_salidas','>',0);
                        })
                        ->where('anomes',$ici->anomes) //cambiar 201801
                        ->where('ici_id',$ici_id) //cambiar 22
                        ->where('tipo_dispositivo_id',1)
                        ->where('establecimiento_id',$establecimiento_id)//cambiar 1
                        ->get();
            }else
                {   if ($opt==2) {
                        $data=DB::table('abastecimientos')->
                        where( function ( $query )
                        {
                            $query->orWhere('unidad_ingreso','>',0)
                                ->orWhere('valor_ingreso','>',0)
                                ->orWhere('stock_final','>',0)                                        
                                ->orWhere('total_salidas','>',0);
                        })
                        ->where('anomes',$ici->anomes) //cambiar 201801
                        ->where('ici_id',$ici_id) //cambiar 22
                        ->where('tipo_dispositivo_id','>',1)
                        ->where('establecimiento_id',$establecimiento_id)//cambiar 1
                        ->get();                    
                    }else
                    {
                        Flash::error('Datos no son correctos, error al descargar archivo');
                        return redirect(route('abastecimiento.index'));  
                    }
            }
        }
        else
        {
            if($opt==1){
                $data=DB::table('abastecimientos_servicios')->
                        where( function ( $query )
                        {
                            $query->orWhere('unidad_ingreso','>',0)
                                ->orWhere('valor_ingreso','>',0)
                                ->orWhere('stock_final','>',0)                                        
                                ->orWhere('total_salidas','>',0);
                        })
                        ->where('anomes',$ici->anomes) //cambiar 201801
                        ->where('ici_id',$ici_id) //cambiar 22
                        ->where('tipo_dispositivo_id',1)
                        ->where('establecimiento_id',$establecimiento_id)//cambiar 1
                        ->where('farmacia_id',$farmacia_id)//cambiar 1
                        ->get();
            }else
                {   if ($opt==2) {
                        $data=DB::table('abastecimientos_servicios')->
                        where( function ( $query )
                        {
                            $query->orWhere('unidad_ingreso','>',0)
                                ->orWhere('valor_ingreso','>',0)
                                ->orWhere('stock_final','>',0)                                        
                                ->orWhere('total_salidas','>',0);
                        })
                        ->where('anomes',$ici->anomes) //cambiar 201801
                        ->where('ici_id',$ici_id) //cambiar 22
                        ->where('tipo_dispositivo_id','>',1)
                        ->where('establecimiento_id',$establecimiento_id)//cambiar 1
                        ->where('farmacia_id',$farmacia_id)//cambiar 1
                        ->get();                    
                    }else
                    {
                        Flash::error('Datos no son correctos, error al descargar archivo');
                        return redirect(route('abastecimiento.index'));  
                    }
            }   
        }     

        //$data = Abastecimiento::get()->toArray();
        $archivo='ICI_'.$ici->desc_mes.'_'.$ici->ano.'_'.$establecimiento->nombre_establecimiento;
        return Excel::create($archivo, function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {   
                
                //3 filas agrupadas con las columnas A,B,C
                $sheet->setMergeColumn(array(
                    'columns' => array('A','B'),
                    'rows' => array(
                        array(1,6)                        
                    )
                ));

                //INSERTAR LOGOS
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('img/logo_sanidad.png')); //your image path
                $objDrawing->setCoordinates('A2');
                $objDrawing->setWorksheet($sheet);
                
                $objDrawing2 = new PHPExcel_Worksheet_Drawing;
                $objDrawing2->setPath(public_path('img/logo_pnp.png')); //your image path
                $objDrawing2->setCoordinates('Q2');
                $objDrawing2->setWorksheet($sheet);
    

                $sheet->cell('B1', function($cell) {$cell->setValue('ICI');   });


                $sheet->setMergeColumn(array(
                    'columns' => array('Q'),
                    'rows' => array(
                        array(1,5)                        
                    )
                ));
                $sheet->cell('B1', function($cell) {$cell->setValue('ICI');  $cell->setFontSize(48); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle');
                });

                $sheet->mergeCells('C1:P1');
                $sheet->cell('C1', function($cell) {$cell->setValue('SISTEMA INTEGRADO DE SUMINISTRO DE  PRODUCTOS ESTRATÉGICOS SISPE-PNP');  $cell->setFontSize(18); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });

                $sheet->setHeight(1, 40);
                $sheet->setHeight(2, 30);
                $sheet->setHeight(4, 30);
                $sheet->setHeight(8, 30);
                

                $sheet->mergeCells('C2:N2');
                $sheet->cell('C2', function($cell) {$cell->setValue('INFORME DE CONSUMO INTEGRADO');  $cell->setFontSize(14); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });


                $sheet->mergeCells('C3:P3');
                
                $sheet->mergeCells('M5:P5');

                $sheet->mergeCells('C5:L5');
                    $sheet->setMergeColumn(array(
                        'columns' => ['Q'],
                        'rows' => [
                            [20, 21]
                        ]
                    ));

                $sheet->setMergeColumn(array(
                    'columns' => array('Q'),
                    'rows' => array(
                        array(1,5)                        
                    )
                ));
                $sheet->cell('B1', function($cell) {$cell->setValue('ICI');   });

                $sheet->mergeCells('O2:P2');
                $sheet->cell('O2', function($cell) {$cell->setValue('SISPE - PNP');   $cell->setFontSize(28); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });

                //colocammos el EE.SS.
                $sheet->cell('C4', function($cell) {$cell->setValue('EE.SS.');   $cell->setFontSize(13); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });

                $sheet->mergeCells('J4:K4');
                $sheet->cell('J4', function($cell) {$cell->setValue('CODIGO IPRESS');   $cell->setFontSize(13); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });

                $sheet->mergeCells('L4:M4');

                $sheet->mergeCells('N4:P4');

                $sheet->mergeCells('C6:L6');

                $sheet->cell('M6', function($cell) {$cell->setValue('(J+K+L)');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); });

                $sheet->cell('N6', function($cell) {$cell->setValue('((I+E)-M)'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); });            

                $sheet->cell('P6', function($cell) {$cell->setValue('(N/D)'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); });            
                $sheet->cell('Q6', function($cell) {$cell->setValue('(N-(Dx6)'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); });            

                $sheet->cell('A7', function($cell) {$cell->setValue('A'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('B7', function($cell) {$cell->setValue('B');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('C7', function($cell) {$cell->setValue('C'); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cell('D7', function($cell) {$cell->setValue('D'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('E7', function($cell) {$cell->setValue('E'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('F7', function($cell) {$cell->setValue('F'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cell('G7', function($cell) {$cell->setValue('G');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('H7', function($cell) {$cell->setValue('H'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('I7', function($cell) {$cell->setValue('I'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cell('J7', function($cell) {$cell->setValue('J'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('K7', function($cell) {$cell->setValue('K');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('L7', function($cell) {$cell->setValue('L'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center');  $cell->setFontWeight('bold'); });

                $sheet->cell('M7', function($cell) {$cell->setValue('M'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('N7', function($cell) {$cell->setValue('N'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('O7', function($cell) {$cell->setValue('O');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cell('P7', function($cell) {$cell->setValue('P'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('Q7', function($cell) {$cell->setValue('Q'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->setMergeColumn(array(
                    'columns' => array('A','B','C','D','Q'),
                    'rows' => array(
                        array(8,11)                        
                    )
                ));

                $sheet->cell('A8', function($cell) {$cell->setValue('COD MED'); $cell->setFontSize(10);   $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('B8', function($cell) {$cell->setValue('NOMBRE DEL PRODUCTO'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(10); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('C8', function($cell) {$cell->setValue('PRECIO'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('D8', function($cell) {$cell->setValue('CPMA');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('Q8', function($cell) {$cell->setValue('SOBRESTOCK');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(13);  $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cells('E8:I8', function($cells) {
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->mergeCells('E8:I8');
                $sheet->cell('E8', function($cell) {$cell->setValue('INGRESOS'); $cell->setFontSize(13);  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cells('J8:M8', function($cells) {
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->mergeCells('J8:M8');
                $sheet->cell('J8', function($cell) {$cell->setValue('SALIDAS'); $cell->setFontSize(13); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cells('N8:P8', function($cells) {
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->mergeCells('N8:P8');
                $sheet->cell('N8', function($cell) {$cell->setValue('STOCK'); $cell->setFontSize(13); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });                

                $sheet->setMergeColumn(array(
                    'columns' => array('J','P'),
                    'rows' => array(
                        array(9,10)                        
                    )
                ));

                $sheet->cell('E9', function($cell) {$cell->setValue('STOCK'); $cell->setFontSize(10); $cell->setAlignment('center'); $cell->setBorder('', '', '', ''); $cell->setFontWeight('bold'); }); 

                $sheet->cell('E10', function($cell) {$cell->setValue('INICIAL '); $cell->setFontSize(10); $cell->setAlignment('center'); $cell->setBorder('', '', '', ''); $cell->setFontWeight('bold'); }); 

                $sheet->cell('F9', function($cell) {$cell->setValue('INGRESO DE ALMACEN'); $cell->setFontSize(10); $cell->setAlignment('center'); $cell->setBorder('', '', '', 'thin');  $cell->setFontWeight('bold'); }); 

                $sheet->cell('F10', function($cell) {$cell->setValue('CENTRAL DIRSAPOL (SALUDPOL)'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setBorder('', '', '', 'thin');  $cell->setFontWeight('bold'); }); 

                $sheet->cell('G9', function($cell) {$cell->setValue('INGRESO DIRECTO '); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setBorder('', '', '', 'thin'); $cell->setFontWeight('bold');  }); 
                $sheet->cell('G10', function($cell) {$cell->setValue('DEL PROVEEDOR'); $cell->setAlignment('center'); $cell->setBorder('', '', '', 'thin'); $cell->setFontSize(10); $cell->setFontWeight('bold');  }); 

                $sheet->cell('H9', function($cell) {$cell->setValue('INGRESO POR'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('H10', function($cell) {$cell->setValue('TRANSFERENCIA'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold');  }); 

                $sheet->cell('I9', function($cell) {$cell->setValue('TOTAL DE'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('I10', function($cell) {$cell->setValue('INGRESO'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('J9', function($cell) {$cell->setValue('CONSUMO');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('K9', function($cell) {$cell->setValue('SALIDA POR'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('K10', function($cell) {$cell->setValue('TRANSFERENCIA'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('L9', function($cell) {$cell->setValue('PERDIDA/'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('L10', function($cell) {$cell->setValue('MERMA'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('M9', function($cell) {$cell->setValue('TOTAL DE'); $cell->setBorder('', '', '', 'thin');  $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('M10', function($cell) {$cell->setValue('SALIDAS'); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontSize(10); $cell->setFontWeight('bold'); }); 

                $sheet->cell('N9', function($cell) {$cell->setValue('STOCK '); $cell->setFontSize(10); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('N10', function($cell) {$cell->setValue('FINAL');$cell->setFontSize(10);  $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('O9', function($cell) {$cell->setValue('F.V. PROXIMA'); $cell->setFontSize(10); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('O10', function($cell) {$cell->setValue('(dia/mes/año)'); $cell->setFontSize(10); $cell->setBorder('', '', '', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('P9', function($cell) {$cell->setValue('DISPONIBILIDAD');$cell->setFontSize(10);  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                $sheet->cell('E11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('F11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cell('G11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('H11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('I11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center');  $cell->setFontWeight('bold'); });

                $sheet->cell('J11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('K11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('L11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cell('M11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('N11', function($cell) {$cell->setValue('UND'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('O11', function($cell) {$cell->setValue('FECHAS');$cell->setFontSize(9);  $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                $sheet->cell('P11', function($cell) {$cell->setValue('MESES'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->cell('Q11', function($cell) {$cell->setValue('UNIDADES'); $cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });            

                $sheet->setWidth(array(
                    'A'     =>  20,
                    'B'     =>  100,
                    'C'     =>  12,
                    'D'     =>  12,
                    'E'     =>  12,
                    'F'     =>  32,
                    'G'     =>  20,
                    'H'     =>  18,
                    'I'     =>  12,
                    'J'     =>  12,
                    'K'     =>  18,
                    'L'     =>  12,
                    'M'     =>  12,
                    'N'     =>  12,
                    'O'     =>  15,
                    'P'     =>  18,
                    'Q'     =>  20,
                ));

                $i=13;

                if (!empty($data)) {

                    $cpma = 0;
                    $stock_inicial = 0;
                    $almacen_central= 0;
                    $ingreso_proveedor = 0;
                    $ingreso_transferencia = 0;
                    $unidad_ingreso = 0;
                    $unidad_consumo = 0;
                    $salida_transferencia = 0;
                    $merma = 0;
                    $total_salidas = 0;
                    $stock_final = 0;
                    $disponibilidad = 0;
                    $unidades_sobrestock = 0;
                    
                    $sheet->mergeCells('D4:H4');

                    foreach ($data as $key => $value) {
                        $i= $key+13;
                        $sheet->cell('A'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('B'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('C'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('D'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('E'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('F'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('G'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('H'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('I'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setBackground('#E0E6F8'); });    
                        $sheet->cell('J'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('K'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('L'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('M'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setBackground('#E0F8E0'); });     
                        $sheet->cell('N'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setBackground('#F5F6CE'); });     
                        $sheet->cell('O'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('P'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setBackground('#D8D8D8'); });    
                        $sheet->cell('Q'.$i, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setBackground('#F8E0E0'); });    

                        $sheet->cell('A'.$i, $value->cod_petitorio); 
                        $sheet->cell('B'.$i, $value->descripcion); 
                        $sheet->cell('C'.$i, $value->precio); 
                        $sheet->cell('D'.$i, $value->cpma); 
                        $sheet->cell('E'.$i, $value->stock_inicial); 
                        $sheet->cell('F'.$i, $value->almacen_central); 
                        $sheet->cell('G'.$i, $value->ingreso_proveedor); 
                        $sheet->cell('H'.$i, $value->ingreso_transferencia); 
                        $sheet->cell('I'.$i, $value->unidad_ingreso); 
                        $sheet->cell('J'.$i, $value->unidad_consumo); 
                        $sheet->cell('K'.$i, $value->salida_transferencia); 
                        $sheet->cell('L'.$i, $value->merma); 
                        $sheet->cell('M'.$i, $value->total_salidas); 
                        $sheet->cell('N'.$i, $value->stock_final); 
                        $sheet->cell('O'.$i, $value->fecha_vencimiento); 
                        $sheet->cell('P'.$i, $value->disponibilidad); 
                        $sheet->cell('Q'.$i, $value->unidades_sobrestock); 
                        $sheet->cell('D4', $value->nombre_establecimiento); 
                        $sheet->cell('L4', $value->cod_establecimiento);     

                        //Calcular sumatoria
                        $cpma = $value->cpma + $cpma;
                        $stock_inicial = $value->stock_inicial+$stock_inicial;
                        $almacen_central= $value->almacen_central+$almacen_central;
                        $ingreso_proveedor = $value->ingreso_proveedor + $ingreso_proveedor;
                        $ingreso_transferencia = $value->ingreso_transferencia + $ingreso_transferencia;
                        $unidad_ingreso = $value->unidad_ingreso + $unidad_ingreso;
                        $unidad_consumo = $value->unidad_consumo + $unidad_consumo;
                        $salida_transferencia = $value->salida_transferencia + $salida_transferencia;
                        $merma = $value->merma + $merma;
                        $total_salidas = $value->total_salidas + $total_salidas;
                        $stock_final = $value->stock_final + $stock_final;
                        $disponibilidad = $value->disponibilidad + $disponibilidad;
                        $unidades_sobrestock = $value->unidades_sobrestock + $unidades_sobrestock;

                    }

                    $sheet->cell('D12', $cpma);
                    $sheet->cell('D12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('E12', $stock_inicial);
                    $sheet->cell('E12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('F12', $almacen_central);
                    $sheet->cell('F12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('G12', $ingreso_proveedor);
                    $sheet->cell('G12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('H12', $ingreso_transferencia);
                    $sheet->cell('H12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('I12', $unidad_ingreso);
                    $sheet->cell('I12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('J12', $unidad_consumo);
                    $sheet->cell('J12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('K12', $salida_transferencia);
                    $sheet->cell('K12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('L12', $merma);
                    $sheet->cell('L12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('M12', $total_salidas);     
                    $sheet->cell('M12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('N12', $stock_final);
                    $sheet->cell('N12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('P12', $disponibilidad);
                    $sheet->cell('P12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                    $sheet->cell('Q12', $unidades_sobrestock);
                    $sheet->cell('Q12', function($cell) {$cell->setFontSize(9); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                }

                $i=$i+10;
                $j=$i+1;

                $sheet->cell('B'.$i, function($cell) {$cell->setValue('____________________________________________________');   $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });

                $sheet->cell('B'.$j, function($cell) {$cell->setValue('RESPONSABLE DEL ESTABLECIMIENTO DE SALUD');   $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });

                $sheet->mergeCells('O2:P2');
                $sheet->mergeCells('H'.$i.':'.'K'.$i);
                $sheet->cell('H'.$i, function($cell) {$cell->setValue('______________________________________');  $cell->setFontSize(14); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });

                $sheet->mergeCells('H'.$j.':'.'K'.$j);
                $sheet->cell('H'.$j, function($cell) {$cell->setValue('RESPONSABLE DE LA FARMACIA');   $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });

            });
        })->download($type);
    }
    
    public function destroy($id)
    {
        $ici = $this->iciRepository->findWithoutFail($id);

        if (empty($ici)) {
            Flash::error('No encontrado');

            return redirect(route('abastecimientos.index'));
        }

        $this->iciRepository->delete($id);
        Flash::success('Borrado correctamente.');
        return redirect(route('abastecimientos.index'));
    }
}
