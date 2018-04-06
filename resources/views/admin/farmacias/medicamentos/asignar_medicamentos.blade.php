@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Asignar Medicamentos - {!!$establecimiento->nombre_establecimiento!!}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::model($establecimiento, ['route' => ['farmacias.guardar_medicamentos', $establecimiento->id,$farmacia->id], 'method' => 'patch']) !!}

                        @include('admin.farmacias.medicamentos.fields')

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
  
$('#example1').dataTable( {
  "pageLength": 1000  
} );
</script>

@stop






