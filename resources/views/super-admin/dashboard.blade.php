@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Dashboard')
@section('content')
<div class="body-main-content">
    <div class="pmu-overview">
        <div class="row">
            <div class="col-md-3">
                <a class="pmu-overview-item" href="{{ route('SA.ContentCreators') }}">
                    <div class="pmu-overview-content">
                        <h2>{{ $cc ?? 0 }}</h2>
                        <p>Total Content Creator</p>
                    </div>
                    <div class="pmu-overview-media">
                        <img src="{!! url('assets/superadmin-images/Content-Creator.svg') !!}">
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a class="pmu-overview-item" href="{{ route('SA.Students') }}">
                    <div class="pmu-overview-content">
                        <h2>{{ $stu ?? 0 }}</h2>
                        <p>Total Students</p>
                    </div>
                    <div class="pmu-overview-media">
                        <img src="{!! url('assets/superadmin-images/students-icon.svg') !!}">
                    </div>
                </a>
            </div>


            <div class="col-md-3">
                <a class="pmu-overview-item" href="{{ route('SA.Products') }}">
                    <div class="pmu-overview-content">
                        <h2>{{ $pro ?? 0 }}</h2>
                        <p>Total Listed Products</p>
                    </div>
                    <div class="pmu-overview-media">
                        <img src="{!! url('assets/superadmin-images/products-icon.svg') !!}">
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a class="pmu-overview-item" href="{{ route('SA.Course') }}">
                    <div class="pmu-overview-content">
                        <h2>{{ $course ?? 0 }}</h2>
                        <p>Total Listed Courses</p>
                    </div>
                    <div class="pmu-overview-media">
                        <img src="{!! url('assets/website-images/book2.svg') !!}" width="48">
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="Overview-card">
                <div class="Overview-card-chart">
                    <div class="" id="studentchart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="Overview-card">
                <div class="Overview-card-chart">
                    <div class="" id="revenuechart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="Overview-card">
                <div class="Overview-card-chart">
                    <div class="" id="arkansaschart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="Overview-card">
                <div class="Overview-card-chart">
                    <div class="" id="ccchart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="Overview-card">
        <div class="Overview-card-content pb-0">
            <h5 style="color: #000">Recent User</h5>
        </div>
        <div class="Overview-card-table pmu-table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Profile Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user as $key => $val)
                    <tr>
                        <td><span class="sno">{{ number_format((int)$key)+1 }}</span> </td>
                        <td>
                            @if ($val->profile_image!=null && $val->profile_image!="")
                                <img width="40" height="40" style="border-radius: 50%; object-fit: cover; object-position: center;" src="{!! url('/upload/profile-image/'.$val->profile_image) !!}">
                            @else
                                <img width="40" height="40" style="border-radius: 50%; object-fit: cover; object-position: center;" src="{!! url('assets/superadmin-images/no-image.png') !!}">
                            @endif
                        </td>
                        <td class="text-capitalize">{{ dataSet($val->first_name) }} {{ $val->last_name ?? '' }}</td>
                        <td class="text-lowercase">{{ dataSet($val->email) }}</td>
                        <td>{{ dataSet($val->phone) }}</td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="Overview-card">
        <div class="Overview-card-content pb-0">
            <h5 style="color: #000">Recent Content Creator</h5>
        </div>
        <div class="Overview-card-table pmu-table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Profile Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contentcreator as $key => $val)
                    <tr>
                        <td><span class="sno">{{ number_format((int)$key)+1 }}</span> </td>
                        <td>
                            @if ($val->profile_image!=null && $val->profile_image!="")
                                <img width="40" height="40" style="border-radius: 50%; object-fit: cover; object-position: center;" src="{!! url('/upload/profile-image/'.$val->profile_image) !!}">
                            @else
                                <img width="40" height="40" style="border-radius: 50%; object-fit: cover; object-position: center;" src="{!! url('assets/superadmin-images/no-image.png') !!}">
                            @endif
                        </td>
                        <td class="text-capitalize">{{ dataSet($val->first_name) }} {{ $val->last_name ?? '' }}</td>
                        <td class="text-lowercase">{{ dataSet($val->email) }}</td>
                        <td>{{ dataSet($val->phone) }}</td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- <div class="Overview-card">
        <div class="Overview-card-content">
            <div class="row">
                <div class="col-md-3">
                    <div class="Overview-info-card">
                        <h2>Total Revenue</h2>
                        <div class="Overview-price">$1799.00</div>
                        <div class="overview-date">May,2023</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="Overview-info-card">
                        <h2>Total Course Enrollments</h2>
                        <div class="Overview-price">12</div>
                        <div class="overview-date">May,2023</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="Overview-info-card">
                        <h2>Total courses rating</h2>
                        <div class="Overview-rating"><img src="{!! url('assets/superadmin-images/star.svg') !!}"> 4.7</div>
                        <div class="overview-date">May,2023</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="Overview-form-card">
                        <input type="date" class="form-control" name="">
                    </div>
                </div>
            </div>
        </div>
        <div class="Overview-card-chart">
            <div class="" id="salechart"></div>
        </div>
    </div> -->
    <input type="hidden" data-json="{{json_encode($userArr)}}" id="student_graph">
    <input type="hidden" data-json="{{json_encode($walletArr)}}" id="revenue_graph">
    <input type="hidden" data-json="{{json_encode($over_graph)}}" id="arkansas_graph">
    <input type="hidden" data-json="{{json_encode($creator_over_graph)}}" id="creator_graph">
