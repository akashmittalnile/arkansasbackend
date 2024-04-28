@extends('layouts.app-master')
@section('title', 'Permanent Makeup University - Performance')
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
            <li class="nav-item"><a class="nav-link active" href="#Overview" data-bs-toggle="tab" id="overviewtab">Overview</a> </li>
            <li class="nav-item"><a class="nav-link" href="#Users" data-bs-toggle="tab" id="userstab">Users</a> </li>
            <li class="nav-item"><a class="nav-link" href="#CourseEngagement" data-bs-toggle="tab" id="coursetab">Course Engagement</a> </li>
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
                                    <div class="Overview-rating"><img src="{!! assets('assets/website-images/star.svg') !!}"> {{ number_format((float)$rating ?? 0, 1) }}</div>
                                    <div class="overview-date">{{ date('M, Y', strtotime($over_month)) }}</div>
                                </div>
                            </div>
                            <input type="hidden" value="{{encrypt_decrypt('encrypt', 1)}}" name="tab">
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
                                    <div class="overview-date">{{ date('M, Y', strtotime(request()->usermonth ?? date('Y-m'))) }}</div>
                                </div>
                            </div>
                            <input type="hidden" value="{{encrypt_decrypt('encrypt', 2)}}" name="tab">
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
                                <th>Course Name</th>
                                <th>Order Date</th>
                                <th>Admin Fee</th>
                                <th>Course fees paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $val)
                            <tr>
                                <td><span class="sno">{{ number_format((int)$index)+1 }}</span> </td>
                                <td class="text-capitalize">{{ $val->first_name ?? "NA" }} {{ $val->last_name }}</td>
                                <td class="text-capitalize">{{ $val->title ?? "NA" }}</td>
                                <td>{{ date('d M, Y H:iA', strtotime($val->created_date)) }}</td>
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
                    <form action="" id="course-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total Course</h2>
                                    <div class="Overview-value">{{ $total_course ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total Unpublished Course </h2>
                                    <div class="Overview-value">{{ $unpublish_course ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-form-card">
                                    <select name="course" class="form-control text-capitalize" id="course-select" style="padding: 16.5px 15px;">
                                        <option value="">Select Course</option>
                                        @foreach($courses as $val)
                                        <option @if(request()->course==encrypt_decrypt('encrypt',$val->id)) selected @endif value="{{encrypt_decrypt('encrypt',$val->id)}}">{{$val->title ?? "NA"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" value="{{encrypt_decrypt('encrypt', 3)}}" name="tab">
                            <div class="col-md-3">
                                <div class="Overview-form-card">
                                    <input type="month" class="form-control" value="{{ request()->coursemonth ?? date('Y-m') }}" name="coursemonth" id="course-input">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="Overview-card-chart">
                    <div class="" id="visitchart"></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" data-json="{{json_encode($over_graph)}}" id="over_graph">
    <input type="hidden" data-json="{{json_encode($course_graph)}}" id="course_graph">
</div>

<script>
    $(document).on('change', '#overview-input', function() {
        $("#overview-form").get(0).submit();
    })

    $(document).on('change', '#user-month', function() {
        $("#user-form").get(0).submit();
    })

    $(document).on('change', '#course-input, #course-select', function() {
        $("#course-form").get(0).submit();
    })

    var tab = "{{ encrypt_decrypt('decrypt', $tab) }}";
    console.log(tab);
    if(tab==1) $("#overviewtab").get(0).click();
    if(tab==2) $("#userstab").get(0).click();
    if(tab==3) $("#coursetab").get(0).click();

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

    let dataOverCourse = [];
    $(document).ready(function() {
        let arrOver = $("#course_graph").data('json');
        arrOver.map(ele => {
            dataOverCourse.push(ele);
        })
    })

    $(function() {
        var options = {
            series: [{
                name: "Sales",
                data: dataOverCourse,
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

        var chart = new ApexCharts(document.querySelector("#visitchart"), options);
        chart.render();
    });
</script>

@endsection