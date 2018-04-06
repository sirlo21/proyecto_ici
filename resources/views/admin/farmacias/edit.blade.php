@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Farmacia
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($farmacia, ['route' => ['farmacias.update', $farmacia->id], 'method' => 'patch']) !!}

                        @include('admin.farmacias.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection