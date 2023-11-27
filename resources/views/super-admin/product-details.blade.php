@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Product Details')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Product Details</h2>
        </div>
        <div class="pmu-search-filter wd30">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="Create-btn" href="{{ route('SA.Products') }}">Back</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="Create-btn" onclick="return confirm('Are you sure you want to delete this product?');" href="{{ route('SA.Delete.Products', encrypt_decrypt('encrypt',$pro->id)) }}">Delete</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="Create-btn" href="{{ route('SA.Edit.Products', encrypt_decrypt('encrypt',$pro->id)) }}">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pmu-content-list">
        <div class="pmu-content">
            <div class="pmu-overview-section">
                <div class="row g-2">

                    <div class="col-md-4">
                        <div class="pmu-overview-item">
                            <div class="pmu-overview-content">
                                <h2>Total Revenue</h2>
                                <div class="pmu-overview-price">${{ number_format((float) $revenue, 2) }}</div>
                            </div>
                            <div class="pmu-overview-media">
                                <img src="{{ assets('assets/superadmin-images/revenue.svg') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="pmu-overview-item">
                            <div class="pmu-overview-content">
                                <h2>Overall Products Rating</h2>
                                <div class="pmu-overview-rating"><img src="{{ assets('assets/superadmin-images/star.svg') }}">
                                    @if(count($review) != 0) {{number_format($reviewAvg, 1)}}
                                    @else 0.0 @endif
                                </div>
                            </div>
                            <div class="pmu-overview-media">
                                <img src="{{ assets('assets/superadmin-images/rating.svg') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="pmu-overview-item">
                            <div class="pmu-overview-content">
                                <h2>Total Orders Placed</h2>
                                <div class="pmu-overview-value">{{ $nooforder ?? 0 }}</div>
                            </div>
                            <div class="pmu-overview-media">
                                <img src="{{ assets('assets/superadmin-images/totalorders.svg') }}">
                            </div>
                        </div>
                    </div>
 
                    <!-- <div class="col-md-4">
                        <div class="pmu-overview-item">
                            <div class="pmu-overview-content">
                                <h2>Total Orders Placed</h2>
                                <div class="pmu-overview-value">1452</div>
                                <div class="pmu-overview-date">May,2023</div>
                            </div>
                            <div class="pmu-overview-media">
                                <img src="images/OrdersPlaced.svg">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="pmu-overview-item">
                            <div class="pmu-overview-content">
                                <h2>Total Ready To Pick-Up Orders</h2>
                                <div class="pmu-overview-price">$499.00</div>
                                <div class="pmu-overview-date">May,2023</div>
                            </div>
                            <div class="pmu-overview-media">
                                <img src="images/pickedOrders.svg">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="pmu-overview-item">
                            <div class="pmu-overview-content">
                                <h2>Total Picked-Up Successfully</h2>
                                <div class="pmu-overview-price">$499.00</div>
                                <div class="pmu-overview-date">May,2023</div>
                            </div>
                            <div class="pmu-overview-media">
                                <img src="images/pickedsuccessfully.svg">
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>

            <div class="pmu-course-details-item">
                <div class="pmu-course-details-media">
                    @if($cover->attribute_value == '' || $cover->attribute_value == null)
                    <img src="{{ assets('assets/superadmin-images/p2.jpg') }}">
                    @else
                    <img src="{{ assets('upload/products/'.$cover->attribute_value) }}" alt="">
                    @endif
                </div>
                <div class="pmu-course-details-content">
                    <div class="@if($pro->status == 0) coursestatus-unpublish @else coursestatus @endif"><img src="{{ assets('assets/superadmin-images/tick.svg') }}">
                        @if ($pro->status == 0)
                            Unpublished
                        @else
                            Published 
                        @endif
                    </div>
                    <h2>{{ ($pro->name) ? : ''}}</h2>
                    <div class="pmu-course-details-price">${{ number_format($pro->price,2) ? : 0}}</div>
                    <p>{{ $pro->product_desc ?? "NA" }}</p>
                    <div class="course-tag">
                        <h3>Tags:</h3>
                        <div class="tags-list">
                            @foreach($combined as $val)
                                @if($val['selected'])
                                    <div class="Tags-text">{{ $val['name'] }}</div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="pmu-comment-section">
                <div class="pmu-comment-list">
                    <div class="pmu-comment-box-head">
                        <div class="pmu-comment-1">
                            <h1>Rating & Review</h1>
                            @if(count($review) != 0)
                            <div class="pmu-comment-rating"><img src="{{ assets('assets/superadmin-images/star.svg') }}"> {{ number_format($reviewAvg, 1) }}</div>
                            @else
                            <div class="pmu-comment-rating"><img src="{{ assets('assets/superadmin-images/star.svg') }}"> 0.0</div>
                            @endif
                        </div>
                        <div class="pmu-comment-head-action">
                            <!-- <a class="addcomment-btn"><i class="las la-plus"></i> See More Reviews</a> -->
                        </div>
                    </div>

                    @forelse($review as $value)
                    <div class="pmu-comment-item">
                        <div class="pmu-comment-profile">
                            @if($value->profile_image == '' || $value->profile_image == null)
                            <img src="{{ assets('assets/superadmin-images/user.png') }}">
                            @else
                            <img src="{{ assets('upload/products/'.$value->profile_image) }}">
                            @endif
                        </div>
                        <div class="pmu-comment-content">
                            <div class="pmu-comment-head">
                                <h2>{{ $value->first_name ?? "NA" }} {{ $value->last_name ?? "" }}</h2>
                                <div class="pmu-date"><i class="las la-calendar"></i>{{ date('d M, Y, g:iA', strtotime($value->created_date ?? "")) }}</div>
                            </div>
                            <div class="pmu-comment-rating"><img src="{{ assets('assets/superadmin-images/star.svg') }}"> {{ number_format($value->rating,1) ?? "0.0" }}</div>
                            <div class="pmu-comment-descr">{{ $value->review ?? "NA" }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                        <div>
                            <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                        </div>
                        <div class="font-weight-bold">
                            <p class="font-weight-bold" style="font-size: 1.2rem;">No rating & review found</p> 
                        </div>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div>
@endsection