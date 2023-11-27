@extends('layouts.app-master')
@section('title', 'Permanent Makeup University - Students')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Students</h2>
            </div>
            <div class="pmu-search-filter wd70">
                <form action="">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group search-form-group">
                                <input type="text" class="form-control" name="name"
                                    placeholder="Search by Student name" value="{{ request()->name }}">
                                <span class="search-icon"><img src="{!! assets('assets/superadmin-images/search-icon.svg') !!}"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option value="">Select Account Type!</option>
                                    <option @if(request()->status==1) selected @endif value="1">Active</option>
                                    <option @if(request()->status==2) selected @endif value="2">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <a class="Create-btn" style="padding: 12px 0px;" href="{{ route('Home.students') }}"><i class="las la-sync"></i></a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="Create-btn" style="padding: 12px 0px;" type="submit">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="creator-table-section">
            <div class="creator-table-list">
                @if($datas->isEmpty())
                    <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                        <div>
                            <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                        </div>
                        <div class="font-weight-bold">
                            <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p> 
                        </div>
                    </div>
                @elseif(!$datas->isEmpty())
                    @foreach($datas as $data)
                        <div class="creator-table-item">
                            <div class="creator-table-col-4">
                                <div class="creator-table-content">
                                    <div class="creator-profile-info">
                                        <div class="creator-profile-image">
                                            @if ($data->profile_image!=null && $data->profile_image!="")
                                                <img src="{!! assets('/upload/profile-image/'.$data->profile_image) !!}">
                                            @else
                                                <img src="{!! assets('assets/superadmin-images/no-image.png') !!}">
                                            @endif
                                        </div>
                                        <div class="creator-profile-text">
                                            <h2 class="text-capitalize">{{ $data->first_name ?? "NA" }} {{ $data->last_name ?? "" }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="creator-table-col-3">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">Phone No.</div>
                                    <div class="creator-table-value">{{ $data->phone ?? "NA" }}</div>
                                </div>
                            </div>
                            <div class="creator-table-col-2">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">Email ID</div>
                                    <div class="creator-table-value">{{ $data->email ?? "NA" }}</div>
                                </div>
                            </div>

                            <div class="creator-table-col-2">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">Account Status</div>
                                    <div class="creator-table-value">@if($data->status == 1) Active @else In-active @endif</div>
                                </div>
                            </div>

                            <div class="creator-table-col-1">
                                <div class="mon-table-box">
                                    <a href="{{ route('Home.student.details', encrypt_decrypt('encrypt',$data->id)) }}" class="btn-go">
                                        <img src="{!! assets('assets/superadmin-images/arrow-right.svg') !!}">
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="pmu-table-pagination">
                        {{$datas->appends(Request::except('page'))->links('pagination::bootstrap-4')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
