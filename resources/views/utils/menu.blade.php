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
            <button class="btn btn-toggle toggle-sidebar toggled">
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
        <li class="nav-item m-auto {{ request()->is('/') ? 'active' : '' }}" style="min-height: 60px;">
            <a href="/">
                <i class="fas fa-home" style="font-size: 25px;width: 40px;"></i>
                <p>Dashboard</p> 
            </a>
        </li>
        <li class="nav-section">
            <span class="sidebar-mini-icon">
                <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Componentes</h4>
        </li>
        <li class="nav-item m-auto {{ request()->is('rooms*') ? 'active' : '' }}" style="min-height: 60px;">
            <a href="/rooms">
                <i class="fas fa-bed" style="font-size: 25px; width: 40px;"></i>
                <p>Habitaciones</p>
            </a>
        </li>
        <li class="nav-item m-auto {{ request()->is('reservations*') ? 'active' : '' }}" style="min-height: 60px;">
            <a href="/reservations">
                <i class="fas fa-calendar-alt" style="font-size: 25px;width: 40px;"></i>
                <p>Reservas</p>
            </a>
        </li>
        <li class="nav-item submenu m-auto {{ request()->is('clients*') || request()->is('suppliers*') ? 'active' : '' }}" style="min-height: 60px;">
            <a data-bs-toggle="collapse" href="#contacts">
                <i class="fas fa-users" style="font-size: 25px;width: 40px;"></i>
                <p>Contactos</p>
                <span class="caret"></span>
            </a>
            <div class="collapse {{ request()->is('clients*') || request()->is('suppliers*') ? 'show' : '' }}" id="contacts">
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
        <li class="nav-item submenu m-auto {{ request()->is('products*') || request()->is('categories*') ? 'active' : '' }}" style="min-height: 60px;">
            <a data-bs-toggle="collapse" href="#products">
                <i class="fas fa-boxes" style="font-size: 25px;width: 40px;"></i>
                <p>Gestión Productos</p>
                <span class="caret"></span>
            </a>
            <div class="collapse {{ request()->is('products*') || request()->is('categories*') ? 'show' : '' }}" id="products">
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
        <li class="nav-item submenu m-auto {{ request()->is('kardex*') || request()->is('purchases*') ? 'active' : '' }}" style="min-height: 60px;">
            <a data-bs-toggle="collapse" href="#inventories">
                <i class="fas fa-edit" style="font-size: 25px;width: 40px;"></i>
                <p>Inventario</p>
                <span class="caret"></span>
            </a>
            <div class="collapse {{ request()->is('kardex*') || request()->is('purchases*') ? 'show' : '' }}" id="inventories">
                <ul class="nav nav-collapse">
                    <li class="{{ request()->is('kardex*') ? 'active' : '' }}">
                        <a href="/kardex">
                        <span class="sub-item">KARDEX</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('purchases') ? 'active' : '' }}">
                        <a href="/purchases">
                        <span class="sub-item">Compras</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item submenu m-auto {{ request()->is('opening*') || request()->is('closures*') || request()->is('summaries*') ? 'active' : '' }}" style="min-height: 60px;">
            <a data-bs-toggle="collapse" href="#openings">
                <i class="fas fa-cube" style="font-size: 25px;width: 40px;"></i>
                <p>Módulo caja</p>
                <span class="caret"></span>
            </a>
            <div class="collapse {{ request()->is('opening*') || request()->is('closures*') || request()->is('summaries*') ? 'show' : '' }}" id="openings">
                <ul class="nav nav-collapse">
                    <li class="{{ request()->is('opening*') ? 'active' : '' }}">
                        <a href="/opening">
                        <span class="sub-item">Apertura caja</span>
                        </a>
                    </li>
                    <!-- <li class="{{ request()->is('closures*') ? 'active' : '' }}">
                        <a href="/summaries">
                        <span class="sub-item">Cierre caja</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('summaries*') ? 'active' : '' }}">
                        <a href="/suppliers">
                        <span class="sub-item">Resumen liquidación</span>
                        </a>
                    </li> -->
                </ul>
            </div>
        </li>
        <li class="nav-item submenu m-auto {{ request()->is('expenses*') ? 'active' : '' }}" style="min-height: 60px;">
            <a data-bs-toggle="collapse" href="#expenses">
                <i class="fas fa-table" style="font-size: 25px;width: 40px;"></i>
                <p>Gastos</p>
                <span class="caret"></span>
            </a>
            <div class="collapse {{ request()->is('expenses*') ? 'show' : '' }}" id="expenses">
                <ul class="nav nav-collapse">
                    <li class="{{ request()->is('expenses') ? 'active' : '' }}">
                        <a href="/expenses">
                        <span class="sub-item">Gastos</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('expenses/cat') ? 'active' : '' }}">
                        <a href="/expenses/cat">
                        <span class="sub-item">Categoría de gastos</span>
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