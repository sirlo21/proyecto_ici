@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Distrito
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($distrito, ['route' => ['distritos.update', $distrito->id], 'method' => 'patch']) !!}

                        @include('admin.distritos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
@section('scripts')
    <script src="{{ asset ("/js/jquery-2.1.0.min.js") }}"></script>
    <script src="{{ asset ("/js/dropdown.js") }}"></script>
@stop