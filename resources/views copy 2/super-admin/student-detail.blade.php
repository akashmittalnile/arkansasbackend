@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Student Details')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <a href="{{ route('SA.Students')}}" class="newcourse-btn">Back</a>
            </div>
            <div class="pmu-search-filter wd20">
                <div class="row g-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            @if ($data->status == 1)
                                <a data-bs-toggle="modal" data-bs-target="#markasactive" class="newcourse-btn"> Mark as inactive</a>
                            @else
                                <a data-bs-toggle="modal" data-bs-target="#markasactive" class="newcourse-btn"> Mark as active</a>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="user-side-profile">
                            <div class="side-profile-item">
                                <div class="side-profile-media">
                                    @if (!empty($data->profile_image))
                                        <img src="{!! url('assets/upload/profile-image/'.$user->profile_image) !!}">
                                    @else
                                        <img src="{!! url('assets/superadmin-images/no-image.png') !!}">
                                    @endif
                                </div>
                                <div class="side-profile-text">
                                    <h2>{{ ucfirst($data->first_name) }} {{ ucfirst($data->last_name) }}</h2>
                                    <p>Student</p>
                                </div>
                            </div>

                            <div class="side-profile-overview-info">
                                <div class="row g-1">
                                    <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{!! url('assets/superadmin-images/email1.svg') !!}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Email Address</h2>
                                                <p>{{ $data->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{!! url('assets/superadmin-images/buliding-1.svg') !!}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Phone No.</h2>
                                                <p>{{ $data->phone }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{!! url('assets/superadmin-images/accountstatus.svg') !!}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Account Status</h2>
                                                <p>@if ($data->status) Active @else In-active @endif</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="notificationsr-section">
                            <h1>Notifications</h1>
                            <div class="notificationsr-list">
                                <div class="notificationsr-card">
                                    <p>01 New Course Purchase</p>
                                </div>
                                <div class="notificationsr-card">
                                    <p>Certicficate Completed <a href=""> View Certificates</a></p>
                                </div>
                                <div class="notificationsr-card">
                                    <p>Product Order Placed Successfully<a href=""> Order Details</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="pmu-filter-section">
                            <div class="pmu-filter-heading">
                                <h2>Courses</h2>
                            </div>
                            <div class="pmu-search-filter wd80">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Course</option>
                                                <option>Products</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group search-form-group">
                                            <input type="text" class="form-control" name="Start Date"
                                                placeholder="Search by course name, Tags Price">
                                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pmu-content-list">
                            <div class="pmu-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="course-item">
                                            <div class="course-item-inner">
                                                <div class="course-item-image">
                                                    <a data-fancybox="" data-type="iframe"
                                                        data-src="https://www.facebook.com/plugins/video.php?height=314&amp;href=https%3A%2F%2Fwww.facebook.com%2Fapciedu%2Fvideos%2F203104562693996%2F&amp;show_text=false&amp;width=560&amp;t=0"
                                                        href="javascript:;">
                                                        <img src="{!! url('assets/superadmin-images/1.png') !!}">
                                                        <div class="course-video-icon"><img src="{!! url('assets/superadmin-images/video.svg') !!}">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="course-item-content">
                                                    <div class="coursestatus"><img src="{!! url('assets/superadmin-images/tick.svg') !!}">
                                                        Completed Course Successfully</div>
                                                    <h2>What Do You Know About Tattoos and Body Piercing?</h2>
                                                    <div class="course-price">$499.00</div>
                                                    <div class="chapter-test-info">
                                                        <div class="chapter-text">Chapter 34</div>
                                                        <div class="chapter-action"><a href="#">Test Results</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="course-info-list">
                                                <ul>
                                                    <li>
                                                        <div class="course-info-box">
                                                            <div class="course-info-text">Course Start Date:
                                                            </div>
                                                            <div class="course-info-value">26 Jun 2023</div>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="course-info-box">
                                                            <div class="course-info-text">Reattempt Test Date:
                                                            </div>
                                                            <div class="course-info-value">26 Jul 2023</div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="course-info-box">
                                                            <div class="course-info-text">Last Open: </div>
                                                            <div class="course-info-value"> 26 May, 2023;
                                                                09:30AM</div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="course-info-box">
                                                            <div class="course-info-text">Payment completed via
                                                                Credit Card:</div>
                                                            <div class="course-info-value">XXXX8987 </div>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="course-info-action">
                                                            <a href="">Send Invoice to email</a>
                                                            <a href="">Download Invoice</a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="course-item">
                                            <div class="course-item-inner">
                                                <div class="course-item-image">
                                                    <a data-fancybox="" data-type="iframe"
                                                        data-src="https://www.facebook.com/plugins/video.php?height=314&amp;href=https%3A%2F%2Fwww.facebook.com%2Fapciedu%2Fvideos%2F203104562693996%2F&amp;show_text=false&amp;width=560&amp;t=0"
                                                        href="javascript:;">
                                                        <img src="{!! url('assets/superadmin-images/1.png') !!}">
                                                        <div class="course-video-icon"><img src="{!! url('assets/superadmin-images/video.svg') !!}">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="course-item-content">
                                                    <div class="coursestatus"><img src="{!! url('assets/superadmin-images/tick.svg') !!}">
                                                        Completed Course Successfully</div>
                                                    <h2>What Do You Know About Tattoos and Body Piercing?</h2>
                                                    <div class="course-price">$499.00</div>
                                                    <div class="chapter-test-info">
                                                        <div class="chapter-text">Chapter 34</div>
                                                        <div class="chapter-action"><a href="#">Test Results</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="course-info-list">
                                                <ul>
                                                    <li>
                                                        <div class="course-info-box">
                                                            <div class="course-info-text">Course Start Date:
                                                            </div>
                                                            <div class="course-info-value">26 Jun 2023</div>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="course-info-box">
                                                            <div class="course-info-text">Reattempt Test Date:
                                                            </div>
                                                            <div class="course-info-value">26 Jul 2023</div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="course-info-box">
                                                            <div class="course-info-text">Last Open: </div>
                                                            <div class="course-info-value"> 26 May, 2023;
                                                                09:30AM</div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="course-info-box">
                                                            <div class="course-info-text">Payment completed via
                                                                Credit Card:</div>
                                                            <div class="course-info-value">XXXX8987 </div>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="course-info-action">
                                                            <a href="">Send Invoice to email</a>
                                                            <a href="">Download Invoice</a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark as In-Active  -->
    <div class="modal ro-modal fade" id="markasactive" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="student-info-form-info">
                        <h2>Mark as Active</h2>
                        <p>Are you sure mark as Active Again for " {{ ucfirst($data->first_name)}} {{ ucfirst($data->last_name)}}" Once mark as active creator will have access to
                            his account until and unless revert action has been taken again!</p>
                        <div class="student-info-btn-action">
                            @if ($data->status == 1)
                                <a href="{{ url('super-admin/inactive/'.encrypt_decrypt('encrypt',$data->id))}}" class="save-btn">Yes! Inactive</a>
                            @else
                                <a href="{{ url('super-admin/inactive/'.encrypt_decrypt('encrypt',$data->id))}}" class="save-btn">Yes! Active</a>
                            @endif

                            @if ($data->status == 1)
                                <a href="#" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">No! Keep it Active</a>
                            @else
                                <a href="#" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">No! Keep it Inactive</a>
                            @endif
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Payment Request  -->
    <div class="modal ro-modal fade" id="PaymentRequest" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="PaymentRequest-form-info">
                        <h2>Payment Request</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modal-settled-info-text">
                                    <img src="images/dollar-circle.svg">
                                    <p>Total Payment earned</p>
                                    <h4>1007.55</h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modal-settled-info-text">
                                    <img src="images/dollar-circle.svg">
                                    <p>Average Settled Amount</p>
                                    <h4>1007.55</h4>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="modal-bank-info-text">
                                    <p>Creator Cash-Out Options</p>
                                    <div class="modal-added-plan-type">Weekly</div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="modal-bank-info-text">
                                    <p>Bank Name</p>
                                    <h4>ICICI Bank LTD</h4>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="modal-bank-info-text">
                                    <p>Account Number</p>
                                    <h4>98374598734949</h4>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="modal-bank-info-text">
                                    <p>Routine Number</p>
                                    <h4>98374598734949</h4>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="modal-request-section">
                                    <div class="modal-request-head">
                                        <h1>PAYMENT REQUEST <span>(02)</span></h1>
                                    </div>
                                    <div class="modal-request-body">
                                        <div class="modal-request-list">
                                            <div class="modal-request-item">
                                                <div class="modal-request-text">
                                                    <p>Requested amount</p>
                                                    <h3>$ 900.55</h3>
                                                </div>
                                                <div class="modal-request-action">
                                                    <a class="approve-btn" href="#"><img
                                                            src="images/approve.svg"></a>
                                                    <a class="reject-btn" href="#"><img
                                                            src="images/reject.svg"></a>
                                                </div>
                                            </div>
                                            <div class="modal-request-item">
                                                <div class="modal-request-text">
                                                    <p>Requested amount</p>
                                                    <h3>$ 900.55</h3>
                                                </div>
                                                <div class="modal-request-action">
                                                    <a class="approve-btn" href="#"><img
                                                            src="images/approve.svg"></a>
                                                    <a class="reject-btn" href="#"><img
                                                            src="images/reject.svg"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit courses  -->
    <div class="modal ro-modal fade" id="Editcourses" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="PaymentRequest-form-info">
                        <h2>Payment Request</h2>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="modal-settled-info-text">
                                    <img src="images/dollar-circle.svg">
                                    <p>Total Course Payment Received</p>
                                    <h4>3207.55</h4>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="modal-settled-info-text">
                                    <img src="images/dollar-circle.svg">
                                    <p>Total Creator Settled Payment May, 2023</p>
                                    <h4>2234.65</h4>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="Setfee-box">
                                        <h4>Set fee% for every Course Purchase</h4>
                                        <div class="grant-progress ye-progress">
                                            <div class="grant-use-text">
                                                <span>2%</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped" role="progressbar"
                                                    style="width: 2%" aria-valuenow="2" aria-valuemin="0"
                                                    aria-valuemax="10"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Custom %">
                                    <div class="note">On every Course Purchases Creator will get the % revenue Cut</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button class="save-btn">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
