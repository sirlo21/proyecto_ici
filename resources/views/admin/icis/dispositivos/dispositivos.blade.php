@extends('layouts.app')
@section('css')
    <style type="text/css">
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            width: 100%;
            margin: 0 auto;
        }        
    </style>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedcolumns/3.2.4/css/fixedColumns.dataTables.min.css" rel="stylesheet">
@stop
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Dispositivos Médicos</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box">
            <div class="box-body">
              <div class="form-group col-sm-12">
                <a href="{!! route('icis.show',[$ici_id]) !!}" class='btn btn-info'><i class="glyphicon glyphicon-hand-left"></i> Regresar</a>
              </div>
              @include('admin.icis.dispositivos.table')      
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js"></script>
<script>
  
    $(document).ready( function () {  
        var table = $('#example').DataTable({
            "responsive": true,
            "order": [[ 1, "desc" ]], 
            "scrollY":        "400px",
                "scrollX":        true,
                "scrollCollapse": true,
                "pageLength": 500,  
                fixedColumns:   {
                    leftColumns: 3
                },       
        });
    } );
</script>
@stop