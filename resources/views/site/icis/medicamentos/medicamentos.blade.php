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
    <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
      <link href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
@stop
@section('content')
    @if ($establecimiento_id !== Auth::user()->establecimiento_id)
        <section class="content-header">
            <h3 class="pull-left">Listado de Medicamentos</h3>
            <div class="clearfix"></div>
            @if ($valor_negativo>0)
                <div class="callout callout-danger">
                    <h4>Advertencia!</h4>
                    <p>No podrá cerrar el petitorio si el Total de Ingreso (Stock_Inicial + Almacen Central + Directo del Proveedor + Transferencia) es menor al total de Salidas. <br/>El Stock Final  no debe ser negativo.</p>
                </div>
            @else
                @if ($nivel==1)
                    @if ($medicamento_cerrado==1)
                    <h1 class="pull-right">
                       <a class="btn btn-danger pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('abastecimiento.cerrar_medicamento',[$ici_id,$establecimiento_id]) !!}">Cerrar Petitorio</a>
                    </h1>                
                    @endif
                @else    
                    @if ($medicamento_cerrado==1)
                    <h1 class="pull-right">
                       <a class="btn btn-danger pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('abastecimiento.cerrar_medicamento_servicio',[$ici_id,$establecimiento_id,$farmacia_id]) !!}">Cerrar Petitorio</a>
                    </h1>                
                    @endif
                @endif                    
            @endif    
        </section>

        <div class="content">
            <div class="clearfix"></div>

            @include('flash::message')

            <div class="clearfix"></div>
            <div class="box box">
                <div class="box-body">
                  
                    <div class="row">
                        <div class="col-xs-12">        
                            <div class="box-body">
                                <table id="abastecimiento" class="table table-striped">
                                    <thead>
                                        <tr>
                                            
                                            <th rowspan="3" style="text-align:center;">Editar</th>
                                            <th rowspan="3" style="text-align:center;">Descripción</th>
                                            <th rowspan="3" style="text-align:center;">Precio</th>
                                            <th rowspan="3" style="text-align:center;">CPMA</th>
                                            <th colspan="6" bgcolor="#D4E6F1" style="text-align:center;">INGRESOS</th>
                                            <th colspan="5" bgcolor="#D4EFDF" style="text-align:center;">SALIDAS</th>
                                            <th colspan="3" bgcolor="#FEF5E7" style="text-align:center;">STOCK</th>
                                            <th rowspan="2" colspan="2" bgcolor="#FDEDEC" style="text-align:center;">SOBRESTOCK</th>
                                        </tr>
                                        <tr>
                                            <th bgcolor="#D4E6F1" style="text-align:center;" ><small>Stock Inicial</small></th>
                                            <th bgcolor="#D4E6F1" style="text-align:center;"><small>Almacen Central</small></th>
                                            <th bgcolor="#D4E6F1" style="text-align:center;"><small>Directo Proveedor</small></th>
                                            <th bgcolor="#D4E6F1" style="text-align:center;"><small>Transferencia</small></th>
                                            <th colspan="2" bgcolor="#D4E6F1" style="text-align:center;">TOTAL INGRESOS</th>
                                            <th colspan="2" bgcolor="#D4EFDF" style="text-align:center;">CONSUMO</th>
                                            <th bgcolor="#D4EFDF" style="text-align:center;"><small>Transferencia</small></th>
                                            <th bgcolor="#D4EFDF" style="text-align:center;"><small>Pérdida/Merma</small></th>
                                            <th bgcolor="#D4EFDF" style="text-align:center;"><small>Total Salidas</small></th>
                                            <th bgcolor="#FEF5E7" style="text-align:center;"><small>Final</small></th>
                                            <th bgcolor="#FEF5E7" style="text-align:center;"><small>Fecha Vencimiento</small></th>
                                            <th bgcolor="#FEF5E7" style="text-align:center;"><small>Disponibilidad</small></th>         
                                        </tr>
                                        <tr>
                                            <th bgcolor="#EAF2F8" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#EAF2F8" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#EAF2F8" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#EAF2F8" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#EAF2F8" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#EAF2F8" style="text-align:center;"><small>Valor</small></th>
                                            <th bgcolor="#D4EFDF" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#D4EFDF" style="text-align:center;"><small>Valor</small></th>
                                            <th bgcolor="#D4EFDF" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#D4EFDF" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#D4EFDF" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#FEF5E7" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#FEF5E7" style="text-align:center;"><small>Fecha</small></th>
                                            <th bgcolor="#FEF5E7" style="text-align:center;"><small>Meses</small></th>
                                            <th bgcolor="#FDEDEC" style="text-align:center;"><small>Unidad</small></th>
                                            <th bgcolor="#FDEDEC" style="text-align:center;"><small>Valor</small></th>
                                        </tr>
                                    </thead>  
                                    <tbody></tbody>
                                </table>
                            </div>                                 
                        </div>    
                    </div>
                    @include('site.icis.medicamentos.form')            
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

