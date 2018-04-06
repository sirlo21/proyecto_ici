<div class="row">
    <div class="col-xs-12">
        <div>
            <div class="box-header">
                <h3 class="box-title"></h3>
            </div>
            <?php $x=1;?>
            <div class="box-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Mes</th>
                            <th>Año</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>#</td>
                            <td>Mes</td>
                            <td>Año</td>
                            <td>Medicamento</td>
                            <td>Dispositivo</td>
                            <td>Descargar</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $id_establecimiento = Auth::user()->establecimiento_id ?>
                    
                    @foreach($icis as $ici)
                        <tr>

                            <td>{!! $x++ !!}</td>
                            <td>{!! $ici->desc_mes !!}</td>
                            <td>{!! $ici->ano !!}</td>
                            <td>
                                <div class='btn-group'>
                                @if ($ici->medicamento_cerrado==1) <!--cerrado = 2 abierto=1-->
                                    <a href="{!! route('abastecimiento.cargar_medicamentos', ['ici_id'=>$ici->ici_id,'establecimiento_id'=>$ici->establecimiento_id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-unlock""></i> Abierto </a>
                                @else
                                    <small class="label label-danger"><i class="fa fa-lock"></i> Cerrado</small>                                    
                                @endif
                                </div>        
                            </td>
                            
                            <td>    
                                <div class='btn-group'>
                                @if ($ici->dispositivo_cerrado==1)
                                    <a href="{!! route('abastecimiento.cargar_dispositivos', ['ici_id'=>$ici->ici_id,'establecimiento_id'=>$ici->establecimiento_id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-unlock"></i> Abierto</a>
                                @else
                                    <small class="label label-danger"><i class="fa fa-lock"></i> Cerrado</small>                                    
                                @endif        
                                </div>
                            </td>
                            <td>
                                <div class='btn-group'>
                                    <a href="{!! route('abastecimiento.descargar_medicamentos', ['ici_id'=>$ici->ici_id,'establecimiento_id'=>$ici->establecimiento_id]) !!}" class='btn bg-orange btn-xs'
                                        @if ($ici->medicamento_cerrado==1)
                                            disabled 
                                        @endif
                                    ><i class="fa fa-medkit""></i></a>
                                    <a href="{!! route('abastecimiento.descargar_dispositivos', ['ici_id'=>$ici->ici_id,'establecimiento_id'=>$ici->establecimiento_id]) !!}" class='btn bg-olive btn-xs'
                                        @if ($ici->dispositivo_cerrado==1)
                                            disabled 
                                        @endif
                                    ><i class="fa fa-stethoscope"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>#</td>
                            <td>Mes</td>
                            <td>Año</td>
                            <td>Medicamento</td>
                            <td>Dispositivo</td>
                            <td>Descargar</td>
                        </tr>
                    </tfoot>
                </table>
            </div>            
        </div>        
    </div>    
</div>


