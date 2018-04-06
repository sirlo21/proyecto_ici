@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Usuarios
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}

                        @include('admin.users.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
@section('script')
<script type="text/javascript">
$(function() { $('#establecimiento_id').on('change', onSelectFarmacia); }); function onSelectCiudad(){ var establecimiento = $(this).val(); if(! establecimiento){ $('#farmacia_id').html('No ha Seleccione un Establecimiento'); return; } 
//ajax $.get('farmacias/'+establecimiento_id, function(data) { var html_select = 'Seleccione una farmacia'; for(var i=0; i<data.length; ++i) html_select += ''+data[i].nombre_farmacia+''; $('#farmacia_id').html(html_select); }); }
</script>
@stop