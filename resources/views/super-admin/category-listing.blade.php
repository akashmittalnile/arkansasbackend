@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Manage Category')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Manage Category</h2>
            </div>
            <div class="pmu-search-filter wd80">
                <form action="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group search-form-group">
                                <input type="text" placeholder="Search by Category Name" name="name" class="form-control" value="{{ request()->name ?? '' }}">
                                <span class="search-icon"><img src="{!! assets('assets/superadmin-images/search-icon.svg') !!}"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option @if(request()->status=="") selected @endif value="">Select Category Status</option>
                                    <option @if(request()->status=="1") selected @endif value="1">Active</option>
                                    <option @if(request()->status=="0") selected @endif value="0">In-Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <a class="Accountapproval-btn" style="padding: 12px 0px;" href="{{ route('SA.Category') }}"><i class="las la-sync"></i></a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="Accountapproval-btn" style="padding: 12px 0px;" type="submit">Search</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <a class="Accountapproval-btn" style="padding: 13.8px 0px;" href="{{ route('SA.AddCategory') }}">Add Category</a>
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
                        <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                    </div>
                    <div class="font-weight-bold">
                        <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p> 
                    </div>
                </div>
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
                                    <div class="creator-table-value center-div"><img style="object-fit: cover; object-position: center; border: 2px solid #261313; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;" width="70" height=70" src="{{ assets('upload/category-image/'.$data->icon)}}" ></img></div>
                                </div>
                            </div>

                            <div class="creator-table-col-2">
                                <div class="creator-table-box">
                                    <div class="creator-table-text">Category Status</div>
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
                    <div class="pmu-table-pagination">
                        {{$datas->appends(Request::except('page'))->links('pagination::bootstrap-4')}}
                    </div>
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
