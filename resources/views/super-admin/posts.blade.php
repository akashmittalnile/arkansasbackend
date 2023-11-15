@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Pages')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Posts</h2>
        </div>
        <div class="pmu-search-filter wd80">
            <form action="">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" name="title" placeholder="Search by Title" value="{{ request()->title }}">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group search-form-group">
                            <select name="status" class="form-control">
                                <option @if(request()->status == "") selected @endif value="">Select Status</option>
                                <option @if(request()->status == "1") selected @endif value="1">Active</option>
                                <option @if(request()->status == "0") selected @endif value="0">In-active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <a href="{{ route('SA.Posts') }}" style="padding: 12px 0px;" class="download-btn"><i class="las la-sync"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="download-btn" style="padding: 12px 0px;" type="">Search</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a class="download-btn" style="padding: 14px 0px;" href="{{ route('SA.Create.Post') }}">Create Post</a>
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
        <div class="pmu-table-content">
            <div class="pmu-card-table pmu-table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th style="width: 15%;">Title</th>
                            <th style="width: 60%;">Description</th>
                            <th>Status</th>
                            <th class="text-center" colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $index => $val)
                        <tr>
                            <td><span class="sno">{{ $index+1 }}</span> </td>
                            <td class="text-capitalize">{{ $val->title ?? "NA" }}</td>
                            <td>{!! $val->description ?? "NA" !!}</td>
                            <td>
                                @if($val->status == 0) In-active
                                @elseif($val->status == 1) Active
                                @endif
                            </td>
                            <td>
                                <a title="Edit Post" href="{{ route('SA.Edit.Post', encrypt_decrypt('encrypt', $val->id)) }}" style="padding: 12px 0px;" class="download-btn"><i class="las la-edit"></i></a>
                            </td>
                            <td>
                                <a onclick="return confirm('Are you sure you want to delete this post?');" title="Delete Post" href="{{ route('SA.Delete.Post', encrypt_decrypt('encrypt', $val->id)) }}" style="padding: 12px 0px;" class="download-btn"><i class="las la-trash"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr class="text-center">
                            <td colspan="5">No record found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pmu-table-pagination">
                    {{$pages->appends(Request::except('page'))->links('pagination::bootstrap-4')}}
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    a:hover {
        color: #fff;
    }
</style>
@endsection