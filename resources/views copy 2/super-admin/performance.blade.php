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
                <li class="nav-item"><a class="nav-link active" href="#Overview" data-bs-toggle="tab">Overview</a> </li>
                <li class="nav-item"><a class="nav-link" href="#Users" data-bs-toggle="tab">Users</a> </li>
                <li class="nav-item"><a class="nav-link" href="#CourseEngagement" data-bs-toggle="tab">Course Engagement</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="#CreatorCourseEngagement" data-bs-toggle="tab">Creator Course
                        Engagement</a> </li>
            </ul>
        </div>

        <div class="pmu-tab-content tab-content">
            <div class="tab-pane active" id="Overview">
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
                                    <h2>Total Course Purchases</h2>
                                    <div class="Overview-price">12</div>
                                    <div class="overview-date">May,2023</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="Overview-info-card">
                                    <h2>Overall Products rating</h2>
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
            <div class="tab-pane" id="Users">
                <div class="Overview-card">
                    <div class="Overview-card-content">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="Overview-info-card">
                                    <h2>Total Enrolled User</h2>
                                    <div class="Overview-value">12</div>
                                    <div class="overview-date">May,2023</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="Overview-form-card">
                                    <input type="date" class="form-control" name="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="Overview-card-table pmu-table-card">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Name</th>
                                    <th>Course fees paid</th>
                                    <th>Product Price Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="sno">1</span> </td>
                                    <td>Anishk Dodavad</td>
                                    <td>$499.00</td>
                                    <td>$499.00</td>
                                </tr>
                                <tr>
                                    <td><span class="sno">2</span> </td>
                                    <td>Swati Tamboli</td>
                                    <td>$499.00</td>
                                    <td>$499.00</td>
                                </tr>
                                <tr>
                                    <td><span class="sno">3</span> </td>
                                    <td>Arjun Menon</td>
                                    <td>$499.00</td>
                                    <td>$499.00</td>
                                </tr>
                                <tr>
                                    <td><span class="sno">4</span> </td>
                                    <td>Elavarasi Harikantr</td>
                                    <td>$499.00</td>
                                    <td>$499.00</td>
                                </tr>

                                <tr>
                                    <td><span class="sno">5</span> </td>
                                    <td>Gokuldas Nayka</td>
                                    <td>$499.00</td>
                                    <td>$499.00</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="pmu-table-pagination">
                            <ul class="pmu-pagination">
                                <li class="disabled" id="example_previous">
                                    <a href="#" aria-controls="example" data-dt-idx="0" tabindex="0"
                                        class="page-link">Previous</a>
                                </li>
                                <li class="active">
                                    <a href="#" class="page-link">1</a>
                                </li>
                                <li class="">
                                    <a href="#" aria-controls="example" data-dt-idx="2" tabindex="0"
                                        class="page-link">2</a>
                                </li>
                                <li class="">
                                    <a href="#" aria-controls="example" data-dt-idx="3" tabindex="0"
                                        class="page-link">3</a>
                                </li>
                                <li class="next" id="example_next">
                                    <a href="#" aria-controls="example" data-dt-idx="7" tabindex="0"
                                        class="page-link">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="CourseEngagement">
                <div class="Overview-card">
                    <div class="Overview-card-content">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="Overview-info-card">
                                    <h2>Total Course</h2>
                                    <div class="Overview-value">23</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="Overview-info-card">
                                    <h2>Total Visits</h2>
                                    <div class="Overview-value">109K Visits</div>
                                </div>
                            </div>

                            <div class="col-md-4">
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

            <div class="tab-pane" id="CreatorCourseEngagement">
                <div class="Overview-card">
                    <div class="Overview-card-content">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="Overview-info-card">
                                    <h2>Total Course</h2>
                                    <div class="Overview-value">23</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="Overview-info-card">
                                    <h2>Total Visits</h2>
                                    <div class="Overview-value">109K Visits</div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="Overview-info-card">
                                    <h2>Total Accepted Course </h2>
                                    <div class="Overview-value">03</div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="Overview-info-card">
                                    <h2>Total Rejected Course </h2>
                                    <div class="Overview-value">3</div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="Overview-info-card">
                                    <input type="date" class="form-control" name="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="Overview-card-chart">
                        <div class="" id="CreatorCourseEngagement"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
