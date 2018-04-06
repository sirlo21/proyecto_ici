<!-- Departamento Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('departamento_id', 'Departamento:') !!}
    {!! Form::select('departamento_id', $departamento_id, null, ['id'=>'departamento','class' => 'form-control']) !!}
</div>

<!-- Provincia Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('provincia_id', 'Provincia:') !!}
    {!! Form::select('provincia_id', $provincia_id, null,['id'=>'provincia', 'class' => 'form-control']) !!}
</div>

<!-- Nombre Dist Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nombre_dist', 'Nombre Dist:') !!}
    {!! Form::text('nombre_dist', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    <button type="submit" value="Guardar" class="btn btn-success">Guardar <i class="fa fa-save"></i></button>
    <a href="{!! route('distritos.index') !!}" class="btn btn-danger">Cancelar <i class="glyphicon glyphicon-remove"></i></a>
</div>
