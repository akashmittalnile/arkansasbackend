@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Earnings')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Earnings</h2>
            </div>
            <div class="pmu-search-filter wd80">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" name="Start Date"
                                placeholder="Enter Student Name, email ID, Phone no., order ID to get order details">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Select Type!</option>
                                <option>Students</option>
                                <option>Content Creator</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-table-content">
                <div class="pmu-card-table pmu-table-card">
                    <div class="pmu-table-filter">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="pmu-table-info-card">
                                    <h2>Total Enrolled User</h2>
                                    <div class="pmu-table-value">$ 5678.99</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="pmu-table-form-card">
                                    <a href="#" class="download-btn">Download Payment Log</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Name</th>
                                <th>Date Of Payment</th>
                                <th>Date Of Payment Received</th>
                                <th>Payment Mode</th>
                                <th>Course fees paid</th>
                                <th>Product Price Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="sno">1</span> </td>
                                <td>Tilakavati Manne</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>CC-XXX89</td>
                                <td>$499.00</td>
                                <td>$499.00</td>
                            </tr>
                            <tr>
                                <td><span class="sno">2</span> </td>
                                <td>Tilakavati Manne</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>CC-XXX89</td>
                                <td>$499.00</td>
                                <td>$499.00</td>
                            </tr>
                            <tr>
                                <td><span class="sno">3</span> </td>
                                <td>Tilakavati Manne</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>CC-XXX89</td>
                                <td>$499.00</td>
                                <td>$499.00</td>
                            </tr>
                            <tr>
                                <td><span class="sno">4</span> </td>
                                <td>Tilakavati Manne</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>CC-XXX89</td>
                                <td>$499.00</td>
                                <td>$499.00</td>
                            </tr>
                            <tr>
                                <td><span class="sno">5</span> </td>
                                <td>Tilakavati Manne</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>26 May, 2023-09:30AM</td>
                                <td>CC-XXX89</td>
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
    </div>
@endsection
