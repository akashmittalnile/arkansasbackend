@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Students')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Students</h2>
            </div>
            <div class="pmu-search-filter wd70">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" name="Start Date"
                                placeholder="Search by Student name, order id etc">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Select Account Type!</option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="Accountapproval-btn" href="">Account approval Request</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="creator-table-section">
            <div class="creator-table-list">
                <div class="creator-table-item">
                    <div class="creator-table-col-4">
                        <div class="creator-table-content">
                            <div class="creator-profile-info">
                                <div class="creator-profile-image">
                                    <img src="{!! url('assets/superadmin-images/user.jpg') !!}">
                                </div>
                                <div class="creator-profile-text">
                                    <h2>Atharv Kalla</h2>
                                    <p>02 Completed Course</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="creator-table-col-3">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Phone No.</div>
                            <div class="creator-table-value">+91-8846454165</div>
                        </div>
                    </div>
                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Email ID</div>
                            <div class="creator-table-value">iravatbyadagi@skynet.org</div>
                        </div>
                    </div>

                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Account Status</div>
                            <div class="creator-table-value">Active</div>
                        </div>
                    </div>

                    <div class="creator-table-col-1">
                        <div class="mon-table-box">
                            <a href="students-details.html" class="btn-go">
                                <img src="{!! url('assets/superadmin-images/arrow-right.svg') !!}">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="creator-table-item">
                    <div class="creator-table-col-4">
                        <div class="creator-table-content">
                            <div class="creator-profile-info">
                                <div class="creator-profile-image">
                                    <img src="{!! url('assets/superadmin-images/user.jpg') !!}">
                                </div>
                                <div class="creator-profile-text">
                                    <h2>Jane Doe</h2>
                                    <p>janedoe@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="creator-table-col-3">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Company Name</div>
                            <div class="creator-table-value">Xyz Solutions Pvt Ltd</div>
                        </div>
                    </div>
                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Fee Settlement</div>
                            <div class="creator-table-value">10% Of Course Fees</div>
                        </div>
                    </div>

                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Account Status</div>
                            <div class="creator-table-value">Active</div>
                        </div>
                    </div>

                    <div class="creator-table-col-1">
                        <div class="mon-table-box">
                            <a href="students-details.html" class="btn-go">
                                <img src="{!! url('assets/superadmin-images/arrow-right.svg') !!}">
                            </a>
                        </div>
                    </div>
                </div>


                <div class="creator-table-item">
                    <div class="creator-table-col-4">
                        <div class="creator-table-content">
                            <div class="creator-profile-info">
                                <div class="creator-profile-image">
                                    <img src="{!! url('assets/superadmin-images/user.jpg') !!}">
                                </div>
                                <div class="creator-profile-text">
                                    <h2>Jane Doe</h2>
                                    <p>janedoe@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="creator-table-col-3">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Company Name</div>
                            <div class="creator-table-value">Xyz Solutions Pvt Ltd</div>
                        </div>
                    </div>
                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Fee Settlement</div>
                            <div class="creator-table-value">10% Of Course Fees</div>
                        </div>
                    </div>

                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Account Status</div>
                            <div class="creator-table-value">Active</div>
                        </div>
                    </div>

                    <div class="creator-table-col-1">
                        <div class="mon-table-box">
                            <a href="students-details.html" class="btn-go">
                                <img src="{!! url('assets/superadmin-images/arrow-right.svg') !!}">
                            </a>
                        </div>
                    </div>
                </div>


                <div class="creator-table-item">
                    <div class="creator-table-col-4">
                        <div class="creator-table-content">
                            <div class="creator-profile-info">
                                <div class="creator-profile-image">
                                    <img src="{!! url('assets/superadmin-images/user.jpg') !!}">
                                </div>
                                <div class="creator-profile-text">
                                    <h2>Jane Doe</h2>
                                    <p>janedoe@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="creator-table-col-3">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Company Name</div>
                            <div class="creator-table-value">Xyz Solutions Pvt Ltd</div>
                        </div>
                    </div>
                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Fee Settlement</div>
                            <div class="creator-table-value">10% Of Course Fees</div>
                        </div>
                    </div>

                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Account Status</div>
                            <div class="creator-table-value">Active</div>
                        </div>
                    </div>

                    <div class="creator-table-col-1">
                        <div class="mon-table-box">
                            <a href="students-details.html" class="btn-go">
                                <img src="{!! url('assets/superadmin-images/arrow-right.svg') !!}">
                            </a>
                        </div>
                    </div>
                </div>


                <div class="creator-table-item">
                    <div class="creator-table-col-4">
                        <div class="creator-table-content">
                            <div class="creator-profile-info">
                                <div class="creator-profile-image">
                                    <img src="{!! url('assets/superadmin-images/user.jpg') !!}">
                                </div>
                                <div class="creator-profile-text">
                                    <h2>Jane Doe</h2>
                                    <p>janedoe@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="creator-table-col-3">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Company Name</div>
                            <div class="creator-table-value">Xyz Solutions Pvt Ltd</div>
                        </div>
                    </div>
                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Fee Settlement</div>
                            <div class="creator-table-value">10% Of Course Fees</div>
                        </div>
                    </div>

                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Account Status</div>
                            <div class="creator-table-value">Active</div>
                        </div>
                    </div>

                    <div class="creator-table-col-1">
                        <div class="mon-table-box">
                            <a href="students-details.html" class="btn-go">
                                <img src="{!! url('assets/superadmin-images/arrow-right.svg') !!}">
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
