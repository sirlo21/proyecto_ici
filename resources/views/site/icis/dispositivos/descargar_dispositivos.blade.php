@extends('layouts.app')
@section('css')
    <style type="text/css">
        th, td { white-space: nowrap; font-size: 10px;}
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
        <h1 class="pull-left">Dispositivos</h1>
        <h1 class="pull-right">
           <a class="btn btn-success pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('abastecimiento.exportAbastecimientoData',[$ici_id,$establecimiento_id,2,'xlsx']) !!}">Descargar <i class="fa fa-file-excel-o"></i></a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box">
            <div class="box-body">
              @include('site.icis.dispositivos.descarga_table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready( function () {
            var table = $('#example').DataTable({
                "responsive": true,
                "order": [[ 0, "asc" ]],                       
                "scrollY":        "400px",
                "scrollX":        true,
                "scrollCollapse": true,
                "pageLength": 500,  
                fixedColumns:   {
                    leftColumns: 3
                },                
            });
        })
    </script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js"></script>

@stop



    
    
    
    
    
    
    
    

