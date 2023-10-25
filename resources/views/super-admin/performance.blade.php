@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Performance')
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
            <li class="nav-item"><a class="nav-link active" href="#ArkansasCourse" data-bs-toggle="tab" id="arkansascoursetab">Arkansas Courses</a> </li>
            <li class="nav-item"><a class="nav-link" href="#CreatorCourses" data-bs-toggle="tab" id="creatorcoursetab">Content Creator Courses</a> </li>
            <li class="nav-item"><a class="nav-link" href="#Users" data-bs-toggle="tab" id="userstab">Users</a> </li>
            <li class="nav-item"><a class="nav-link" href="#Products" data-bs-toggle="tab" id="productstab">Products</a> </li>
            <!-- <li class="nav-item"><a class="nav-link" href="#CourseEngagement" data-bs-toggle="tab" id="coursetab">Course Engagement</a> </li> -->
        </ul>
    </div>

    <div class="pmu-tab-content tab-content">
        <div class="tab-pane active" id="ArkansasCourse">
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
                    <div class="" id="arkansaschart"></div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="CreatorCourses">
            <div class="Overview-card">
                <div class="Overview-card-content">
                    <form action="" id="creator-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total Revenue</h2>
                                    <div class="Overview-price">${{ number_format((float)$creator_earn ?? 0, 2) }}</div>
                                    <div class="overview-date">{{ date('M, Y', strtotime($over_month)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total Added Course</h2>
                                    <div class="Overview-price">{{ $creator_course ?? 0 }}</div>
                                    <div class="overview-date">{{ date('M, Y', strtotime($creator_month)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total courses rating</h2>
                                    <div class="Overview-rating"><img src="{!! url('assets/website-images/star.svg') !!}"> {{ number_format((float)$creator_rating ?? 0, 1) }}</div>
                                    <div class="overview-date">{{ date('M, Y', strtotime($creator_month)) }}</div>
                                </div>
                            </div>
                            <input type="hidden" value="{{encrypt_decrypt('encrypt', 2)}}" name="tab">
                            <div class="col-md-3">
                                <div class="Overview-form-card">
                                    <input type="month" class="form-control" value="{{ request()->creatormonth ?? date('Y-m') }}" name="creatormonth" id="creator-input">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="Overview-card-chart">
                    <div class="" id="creatorchart"></div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="Users">
            <div class="Overview-card">
                <div class="Overview-card-content">
                    <form action="" id="user-form">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="Overview-info-card">
                                    <h2>Total Enrolled User</h2>
                                    <div class="Overview-value">{{ $user ?? 0 }}</div>
                                    <div class="overview-date">{{ date('M, Y', strtotime(request()->usermonth ?? date('Y-m'))) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="Overview-form-card">
                                    <select name="type" class="form-control text-capitalize" id="user-type" style="padding: 16.5px 15px;">
                                        <option @if(request()->type == 0) selected @endif value="0">Arkansas</option>
                                        <option @if(request()->type == 1) selected @endif value="1">Content Creators</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" value="{{encrypt_decrypt('encrypt', 3)}}" name="tab">
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
                                <th>Email</th>
                                <th>Course Name</th>
                                <th>Order Date</th>
                                @if($user_type==1)
                                <th>Content Creator</th>
                                <th>Admin Fee</th>
                                @endif
                                <th>Course fees paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $val)
                            <tr>
                                <td><span class="sno">{{ number_format((int)$index)+1 }}</span> </td>
                                <td class="text-capitalize">{{ $val->first_name ?? "NA" }} {{ $val->last_name ?? "" }}</td>
                                <td class="">{{ $val->email ?? "NA" }}</td>
                                <td class="text-capitalize">{{ $val->title ?? "NA" }}</td>
                                <td>{{ date('d M, Y H:iA', strtotime($val->created_date)) }}</td>
                                @if($user_type==1)
                                <td class="text-capitalize">{{ $val->ccf_name ?? "NA" }} {{ $val->ccl_name ?? "" }}</td>
                                <td>${{ number_format((float)($val->admin_amount), 2) }}</td>
                                @endif
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
        <div class="tab-pane" id="Products">
            <div class="Overview-card">
                <div class="Overview-card-content">
                    <form action="" id="product-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total Product</h2>
                                    <div class="Overview-value">{{ $total_product ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Total Unpublished Product </h2>
                                    <div class="Overview-value">{{ $unpublish_product ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-form-card">
                                    <select name="product" class="form-control text-capitalize" id="product-select" style="padding: 16.5px 15px;">
                                        <option value="">Select Product</option>
                                        @foreach($product as $val)
                                        <option @if(request()->product==encrypt_decrypt('encrypt',$val->id)) selected @endif value="{{encrypt_decrypt('encrypt',$val->id)}}">{{$val->name ?? "NA"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" value="{{encrypt_decrypt('encrypt', 4)}}" name="tab">
                            <div class="col-md-3">
                                <div class="Overview-form-card">
                                    <input type="month" class="form-control" value="{{ request()->productmonth ?? date('Y-m') }}" name="productmonth" id="product-input">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="Overview-card-chart">
                    <div class="" id="productchart"></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" data-json="{{json_encode($over_graph)}}" id="over_graph">
    <input type="hidden" data-json="{{json_encode($creator_over_graph)}}" id="creator_graph">
    <input type="hidden" data-json="{{json_encode($product_graph)}}" id="product_graph">
</div>

<script>
    $(document).on('change', '#overview-input', function() {
        $("#overview-form").get(0).submit();
    })

    $(document).on('change', '#creator-input', function() {
        $("#creator-form").get(0).submit();
    })

    $(document).on('change', '#user-month, #user-type', function() {
        $("#user-form").get(0).submit();
    })

    $(document).on('change', '#product-input, #product-select', function() {
        $("#product-form").get(0).submit();
    })

    var tab = "{{ encrypt_decrypt('decrypt', $tab) }}";
    console.log(tab);
    if (tab == 1) $("#arkansascoursetab").get(0).click();
    if (tab == 2) $("#creatorcoursetab").get(0).click();
    if (tab == 3) $("#userstab").get(0).click();
    if (tab == 4) $("#productstab").get(0).click();

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

        var chart = new ApexCharts(document.querySelector("#arkansaschart"), options);
        chart.render();
    });

    let dataOverProduct = [];
    $(document).ready(function() {
        let arrOver = $("#product_graph").data('json');
        arrOver.map(ele => {
            dataOverProduct.push(ele);
        })
    })

    $(function() {
        var options1 = {
            series: [{
                name: "Sales",
                data: dataOverProduct,
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

        var chart = new ApexCharts(document.querySelector("#productchart"), options1);
        chart.render();
    });

    let dataOverCreator = [];
    $(document).ready(function() {
        let arrOver = $("#creator_graph").data('json');
        arrOver.map(ele => {
            dataOverCreator.push(ele);
        })
    })

    $(function() {
        var options2 = {
            series: [{
                name: "Sales",
                data: dataOverCreator,
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

        var chart = new ApexCharts(document.querySelector("#creatorchart"), options2);
        chart.render();
    });
</script>
@endsection