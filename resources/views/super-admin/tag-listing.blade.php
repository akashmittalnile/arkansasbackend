@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Tags')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Tags</h2>
        </div>
        <div class="pmu-search-filter wd70">
            <form action="">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <input type="text" placeholder="Search by Tag Name" name="name" class="form-control" value="{{ request()->name ?? '' }}">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" name="status">
                                <option @if(request()->status=="") selected @endif value="">Select Tag Status</option>
                                <option @if(request()->status=="1") selected @endif value="1">Active</option>
                                <option @if(request()->status=="0") selected @endif value="0">In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <a class="Accountapproval-btn" style="padding: 12px 0px;" href="{{ route('SA.TagListing') }}"><i class="las la-sync"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="Accountapproval-btn" style="padding: 12px 0px;" type="submit">Search</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a class="Accountapproval-btn" data-bs-toggle="modal" data-bs-target="#Addcourses">Add Tag</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('layouts.partials.messages')
    <div class="creator-table-section">
        <div class="creator-table-list">
            @if($datas->isEmpty())
            <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                <div>
                    <img src="{{ url('/assets/website-images/nodata.svg') }}" alt="">
                </div>
                <div class="font-weight-bold">
                    <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p> 
                </div>
            </div>
            @elseif(!$datas->isEmpty())
                @foreach($datas as $data)
                <div class="creator-table-item">
                    <div class="creator-table-col-4">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Tag Name</div>
                            <div class="creator-table-value">{{ $data->tag_name }}</div>
                        </div>
                    </div>

                    <div class="creator-table-col-2">
                        <div class="creator-table-box">
                            <div class="creator-table-text">Tag Status</div>
                            <div class="creator-table-value">@if ($data->status) Active @else Inactive @endif</div>
                        </div>
                    </div>

                    <div class="creator-table-col-1">
                        <div class="mon-table-box">
                            <a onclick='accept_order("{{ $data->tag_name }}","{{ $data->status }}","{{ $data->id }}","{{ $data->type }}")' class="btn-go">
                                {{-- <img src="{!! url('assets/superadmin-images/arrow-right.svg') !!}"> --}}
                                <i class="las la-edit"></i>
                            </a>
                        </div>
                    </div>

                    <div class="creator-table-col-1">
                        <div class="mon-table-box">
                            <a href="{{ url('super-admin/delete-tags/'.encrypt_decrypt('encrypt', $data->id)) }}" onclick="return confirm('Are you sure you want to delete this tag?');" class="btn-go">
                                <i class="las la-trash-alt"></i>
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

<!-- Add Tag Model  -->
<div class="modal ro-modal fade" id="Addcourses" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="PaymentRequest-form-info">
                    <h2>Create Tags</h2>
                    <div class="row">
                        <form method="POST" action="{{ route('SA.SaveTag') }}" id="create-tags">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="tag_name" placeholder="Enter Tag Name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="type" id="type" class="form-control">
                                        <option value="" selected>Select Tags For</option>
                                        <option value="1">Course</option>
                                        <option value="2">Product</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button class="save-btn" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Tag Model  -->
<div class="modal ro-modal fade" id="Editcourses" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="PaymentRequest-form-info">
                    <h2>Update Tags</h2>
                    <div class="row">
                        <form method="POST" action="{{ route('SA.UpdateTag') }}" id="update-tags">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" class="form-control" name="tag_id" id="tag_id" value="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="tag_name" id="tag_name_value" placeholder="Enter Tag Name" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="status" id="status" class="form-control form-field-user-edit">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="type" id="tag_type" class="form-control">
                                        <option value="" selected>Select Tags For</option>
                                        <option value="1">Course</option>
                                        <option value="2">Product</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button class="save-btn" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<!-- Show data on edit form -->
<script>
    function accept_order(tag_name, status, id, type) {
        document.getElementById("tag_name_value").value = tag_name;
        document.getElementById("tag_id").value = id;
        document.getElementById("tag_type").value = type;
        var selectedUser = status;
        $('.form-field-user-edit > option[value="' + selectedUser + '"]').prop('selected', true);
        $('#Editcourses').modal('show');

    }
</script>
<script>
    $(document).ready(function() {
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param * 1000000)
        }, 'File size must be less than {0} MB');
        $('#create-tags').validate({
            rules: {
                tag_name: {
                    required: true,
                },
                status: {
                    required: true,
                },
                type: {
                    required: true,
                },
            },
            messages: {
                tag_name: {
                    required: 'Please enter tag name',
                },
                status: {
                    required: 'Please select the status',
                },
                "type": {
                    required: 'Please select the type',
                },
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param * 1000000)
        }, 'File size must be less than {0} MB');
        $('#update-tags').validate({
            rules: {
                tag_name: {
                    required: true,
                },
                status: {
                    required: true,
                },
                type: {
                    required: true,
                },
            },
            messages: {
                tag_name: {
                    required: 'Please enter tag name',
                },
                status: {
                    required: 'Please select the status',
                },
                "type": {
                    required: 'Please select the type',
                },
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });
    });
</script>
@endsection