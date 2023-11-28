@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Listed Course')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <a href="{{ route('SA.ContentCreators') }}" class="newcourse-btn">Back</a>
            </div>
            <div class="pmu-search-filter wd40">
                <div class="row g-2">
                    @if ($user->status == 0 || $user->status == 3)
                        <div class="col-md-6">
                            @if($user->status == 0)
                            <div class="form-group">
                                <a href="{{ url('super-admin/update-approval-request/' . encrypt_decrypt('encrypt', $user->id) . '/' . encrypt_decrypt('encrypt', 3)) }}"
                                    class="newcourse-btn"><i class="las la-times-circle"></i> Reject Request</a>
                            </div>
                            @endif
                        </div>
                        @if($user->status == 3)
                        <div class="col-md-6">
                            <div class="form-group">
                                <a style="background: #E0B220;" class="newcourse-btn"
                                    href="{{ url('super-admin/update-approval-request/' . encrypt_decrypt('encrypt', $user->id) . '/' . encrypt_decrypt('encrypt', 1)) }}"><i
                                        class="las la-check-circle"></i> Approve request</a>
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="col-md-5">
                            <div class="form-group">
                                @if ($user->status == 1)
                                    <a data-bs-toggle="modal" data-bs-target="#markasactive" class="newcourse-btn">Mark as Inactive</a>
                                @else
                                    <a data-bs-toggle="modal" data-bs-target="#markasactive" class="newcourse-btn">Mark as active</a>
                                @endif
                                
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <a class="newcourse-btn" data-bs-toggle="modal" data-bs-target="#Editcourses">Edit Course
                                    fee %
                                    Settlement </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-3">
                        <div class="user-side-profile">
                            <div class="side-profile-item">
                                <div class="side-profile-media">
                                    @if (!empty($user->profile_image))
                                        <img src="{!! uploadAssets('upload/profile-image/'.$user->profile_image) !!}">
                                    @else
                                        <img src="{!! assets('assets/superadmin-images/no-image.png') !!}">
                                    @endif

                                </div>
                                <div class="side-profile-text">
                                    <h2>{{ ucfirst($user->first_name) }}{{ ucfirst($user->last_name) }}</h2>
                                    <p>
                                        @if ($user->CreatorType == 1)
                                            Permanent Makeup Training
                                        @else
                                            Tattooing & Piercing Institute
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="side-profile-overview-info">
                                <div class="row g-1">
                                    <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{!! assets('assets/superadmin-images/email1.svg') !!}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Email Address</h2>
                                                <p>{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{!! assets('assets/superadmin-images/book-1.svg') !!}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Fee Settlement</h2>
                                                <p>{{ $user->admin_cut ?? 0 }}% Of Course Fees</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{!! assets('assets/superadmin-images/buliding-1.svg') !!}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Company Name</h2>
                                                <p>{{ $user->company_name}}</p>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{!! assets('assets/superadmin-images/accountstatus.svg') !!}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Account Status</h2>
                                                @if ($user->status == 1)
                                                    <p>Active</p>
                                                @else
                                                    <p>Inactive</p>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="user-side-creator-payout">
                            <h1>Creator Payout Detail</h1>
                            <div class="added-bank-info-card">
                                <div class="added-bank-info-icon">
                                    <img src="{!! assets('assets/superadmin-images/card.svg') !!}">
                                </div>
                                <div class="added-bank-info-text">
                                    <h2>Account Number</h2>
                                    @if(isset($account->account_number))
                                    <p>{{ $account->account_number }}</p>
                                    @else
                                    <p>NA</p>
                                    @endif
                                    <!-- <div class="added-plan-type">Weekly</div> -->
                                </div>
                            </div>

                            <div class="added-bank-info-card">
                                <div class="added-bank-info-icon">
                                    <img src="{!! assets('assets/superadmin-images/card.svg') !!}">
                                </div>
                                <div class="added-bank-info-text">
                                    <h2>Routing Number</h2>
                                    <p>{{ $account->routine_number ?? "NA" }}</p>
                                    <!-- <div class="added-plan-type">Weekly</div> -->
                                </div>
                            </div>

                            <div class="settled-info-text">
                                <div class="added-new-request">{{ $count ?? 0 }} new request</div>
                                <p>Total Settled Amount</p>
                                <h2>${{ number_format((float)$amount, 2) }}</h2>
                            </div>

                            <div class="Payment-Request-action">
                                <a href="{{ route('SA.Payment.Request', encrypt_decrypt('encrypt', $user->id)) }}" class="newcourse-btn">View Payment Requests</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="pmu-filter-section">
                            <div class="pmu-filter-heading">
                                <h2>Listed Course</h2>
                            </div>
                            <div class="pmu-search-filter wd80">
                                <form action="">
                                    <div class="row g-2">
                                        <div class="col-md-5">
                                            <div class="form-group search-form-group">
                                                <input type="text" class="form-control" name="name"
                                                    placeholder="Search by Course Name" value="{{ request()->name ?? '' }}">
                                                <span class="search-icon"><img src="{!! assets('assets/superadmin-images/search-icon.svg') !!}"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="date" class="form-control" name="date" value="{{ request()->date ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <a class="Create-btn" style="padding: 12px 0px;" href="{{ route('SA.ListedCourse', encrypt_decrypt('encrypt', $user->id)) }}"><i class="las la-sync"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button class="save-btn" style="padding: 13px 17px;" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="pmu-content-list">
                            <div class="pmu-content">
                                <div class="row">
                                    @if ($courses->isEmpty())
                                    <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                                        <div>
                                            <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                                        </div>
                                        <div class="font-weight-bold">
                                            <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p> 
                                        </div>
                                    </div>
                                    @elseif(!$courses->isEmpty())
                                        @foreach ($courses as $data)
                                            <div class="col-md-6">
                                                <div class="pmu-course-item">
                                                    <div class="pmu-course-media">
                                                        <a data-fancybox data-type="iframe"
                                                            data-src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fapciedu%2Fvideos%2F203104562693996%2F&show_text=false&width=560&t=0"
                                                            href="javascript:;">
                                                            <video width="415" height="240" controls controlslist="nodownload noplaybackrate" disablepictureinpicture volume>
                                                                <source src="{{ uploadAssets('upload/disclaimers-introduction/' . $data->introduction_image) }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                            <!-- <div class="pmu-video-icon"><img src="{!! assets('assets/superadmin-images/video.svg') !!}"></div> -->
                                                        </a>
                                                    </div>
                                                    <div class="pmu-course-content">
                                                        <div class="@if($data->status == 0) coursestatus-unpublish @else coursestatus @endif"><img src="{!! assets('assets/superadmin-images/tick.svg') !!}">
                                                            @if ($data->status == 0)
                                                                Unpublished
                                                            @else
                                                                Published
                                                            @endif
                                                        </div>
                                                        <form action="{{ route('SaveStatusCourse') }}" method="POST">
                                                            <div class="course-status">
                                                                <input type="hidden" name="_token"
                                                                    value="{{ csrf_token() }}" />
                                                                <input type="hidden" name="course_id"
                                                                    value="{{ $data->id }}" />
                                                                <input type="hidden" name="admin_id"
                                                                    value="{{ $data->admin_id }}" />
                                                                <label for="user_id">Select Status</label>
                                                                <select name="status" id="status"
                                                                    class="course-select">
                                                                    <option disabled>Select Status</option>
                                                                    <option value="1"
                                                                        @if ($data->status == 1) selected='selected' @else @endif>
                                                                        Published</option>
                                                                    <option value="0"
                                                                        @if ($data->status == 0) selected='selected' @else @endif>
                                                                        Unpublished</option>
                                                                </select>

                                                                <button type="submit" class="course-save">Save</button>
                                                            </div>
                                                        </form>
                                                        <h2>{{ $data->title ?: '' }}</h2>
                                                        <div class="pmu-course-price">
                                                            ${{ number_format($data->course_fee, 2) ?: 0 }}</div>
                                                        <p>{{ ($data->description != "" && $data->description != null) ? (strlen($data->description) > 53 ?  substr($data->description, 0, 53)."....." : $data->description) : "NA" }}</p>
                                                        <a href="{{ route('SA.Addcourse2', [ 'userID'=> encrypt_decrypt('encrypt', $user->id), 'courseID'=> encrypt_decrypt('encrypt',$data->id) ] ) }}">
                                                        <?php
                                                        $chapter_count = \App\Models\CourseChapter::where('course_id', $data->id)->count();
                                                        ?>
                                                        @if ($chapter_count == 0)
                                                            <div class="chapter-text">Create Chapters</div>
                                                        @elseif ($chapter_count == 1)
                                                            <div class="chapter-text">Chapter 1</div>
                                                        @else
                                                            <div class="chapter-text">Chapter 1-{{ $chapter_count }}</div>
                                                        @endif
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark as Active  -->
    <div class="modal ro-modal fade" id="markasactive" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="becomeacreator-form-info">
                        <h2>Mark as @if ($user->status == 1) Inactive  @elseif ($user->status == 2) Active @endif</h2>
                        
                        @if ($user->status == 1)
                        <p>Are you sure to mark "{{ ucfirst($user->first_name)}} {{ ucfirst($user->last_name)}}" as an inactive content creator his all courses will not be displayed to the users.</p>
                        @elseif ($user->status == 2)
                        <p>Are you sure to mark "{{ ucfirst($user->first_name)}} {{ ucfirst($user->last_name)}}" as an active content creator his all courses will be displayed to the users.</p>
                        @endif

                        <div class="becomeacreator-btn-action">
                            @if ($user->status == 1)
                                <a href="{{ url('super-admin/inactive/'.encrypt_decrypt('encrypt',$user->id))}}" class="save-btn">Yes! Inactive</a>
                            @elseif ($user->status == 2)
                                <a href="{{ url('super-admin/inactive/'.encrypt_decrypt('encrypt',$user->id))}}" class="save-btn">Yes! Active</a>
                            @endif
                            <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            
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
                                    <img src="{!! assets('assets/superadmin-images/dollar-circle.svg') !!}">
                                    <p>Total Payment earned</p>
                                    <h4>1007.55</h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modal-settled-info-text">
                                    <img src="{!! assets('assets/superadmin-images/dollar-circle.svg') !!}">
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
                                    <p>Routing Number</p>
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
                                                            src="{!! assets('assets/superadmin-images/approve.svg') !!}"></a>
                                                    <a class="reject-btn"href="#"><img src="{!! assets('assets/superadmin-images/reject.svg') !!}"></a>
                                                </div>
                                            </div>
                                            <div class="modal-request-item">
                                                <div class="modal-request-text">
                                                    <p>Requested amount</p>
                                                    <h3>$ 900.55</h3>
                                                </div>
                                                <div class="modal-request-action">
                                                    <a class="approve-btn" href="#"><img
                                                            src="{!! assets('assets/superadmin-images/approve.svg') !!}"></a>
                                                    <a class="reject-btn"href="#"><img src="{!! assets('assets/superadmin-images/reject.svg') !!}"></a>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="PaymentRequest-form-info">
                        <h2>Admin Fee</h2>
                        <div class="row">
                            <form method="POST" action="{{ route('Savecoursefee') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="Setfee-box">
                                            <h4>Set fee% for every Course Purchase</h4>
                                            <div class="grant-progress ye-progress">
                                                <div class="grant-use-text">
                                                    <span>{{ $user->admin_cut }}%</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar"
                                                        style="width: {{ $user->admin_cut }}%" aria-valuenow="2" aria-valuemin="0"
                                                        aria-valuemax="10"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="admin_id" value="{{ $user->id }}" />
                                        <input type="number" min="1" step="0.1" max="100" class="form-control" name="course_fee" placeholder="Custom %" value="{{$user->admin_cut}}" required>
                                        <div class="note">On every Course Purchases Arkansas will get the % revenue Cut</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="cancel-btn"type="button" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                        <button class="save-btn" type="submit">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#Editcourses').on('hidden.bs.modal', function(e) {
            $(this).find('form').trigger('reset');
        })
    </script>
@endsection
