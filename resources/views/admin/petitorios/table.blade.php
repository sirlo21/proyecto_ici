<div class="row">
    <div class="col-xs-12">
            <?php $x=1; ?>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="example" class="table table-responsive table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>                            
                                <th></th>
                                <th></th>
                                <th>Tipo_Dispositivo</th>
                                <th>Nivel</th>
                                <th>Tipo Uso</th>
                                <th></th>                  
                            </tr>
                            <tr>
                                <td><b>N°</b></td>
                                <td><b>Código</b></td>                            
                                <td><b>Descripción</b></td>
                                <td><b>Precio</b></td>
                                <td><b>Tipo_Dispositivo</b></td>
                                <td><b>Nivel</b></td>
                                <td><b>Tipo Uso</b></td>
                                <td><b>Operaciones</b></td>                  
                            </tr>
                        </thead>                
                        <tbody>                        
                        @foreach($petitorios as $key => $petitorio)
                            <tr>
                                <td>{{$x++}}</td>
                                <td>{!! $petitorio->codigo_petitorio !!}</td>
                                <td>{!! $petitorio->descripcion !!}</td>
                                <td>{!! $petitorio->precio !!}</td>
                                <!--td>{//!! $petitorio->pet_tipo_dispositivo->descripcion !!}</td-->
                                <td>{!! $petitorio->descripcion_tipo_dispositivo !!}</td>
                                <td>{!! $petitorio->descripcion_nivel !!}</td>
                                <!--td>{//!! $petitorio->pet_nivel->descripcion !!}</td-->
                                <td>{!! $petitorio->descripcion_tipo_uso !!}</td>
                                <!--td>{//!! $petitorio->pet_tipo_uso->descripcion !!}</td-->
                                
                                <td>{!! Form::open(['route' => ['petitorios.destroy', $petitorio->id], 'method' => 'delete']) !!}
                                <div class='btn-group'>
                                    <a href="{!! route('petitorios.show', [$petitorio->id]) !!}" class='btn btn-info btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                                    <a href="{!! route('petitorios.edit', [$petitorio->id]) !!}" class='btn btn-primary btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                </div>
                                {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>                  
                                <th>N°</th>
                                <th>Código</th>                            
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Tipo_Dispositivo</th>
                                <th>Nivel</th>
                                <th>Tipo Uso</th>
                                <th>Operaciones</th>       
                            </tr>
                        </tfoot>
                    </table>
                </div>    
            </div>            
        </div>        
    </div>    
</div>