<!-- Codigo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('codigo_establecimiento', 'Código:') !!}
    {!! Form::text('codigo_establecimiento', null, ['class' => 'form-control']) !!}
</div>

<!-- Nombre Establecimiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nombre_establecimiento', 'Nombre Establecimiento:') !!}
    {!! Form::text('nombre_establecimiento', null, ['class' => 'form-control']) !!}
</div>

<!-- Region Red Field -->
<div class="form-group col-sm-6">
    {!! Form::label('region_red', 'Región/Red:') !!}
    {!! Form::select('region_id', $region_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Nivel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nivel', 'Nivel:') !!}
    {!! Form::select('nivel_id', $nivel_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Categoria Field -->
<div class="form-group col-sm-6">
    {!! Form::label('categoria', 'Categoría:') !!}
    {!! Form::select('categoria_id', $categoria_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Tipo Ipress Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipo_ipress', 'Tipo Establecimiento:') !!}
    {!! Form::select('tipo_establecimiento_id', $tipo_establecimiento_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Tipo Internamiento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipo_internamiento', 'Tipo Internamiento:') !!}
    {!! Form::select('tipo_internamiento', $tipo_internamiento_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Departamento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('departamento', 'Departamento:') !!}
    {!! Form::select('departamento_id', $departamento_id, null, ['id'=>'select-dpto','class' => 'form-control']) !!}
</div>

<!-- Provincia Field -->
<div class="form-group col-sm-6">
    {!! Form::label('provincia', 'Provincia:') !!}
    {!! Form::select('provincia_id', $provincia_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Distrito Field -->
<div class="form-group col-sm-6">
    {!! Form::label('distrito', 'Distrito:') !!}
    {!! Form::select('distrito_id', $distrito_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Disa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('disa', 'DISA:') !!}
    {!! Form::select('disa_id', $disa_id, null, ['class' => 'form-control']) !!}
</div>

<!-- Norte Field -->
<div class="form-group col-sm-6">
    {!! Form::label('norte', 'Norte:') !!}
    {!! Form::text('norte', null, ['class' => 'form-control']) !!}
</div>

<!-- Este Field -->
<div class="form-group col-sm-6">
    {!! Form::label('este', 'Este:') !!}
    {!! Form::text('este', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
    <a href="{!! route('establecimientos.index') !!}" class="btn btn-danger">Cancelar</a>
</div>
