<div class="modal" id="modal-formu" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-contacto" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
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
                        <label for="descripcion" class="col-md-6 control-label">Número de Recetas Atendidas</label>
                        <div class="col-md-3">
                            <input type="text" id="descripcion" name="descripcion" class="form-control" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>