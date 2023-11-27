@extends('layouts.app-master')
@section('title', 'Permanent Makeup University - Courses')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Courses</h2>
            </div>
            <div class="pmu-search-filter wd70">
                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group search-form-group">
                                <input type="text" class="form-control" name="course"
                                    placeholder="Search by course name" value="{{request()->course}}">
                                <span class="search-icon"><img src="{!! assets('assets/superadmin-images/search-icon.svg')!!}"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option @if(request()->status == '') selected @endif value="">Select Course Type</option>
                                    <option @if(request()->status == '1') selected @endif value="1">Published</option>
                                    <option @if(request()->status == '0') selected @endif value="0">Unpublished</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="add-more py-2" type="">Search</button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <a href="{{ route('home.index') }}" style="padding: 12px 0px;" class="newcourse-btn"><i class="las la-sync"></i></a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <a class="newcourse-btn" href="{{ route('Home.Addcourse') }}">Create New Course</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    @if($courses->isEmpty())
                        <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                            <div>
                                <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                            </div>
                            <div class="font-weight-bold">
                                <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p> 
                            </div>
                        </div>
                    @elseif(!$courses->isEmpty())
                        @foreach($courses as $data)
                            <div class="col-md-4">
                                <div class="pmu-course-item">
                                    <div class="pmu-course-media">
                                        <a data-fancybox data-type="iframe"
                                            data-src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fapciedu%2Fvideos%2F203104562693996%2F&show_text=false&width=560&t=0"
                                            href="javascript:;">
                                            <video width="415" height="240" controls controlslist="nodownload noplaybackrate" disablepictureinpicture volume>
                                                <source src="{{ assets( 'upload/disclaimers-introduction/' . $data->introduction_image) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <!-- <div class="pmu-video-icon"><img src="{!! assets('assets/website-images/video.svg') !!}"></div> -->
                                        </a>
                                    </div> 
                                      
                                    <div class="pmu-course-content">
                                        <div class="d-flex">
                                            <div class="col-md-2 mb-2">
                                                <a class="newcourse-btn" href="{{ route('Home.edit.course', encrypt_decrypt('encrypt',$data->id)) }}"> <i class="las la-edit"></i></a>
                                            </div>
                                            <div class="col-md-2 mb-2 mx-2">
                                                <a class="newcourse-btn" onclick="return confirm('Are you sure you want to delete this course?');" href="{{ route('Home.delete.course', encrypt_decrypt('encrypt',$data->id)) }}"> <i class="las la-trash"></i></a>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <a class="newcourse-btn" href="{{ route('Home.view.course', encrypt_decrypt('encrypt',$data->id)) }}"> <i class="las la-eye"></i></a>
                                            </div>
                                        </div>

                                        <a href="{{ url('admin/addcourse2/'.encrypt_decrypt('encrypt',$data->id))}}"> 
                                        <div class="@if($data->status == 0) coursestatus-unpublish @else coursestatus @endif"><img src="{!! assets('assets/website-images/tick.svg') !!}">
                                            @if ($data->status == 0)
                                                Unpublished
                                            @else
                                                Published 
                                            @endif
                                        </div>
                                        <h2>{{ ($data->title) ? : ''}}</h2>
                                        <div class="pmu-course-price">${{ number_format($data->course_fee,2) ? : 0}}</div>
                                        <p>{{ ($data->description != "" && $data->description != null) ? (strlen($data->description) > 53 ?  substr($data->description, 0, 53)."....." : $data->description) : "NA" }}</p>
                                        <?php
                                            $chapter_count = \App\Models\CourseChapter::where('course_id',$data->id)->count();
                                        ?>
                                        @if ($chapter_count == 0)
                                            <div class="chapter-text">Create Chapters</div>
                                        @elseif ($chapter_count == 1)
                                            <div class="chapter-text">Chapter 1</div>
                                        @else
                                            <div class="chapter-text">Chapter 1-{{$chapter_count}}</div>
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
@endsection
