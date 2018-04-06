@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Activar/Desactivar Petitorio
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($ici, ['route' => ['icis.update_petitorio_servicio', $ici->id,$establecimiento_id,$farmacia_id], 'method' => 'patch']) !!}

                        @include('admin.icis.fields_activar_servicio')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
@section('scripts')
    <script>
        $('document').ready(function(){
           $("#checkTodos").change(function () {
              $("input:checkbox").prop('checked', $(this).prop("checked"));
          });
        });
</script>
@stop
