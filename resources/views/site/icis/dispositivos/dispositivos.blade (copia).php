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
    @if ($establecimiento_id !== Auth::user()->establecimiento_id)
        <section class="content-header">
            <h3 class="pull-left">Listado de Dispositivos</h3>
            <div class="clearfix"></div>
            @if ($valor_negativo>0)
                <div class="callout callout-danger">
                    <h4>Advertencia!</h4>
                    <p>No podrá cerrar el petitorio si el Total de Ingreso (Stock_Inicial + Almacen Central + Directo del Proveedor + Transferencia) es menor al total de Salidas. <br/>El Stock Final  no debe ser negativo.</p>
                </div>
            @else
                @if ($dispositivo_cerrado==1)
                <h1 class="pull-right">
                   <a class="btn btn-danger pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('abastecimiento.cerrar_dispositivos',[$ici_id,$establecimiento_id]) !!}">Cerrar Petitorio</a>
                </h1>
                @endif
            @endif    
        </section>
        <div class="content">
            <div class="clearfix"></div>
            @include('flash::message')
            <div class="clearfix"></div>
            <div class="box box">
                <div class="box-body">
                  @include('site.icis.dispositivos.table')      
                </div>
            </div>
            <div class="text-center">
            
            </div>
        </div>
    @else
        <div class="content">
            <div class="clearfix"></div>
            <div class="box box">
                <div class="box-body">
                  No tienes autorización para ingresar a esta zona
                </div>
            </div>
            <div class="text-center">
            
            </div>
        </div>
    @endif  
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