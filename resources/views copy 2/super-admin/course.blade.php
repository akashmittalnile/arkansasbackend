@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Course')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Manage Course</h2>
            </div>
            <div class="pmu-search-filter wd70">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" name="Start Date"
                                placeholder="Search by course name, Tags Price">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg')!!}"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Select Course Type!</option>
                                <option>Published</option>
                                <option>Deleted</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="Create-btn" href="{{ route('SA.AddCourse')}}">Create New Course</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    @if($courses->isEmpty())
                        <tr>
                            <td colspan="11" class="text-center">
                                No record found
                            </td>
                        </tr>
                    @elseif(!$courses->isEmpty())
                        @foreach($courses as $data)
                            <div class="col-md-4">
                                <div class="pmu-course-item">
                                    <div class="pmu-course-media">
                                        <a data-fancybox data-type="iframe"
                                            data-src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fapciedu%2Fvideos%2F203104562693996%2F&show_text=false&width=560&t=0"
                                            href="javascript:;">
                                            <img src="{!! url('assets/website-images/1.png') !!}">
                                            <div class="pmu-video-icon"><img src="{!! url('assets/website-images/video.svg') !!}"></div>
                                        </a>
                                    </div>
                                    <div class="pmu-course-content">
                                        <div class="coursestatus"><img src="{!! url('assets/website-images/tick.svg') !!}">
                                            @if ($data->status == 0)
                                                Unpublished
                                            @else
                                                Published 
                                            @endif
                                        </div>
                                        <form action="{{ route('SaveStatusCourse') }}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <input type="hidden" name="course_id" value="{{$data->id}}" />
                                            <label for="user_id">Select Status</label>
                                            <select name="status" id="status">
                                                <option disabled>Select Status</option>
                                                <option value="1" @if ($data->status == 1) selected='selected' @else @endif>Published</option>
                                                <option value="0" @if ($data->status == 0) selected='selected' @else @endif>Unpublished</option> 
                                            </select>
                                        
                                            <button type="submit">Save</button>
                                        </form>
                                        <h2>{{ ($data->title) ? : ''}}</h2>
                                        <div class="pmu-course-price">${{ number_format($data->course_fee,2) ? : 0}}</div>
                                        <p>{{ ($data->description) ? : ''}}</p>
                                            <?php
                                                $chapter_count = \App\Models\CourseChapter::where('course_id',$data->id)->count();
                                            ?>
                                            @if ($chapter_count == 0)
                                                <div class="chapter-text">Chapter 0</div>
                                            @elseif ($chapter_count == 1)
                                                <div class="chapter-text">Chapter 1</div>
                                            @else
                                                <div class="chapter-text">Chapter 1-{{$chapter_count}}</div>
                                            @endif
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
