@extends('layouts.app')

@section('content')
<div class="wrapper sidebar_minimize">
    @include('utils.menu')

    <div class="main-panel">
        <div class="main-header">
            @include('utils.topbar')
            
            @include('utils.navbar')
        </div>

        <div class="container">
            <div class="page-inner">
            <div
                class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
                <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Transacciones del día</h6>
                </div>
                <!-- <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                <a href="#" class="btn btn-primary btn-round">Add Customer</a>
                </div> -->
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                            <div
                                class="icon-big text-center icon-primary bubble-shadow-small"
                            >
                                <i class="fas fa-users"></i>
                            </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Huespedes</p>
                                <h4 class="card-title">{{$clientes}}</h4>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                            <div
                                class="icon-big text-center icon-info bubble-shadow-small"
                            >
                                <i class="fas fa-boxes"></i>
                            </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Productos</p>
                                <h4 class="card-title">{{$productos}}</h4>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                            <div
                                class="icon-big text-center icon-success bubble-shadow-small"
                            >
                                <i class="fas fa-luggage-cart"></i>
                            </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Ingresos</p>
                                <h4 class="card-title">S/. {{$ingresos}}</h4>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                            <div
                                class="icon-big text-center icon-secondary bubble-shadow-small"
                            >
                                <i class="fas fa-money-check"></i>
                            </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Egresos</p>
                                <h4 class="card-title">S/. {{$egresos}}</h4>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Año actual</div>
                            <div class="card-tools">
                            <a
                                href="#"
                                class="btn btn-label-success btn-round btn-sm me-2"
                            >
                                <span class="btn-label">
                                <i class="fa fa-pencil"></i>
                                </span>
                                Exportar
                            </a>
                            <a href="#" class="btn btn-label-info btn-round btn-sm">
                                <span class="btn-label">
                                <i class="fa fa-print"></i>
                                </span>
                                Imprimir
                            </a>
                            </div>
                        </div>
                        </div>
                        <div class="card-body">
                            <div >
                                <canvas class="chart-container" id="statisticsChart"></canvas>
                            </div>
                            <div id="myChartLegend"></div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Daily Sales</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button
                                        class="btn btn-sm btn-label-light dropdown-toggle"
                                        type="button"
                                        id="dropdownMenuButton"
                                        data-bs-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        >
                                        Export
                                        </button>
                                        <div
                                        class="dropdown-menu"
                                        aria-labelledby="dropdownMenuButton"
                                        >
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#"
                                            >Something else here</a
                                        >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-category">March 25 - April 02</div>
                        </div>
                        <div class="card-body pb-0">
                        <div class="mb-4 mt-2">
                            <h1>S/. 4,578.58</h1>
                        </div>
                        <div class="pull-in">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                        </div>
                    </div>                    
                </div>
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-body pb-0">
                        <div class="h1 fw-bold float-end text-primary">+5%</div>
                        <h2 class="mb-2">17</h2>
                        <p class="text-muted">Users online</p>
                        <div class="pull-in sparkline-fix">
                            <div id="lineChart"></div>
                        </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="row">
                <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Historial de transacciones</div>
                        <div class="card-tools">
                        <div class="dropdown">
                            <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            >
                            <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                            >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                                >Something else here</a
                            >
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center mb-0" id="basic-datatables">
                        <thead class="thead-light">
                            <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col" class="text-end">Fecha</th>
                            <th scope="col" class="text-end">Caja</th>
                            <th scope="col" class="text-end">Método</th>
                            <th scope="col" class="text-end">Monto</th>
                            <th scope="col" class="text-end">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movimientos as $mov)
                            <tr>
                                <th>
                                    <button
                                    class="btn btn-icon btn-round btn-success btn-sm me-2"
                                    >
                                    <i class="fa fa-check"></i>
                                    </button>
                                    {{$mov->type}}
                                </th>
                                <td class="text-end">{{$mov->date}}</td>
                                <td class="text-end">{{$mov->boxe_opening_id}}</td>
                                <td class="text-end">{{$mov->paymethod->name ?? 'Sin método' }}</td>
                                <td class="text-end">S/. {{ number_format($mov->amount, 2) }}</td>
                                <td class="text-end">
                                    <span class="badge badge-success">Completed</span>
                                </td>
                            </tr>
                            @endforeach                            
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

<script src="{{asset('assets/js/plugin/chart.js/chart.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $("#basic-datatables").DataTable({
            order: [[1, 'desc']],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            }
        });
    
    });
</script>
<script>
    const ingresosData = @json($ingresosMensuales);

    const labels = ingresosData.map(item => 
        new Date(0, item.mes - 1).toLocaleString('es', { month: 'short' })
    );

    const data = ingresosData.map(item => item.total);
  
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ingresos mensuales',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                bodySpacing: 4,
                mode:"nearest",
                intersect: 0,
                position:"nearest",
                xPadding:10,
                yPadding:10,
                caretPadding:10
            },
            layout:{
                padding:{left:5,right:5,top:15,bottom:15}
            },
            scales: {
                yAxes: [{
                    ticks: {
                        fontStyle: "500",
                        beginAtZero: false,
                        maxTicksLimit: 5,
                        padding: 10
                    },
                    gridLines: {
                        drawTicks: false,
                        display: false
                    }
                }],
                xAxes: [{
                    gridLines: {
                        zeroLineColor: "transparent"
                    },
                    ticks: {
                        padding: 10,
                        fontStyle: "500"
                    }
                }]
            }, 
            legendCallback: function(chart) { 
                var text = []; 
                text.push('<ul class="' + chart.id + '-legend html-legend">'); 
                for (var i = 0; i < chart.data.datasets.length; i++) { 
                    text.push('<li><span style="background-color:' + chart.data.datasets[i].legendColor + '"></span>'); 
                    if (chart.data.datasets[i].label) { 
                        text.push(chart.data.datasets[i].label); 
                    } 
                    text.push('</li>'); 
                } 
                text.push('</ul>'); 
                return text.join(''); 
            }

            
        }
    });
</script>

@endsection