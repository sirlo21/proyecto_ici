<div class="row">
    <div class="col-xs-12">
        <div>
            <div class="box-header">
                <h3 class="box-title"></h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                <table id="example" class="table table-responsive table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Región/Red</th>
                            <th>Categoría</th>
                            <th>T_Ipress</th>
                            <th></th>
                            <th></th>                  
                        </tr>
                        <tr>
                            <td>Código</td>
                            <td>Nombre_Establecimiento</td>
                            <td>Región/Red</td>
                            <td>Categoría</td>
                            <td>T_Ipress</td>
                            <td>Petitorio</td>
                            <td>Operaciones</td>                  
                        </tr>
                    </thead>                
                    <tbody>                        
                    @foreach($establecimientos as $key => $establecimiento)
                        <tr>
                            <td>{!! $establecimiento->codigo_establecimiento !!}</td>
                            <td>{!! $establecimiento->nombre_establecimiento !!}</td>
                            <td>{!! $establecimiento->est_region->descripcion !!}</td>
                            <td>{!! $establecimiento->est_categoria->descripcion !!}</td>
                            <td>{!! $establecimiento->est_tipo->descripcion !!}</td>
                            <td>
                            <div class='btn-group'>
                                @if ($establecimiento->nivel_id > 1)
                                    <a href="{!! route('farmacias.ver_farmacia', [$establecimiento->id,$establecimiento->nivel_id]) !!}" class='btn bg-orange btn-xs'><i class="fa fa-fw fa-h-square"></i></a>                                
                                @else
                                    <a href="{!! route('establecimientos.ver_medicamentos', [$establecimiento->id]) !!}" class='btn bg-purple btn-xs'><i class="fa fa-medkit"></i></a>
                                    <a href="{!! route('establecimientos.ver_dispositivos', [$establecimiento->id]) !!}" class='btn bg-navy btn-xs'><i class="fa fa-stethoscope"></i></a>
                                @endif

                            </div>
                            </td>                            
                            <td>
                                {!! Form::open(['route' => ['establecimientos.destroy', $establecimiento->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{!! route('establecimientos.show', [$establecimiento->id]) !!}" class='btn btn-info btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href="{!! route('establecimientos.edit', [$establecimiento->id]) !!}" class='btn btn-primary btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro de eliminar?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>                  
                            <th>Código</th>
                            <th>Nombre_Establecimiento</th>
                            <th>Región/Red</th>
                            <th>Categoría</th>
                            <th>T_Ipress</th>
                            <th>Petitorio</th>                            
                            <th>Operaciones</th>        
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>            
        </div>        
    </div>    
</div>