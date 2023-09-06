@extends('layouts.app-master')
@section('title', 'Makeup University - Courses')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Courses</h2>
            </div>
            <div class="pmu-search-filter wd40">
                <div class="row g-2">
                    <div class="col-md-8">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Show all</option>
                                <option>Published</option>
                                <option>Unpublished</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="newcourse-btn" href="{{ route('Home.Addcourse') }}">New Course</a>
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
                                    <a href="{{ url('admin/addcourse2/'.encrypt_decrypt('encrypt',$data->id))}}">   
                                        <div class="pmu-course-content">
                                            <div class="coursestatus"><img src="{!! url('assets/website-images/tick.svg') !!}">
                                                @if ($data->status == 0)
                                                    Unpublished
                                                @else
                                                    Published 
                                                @endif
                                                
                                            </div>
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
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
