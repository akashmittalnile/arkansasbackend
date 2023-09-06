@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Coupons')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Manage Coupon</h2>
            </div>
            <div class="pmu-search-filter wd40">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a class="Create-btn" href="{{ route('SA.Products') }}">Back</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <a class="Create-btn" data-bs-toggle="modal" data-bs-target="#CreateCoupon">Create
                                New Coupon</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="manage-coupon-card">
                            <div class="manage-coupon-content">
                                <div class="coupon-code-value">TIMESRE50</div>
                                <p>Get 50% extra rate in point rent out with Marriott points</p>
                                <div class="manage-coupon-list">
                                    <ul>
                                        <li><span>Start From:</span> 29 March 2023</li>
                                        <li><span>Valid Upto:</span> 29 March 2024</li>
                                        <li><span>Coupon for Developer:</span> Marriott</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="manage-point-card-action">
                                <a class="edit-btn" data-bs-toggle="modal" data-bs-target="#editCoupon"><img
                                        src="{!! url('assets/superadmin-images/edit-2.svg') !!}"></a>
                                <a class="delete-btn" href="#"><img src="{!! url('assets/superadmin-images/trash.svg') !!}"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="manage-coupon-card">
                            <div class="manage-coupon-content">
                                <div class="coupon-code-value">TIMESRE50</div>
                                <p>Get 50% extra rate in point rent out with Marriott points</p>
                                <div class="manage-coupon-list">
                                    <ul>
                                        <li><span>Start From:</span> 29 March 2023</li>
                                        <li><span>Valid Upto:</span> 29 March 2024</li>
                                        <li><span>Coupon for Developer:</span> Marriott</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="manage-point-card-action">
                                <a class="edit-btn" data-bs-toggle="modal" data-bs-target="#editCoupon"><img
                                        src="{!! url('assets/superadmin-images/edit-2.svg') !!}"></a>
                                <a class="delete-btn" href="#"><img src="{!! url('assets/superadmin-images/trash.svg') !!}"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="manage-coupon-card">
                            <div class="manage-coupon-content">
                                <div class="coupon-code-value">TIMESRE50</div>
                                <p>Get 50% extra rate in point rent out with Marriott points</p>
                                <div class="manage-coupon-list">
                                    <ul>
                                        <li><span>Start From:</span> 29 March 2023</li>
                                        <li><span>Valid Upto:</span> 29 March 2024</li>
                                        <li><span>Coupon for Developer:</span> Marriott</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="manage-point-card-action">
                                <a class="edit-btn" data-bs-toggle="modal" data-bs-target="#editCoupon"><img
                                        src="{!! url('assets/superadmin-images/edit-2.svg') !!}"></a>
                                <a class="delete-btn" href="#"><img src="{!! url('assets/superadmin-images/trash.svg') !!}"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="manage-coupon-card">
                            <div class="manage-coupon-content">
                                <div class="coupon-code-value">TIMESRE50</div>
                                <p>Get 50% extra rate in point rent out with Marriott points</p>
                                <div class="manage-coupon-list">
                                    <ul>
                                        <li><span>Start From:</span> 29 March 2023</li>
                                        <li><span>Valid Upto:</span> 29 March 2024</li>
                                        <li><span>Coupon for Developer:</span> Marriott</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="manage-point-card-action">
                                <a class="edit-btn" data-bs-toggle="modal" data-bs-target="#editCoupon"><img
                                        src="{!! url('assets/superadmin-images/edit-2.svg') !!}"></a>
                                <a class="delete-btn" href="#"><img src="{!! url('assets/superadmin-images/trash.svg') !!}"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Coupon -->
    <div class="modal lm-modal fade" id="CreateCoupon" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="CreateCoupon-modal-form">
                        <h2>Add Coupon</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Coupon Name (EX. TIMESHARE20)"
                                        value="TIMESRE50">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Coupon Type</option>
                                        <option>Show all</option>
                                        <option selected="">Flat</option>
                                        <option selected="">Price</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Created Coupon Available for?</option>
                                        <option selected="">Courses</option>
                                        <option>Products</option>
                                        <option>Both</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Add Value" value="50">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" placeholder="">
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Select Content Creator</option>
                                        <option>Show all</option>
                                        <option selected="">Ramdas Rastogi</option>
                                        <option>Yuvraj Tak</option>
                                        <option>Bahugandha Sihan</option>
                                        <option>Tarangini Bapatla</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Select Products</option>
                                        <option>Show all</option>
                                        <option selected="">Marriott</option>
                                        <option>Westin</option>
                                        <option>Holiday Inn</option>
                                        <option>Wyndham</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Coupon Description (ex. Get 20% extra amount on timeshare points rent out"
                                        value="">Get 50% extra rate in point rent out with Marriott points</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button class="save-btn">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Coupon -->
    <div class="modal lm-modal fade" id="editCoupon" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="CreateCoupon-modal-form">
                        <h2>Add Coupon</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Coupon Name (EX. TIMESHARE20)"
                                        value="TIMESRE50">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Coupon Type</option>
                                        <option>Show all</option>
                                        <option selected="">Flat</option>
                                        <option selected="">Price</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Created Coupon Available for?</option>
                                        <option selected="">Courses</option>
                                        <option>Products</option>
                                        <option>Both</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Add Value" value="50">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" placeholder="">
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Select Content Creator</option>
                                        <option>Show all</option>
                                        <option selected="">Ramdas Rastogi</option>
                                        <option>Yuvraj Tak</option>
                                        <option>Bahugandha Sihan</option>
                                        <option>Tarangini Bapatla</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Select Products</option>
                                        <option>Show all</option>
                                        <option selected="">Marriott</option>
                                        <option>Westin</option>
                                        <option>Holiday Inn</option>
                                        <option>Wyndham</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Coupon Description (ex. Get 20% extra amount on timeshare points rent out"
                                        value="">Get 50% extra rate in point rent out with Marriott points</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button class="save-btn">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