<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--script src="{{ asset('assets/jquery/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script-->

    {{-- dataTables --}}
    <!--script src="{{ asset('assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dataTables/js/dataTables.bootstrap.min.js') }}"></script-->

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{{ asset('assets/bootstrap/js/ie10-viewport-bug-workaround.js') }}"></script>

    <script type="text/javascript">
      var table = $('#abastecimiento').DataTable({
                      processing: true,
                      serverSide: true,
                        "responsive": true,
                        "scrollY":        "400px",
                        "scrollX":        true,
                        "scrollCollapse": true,
                        "pageLength": 1000,  
                        fixedColumns:   {
                                leftColumns: 3
                        }, //$abastecimientos
                      ajax: "{{ route('api.contact',['ici'=>$ici_id, 'establecimiento_id'=>$establecimiento_id,'anomes'=>$anomes,'tipo'=>$tipo]) }}",
                      columns: [
                        {data: 'action', name: 'Editar', orderable: false, searchable: false},
                        {data: 'descripcion'},
                        {data: 'precio'},
                        {data: 'cpma'},
                        {data: 'stock_inicial'},
                        {data: 'almacen_central'},
                        {data: 'ingreso_proveedor'},
                        {data: 'ingreso_transferencia'},
                        {data: 'unidad_ingreso'},
                        {data: 'valor_ingreso'},
                        {data: 'unidad_consumo'},
                        {data: 'valor_consumo'},
                        {data: 'salida_transferencia'},
                        {data: 'merma'},
                        {data: 'total_salidas'},
                        {data: 'stock_final'},
                        {data: 'fecha_vencimiento'},
                        {data: 'disponibilidad'},
                        {data: 'unidades_sobrestock'},
                        {data: 'valor_sobrestock'},
                      ]
                    });                          
                            

      function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#modal-form form')[0].reset();
        $.ajax({
          url: "{{ url('abastecimiento') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Abastecimiento');

            $('#id').val(data.id);
            $('#descripcion').val(data.descripcion);
            $('#cpma').val(data.cpma);
            $('#stock_inicial').val(data.stock_inicial);
            $('#almacen_central').val(data.almacen_central);
            $('#ingreso_proveedor').val(data.ingreso_proveedor);
            $('#ingreso_transferencia').val(data.ingreso_transferencia);
            $('#nombre_establecimiento').val(data.nombre_establecimiento);
            $('#unidad_consumo').val(data.unidad_consumo);
            $('#salida_transferencia').val(data.salida_transferencia);
            $('#merma').val(data.merma);
            $('#fecha_vencimiento').val(data.fecha_vencimiento);            
          },
          error : function() {
              alert("No hay Datos");
          }
        });
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    url = "{{ url('abastecimiento') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
//                        data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Excelente!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>

@stop

    
    
    
    
    
    
    
    

