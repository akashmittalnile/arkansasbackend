@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Content Creators')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Content Creators</h2>
            </div>
            <div class="pmu-search-filter wd80">
                <form action="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group search-form-group">
                                <input type="text" class="form-control" name="name"
                                    placeholder="Search by name" value="{{request()->name}}">
                                <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg')!!}"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option @if(request()->status=="") selected @endif value="">Select Account Type!</option>
                                    <option @if(request()->status=="1") selected @endif value="1">Active</option>
                                    <option @if(request()->status=="0") selected @endif value="0">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="add-more py-2" type="">Search</button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
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
                    <tr>
                        <td colspan="11" class="text-center">
                            No record found
                        </td>
                    </tr>
                @elseif(!$users->isEmpty())
                    @foreach($users as $data)
                        <div class="creator-table-item">
                            <div class="creator-table-col-4">
                                <div class="creator-table-content">
                                    <div class="creator-profile-info">
                                        <div class="creator-profile-image">
                                            @if(empty($data->profile_image))
                                            <img src="{!! asset('assets/superadmin-images/no-image.png') !!}">
                                            @else
                                            <img src="{!! url('upload/profile-image/'.$data->profile_image) !!}">
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
                                    <div class="creator-table-value">@if ($data->status) Active @else Pending @endif</div>
                                </div>
                            </div>

                            <div class="creator-table-col-1">
                                <div class="mon-table-box">
                                    <a href="{{ url('super-admin/listed-course/'.encrypt_decrypt('encrypt', $data->id)) }}" class="btn-go">
                                        <img src="{!! url('assets/superadmin-images/arrow-right.svg') !!}">
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
@push('css')
<link rel="stylesheet" href="{{ asset('assets/superadmin-css/course.css') }}">
@endpush
