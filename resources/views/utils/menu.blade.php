<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo">
            <h3 class="text-white">Sistema Hotel</h3>
            <!-- <img
            src="assets/img/kaiadmin/logo_light.svg"
            alt="navbar brand"
            class="navbar-brand"
            height="20"
            /> -->
        </a>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
            <i class="gg-menu-left"></i>
            </button>
        </div>
        <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
        </button>
        </div>
        <!-- End Logo Header -->
    </div>
<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
    <ul class="nav nav-secondary">
        <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
            <a href="/">
                <i class="fas fa-home"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <li class="nav-section">
            <span class="sidebar-mini-icon">
                <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Componentes</h4>
        </li>
        <li class="nav-item {{ request()->is('rooms*') ? 'active' : '' }}">
            <a href="/rooms">
                <i class="fas fa-bed"></i>
                <p>Habitaciones</p>
            </a>
        </li>
        <li class="nav-item {{ request()->is('reservations*') ? 'active' : '' }}">
            <a href="/reservations">
                <i class="fas fa-calendar-alt"></i>
                <p>Reservas</p>
            </a>
        </li>
        <li class="nav-item submenu">
            <a data-bs-toggle="collapse" href="#base">
                <i class="fas fa-users"></i>
                <p>Contactos</p>
                <span class="caret"></span>
            </a>
            <div class="collapse {{ request()->is('clients*') || request()->is('suppliers*') ? 'show' : '' }}" id="base">
                <ul class="nav nav-collapse">
                    <li class="{{ request()->is('clients*') ? 'active' : '' }}">
                        <a href="/clients">
                        <span class="sub-item">Clientes</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('suppliers*') ? 'active' : '' }}">
                        <a href="/suppliers">
                        <span class="sub-item">Proveedores</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item submenu">
            <a data-bs-toggle="collapse" href="#base">
                <i class="fas fa-boxes"></i>
                <p>Gestión Productos</p>
                <span class="caret"></span>
            </a>
            <div class="collapse {{ request()->is('products*') || request()->is('categories*') ? 'show' : '' }}" id="base">
                <ul class="nav nav-collapse">
                    <li class="{{ request()->is('products*') ? 'active' : '' }}">
                        <a href="/products">
                        <span class="sub-item">Productos</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('categories*') ? 'active' : '' }}">
                        <a href="/categories">
                        <span class="sub-item">Categorías</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    </div>
</div>
</div>
<!-- End Sidebar -->