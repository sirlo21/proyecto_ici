<div class="row">
    <div class="col-xs-12">
            <!-- Mes Field -->
        <div class="col-xs-3 form-group">
            {!! Form::label('mes', 'Mes:') !!}
            {!! $ici->meses->descripcion !!}
        </div>
        <!-- Ano Field -->
        <div class="col-xs-3 form-group">
            {!! Form::label('ano', 'Año:') !!}
            {!! $ici->ano !!}
        </div>        
    </div>    
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box-body">
            <div class="box-body chat" id="chat-box">
                <?php $x=1; ?>
                <table id="example1" class="table table-responsive table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Establecimiento</th>
                            <th>Medicamento</th>
                            <th>Dispositivo</th>                            
                        </tr>                        
                    </thead>                
                    <tbody>               
                    @foreach($consulta as $id => $consulta)                        
                        <tr>
                            <td>{{$x++}}</td>
                            @if ($consulta->nivel_id == 1)         
                            <td><a href="{!! route('icis.activar',['ici_id'=>$consulta->ici_id,'establecimiento_id'=>$consulta->establecimiento_id]) !!}"'>{{ $consulta->nombre_establecimiento }}</a></td>                   
                            @else
                            <td><a href="{!! route('icis.mostrar_servicios',['ici_id'=>$consulta->ici_id,'establecimiento_id'=>$consulta->establecimiento_id]) !!}"'>{{ $consulta->nombre_establecimiento }}</a></td>
                            @endif
                            @if ($consulta->medicamento_cerrado == 1)         
                                <td> <a href="{!! route('icis.medicamentos',['ici_id'=>$consulta->ici_id,'establecimiento_id'=>$consulta->establecimiento_id]) !!}" class='btn btn-success btn-xs'> Abierto </a></td>
                            @else
                                <td> <a href="{!! route('icis.medicamentos_abastecimientos',['ici_id'=>$consulta->ici_id,'establecimiento_id'=>$consulta->establecimiento_id]) !!}" class='btn btn-danger btn-xs'> Cerrado </a></td>
                            @endif
                            @if ($consulta->dispositivo_cerrado == 1)         
                                <td> <a href="{!! route('icis.dispositivos', ['ici_id'=>$consulta->ici_id,'establecimiento_id'=>$consulta->establecimiento_id]) !!}" class='btn btn-success btn-xs'> Abierto </a></td>
                            @else
                                <td> <a href="{!! route('icis.dispositivos_abastecimientos', ['ici_id'=>$consulta->ici_id,'establecimiento_id'=>$consulta->establecimiento_id]) !!}" class='btn btn-danger btn-xs'> Cerrado </a></td>
                            @endif
                        </tr>
                        
                        
                    @endforeach
                    </tbody>
                </table>
            </div>            
        </div>  

    </div>    
</div>


