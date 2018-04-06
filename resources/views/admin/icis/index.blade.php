@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h3 class="pull-left">Configurar Abastecimiento</h3>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('icis.create') !!}">Nuevo <i class="glyphicon glyphicon-file"></i></a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')
        <br/><br/>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">

                    @include('admin.icis.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection


