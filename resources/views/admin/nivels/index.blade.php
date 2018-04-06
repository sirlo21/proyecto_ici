@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Nivel</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('nivels.create') !!}">Nuevo <i class="glyphicon glyphicon-file"></i></a>
        </h1>
        <br/><br/>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.nivels.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection
