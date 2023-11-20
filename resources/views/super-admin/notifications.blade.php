@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Notifications')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Manage Notifications</h2>
            </div>
            <div class="pmu-search-filter wd60">
                <form action="">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-group search-form-group">
                                <input type="text" class="form-control" value="{{ request()->title }}" name="title" placeholder="Search by Title">
                                <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="date" class="form-control" name="date" value="{{ request()->date }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <a class="Create-btn" style="padding: 12px 0px;" href="{{ route('SA.Notifications') }}"><i class="las la-sync"></i></a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="Create-btn" style="padding: 12px 0px;" type="submit">Search</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <a class="Create-btn" style="padding: 13px 0px;" href="{{ route('SA.Create.Notifications') }}">Create New</a>
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

                    @forelse($notify as $key => $val)
                    <div class="col-md-12">
                        <div class="manage-notification-item">
                            <div class="manage-notification-image">
                                <img src="{!! url('/upload/notification/'.$val->image) !!}">
                            </div>

                            <div class="manage-notification-content">
                                <div class="notification-date">Pushed on: {{ date('d M, Y - H:iA', strtotime($val->created_date)) }}</div>
                                <div class="notification-descr">
                                    <h2><img src="{!! url('assets/superadmin-images/notification.svg') !!}">{{ $val->title ?? "NA" }}</h2>
                                    <p>{{ $val->description ?? "NA" }}</p>
                                    <!-- <h3><img src="{!! url('assets/superadmin-images/danger.svg') !!}"> Limited Stock Alert </h3> -->
                                </div>
                                <div class="notification-tag">
                                    <h3>Notification for:</h3>
                                    <div class="tags-list">
                                        <div class="Tags-text">
                                            @if($val->push_target == 1)
                                                Students
                                            @elseif($val->push_target == 2)
                                                @if($val->creators == 'A')
                                                    Content Creator
                                                @else
                                                    @foreach($data = $val->notificationCreator as $key => $item)
                                                        @php $name = $item->user; @endphp
                                                        {{ $name->first_name . ' ' . $name->last_name }} @if(count($data) != $key+1) || @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="creator-table mx-2">
                                <div class="mon-table-box">
                                    <a href="{{ route('SA.Delete.Notifications', encrypt_decrypt('encrypt', $val->id)) }}" onclick="return confirm('Are you sure you want to delete this notification?');" class="btn-go">
                                        <i class="las la-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                        <div>
                            <img src="{{ url('/assets/website-images/nodata.svg') }}" alt="">
                        </div>
                        <div class="font-weight-bold">
                            <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p> 
                        </div>
                    </div>
                    @endforelse

                </div>
                <div class="pmu-table-pagination">
                    {{$notify->appends(Request::except('page'))->links('pagination::bootstrap-4')}}
                </div>
            </div>
        </div>
    </div>
@endsection
