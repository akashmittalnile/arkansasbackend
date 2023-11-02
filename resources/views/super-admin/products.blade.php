@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Products')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Manage Products</h2>
            </div>
            <div class="pmu-search-filter wd80">
                <form action="">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-group search-form-group">
                                <input type="text" class="form-control" value="{{ request()->name }}" name="name" placeholder="Search by Product Name">
                                <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option @if(request()->status=="") selected @endif value="">Select Product Type</option>
                                    <option @if(request()->status=="1") selected @endif value="1">Published</option>
                                    <option @if(request()->status=="0") selected @endif value="0">Unpublished</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <a class="Create-btn" style="padding: 12px 0px;" href="{{ route('SA.Products') }}"><i class="las la-sync"></i></a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="Create-btn" style="padding: 12px 0px;" type="submit">Search</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <a class="Create-btn" style="padding: 13.8px 0px;" href="{{ route('SA.AddProduct')}}">Create Products</a>
                            </div>
                        </div>
                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <a class="Create-btn" href="{{ route('SA.Coupons')}}">Manage Coupon</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <a class="Create-btn" href="">View Product Orders</a>
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
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
                            <div class="col-md-4">
                                <div class="pmu-course-item">
                                    <div class="pmu-course-media">
                                        <a href="javascript:void(0)">
                                            <?php
                                                $first_image = \App\Models\ProductAttibutes::where('product_id', $data->id)->first();
                                            ?>
                                                <img src="{!! url('upload/products/'.$first_image->attribute_value) !!}"> 
                                                {{-- <img src="{!! url('assets/superadmin-images/p2.jpg') !!}"> --}}
                                            
                                        </a>
                                    </div>
                                    <div class="pmu-course-content">

                                        <div class="d-flex">
                                            <div class="col-md-2 mb-2">
                                                <a class="Create-btn" href="{{ route('SA.Edit.Products', encrypt_decrypt('encrypt',$data->id)) }}"> <i class="las la-edit"></i></a>
                                            </div>
                                            <div class="col-md-2 mb-2 mx-2">
                                                <a class="Create-btn" onclick="return confirm('Are you sure you want to delete this product?');" href="{{ route('SA.Delete.Products', encrypt_decrypt('encrypt',$data->id)) }}"> <i class="las la-trash"></i></a>
                                            </div>
                                        </div>

                                        <div class="coursestatus"><img src="{!! url('assets/superadmin-images/tick.svg') !!}">
                                            @if ($data->status == 0)
                                                Unpublished
                                            @else
                                                Published 
                                            @endif
                                        </div>

                                        <h2>{{ ($data->name) ? : ''}}</h2>
                                        <div class="pmu-course-price">${{ number_format($data->price,2) ? : 0}}</div>
                                        <p>{{ ($data->product_desc != "" && $data->product_desc != null) ? (strlen($data->product_desc) > 53 ?  substr($data->product_desc, 0, 53)."....." : $data->product_desc) : "NA" }}</p>
                                        {{-- <div class="notification-tag">
                                            <h3>Course Tags:</h3>
                                            <div class="tags-list">
                                                <div class="Tags-text">Tattoo Course </div>
                                            </div>
                                        </div> --}}
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
    </div>
@endsection
