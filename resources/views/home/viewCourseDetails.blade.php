@extends('layouts.app-master')
@section('title', 'Permanent Makeup University - View Course Details')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Courses Details</h2>
        </div>
        <div class="pmu-search-filter wd50">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="form-group">
                        <a href="{{ route('home.index') }}" class="newcourse-btn">Back</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a onclick="return confirm('Are you sure you want to delete this course?');" href="{{ route('Home.delete.course', encrypt_decrypt('encrypt',$course->id)) }}" class="newcourse-btn">Delete</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="newcourse-btn" href="{{ route('Home.edit.course', encrypt_decrypt('encrypt',$course->id)) }}">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pmu-content-list">
        <div class="pmu-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="pmu-course-details-item">
                        <div class="pmu-course-details-media w-25">
                            <a data-fancybox data-type="iframe" data-src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fapciedu%2Fvideos%2F203104562693996%2F&show_text=false&width=560&t=0" href="javascript:;">
                                <video width="300" height="210" controls controlslist="nodownload noplaybackrate" disablepictureinpicture volume>
                                    <source src="{{ uploadAssets('upload/disclaimers-introduction/' . $course->introduction_image) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <!-- <div class="pmu-video-icon"><img src="{!! assets('assets/superadmin-images/video.svg')!!}"></div> -->
                            </a>
                        </div>
                        <div class="pmu-course-details-content w-75">
                            <div class="coursestatus"><img src="{!! assets('assets/superadmin-images/tick.svg')!!}">
                                @if ($course->status == 0)
                                    Unpublished
                                @else
                                    Published 
                                @endif
                            </div>

                            <h2>{{ ($course->title) ? : ''}}</h2>
                            <div class="pmu-course-details-price">${{ number_format($course->course_fee,2) ? : 0}}</div>
                            <p>{{ ($course->description) ? : ''}}</p>
                            <div class="course-tag">
                                <h3>Tags:</h3>
                                <div class="tags-list">
                                    @foreach($combined as $val)
                                        @if($val['selected'])
                                            <div class="Tags-text">{{ $val['name'] }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="pmu-course-details-Chapter">
                        <div class="pmu-course-details-heading">
                            <h2>Chapter</h2>
                        </div>
                        <div class="pmu-course-details-accordion-list">


                            <div class="pmu-course-accordion-item">
                                <div class="pmu-course-accordion-head accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#Disclaimers">
                                    <div class="pmu-course-accordion-title">
                                        <h2>Disclaimers & Introduction</h2>
                                        <a class="edit-icon-btn" href="#"><img src="images/edit-2.svg"></a>
                                    </div>
                                </div>
                                <div class="pmu-course-accordion-body accordion-collapse collapse" id="Disclaimers">
                                    <div class="pmu-course-point-list">
                                        <h2>Disclaimers & Introduction</h2>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Introduction Video</div>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Disclaimers Video</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Hands-on Training Manual (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Student Handbook (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> The AOPC Book (Digital version. You should have a hard copy)</div>
                                    </div>
                                </div>
                            </div>

                            <div class="pmu-course-accordion-item">
                                <div class="pmu-course-accordion-head accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#Chapter2">
                                    <div class="pmu-course-accordion-title">
                                        <h2>Chapter 1: What is Permanent Cosmetics?</h2>
                                        <a class="edit-icon-btn" href="#"><img src="images/edit-2.svg"></a>
                                    </div>
                                </div>
                                <div class="pmu-course-accordion-body accordion-collapse collapse" id="Chapter2">
                                    <div class="pmu-course-point-list">
                                        <h2>Disclaimers & Introduction</h2>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Introduction Video</div>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Disclaimers Video</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Hands-on Training Manual (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Student Handbook (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> The AOPC Book (Digital version. You should have a hard copy)</div>
                                    </div>
                                </div>
                            </div>

                            <div class="pmu-course-accordion-item">
                                <div class="pmu-course-accordion-head accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#Chapter3">
                                    <div class="pmu-course-accordion-title">
                                        <h2>Chapter 2: Frequently Asked Questions</h2>
                                        <a class="edit-icon-btn" href="#"><img src="images/edit-2.svg"></a>
                                    </div>
                                </div>
                                <div class="pmu-course-accordion-body accordion-collapse collapse" id="Chapter3">
                                    <div class="pmu-course-point-list">
                                        <h2>Disclaimers & Introduction</h2>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Introduction Video</div>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Disclaimers Video</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Hands-on Training Manual (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Student Handbook (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> The AOPC Book (Digital version. You should have a hard copy)</div>
                                    </div>
                                </div>
                            </div>


                            <div class="pmu-course-accordion-item">
                                <div class="pmu-course-accordion-head accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#Chapter3">
                                    <div class="pmu-course-accordion-title">
                                        <h2>Chapter 3: Professionalism</h2>
                                        <a class="edit-icon-btn" href="#"><img src="images/edit-2.svg"></a>
                                    </div>
                                </div>
                                <div class="pmu-course-accordion-body accordion-collapse collapse" id="Chapter3">
                                    <div class="pmu-course-point-list">
                                        <h2>Disclaimers & Introduction</h2>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Introduction Video</div>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Disclaimers Video</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Hands-on Training Manual (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Student Handbook (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> The AOPC Book (Digital version. You should have a hard copy)</div>
                                    </div>
                                </div>
                            </div>



                            <div class="pmu-course-accordion-item">
                                <div class="pmu-course-accordion-head accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#Chapter3">
                                    <div class="pmu-course-accordion-title">
                                        <h2>Chapter 4: Safe Practices for PMU Artists</h2>
                                        <a class="edit-icon-btn" href="#"><img src="images/edit-2.svg"></a>
                                    </div>
                                </div>
                                <div class="pmu-course-accordion-body accordion-collapse collapse" id="Chapter3">
                                    <div class="pmu-course-point-list">
                                        <h2>Disclaimers & Introduction</h2>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Introduction Video</div>
                                        <div class="pmu-course-point-item"><img src="images/video-icon.svg"> Disclaimers Video</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Hands-on Training Manual (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> Student Handbook (Digital version. You should have a hard copy)</div>
                                        <div class="pmu-course-point-item"><img src="images/document-text.svg"> The AOPC Book (Digital version. You should have a hard copy)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->



                    <div class="pmu-comment-section">
                        <div class="pmu-comment-list">
                            <div class="pmu-comment-box-head">
                                <div class="pmu-comment-1">
                                    <h1>Rating & Review</h1>
                                    @if(count($review) != 0)
                                    <div class="pmu-comment-rating"><img src="{!! assets('assets/superadmin-images/star.svg')!!}"> {{ number_format($reviewAvg, 1) }}</div>
                                    @else
                                    <div class="pmu-comment-rating"><img src="{{ assets('assets/superadmin-images/star.svg') }}"> 0.0</div>
                                    @endif
                                </div>
                                <div class="pmu-comment-head-action">
                                    <!-- <a class="addcomment-btn"><i class="las la-plus"></i> See More Reviews</a> -->
                                </div>
                            </div>

                            @forelse($review as $value)
                            <div class="pmu-comment-item">
                                <div class="pmu-comment-profile">
                                @if($value->profile_image == '' || $value->profile_image == null)
                                <img src="{{ assets('assets/superadmin-images/user.png') }}">
                                @else
                                <img src="{{ uploadAssets('upload/products/'.$value->profile_image) }}">
                                @endif
                                </div>
                                <div class="pmu-comment-content">
                                    <div class="pmu-comment-head">
                                        <h2>{{ $value->first_name ?? "NA" }} {{ $value->last_name ?? "" }}</h2>
                                        <div class="pmu-date"><i class="las la-calendar"></i>{{ date('d M, Y, g:iA', strtotime($value->created_date ?? "")) }}</div>
                                    </div>
                                    <div class="pmu-comment-rating"><img src="{!! assets('assets/superadmin-images/star.svg')!!}"> {{ number_format($value->rating,1) ?? "0.0" }}</div>
                                    <div class="pmu-comment-descr">{{ $value->review ?? "NA" }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                                <div>
                                    <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                                </div>
                                <div class="font-weight-bold">
                                    <p class="font-weight-bold" style="font-size: 1.2rem;">No rating & review found</p> 
                                </div>
                            </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection