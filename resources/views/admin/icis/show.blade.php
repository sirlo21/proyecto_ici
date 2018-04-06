@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Listado de Establecimientos
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.icis.show_fields')
                    <a href="{!! route('icis.index') !!}" class='btn btn-info'><i class="glyphicon glyphicon-hand-left"></i> Regresar</a>
                </div>
            </div>
        </div>
    </div>
@endsection