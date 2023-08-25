@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Products')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Manage Products</h2>
            </div>
            <div class="pmu-search-filter wd80">
                <div class="row g-2">
                    <div class="col-md-2">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" name="Start Date"
                                placeholder="Enter order ID to get order details">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Select Products Type!</option>
                                <option>Published</option>
                                <option>Deleted</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a class="Create-btn" href="">New Products</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="Create-btn" href="coupon.html">manage Coupon</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="Create-btn" href="">View Product Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="pmu-course-item">
                            <div class="pmu-course-media">
                                <a href="products-details.html">
                                    <img src="{!! url('assets/superadmin-images/p2.jpg') !!}">
                                </a>
                            </div>
                            <div class="pmu-course-content">
                                <div class="coursestatus"><img src="{!! url('assets/superadmin-images/tick.svg') !!}">Published</div>
                                <h2>O’Reilly’s tattoo machine Motor</h2>
                                <div class="pmu-course-price">$499.00</div>
                                <p>Tattooing has been done as a decorative practice since ancient times. It’s now also being
                                    used for some cosmetic medical procedures and for permanent makeup applications. </p>
                                <div class="notification-tag">
                                    <h3>Course Tags:</h3>
                                    <div class="tags-list">
                                        <div class="Tags-text">Tattoo Course </div>
                                        <div class="Tags-text">Body Piercing </div>
                                        <div class="Tags-text">Tattoo </div>
                                        <div class="Tags-text">Tattoos 2023 </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="pmu-course-item">
                            <div class="pmu-course-media">
                                <a href="products-details.html">
                                    <img src="{!! url('assets/superadmin-images/p1.jpg') !!}">
                                </a>
                            </div>
                            <div class="pmu-course-content">
                                <div class="coursestatus"><img src="{!! url('assets/superadmin-images/tick.svg') !!}">Published</div>
                                <h2>O’Reilly’s tattoo machine Motor</h2>
                                <div class="pmu-course-price">$499.00</div>
                                <p>Tattooing has been done as a decorative practice since ancient times. It’s now also being
                                    used for some cosmetic medical procedures and for permanent makeup applications. </p>
                                <div class="notification-tag">
                                    <h3>Course Tags:</h3>
                                    <div class="tags-list">
                                        <div class="Tags-text">Tattoo Course </div>
                                        <div class="Tags-text">Body Piercing </div>
                                        <div class="Tags-text">Tattoo </div>
                                        <div class="Tags-text">Tattoos 2023 </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="pmu-course-item">
                            <div class="pmu-course-media">
                                <a href="products-details.html">
                                    <img src="{!! url('assets/superadmin-images/p2.jpg') !!}">
                                </a>
                            </div>
                            <div class="pmu-course-content">
                                <div class="coursestatus"><img src="{!! url('assets/superadmin-images/tick.svg') !!}">Published</div>
                                <h2>O’Reilly’s tattoo machine Motor</h2>
                                <div class="pmu-course-price">$499.00</div>
                                <p>Tattooing has been done as a decorative practice since ancient times. It’s now also being
                                    used for some cosmetic medical procedures and for permanent makeup applications. </p>
                                <div class="notification-tag">
                                    <h3>Course Tags:</h3>
                                    <div class="tags-list">
                                        <div class="Tags-text">Tattoo Course </div>
                                        <div class="Tags-text">Body Piercing </div>
                                        <div class="Tags-text">Tattoo </div>
                                        <div class="Tags-text">Tattoos 2023 </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
