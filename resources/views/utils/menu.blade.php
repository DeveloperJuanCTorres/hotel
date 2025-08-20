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
        <li class="nav-item">
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
        <li class="nav-item">
            <a href="/rooms">
                <i class="fas fa-bed"></i>
                <p>Habitaciones</p>
            </a>
        </li>
        <li class="nav-item submenu">
            <a data-bs-toggle="collapse" href="#base">
                <i class="fas fa-layer-group"></i>
                <p>Contactos</p>
                <span class="caret"></span>
            </a>
            <div class="collapse" id="base">
                <ul class="nav nav-collapse">
                    <li>
                        <a href="#">
                        <span class="sub-item">Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                        <span class="sub-item">Proveedores</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item active submenu">
            <a data-bs-toggle="collapse" href="#base">
                <i class="fas fa-layer-group"></i>
                <p>Gestión Productos</p>
                <span class="caret"></span>
            </a>
            <div class="collapse show" id="base">
                <ul class="nav nav-collapse">
                    <li class="active">
                        <a href="#">
                        <span class="sub-item">Productos</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
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