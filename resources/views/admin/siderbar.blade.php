<div class="scroll-sidebar">
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav">
        <ul id="sidebarnav">
            @if(kvfj(Auth::user()->permissions,'dashboard'))
            <li class="sidebar-item"> 
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{url('/admin')}}" aria-expanded="false">
                    <i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span>
                </a>
            </li>
            @endif
            @if(kvfj(Auth::user()->permissions,'sucursales'))
            <li class="sidebar-item"> 
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{url('/admin/sucursales')}}" aria-expanded="false">
                    <i class="fas fa-store-alt"></i><span class="hide-menu">Sucursales</span>
                </a>
            </li>
            @endif
            @if(kvfj(Auth::user()->permissions,'vehiculos'))
            <li class="sidebar-item"> 
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{url('/admin/vehiculos')}}" aria-expanded="false">
                    <i class="fas fa-truck-moving"></i><span class="hide-menu">Vehículos</span>
                </a>
            </li>
            @endif
            @if(kvfj(Auth::user()->permissions,'envios'))
            <li class="sidebar-item"> 
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{url('/admin/envios')}}" aria-expanded="false">
                    <i class="fas fa-map-marker-alt"></i><span class="hide-menu">Envíos</span>
                </a>
            </li>
            @endif
            @if(kvfj(Auth::user()->permissions,'recepcion'))
            <li class="sidebar-item"> 
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{url('/admin/recepcion')}}" aria-expanded="false">
                    <i class="fas fa-concierge-bell"></i><span class="hide-menu">Recepción</span>
                </a>
            </li>
            @endif
            @if(kvfj(Auth::user()->permissions,'user_list'))
            <li class="sidebar-item"> 
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{url('/admin/user')}}" aria-expanded="false">
                    <i class="fas fa-users"></i><span class="hide-menu">Usuarios</span>
                </a>
            </li>
            @endif
            @if(kvfj(Auth::user()->permissions,'anuncios'))
            <li class="sidebar-item"> 
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{url('/admin/anuncios')}}" aria-expanded="false">
                    <i class="mdi mdi-file"></i><span class="hide-menu">Anuncios</span>
                </a>
            </li>
            @endif
            @if(kvfj(Auth::user()->permissions,'auditoria'))
            <li class="sidebar-item"> 
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{url('/admin/acciones')}}" aria-expanded="false">
                    <i class="fas fa-user-shield"></i><span class="hide-menu">Auditoria</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
    <!-- End Sidebar navigation -->
</div>