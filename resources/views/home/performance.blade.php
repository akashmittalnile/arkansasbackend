@extends('layouts.app-master')

@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Performance</h2>
        </div>
        <div class="pmu-search-filter wd40">

        </div>
    </div>


    <div class="pmu-tab-nav">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#Overview" data-bs-toggle="tab">Overview</a> </li>
            <li class="nav-item"><a class="nav-link" href="#Users" data-bs-toggle="tab">Users</a> </li>
            <li class="nav-item"><a class="nav-link" href="#CourseEngagement" data-bs-toggle="tab">Course
                    Engagement</a> </li>
        </ul>
    </div>

    <div class="pmu-tab-content tab-content">
        <div class="tab-pane active" id="Overview">
            <div class="Overview-card">
                <div class="Overview-card-content">
                    <form action="" id="overview-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total Revenue</h2>
                                    <div class="Overview-price">${{ number_format((float)$earn ?? 0, 2) }}</div>
                                    <div class="overview-date">{{ date('M, Y', strtotime($over_month)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total Added Course</h2>
                                    <div class="Overview-price">{{ $course ?? 0 }}</div>
                                    <div class="overview-date">{{ date('M, Y', strtotime($over_month)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total courses rating</h2>
                                    <div class="Overview-rating"><img src="{!! url('assets/website-images/star.svg') !!}"> {{ number_format((float)$rating ?? 0, 1) }}</div>
                                    <div class="overview-date">{{ date('M, Y', strtotime($over_month)) }}</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="Overview-form-card">
                                    <input type="month" class="form-control" value="{{ request()->month ?? date('Y-m') }}" name="month" id="overview-input">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="Overview-card-chart">
                    <div class="" id="salechart"></div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="Users">
            <div class="Overview-card">
                <div class="Overview-card-content">
                    <form action="" id="user-form">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="Overview-info-card">
                                    <h2>Total Enrolled User</h2>
                                    <div class="Overview-value">{{ $user ?? 0 }}</div>
                                    @if(isset(request()->usermonth))
                                    <div class="overview-date">{{ date('M, Y', strtotime(request()->usermonth)) }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="Overview-form-card">
                                    <input type="month" value="{{ request()->usermonth ?? date('Y-m') }}" class="form-control" name="usermonth" id="user-month">
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="Overview-card-table pmu-table-card">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Name</th>
                                <th>Admin Fee</th>
                                <th>Course fees paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $val)
                            <tr>
                                <td><span class="sno">{{ number_format((int)$index)+1 }}</span> </td>
                                <td>{{ $val->first_name ?? "NA" }} {{ $val->last_name }}</td>
                                <td>${{ number_format((float)($val->admin_amount), 2) }}</td>
                                <td>${{ number_format((float)$val->amount, 2) }}</td>
                            </tr>
                            @empty
                            @endforelse

                        </tbody>
                    </table>

                    <div class="pmu-table-pagination">
                        {{$orders->appends(Request::except('page'))->links('pagination::bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="CourseEngagement">
            <div class="Overview-card">
                <div class="Overview-card-content">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="Overview-info-card">
                                <h2>Total Course</h2>
                                <div class="Overview-value">23</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="Overview-info-card">
                                <h2>Total Visits</h2>
                                <div class="Overview-value">109K Visits</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="Overview-info-card">
                                <h2>Total Unpublished Course </h2>
                                <div class="Overview-value">03</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="Overview-info-card">
                                <input type="date" class="form-control" name="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="Overview-card-chart">
                    <div class="" id="visitchart"></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" data-json="{{json_encode($over_graph)}}" id="over_graph">
</div>

<script src="{{ asset('assets/website-js/performance.js') }}"></script>

<script>
    $(document).on('change', '#overview-input', function() {
        $("#overview-form").get(0).submit();
    })

    $(document).on('change', '#user-month', function() {
        $("#user-form").get(0).submit();
    })


    let dataOver = [];
    $(document).ready(function() {
        let arrOver = $("#over_graph").data('json');
        arrOver.map(ele => {
            dataOver.push(ele);
        })
    })


    $(function() {
        var options = {
            series: [{
                name: "Sales",
                data: dataOver,
            }, ],
            chart: {
                height: 350,
                type: 'bar',

                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false,

            },
            legend: {
                markers: {
                    fillColors: ['#e0b220']
                }
            },
            tooltip: {
                marker: {
                    fillColors: ['#e0b220'],
                },

            },
            stroke: {
                curve: 'smooth',
                colors: ['#e0b220']
            },
            fill: {
                colors: ['#e0b220']
            },
            markers: {
                colors: ['#e0b220']
            },
            xaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                tickAmount: 4,
                floating: false,
                labels: {
                    style: {
                        colors: '#555',
                    },
                    offsetY: -7,
                    offsetX: 0,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#salechart"), options);
        chart.render();
    });
</script>

@endsection