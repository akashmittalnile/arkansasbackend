@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Approval Request')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Account Approval Request</h2>
            </div>
            <div class="pmu-search-filter wd70">
                <form action="">
                    <div class="row">
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
                                    <option @if(request()->status=="0") selected @endif value="0">New Creators</option>
                                    <option @if(request()->status=="3") selected @endif value="3">Rejected Creators</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <a class="Accountapproval-btn" style="padding: 13px 20px;" href="{{ route('SA.AccountApprovalRequest') }}"><i class="las la-sync"></i></a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="Accountapproval-btn" style="padding: 12.5px 41px;" type="">Search</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <a class="Accountapproval-btn" style="padding: 14px 41px;" href="{{ route('SA.ContentCreators') }}">Back</a>
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
                                            <img src="{!! assets('assets/superadmin-images/no-image.png') !!}">
                                        </div>
                                        <div class="creator-profile-text">
                                            <h2>{{ ucfirst($data->first_name) }} {{ ucfirst($data->last_name) }}</h2>
                                            <p>{{ $data->email }}</p>
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
                                    <div class="creator-table-value">@if($data->status == '0') Pending For Approval @elseif($data->status == '3') Rejected @endif</div>
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
                @endif
            </div>
        </div>
    </div>
@endsection
