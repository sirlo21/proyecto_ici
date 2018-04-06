<!-- Descripcion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('descripcion', 'Descripcion:') !!}
    {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
</div>
<input type="hidden" name="id" value="<?php echo $establecimiento_id;?>">
<input type="hidden" name="nivel" value="<?php echo $nivel_id;?>">
<!-- Submit Field -->
<div class="form-group col-sm-12">
    <button type="submit" value="Guardar" class="btn btn-success">Guardar <i class="fa fa-save"></i></button>
    <a href="{!! route('farmacias.ver_farmacia',['establecimiento_id'=>$establecimiento_id,'nivel_id'=>$nivel_id]) !!}" class="btn btn-danger">Cancelar <i class="glyphicon glyphicon-remove"></i></a>
</div>

