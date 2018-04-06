<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dni', 'DNI:') !!}
    {!! Form::text('dni', null, ['class' => 'form-control']) !!}
</div>
<!-- Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nombres', 'Nombres:') !!}
    {!! Form::text('nombres', null, ['class' => 'form-control']) !!}
</div>

<!-- Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('apellidos', 'Apellidos:') !!}
    {!! Form::text('apellidos', null, ['class' => 'form-control']) !!}
</div>

<!-- Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('telefono', 'Telefono:') !!}
    {!! Form::text('telefono', null, ['class' => 'form-control']) !!}
</div>

<!-- Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('grado', 'Grado:') !!}
    {!! Form::select('grado_id', $grado_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('establecimiento', 'Establecimiento:') !!}
    {!! Form::select('establecimiento_id', $establecimiento_id, null, ['class' => 'form-control','id'=>'establecimiento_id']) !!}
</div>

<!-- Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('farmacia', 'Farmacia:') !!}
    {!! Form::select('farmacia_id', $farmacia_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Contraseña:') !!}
    {!! Form::password('password', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancelar</a>
</div>
