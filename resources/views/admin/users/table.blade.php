<table class="table table-responsive" id="users-table">
    <thead>
        <tr>
            <th>#</th>                          
            <th>Nombre</th>
            <th>Grado</th>
            <th>Email</th>
            <th>Establecimiento</th>
            <th>Operaciones</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $key => $user)
        <tr>
            <td>{{$key+1}}</td>
            <td>{!! $user->name !!}</td>
            <td>{!! $user->grado !!}</td>
            <td>{!! $user->email !!}</td>
            <td>{!! $user->nombre_establecimiento !!}</td>
            <td>
                {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('users.show', [$user->id]) !!}" class='btn btn-info btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('users.edit', [$user->id]) !!}" class='btn btn-primary btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>