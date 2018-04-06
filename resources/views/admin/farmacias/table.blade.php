<table class="table table-responsive" id="farmacias-table">
    <thead>
        <tr>
            <th>Descripcion</th>
            <th>Medicamento/Dispositivo</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
    @foreach($farmacias as $farmacia)
        <tr>
            <td>{!! $farmacia->descripcion !!}</td>
            <td>
                <div class='btn-group'>
                    <a href="{!! route('farmacias.ver_medicamentos', [$farmacia->establecimiento_id,$farmacia->id]) !!}" class='btn bg-purple btn-xs'><i class="fa fa-medkit"></i></a>
                    <a href="{!! route('farmacias.ver_dispositivos', [$farmacia->establecimiento_id,$farmacia->id]) !!}" class='btn bg-navy btn-xs'><i class="fa fa-stethoscope"></i></a>
                </div>
            </td>    
            <td>
                {!! Form::open(['route' => ['farmacias.eliminar', $farmacia->id,$farmacia->establecimiento_id,$nivel_id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('farmacias.editar', [$farmacia->id,$farmacia->establecimiento_id,$nivel_id,]) !!}" class='btn btn-primary btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>