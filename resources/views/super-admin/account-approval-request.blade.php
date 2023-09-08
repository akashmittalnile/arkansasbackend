@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Approval Request')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Account Approval Request</h2>
            </div>
            <div class="pmu-search-filter wd70">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Select Content Creator Type! </option>
                                <option>Permanent Makeup Training</option>
                                <option>Tattooing & Piercing Institute</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="date" class="form-control" name="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="Accountapproval-btn" href="{{ route('SA.ContentCreators') }}">Back</a>
                        </div>
                    </div>
                </div>
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
                                            <img src="{!! url('assets/superadmin-images/no-image.png') !!}">
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
                                    <div class="creator-table-value">Pending For Approval</div>
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
