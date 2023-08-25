@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Help-Support')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Help & Support</h2>
            </div>
            <div class="pmu-search-filter wd40">

            </div>
        </div>

        <div class="help-card">
            <div class="help-card-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="help-info-card">
                            <h2>Total Query raised</h2>
                            <div class="help-value">12</div>
                            <div class="help-date">May,2023</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" name="Start Date"
                                placeholder="Enter order ID to get order details">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="date" class="form-control" name="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="help-card-query">
                <div class="help-card-query-card">
                    <div class="help-card-query-head">
                        <div class="help-card-query-icon">
                            <img src="{!! url('assets/superadmin-images/message.svg') !!}">
                        </div>
                        <div class="help-card-query-text">
                            <h3>Uploaded Content With Related To Tattoo And Also Created Question & Answer, Waiting For The
                                Review To Approved</h3>
                        </div>
                    </div>
                    <div class="help-card-query-body">
                        <div class="help-card-respond-card">
                            <div class="help-card-respond-icon">
                                <img src="{!! url('assets/superadmin-images/admin.svg') !!}">
                            </div>
                            <div class="help-card-respond-text">
                                <h3>Admin Respond</h3>
                                <div class="">
                                    <p>Dear Creator,</p>
                                    <p>Thank You For Reaching Out To Us Through The Help & Support App Section. We Are Sorry
                                        To Hear That You Are Experiencing An Issue With Your Points Estimate Not Increasing
                                        After Applying A Coupon. To Help You With This Issue, Please Provide Us With The
                                        Following Information:</p>
                                    <p>- Your Account Username Or Email Address</p>
                                    <p>- The Date And Time You Uploaded The Course</p>
                                    <p>- A Screenshot Of The Coupon Code And Any Error Message You Received, If Applicable
                                    </p>
                                    <p>Once We Receive This Information, We Will Investigate The Issue And Work Towards
                                        Resolving It As Soon As Possible.</p>
                                    <p>Thank You For Your Patience And Understanding.</p>
                                    <p>Best Regards,</p>
                                    <p>Timeshare Simplified App Owner Admin</p>
                                </div>
                                <div class="help-card-respond-form">
                                    <div class="form-group">
                                        <textarea class="form-control" placeholder="Type your Message Here…"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button class="Submit-btn">Submit</button>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>

                <div class="help-card-query-card">
                    <div class="help-card-query-head">
                        <div class="help-card-query-icon">
                            <img src="{!! url('assets/superadmin-images/message.svg') !!}">
                        </div>
                        <div class="help-card-query-text">
                            <h3>Uploaded Content With Related To Tattoo And Also Created Question & Answer, Waiting For The
                                Review To Approved</h3>
                        </div>
                    </div>
                    <div class="help-card-query-body">
                        <div class="help-card-respond-card">
                            <div class="help-card-respond-icon">
                                <img src="{!! url('assets/superadmin-images/admin.svg') !!}">
                            </div>
                            <div class="help-card-respond-text">
                                <h3>Admin Respond</h3>
                                <div class="">
                                    <p>Dear Creator,</p>
                                    <p>Thank You For Reaching Out To Us Through The Help & Support App Section. We Are Sorry
                                        To Hear That You Are Experiencing An Issue With Your Points Estimate Not Increasing
                                        After Applying A Coupon. To Help You With This Issue, Please Provide Us With The
                                        Following Information:</p>
                                    <p>- Your Account Username Or Email Address</p>
                                    <p>- The Date And Time You Uploaded The Course</p>
                                    <p>- A Screenshot Of The Coupon Code And Any Error Message You Received, If Applicable
                                    </p>
                                    <p>Once We Receive This Information, We Will Investigate The Issue And Work Towards
                                        Resolving It As Soon As Possible.</p>
                                    <p>Thank You For Your Patience And Understanding.</p>
                                    <p>Best Regards,</p>
                                    <p>Timeshare Simplified App Owner Admin</p>
                                </div>
                                <div class="help-card-respond-form">
                                    <div class="form-group">
                                        <textarea class="form-control" placeholder="Type your Message Here…"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button class="Submit-btn">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