</div>

<script>
    let dataOverStudent = [];
    $(document).ready(function() {
        let arrOver = $("#student_graph").data('json');
        arrOver.map(ele => {
            dataOverStudent.push(ele);
        })
    })
    $(function() {
        var options1 = {
            series: [{
                name: "Users",
                data: dataOverStudent,
            }, ],
            chart: {
                height: 350,
                type: 'area',

                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            title: {
                text: "Student Register Per Month ({{date('Y')}})",
                align: 'center',
            },
            dataLabels: {
                enabled: true,
                style: {
                    colors: ['#261313']
                },
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
                },
                title: {
                    display: true,
                    text: 'Months',
                    font: {
                        size: 15
                    }
                },
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
                },
                title: {
                    display: true,
                    text: 'Number of Students',
                    font: {
                        size: 15
                    }
                },
            }
        };

        var chart = new ApexCharts(document.querySelector("#studentchart"), options1);
        chart.render();
    });


    let dataOverRevenue = [];
    $(document).ready(function() {
        let arrOverRevenue = $("#revenue_graph").data('json');
        arrOverRevenue.map(ele => {
            dataOverRevenue.push(ele);
        })
    })
    $(function() {
        var options1 = {
            series: [{
                name: "Revenew",
                data: dataOverRevenue,
            }, ],
            chart: {
                height: 350,
                type: 'area',

                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            title: {
                text: "Total Arkansas Revenue ({{ date('Y') }})",
                align: 'center',
            },
            dataLabels: {
                enabled: true,
                style: {
                    colors: ['#261313']
                },
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
                },
                title: {
                    display: true,
                    text: 'Months',
                    font: {
                        size: 15
                    }
                },
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
                },
                title: {
                    display: true,
                    text: 'Amount (in $)',
                    font: {
                        size: 15
                    }
                },
            }
        };

        var chart = new ApexCharts(document.querySelector("#revenuechart"), options1);
        chart.render();
    });


    let dataOverArkansas = [];
    $(document).ready(function() {
        let arrOverArkansas = $("#arkansas_graph").data('json');
        arrOverArkansas.map(ele => {
            dataOverArkansas.push(ele);
        })
    })
    $(function() {
        var options = {
            series: [{
                name: "Sales",
                data: dataOverArkansas,
            }, ],
            chart: {
                height: 350,
                type: 'area',

                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            title: {
                text: "Total Arkansas Course Sales ({{ date('M, Y') }})",
                align: 'center',
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
                },
                title: {
                    display: true,
                    text: 'Day',
                    font: {
                        size: 15
                    }
                },
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
                },
                title: {
                    display: true,
                    text: 'Revenue',
                    font: {
                        size: 15
                    }
                },
            }
        };

        var chart = new ApexCharts(document.querySelector("#arkansaschart"), options);
        chart.render();
    });


    let dataOverCreator = [];
    $(document).ready(function() {
        let arrOverCreator = $("#creator_graph").data('json');
        arrOverCreator.map(ele => {
            dataOverCreator.push(ele);
        })
    })
    $(function() {
        var options = {
            series: [{
                name: "Sales",
                data: dataOverCreator,
            }, ],
            chart: {
                height: 350,
                type: 'area',

                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            title: {
                text: "Total Content Creators Course Sales ({{ date('M, Y') }})",
                align: 'center',
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
                },
                title: {
                    display: true,
                    text: 'Days',
                    font: {
                        size: 15
                    }
                },
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
                },
                title: {
                    display: true,
                    text: 'Revenue',
                    font: {
                        size: 15
                    }
                },
            }
        };

        var chart = new ApexCharts(document.querySelector("#ccchart"), options);
        chart.render();
    });
</script>
@endsection