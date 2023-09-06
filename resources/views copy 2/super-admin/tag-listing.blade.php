@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Content Creators')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Content Creators</h2>
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
                            <select class="form-control">
                                <option>Select Account Type!</option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="Accountapproval-btn" data-bs-toggle="modal" data-bs-target="#Addcourses">Add Tag</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.partials.messages')
        <div class="creator-table-section">
            <div class="creator-table-list">
                @if($datas->isEmpty())
                    <tr>
                        <td colspan="11" class="text-center">
                            No record found
                        </td>
                    </tr>
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
                                    <div class="creator-table-text">Account Status</div>
                                    <div class="creator-table-value">@if ($data->status) Active @else Inactive @endif</div>
                                </div>
                            </div>

                            <div class="creator-table-col-1">
                                <div class="mon-table-box">
                                    <a onclick='accept_order("{{ $data->tag_name }}","{{ $data->status }}","{{ $data->id }}")' class="btn-go">
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
                            <form method="POST" action="{{ route('SA.SaveTag') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="tag_name" placeholder="Enter Tag Name" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="" selected>Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
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
                            <form method="POST" action="{{ route('SA.UpdateTag') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" class="form-control" name="tag_id" id="tag_id" value="">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="tag_name" id="tag_name_value" placeholder="Enter Tag Name" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="status" id="status" class="form-control form-field-user-edit" required>
                                            <option value="">Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
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

    <!-- Show data on edit form -->
    <script>
        function accept_order(tag_name,status,id) {
            document.getElementById("tag_name_value").value = tag_name;
            document.getElementById("tag_id").value = id;
            var selectedUser = status;
            $('.form-field-user-edit > option[value="'+ selectedUser +'"]').prop('selected', true);
            $('#Editcourses').modal('show');

        }
    </script>
@endsection
