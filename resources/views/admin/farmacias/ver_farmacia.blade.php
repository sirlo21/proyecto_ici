@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Farmacia</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('farmacias.crear_farmacia',['establecimiento_id'=>$establecimiento_id,'nivel_id'=>$nivel_id]) !!}"> Nuevo <i class="glyphicon glyphicon-file"></i></a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.farmacias.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
        <a href="{!! route('establecimientos.index') !!}" class='btn btn-info'><i class="glyphicon glyphicon-hand-left"></i> Regresar</a>
    </div>
@endsection

