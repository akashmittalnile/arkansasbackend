@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Manage Category')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Manage Category</h2>
            </div>
            <div class="pmu-search-filter wd70">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">

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
                            <a class="Accountapproval-btn" href="{{ route('SA.AddCategory') }}">Add Category</a>
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
                    <?php $s_no = 1;?>
                    @foreach($datas as $data)
                        <div class="creator-table-item">
                            <div class="creator-table-col-2">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">S.No</div>
                                    <div class="creator-table-value">{{ $s_no }}</div>
                                </div>
                            </div>

                            <div class="creator-table-col-3">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">Category Name</div>
                                    <div class="creator-table-value">{{ $data->name }}</div>
                                </div>
                            </div>

                            <div class="creator-table-col-3">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">Category Image</div>
                                    <div class="creator-table-value center-div"><img style="object-fit: cover; object-position: center; border: 2px solid #261313; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;" width="70" height=70" src="{{ url('upload/category-image/'.$data->icon)}}" ></img></div>
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
                                    <a href="{{ url('super-admin/edit-category/'.encrypt_decrypt('encrypt',$data->id))}}" class="btn-go">
                                        <i class="las la-edit"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="creator-table-col-1">
                                <div class="mon-table-box">
                                    <a href="{{ url('super-admin/delete-category/'.encrypt_decrypt('encrypt', $data->id)) }}" onclick="return confirm('Are you sure you want to delete this category?');" class="btn-go">
                                        <i class="las la-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php $s_no++;?>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Show data on edit form -->
    <script>
        function accept_value(tag_name,status,id) {
            document.getElementById("tag_name_value").value = tag_name;
            document.getElementById("tag_id").value = id;
            var selectedUser = status;
            $('.form-field-user-edit > option[value="'+ selectedUser +'"]').prop('selected', true);
            $('#Editcourses').modal('show');

        }
    </script>
@endsection
