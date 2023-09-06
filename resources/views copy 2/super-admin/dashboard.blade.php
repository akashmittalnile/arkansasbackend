@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Dashboard')
@section('content')
    <div class="body-main-content">
        <div class="pmu-overview">
            <div class="row">
                <div class="col-md-4">
                    <div class="pmu-overview-item">
                        <div class="pmu-overview-content">
                            <h2>1452</h2>
                            <p>Total Content Creator</p>
                        </div>
                        <div class="pmu-overview-media">
                            <img src="{!! url('assets/superadmin-images/Content-Creator.svg') !!}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="pmu-overview-item">
                        <div class="pmu-overview-content">
                            <h2>9888</h2>
                            <p>Total Students</p>
                        </div>
                        <div class="pmu-overview-media">
                            <img src="{!! url('assets/superadmin-images/students-icon.svg') !!}">
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="pmu-overview-item">
                        <div class="pmu-overview-content">
                            <h2>9888</h2>
                            <p>Total Listed Products</p>
                        </div>
                        <div class="pmu-overview-media">
                            <img src="{!! url('assets/superadmin-images/products-icon.svg') !!}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="Overview-card">
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
        </div>
    </div>
@endsection
