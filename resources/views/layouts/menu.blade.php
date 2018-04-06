@if (Auth::user()->rol==1)
<ul class="sidebar-menu">
    <li class="header">MENU</li>
    <li class="{{ Request::is('home*') ? 'active' : '' }}">
        <a href="{{ url('/home') }}">
            <i class="fa fa-fw fa-home "></i>
            <span>Inicio</span>
        </a>    
    </li>   
    <li class="{{ Request::is('icis*') ? 'active' : '' }}">
        <a href="#"> <i class="fa fa-truck"></i> <span>Abastecimiento</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>  
        <ul class="treeview-menu">
            <li class="{{ Request::is('icis*') ? 'active' : '' }}">
                <a href="{!! route('icis.index') !!}"><i class="fa fa-fw fa-list-alt "></i><span>   Registrar</span></a>
            </li>            
        </ul>
    </li>
    <li class="{{ Request::is('petitorios*') ? 'active' : ''}}">
        <a href="#">
            <i class="fa fa-fw fa-medkit"></i><span>Petitorio</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('petitorios*') ? 'active' : '' }}">
                <a href="{!! route('petitorios.index') !!}"><i class="fa fa-fw fa-medkit"></i><span>Petitorio</span></a>
            </li>
            <li class="{{ Request::is('tipoDispositivoMedicos*') ? 'active' : '' }}">
                <a href="{!! route('tipoDispositivoMedicos.index') !!}"><i class="fa fa-fw fa-flask"></i><span>Tipo Dispositivo Medicos</span></a>
            </li>
            <li class="{{ Request::is('tipoUsos*') ? 'active' : '' }}">
                <a href="{!! route('tipoUsos.index') !!}"><i class="fa fa-fw fa-plus-square"></i><span>Tipo Usos</span></a>
            </li> 
            <li class="{{ Request::is('unidadMedidas*') ? 'active' : '' }}">
                <a href="{!! route('unidadMedidas.index') !!}"><i class="fa fa-fw fa-ticket"></i><span>Unidad Medidas</span></a>
            </li>
            <li class="{{ Request::is('years*') ? 'active' : '' }}">
                <a href="{!! route('years.index') !!}"><i class="fa fa-edit"></i><span>Año</span></a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
        <i class="fa fa-fw fa-industry "></i>
            <span>Establecimiento</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('establecimientos*') ? 'active' : '' }}">
                <a href="{!! route('establecimientos.index') !!}"><i class="fa fa-fw fa-h-square"></i><span>Establecimientos</span></a>
            </li>
            <!--li class="{//{ Request::is('farmacias*') ? 'active' : '' }}">
                <a href="{//!! route('farmacias.index') !!}"><i class="fa fa-edit"></i><span>Farmacias</span></a>
            </li-->
            <li class="{{ Request::is('nivels*') ? 'active' : '' }}">
                <a href="{!! route('nivels.index') !!}"><i class="fa fa-fw fa-level-up"></i><span>Nivel</span></a>
            </li>
            <li class="{{ Request::is('categorias*') ? 'active' : '' }}">
                <a href="{!! route('categorias.index') !!}"><i class="fa fa-fw fa-plus-square"></i><span>Categorias</span></a>
            </li>
            <li class="{{ Request::is('tipoEstablecimientos*') ? 'active' : '' }}">
                <a href="{!! route('tipoEstablecimientos.index') !!}"><i class="fa fa-fw fa-building"></i><span>Tipo Establecimientos</span></a>
            </li>
            <li class="{{ Request::is('tipoInternamientos*') ? 'active' : '' }}">
                <a href="{!! route('tipoInternamientos.index') !!}"><i class="fa fa-fw fa-bed"></i><span>Tipo Internamientos</span></a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-fw fa-cogs "></i>
            <span>Ubigeo</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('regions*') ? 'active' : '' }}">
                <a href="{!! route('regions.index') !!}"><i class="fa fa-fw fa-map-pin "></i><span>Red/Región</span></a>
            </li>
            <li class="{{ Request::is('disas*') ? 'active' : '' }}">
                <a href="{!! route('disas.index') !!}"><i class="fa fa-fw fa-map-marker"></i><span>Disas</span></a>
            </li>
            <li class="{{ Request::is('departamentos*') ? 'active' : '' }}">
                <a href="{!! route('departamentos.index') !!}"><i class="fa fa-fw fa-map"></i><span>Departamentos</span></a>
            </li>
            <li class="{{ Request::is('provincias*') ? 'active' : '' }}">
                <a href="{!! route('provincias.index') !!}"><i class="fa fa-fw fa-map-marker"></i><span>Provincias</span></a>
            </li>

            <li class="{{ Request::is('distritos*') ? 'active' : '' }}">
                <a href="{!! route('distritos.index') !!}"><i class="fa fa-fw fa-map-signs"></i><span>Distritos</span></a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-fw fa-users "></i>
            <span>Usuarios</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

        <ul class="treeview-menu {{ Request::is('users*') ? 'active' : '' }}">
            <li class="{{ Request::is('users*') ? 'active' : '' }}"><a href="{!! route('users.index') !!}"><i class="fa fa-edit"></i><span>Usuarios</span></a></li>
            <li class="{{ Request::is('grados*') ? 'active' : '' }}">
                <a href="{!! route('grados.index') !!}"><i class="fa fa-edit"></i><span>Grados</span></a>
            </li>
        </ul> 

    </li>
</ul>    
@else
<ul class="sidebar-menu">
    <li class="header">MENU</li>
    <li class="{{ Request::is('home*') ? 'active' : '' }}">
        <a href="{{ url('/home') }}">
            <i class="fa fa-fw fa-home "></i>
            <span>Inicio</span>
        </a>    
    </li>   
    <li class="treeview">
        <a href="#">
            <i class="fa fa-fw fa-truck "></i>
            <span>Abastecimiento</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('icis*') ? 'active' : '' }}">

                <a href="{!! route('abastecimiento.cargar_medicamentos',[0,Auth::user()->establecimiento_id]) !!}"><i class="fa fa-medkit"></i><span>Medicamentos</span></a>
            </li>
            <li class="{{ Request::is('icis*') ? 'active' : '' }}">
                <a href="{!! route('abastecimiento.cargar_dispositivos',[0,Auth::user()->establecimiento_id]) !!}"><i class="fa fa-stethoscope"></i><span>Dispositivos Médicos</span></a>
            </li>        
        </ul>
    </li>
    <li class="{{ Request::is('abastecimiento') ? 'active' : '' }}">
        <a href="{{ url('abastecimiento') }}">
            <i class="fa fa-fw fa-list-alt "></i>
            <span>Listar</span>
        </a>    
    </li>       
</ul>
@endif
