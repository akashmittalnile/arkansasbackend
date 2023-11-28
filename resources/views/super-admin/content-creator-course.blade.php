@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Add Category')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Content Creators Course</h2>
        </div>
        <div class="pmu-search-filter wd70">
            <form action="">
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" name="name" placeholder="Search by Course Name" value="{{request()->name}}">
                            <span class="search-icon"><img src="{!! assets('assets/superadmin-images/search-icon.svg')!!}"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" name="status">
                                <option @if(request()->status=="") selected @endif value="">Select Course Type</option>
                                <option @if(request()->status=="1") selected @endif value="1">Published</option>
                                <option @if(request()->status=="0") selected @endif value="0">Unpublished</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" name="creator">
                                <option @if(request()->creator=="") selected @endif value="">Select Content Creator</option>
                                @foreach(getUser(2) as $val)
                                <option @if(request()->creator==encrypt_decrypt('encrypt',$val->id)) selected @endif value="{{encrypt_decrypt('encrypt',$val->id)}}">{{$val->first_name ?? "NA"}} {{$val->last_name ?? ""}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <a class="Accountapproval-btn" style="padding: 13px 20px;" href="{{ route('SA.Content-Creator.Course') }}"><i class="las la-sync"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="add-more" style="padding: 8px 45px;" type="">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="creator-table-section">
        <div class="creator-table-list">
            @if($course->isEmpty())
            <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                <div>
                    <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                </div>
                <div class="font-weight-bold">
                    <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p>
                </div>
            </div>
            @elseif(!$course->isEmpty())
            @foreach($course as $data)
            <div class="creator-table-item">
                <div class="creator-table-col-2">
                    <div class="creator-table-content">
                        <div class="creator-profile-info">
                            <div class="creator-profile-image">
                                @if(empty($data->profile_image))
                                <img src="{!! assets('assets/superadmin-images/no-image.png') !!}">
                                @else
                                <img src="{!! assets('upload/profile-image/'.$data->profile_image) !!}">
                                @endif
                            </div>
                            <div class="creator-profile-text">
                                <h2>Creator Name</h2>
                                <p>{{ $data->first_name ?? "NA" }} {{ $data->last_name ?? "" }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="creator-table-col-3">
                    <div class="creator-table-box">
                        <div class="creator-table-text">Course Name</div>
                        <div class="creator-table-value">{{ ucfirst($data->title) }}</div>
                    </div>
                </div>

                <div class="creator-table-col-2">
                    <div class="creator-table-box">
                        <div class="creator-table-text">Course Fee</div>
                        <div class="creator-table-value">${{ $data->course_fee ?? 0 }}</div>
                    </div>
                </div>

                <div class="creator-table-col-2">
                    <div class="creator-table-box">
                        <div class="creator-table-text">Course Status</div>
                        <div class="creator-table-value">@if ($data->status==1) Published @elseif($data->status == 0) Unpublished @endif</div>
                    </div>
                </div>

                <div class="creator-table-col-2">
                    <div class="creator-table-box">
                        <div class="creator-table-text">Course Created Date</div>
                        <div class="creator-table-value">{{ date('d M Y', strtotime($data->created_date)) }}</div>
                    </div>
                </div>

                <div class="creator-table-col-1">
                    <div class="mon-table-box">
                        <a href="{{ route('SA.Content-Creator.Course.Chapter',encrypt_decrypt('encrypt', $data->id)) }}" class="btn-go">
                            <img src="{!! assets('assets/superadmin-images/arrow-right.svg') !!}">
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="pmu-table-pagination">
                {{$course->appends(Request::except('page'))->links('pagination::bootstrap-4')}}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ assets('assets/superadmin-css/course.css') }}">
@endpush