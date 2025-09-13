<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- <nav class="navbar navbar-expand-lg p-0 d-none d-lg-flex w-100">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="input-group">
                    <a href="/pos" class="btn btn-primary"><i class="fas fa-arrow-circle-right pe-2"></i>POS</a>
                </div>                
            </div>
        </nav> -->

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <li class="nav-item topbar-icon dropdown hidden-caret px-2">
                <div class="d-flex m-auto">
                    <div class="input-group">
                        <a href="/pos" class="btn btn-primary"><i class="fas fa-cart-plus" style="font-size: 24px;"></i></a>
                    </div>                
                </div>
            </li>
            <li class="nav-item topbar-icon dropdown hidden-caret">
                @if(!$caja_abierta)
                <div class="d-flex m-auto">
                    <div class="input-group">
                        <button class="btn btn-success" id="btnApertura">
                            <i class="fas fa-box-open" style="font-size: 24px;"></i>
                        </button>
                    </div>
                </div>
                @else
                <div class="d-flex m-auto">
                    <div class="input-group">
                        <button class="btn btn-danger" data-apertura="{{$caja_abierta->id}}" id="btnCerrarCaja">
                            <i class="fas fa-box-open" style="font-size: 24px;"></i>
                        </button>
                    </div>
                </div>
                @endif
            </li>
            
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a
                class="nav-link dropdown-toggle"
                href="#"
                id="notifDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                >
                <i class="fa fa-bell"></i>
                <span class="notification">4</span>
                </a>
                <ul
                class="dropdown-menu notif-box animated fadeIn"
                aria-labelledby="notifDropdown"
                >
                <li>
                    <div class="dropdown-title">
                    You have 4 new notification
                    </div>
                </li>
                <li>
                    <div class="notif-scroll scrollbar-outer">
                    <div class="notif-center">
                        <a href="#">
                        <div class="notif-icon notif-primary">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <div class="notif-content">
                            <span class="block"> New user registered </span>
                            <span class="time">5 minutes ago</span>
                        </div>
                        </a>
                        <a href="#">
                        <div class="notif-icon notif-success">
                            <i class="fa fa-comment"></i>
                        </div>
                        <div class="notif-content">
                            <span class="block">
                            Rahmad commented on Admin
                            </span>
                            <span class="time">12 minutes ago</span>
                        </div>
                        </a>
                        <a href="#">
                        <div class="notif-img">
                            <img
                            src="{{asset('assets/img/profile2.jpg')}}"
                            alt="Img Profile"
                            />
                        </div>
                        <div class="notif-content">
                            <span class="block">
                            Reza send messages to you
                            </span>
                            <span class="time">12 minutes ago</span>
                        </div>
                        </a>
                        <a href="#">
                        <div class="notif-icon notif-danger">
                            <i class="fa fa-heart"></i>
                        </div>
                        <div class="notif-content">
                            <span class="block"> Farrah liked Admin </span>
                            <span class="time">17 minutes ago</span>
                        </div>
                        </a>
                    </div>
                    </div>
                </li>
                <li>
                    <a class="see-all" href="javascript:void(0);"
                    >See all notifications<i class="fa fa-angle-right"></i>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a
                class="nav-link"
                data-bs-toggle="dropdown"
                href="#"
                aria-expanded="false"
                >
                <i class="fas fa-layer-group"></i>
                </a>
                <div class="dropdown-menu quick-actions animated fadeIn">
                <div class="quick-actions-header">
                    <span class="title mb-1">Acciones rápudas</span>
                    <span class="subtitle op-7">Atajos</span>
                </div>
                <div class="quick-actions-scroll scrollbar-outer">
                    <div class="quick-actions-items">
                    <div class="row m-0">
                        <a class="col-6 col-md-4 p-0" href="/">
                        <div class="quick-actions-item">
                            <div class="avatar-item bg-danger rounded-circle">
                            <i class="fas fa-home"></i>
                            </div>
                            <span class="text">Dashboard</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="/rooms">
                        <div class="quick-actions-item">
                            <div
                            class="avatar-item bg-warning rounded-circle"
                            >
                            <i class="fas fa-bed"></i>
                            </div>
                            <span class="text">Habitaciones</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="/reservations">
                        <div class="quick-actions-item">
                            <div class="avatar-item bg-info rounded-circle">
                            <i class="fas fa-calendar"></i>
                            </div>
                            <span class="text">Reservas</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="/contacts">
                        <div class="quick-actions-item">
                            <div
                            class="avatar-item bg-success rounded-circle"
                            >
                            <i class="fas fa-users"></i>
                            </div>
                            <span class="text">Contactos</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="/products">
                        <div class="quick-actions-item">
                            <div
                            class="avatar-item bg-primary rounded-circle"
                            >
                            <i class="fas fa-boxes"></i>
                            </div>
                            <span class="text">Productos</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="/expenses">
                        <div class="quick-actions-item">
                            <div
                            class="avatar-item bg-secondary rounded-circle"
                            >
                            <i class="fas fa-table"></i>
                            </div>
                            <span class="text">Gastos</span>
                        </div>
                        </a>
                    </div>
                    </div>
                </div>
                </div>
            </li>

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a
                class="dropdown-toggle profile-pic"
                data-bs-toggle="dropdown"
                href="#"
                aria-expanded="false"
                >
                <div class="avatar-sm">
                    <img
                    src="{{asset('assets/img/profile.jpg')}}"
                    alt="..."
                    class="avatar-img rounded-circle"
                    />
                </div>
                <span class="profile-username">
                    <span class="op-7">Hola,</span>
                    <span class="fw-bold">{{Auth()->user()->name}}</span>
                </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                <div class="dropdown-user-scroll scrollbar-outer">
                    <li>
                    <div class="user-box">
                        <div class="avatar-lg">
                        <img
                            src="{{asset('assets/img/profile.jpg')}}"
                            alt="image profile"
                            class="avatar-img rounded"
                        />
                        </div>
                        <div class="u-text">
                        <h4>Hizrian</h4>
                        <p class="text-muted">hello@example.com</p>
                        <a
                            href="profile.html"
                            class="btn btn-xs btn-secondary btn-sm"
                            >View Profile</a
                        >
                        </div>
                    </div>
                    </li>
                    <li>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">My Profile</a>
                    <a class="dropdown-item" href="#">My Balance</a>
                    <a class="dropdown-item" href="#">Inbox</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Account Setting</a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item" type="submit">Cerrar sesión</button>
                    </form>
                    <!-- <a class="dropdown-item" href="#">Cerrar sesión</a> -->
                    </li>
                </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!-- End Navbar -->


 <!-- <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid d-flex justify-content-between align-items-center">

       
        <div>
            <a href="/pos" 
            class="btn btn-primary d-flex align-items-center justify-content-center my-auto" 
            style="height:60px; width:60px;">
                <i class="fas fa-arrow-circle-right" style="font-size: 24px;"></i>
            </a>
        </div>

      
        <div class="d-flex gap-2 justify-content-center">
            <a href="/rooms" class="btn btn-primary d-flex align-items-center justify-content-center"
               style="height:60px; width:60px;">
                <i class="fas fa-bed" style="font-size: 24px;"></i>
            </a>
            <a href="/reservations" class="btn btn-primary d-flex align-items-center justify-content-center"
               style="height:60px; width:60px;">
                <i class="fas fa-calendar-alt" style="font-size: 24px;"></i>
            </a>
            <a href="/clients" class="btn btn-primary d-flex align-items-center justify-content-center"
               style="height:60px; width:60px;">
                <i class="fas fa-users" style="font-size: 24px;"></i>
            </a>
            <a href="/products" class="btn btn-primary d-flex align-items-center justify-content-center"
               style="height:60px; width:60px;">
                <i class="fas fa-boxes" style="font-size: 24px;"></i>
            </a>
        </div>

        
        <ul class="navbar-nav topbar-nav align-items-center">
            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                <a
                class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown"
                href="#"
                role="button"
                aria-expanded="false"
                aria-haspopup="true"
                >
                <i class="fa fa-search"></i>
                </a>
                <ul class="dropdown-menu dropdown-search animated fadeIn">
                <form class="navbar-left navbar-form nav-search">
                    <div class="input-group">
                        <a href="#" class="btn btn-primary">POS</a>
                    </div>
                </form>
                </ul>
            </li>
            
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a
                class="nav-link dropdown-toggle"
                href="#"
                id="notifDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                >
                <i class="fa fa-bell"></i>
                <span class="notification">4</span>
                </a>
                <ul
                class="dropdown-menu notif-box animated fadeIn"
                aria-labelledby="notifDropdown"
                >
                <li>
                    <div class="dropdown-title">
                    You have 4 new notification
                    </div>
                </li>
                <li>
                    <div class="notif-scroll scrollbar-outer">
                    <div class="notif-center">
                        <a href="#">
                        <div class="notif-icon notif-primary">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <div class="notif-content">
                            <span class="block"> New user registered </span>
                            <span class="time">5 minutes ago</span>
                        </div>
                        </a>
                        <a href="#">
                        <div class="notif-icon notif-success">
                            <i class="fa fa-comment"></i>
                        </div>
                        <div class="notif-content">
                            <span class="block">
                            Rahmad commented on Admin
                            </span>
                            <span class="time">12 minutes ago</span>
                        </div>
                        </a>
                        <a href="#">
                        <div class="notif-img">
                            <img
                            src="{{asset('assets/img/profile2.jpg')}}"
                            alt="Img Profile"
                            />
                        </div>
                        <div class="notif-content">
                            <span class="block">
                            Reza send messages to you
                            </span>
                            <span class="time">12 minutes ago</span>
                        </div>
                        </a>
                        <a href="#">
                        <div class="notif-icon notif-danger">
                            <i class="fa fa-heart"></i>
                        </div>
                        <div class="notif-content">
                            <span class="block"> Farrah liked Admin </span>
                            <span class="time">17 minutes ago</span>
                        </div>
                        </a>
                    </div>
                    </div>
                </li>
                <li>
                    <a class="see-all" href="javascript:void(0);"
                    >See all notifications<i class="fa fa-angle-right"></i>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a
                class="nav-link"
                data-bs-toggle="dropdown"
                href="#"
                aria-expanded="false"
                >
                <i class="fas fa-layer-group"></i>
                </a>
                <div class="dropdown-menu quick-actions animated fadeIn">
                <div class="quick-actions-header">
                    <span class="title mb-1">Quick Actions</span>
                    <span class="subtitle op-7">Shortcuts</span>
                </div>
                <div class="quick-actions-scroll scrollbar-outer">
                    <div class="quick-actions-items">
                    <div class="row m-0">
                        <a class="col-6 col-md-4 p-0" href="#">
                        <div class="quick-actions-item">
                            <div class="avatar-item bg-danger rounded-circle">
                            <i class="far fa-calendar-alt"></i>
                            </div>
                            <span class="text">Calendar</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                        <div class="quick-actions-item">
                            <div
                            class="avatar-item bg-warning rounded-circle"
                            >
                            <i class="fas fa-map"></i>
                            </div>
                            <span class="text">Maps</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                        <div class="quick-actions-item">
                            <div class="avatar-item bg-info rounded-circle">
                            <i class="fas fa-file-excel"></i>
                            </div>
                            <span class="text">Reports</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                        <div class="quick-actions-item">
                            <div
                            class="avatar-item bg-success rounded-circle"
                            >
                            <i class="fas fa-envelope"></i>
                            </div>
                            <span class="text">Emails</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                        <div class="quick-actions-item">
                            <div
                            class="avatar-item bg-primary rounded-circle"
                            >
                            <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <span class="text">Invoice</span>
                        </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                        <div class="quick-actions-item">
                            <div
                            class="avatar-item bg-secondary rounded-circle"
                            >
                            <i class="fas fa-credit-card"></i>
                            </div>
                            <span class="text">Payments</span>
                        </div>
                        </a>
                    </div>
                    </div>
                </div>
                </div>
            </li>

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a
                class="dropdown-toggle profile-pic"
                data-bs-toggle="dropdown"
                href="#"
                aria-expanded="false"
                >
                <div class="avatar-sm">
                    <img
                    src="{{asset('assets/img/profile.jpg')}}"
                    alt="..."
                    class="avatar-img rounded-circle"
                    />
                </div>
                <span class="profile-username">
                    <span class="op-7">Hola,</span>
                    <span class="fw-bold">{{Auth()->user()->name}}</span>
                </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                <div class="dropdown-user-scroll scrollbar-outer">
                    <li>
                    <div class="user-box">
                        <div class="avatar-lg">
                        <img
                            src="{{asset('assets/img/profile.jpg')}}"
                            alt="image profile"
                            class="avatar-img rounded"
                        />
                        </div>
                        <div class="u-text">
                        <h4>Hizrian</h4>
                        <p class="text-muted">hello@example.com</p>
                        <a
                            href="profile.html"
                            class="btn btn-xs btn-secondary btn-sm"
                            >View Profile</a
                        >
                        </div>
                    </div>
                    </li>
                    <li>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">My Profile</a>
                    <a class="dropdown-item" href="#">My Balance</a>
                    <a class="dropdown-item" href="#">Inbox</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Account Setting</a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item" type="submit">Cerrar sesión</button>
                    </form>
                 
                    </li>
                </div>
                </ul>
            </li>
        </ul>

    </div>
</nav> -->