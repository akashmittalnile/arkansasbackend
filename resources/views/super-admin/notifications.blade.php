@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Notifications')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Manage Notifications</h2>
            </div>
            <div class="pmu-search-filter wd50">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" name="Start Date"
                                placeholder="Enter order ID to get order details">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="date" class="form-control" name="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="Create-btn" href="">Create New</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="manage-notification-item">
                            <div class="manage-notification-image">
                                <img src="{!! url('assets/superadmin-images/p1.jpg') !!}">
                            </div>

                            <div class="manage-notification-content">
                                <div class="notification-date">Pushed on: 06 Dec, 2022 - 09:39Am</div>
                                <div class="notification-descr">
                                    <h2><img src="{!! url('assets/superadmin-images/notification.svg') !!}"> Limited Time Offer - Unbeatable Discounts on
                                        Arkansas Products!</h2>
                                    <p>Hey There, Valued Shopper! We’re Thrilled To Share Some Exciting News With You. The
                                        Arkansas Store Is Offering A Limited-Time Discount On Our Premium Product Range.
                                        Take Advantage Of This Incredible Opportunity To Shop For High-Quality Items At The
                                        Best Rates. Don’t Miss Out On The Chance To Grab Your Favorite Products With Huge
                                        Savings!</p>
                                    <h3><img src="{!! url('assets/superadmin-images/danger.svg') !!}"> Limited Stock Alert </h3>
                                </div>
                                <div class="notification-tag">
                                    <h3>Category:</h3>
                                    <div class="tags-list">
                                        <div class="Tags-text">Students</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="manage-notification-item">
                            <div class="manage-notification-image">
                                <img src="{!! url('assets/superadmin-images/p1.jpg') !!}">
                            </div>

                            <div class="manage-notification-content">
                                <div class="notification-date">Pushed on: 06 Dec, 2022 - 09:39Am</div>
                                <div class="notification-descr">
                                    <h2><img src="{!! url('assets/superadmin-images/notification.svg') !!}"> Limited Time Offer - Unbeatable Discounts on
                                        Arkansas Products!</h2>
                                    <p>Hey There, Valued Shopper! We’re Thrilled To Share Some Exciting News With You. The
                                        Arkansas Store Is Offering A Limited-Time Discount On Our Premium Product Range.
                                        Take Advantage Of This Incredible Opportunity To Shop For High-Quality Items At The
                                        Best Rates. Don’t Miss Out On The Chance To Grab Your Favorite Products With Huge
                                        Savings!</p>
                                    <h3><img src="{!! url('assets/superadmin-images/danger.svg') !!}"> Limited Stock Alert </h3>
                                </div>
                                <div class="notification-tag">
                                    <h3>Category:</h3>
                                    <div class="tags-list">
                                        <div class="Tags-text">Students</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="manage-notification-item">
                            <div class="manage-notification-image">
                                <img src="{!! url('assets/superadmin-images/p1.jpg') !!}">
                            </div>

                            <div class="manage-notification-content">
                                <div class="notification-date">Pushed on: 06 Dec, 2022 - 09:39Am</div>
                                <div class="notification-descr">
                                    <h2><img src="{!! url('assets/superadmin-images/notification.svg') !!}"> Limited Time Offer - Unbeatable Discounts on
                                        Arkansas Products!</h2>
                                    <p>Hey There, Valued Shopper! We’re Thrilled To Share Some Exciting News With You. The
                                        Arkansas Store Is Offering A Limited-Time Discount On Our Premium Product Range.
                                        Take Advantage Of This Incredible Opportunity To Shop For High-Quality Items At The
                                        Best Rates. Don’t Miss Out On The Chance To Grab Your Favorite Products With Huge
                                        Savings!</p>
                                    <h3><img src="{!! url('assets/superadmin-images/danger.svg') !!}"> Limited Stock Alert </h3>
                                </div>
                                <div class="notification-tag">
                                    <h3>Category:</h3>
                                    <div class="tags-list">
                                        <div class="Tags-text">Students</div>
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
