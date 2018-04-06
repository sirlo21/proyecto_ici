<div class="modal" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-contact" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="descripcion" class="col-md-3 control-label">Nombre del Medicamento</label>
                        <div class="col-md-9">
                            <input type="text" id="descripcion" name="descripcion" disabled class="form-control" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="cpma" class="col-md-3 control-label">CPMA</label>
                      <div class="col-md-3">
                          <input type="number" id="cpma" name="cpma" class="form-control" required>
                          <span class="help-block with-errors"></span>
                      </div>
                      <label for="stock_inicial" class="col-md-3 control-label">Stock Inicial</label>
                        <div class="col-md-3">
                            <input type="number" id="stock_inicial" name="stock_inicial" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="almacen_central" class="col-md-3 control-label">Ingreso de Almacén Central Saludpol (DIRSAPOL)</label>
                        <div class="col-md-3">
                            <input type="number" id="almacen_central" name="almacen_central" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="ingreso_proveedor" class="col-md-3 control-label">Ingreso directo del Proveedor</label>
                        <div class="col-md-3">
                            <input type="number" id="ingreso_proveedor" name="ingreso_proveedor" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ingreso_transferencia" class="col-md-3 control-label">Ingreso por Transferencia</label>
                        <div class="col-md-3">
                            <input type="number" id="ingreso_transferencia" name="ingreso_transferencia" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="unidad_consumo" class="col-md-3 control-label">Unidad de Consumo</label>
                        <div class="col-md-3">
                            <input type="number" id="unidad_consumo" name="unidad_consumo" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="salida_transferencia" class="col-md-3 control-label">Salida por Transferencia</label>
                        <div class="col-md-3">
                            <input type="number" id="salida_transferencia" name="salida_transferencia" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="merma" class="col-md-3 control-label">Pérdida/Merma</label>
                        <div class="col-md-3">
                            <input type="number" id="merma" name="merma" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fecha_vencimiento" class="col-md-3 control-label">Fecha Vencimiento Próxima</label>
                        <div class="col-md-3">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" class="form-control">
                                <span class="help-block with-errors"></span>
                            </div>   
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>