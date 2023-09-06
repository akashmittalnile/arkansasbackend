@extends('layouts.app-master')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Help & Support</h2>
        </div>
        <div class="pmu-search-filter wd40">

        </div>
    </div>


    <div class="pmu-tab-nav">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#RecentQuery" data-bs-toggle="tab">Recent
                    Query</a> </li>
            <li class="nav-item"><a class="nav-link" href="#Getintouch" data-bs-toggle="tab">Get in
                    touch</a> </li>
        </ul>
    </div>

    <div class="pmu-tab-content tab-content">
        <div class="tab-pane active" id="RecentQuery">
            <div class="help-card">
                <div class="help-card-content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="help-info-card">
                                <h2>Total Query raised</h2>
                                <div class="help-value">12</div>
                                <div class="help-date">May,2023</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="help-form-card">
                                <input type="date" class="form-control" name="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="help-card-query">
                    <div class="help-card-query-card">
                        <div class="help-card-query-head">
                            <div class="help-card-query-icon">
                                <img src="{!! url('assets/website-images/message.svg') !!}">
                            </div>
                            <div class="help-card-query-text">
                                <h3>Uploaded Content With Related To Tattoo And Also Created Question &
                                    Answer, Waiting For The Review To Approved</h3>
                            </div>
                        </div>
                        <div class="help-card-query-body">
                            <div class="help-card-respond-card">
                                <div class="help-card-respond-icon">
                                    <img src="{!! url('assets/website-images/admin.svg') !!}">
                                </div>
                                <div class="help-card-respond-text">
                                    <h3>Admin Respond</h3>
                                    <div class="">
                                        <p>Dear Creator,</p>
                                        <p>Thank You For Reaching Out To Us Through The Help & Support App
                                            Section. We Are Sorry To Hear That You Are Experiencing An Issue
                                            With Your Points Estimate Not Increasing After Applying A
                                            Coupon. To Help You With This Issue, Please Provide Us With The
                                            Following Information:</p>
                                        <p>- Your Account Username Or Email Address</p>
                                        <p>- The Date And Time You Uploaded The Course</p>
                                        <p>- A Screenshot Of The Coupon Code And Any Error Message You
                                            Received, If Applicable</p>
                                        <p>Once We Receive This Information, We Will Investigate The Issue
                                            And Work Towards Resolving It As Soon As Possible.</p>
                                        <p>Thank You For Your Patience And Understanding.</p>
                                        <p>Best Regards,</p>
                                        <p>Timeshare Simplified App Owner Admin</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="help-card-query-card">
                        <div class="help-card-query-head">
                            <div class="help-card-query-icon">
                                <img src="{!! url('assets/website-images/message.svg') !!}">
                            </div>
                            <div class="help-card-query-text">
                                <h3>Uploaded Content With Related To Tattoo And Also Created Question &
                                    Answer, Waiting For The Review To Approved</h3>
                            </div>
                        </div>
                        <div class="help-card-query-body">
                            <div class="help-card-respond-card">
                                <div class="help-card-respond-icon">
                                    <img src="{!! url('assets/website-images/admin.svg')!!}">
                                </div>
                                <div class="help-card-respond-text">
                                    <h3>Admin Respond</h3>
                                    <div class="">
                                        <p>Dear Creator,</p>
                                        <p>Thank You For Reaching Out To Us Through The Help & Support App
                                            Section. We Are Sorry To Hear That You Are Experiencing An Issue
                                            With Your Points Estimate Not Increasing After Applying A
                                            Coupon. To Help You With This Issue, Please Provide Us With The
                                            Following Information:</p>
                                        <p>- Your Account Username Or Email Address</p>
                                        <p>- The Date And Time You Uploaded The Course</p>
                                        <p>- A Screenshot Of The Coupon Code And Any Error Message You
                                            Received, If Applicable</p>
                                        <p>Once We Receive This Information, We Will Investigate The Issue
                                            And Work Towards Resolving It As Soon As Possible.</p>
                                        <p>Thank You For Your Patience And Understanding.</p>
                                        <p>Best Regards,</p>
                                        <p>Timeshare Simplified App Owner Admin</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="Getintouch">
            <div class="help-card">
                <div class="help-card-content">
                    <h2>Get In Touch</h2>
                    <p>If You Have Any Query Related To Course And Account Payout</p>
                </div>
                <div class="help-card-form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control"
                                    placeholder="Enter your name here" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control"
                                    placeholder="Enter your email here" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control"
                                    placeholder="Enter your phone here" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control"
                                    placeholder="Type your Message Here…"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
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
@endsection