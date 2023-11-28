@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Content Creators')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Content Creators</h2>
            </div>
            <div class="pmu-search-filter wd80">
                <form action="">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-group search-form-group">
                                <input type="text" class="form-control" name="name"
                                    placeholder="Search by Content Creator Name" value="{{request()->name}}">
                                <span class="search-icon"><img src="{!! assets('assets/superadmin-images/search-icon.svg')!!}"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option @if(request()->status=="") selected @endif value="">Select Account Status</option>
                                    <option @if(request()->status=="1") selected @endif value="1">Active</option>
                                    <option @if(request()->status=="2") selected @endif value="2">In-Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <a class="Accountapproval-btn" style="padding: 13px 20px;" href="{{ route('SA.ContentCreators') }}"><i class="las la-sync"></i></a>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button class="add-more" style="padding: 8px 14px;" type="">Search</button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group ml-2">
                                <a class="Accountapproval-btn" href="{{ route('SA.AccountApprovalRequest') }}">Account approval Request</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="creator-table-section">
            <div class="creator-table-list">
                @if($users->isEmpty())
                <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                    <div>
                        <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                    </div>
                    <div class="font-weight-bold">
                        <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p> 
                    </div>
                </div>
                @elseif(!$users->isEmpty())
                    @foreach($users as $data)
                        <div class="creator-table-item">
                            <div class="creator-table-col-4">
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
                                            <h2>{{ ucfirst($data->first_name) }} {{ ucfirst($data->last_name) }}</</h2>
                                            <!-- <p>{{ $data->email }}</p> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="creator-table-col-3">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">{{ $data->email }}</div>
                                    <!-- <div class="creator-table-value">{{ $data->email }}</div> -->
                                </div>
                            </div>
                            <div class="creator-table-col-2">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">Creator Type</div>
                                    <div class="creator-table-value">@if($data->CreatorType == '1') Permanent Makeup Training @elseif($data->CreatorType == '2') Tattooing & Piercing Institute @endif</div>
                                </div>
                            </div>

                            <div class="creator-table-col-2">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">Account Status</div>
                                    <div class="creator-table-value">@if ($data->status==1) Active @elseif($data->status == 2) In-Active @endif</div>
                                </div>
                            </div>

                            <div class="creator-table-col-1">
                                <div class="mon-table-box">
                                    <a href="{{ url('super-admin/listed-course/'.encrypt_decrypt('encrypt', $data->id)) }}" class="btn-go">
                                        <img src="{!! assets('assets/superadmin-images/arrow-right.svg') !!}">
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="pmu-table-pagination">
                        {{$users->appends(Request::except('page'))->links('pagination::bootstrap-4')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ assets('assets/superadmin-css/course.css') }}">
@endpush
