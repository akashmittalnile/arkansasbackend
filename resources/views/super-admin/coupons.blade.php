@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Coupons')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Manage Coupon</h2>
        </div>
        <div class="pmu-search-filter wd60">
            <form action="">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" value="{{ request()->coupon_code }}" name="coupon_code" placeholder="Search by Coupon Code">
                            <span class="search-icon"><img src="{!! url('assets/superadmin-images/search-icon.svg') !!}"></span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <a class="Create-btn" style="padding: 12px 0px;" href="{{ route('SA.Coupons') }}"><i class="las la-sync"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="Create-btn" style="padding: 12px 0px;" type="submit">Search</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="Create-btn" data-bs-toggle="modal" data-bs-target="#CreateCoupon">Create
                                New Coupon</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a class="Create-btn" href="{{ route('SA.Products') }}">Back</a>
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
        <div class="pmu-content">
            <div class="row">

                @forelse($coupon as $val)
                <div class="col-md-6">
                    <div class="manage-coupon-card">
                        <div class="manage-coupon-content">
                            <div class="coupon-code-value">{{ $val->coupon_code ?? "NA" }}</div>
                            <p>{{ $val->description ?? "NA" }}</p>
                            <div class="manage-coupon-list">
                                <ul>
                                    <li><span>Start From:</span> {{ date('d M, Y', strtotime($val->created_at)) }}</li>
                                    <li><span>Valid Upto:</span> {{ date('d M, Y', strtotime($val->coupon_expiry_date)) }}</li>
                                    <li>
                                        <span>Discount type:</span>
                                        @if($val->coupon_discount_type == 1) Flat &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                                        @elseif($val->coupon_discount_type == 2) Percentage
                                        @endif
                                    </li>
                                    <li>
                                        <span>Discount Value:</span> 
                                        @if($val->coupon_discount_type == 1)$@endif{{ $val->coupon_discount_amount ?? 0 }}@if($val->coupon_discount_type == 2)%@endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="manage-point-card-action">
                            <a class="edit-btn" data-id="{{encrypt_decrypt('encrypt', $val->id)}}" href="javascript:void(0)"><img src="{!! url('assets/superadmin-images/edit-2.svg') !!}"></a>
                            <a class="delete-btn" onclick="return confirm('Are you sure you want to delete this coupon?');" href="{{ route('SA.Coupon.Delete', encrypt_decrypt('encrypt', $val->id)) }}"><img src="{!! url('assets/superadmin-images/trash.svg') !!}"></a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                    <div>
                        <img src="{{ url('/assets/website-images/nodata.svg') }}" alt="">
                    </div>
                    <div class="font-weight-bold">
                        <p class="font-weight-bold" style="font-size: 1.2rem;">No record found </p>
                    </div>
                </div>
                @endforelse

            </div>
            <div class="pmu-table-pagination">
                {{ $coupon->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Create Coupon -->
<div class="modal lm-modal fade" id="CreateCoupon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="CreateCoupon-modal-form">
                    <h2>Add Coupon</h2>
                    <form action="{{ route('SA.Store.Coupon') }}" method="POST" id="addCoupon">@csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Coupon Code" id="code" name="code">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Coupon Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="type" style="padding: 13px 10px;">
                                        <option value="">Coupon Type</option>
                                        <option value="1">Flat</option>
                                        <option value="2">Percentage</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Minumum Order Amount <span class="text-danger">*</span></label>
                                    <input type="number" min="0.1" step="0.1" class="form-control" placeholder="Minumum Order Amount" name="min_amount">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Coupon Discount Value <span class="text-danger">*</span></label>
                                    <input type="number" min="0.1" step="0.1" class="form-control" placeholder="Coupon Discount Amount" name="amount">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Coupon Available for ? <span class="text-danger">*</span></label>
                                    <select class="form-control" name="object_type" disabled style="padding: 13px 10px;">
                                        <option value="">Coupon Available for ?</option>
                                        <option value="1">Courses</option>
                                        <option value="2" selected>Products</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Valid Upto <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" placeholder="" name="date" min="{{ date('Y-m-d', strtotime('+3days')) }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" placeholder="Coupon Description (ex. Get 20% extra amount on timeshare points rent out" name="description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button class="save-btn" type="submit">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Coupon -->
<div class="modal lm-modal fade" id="editCoupon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="CreateCoupon-modal-form">
                    <h2>Edit Coupon</h2>
                    <form action="{{ route('SA.Update.Coupon') }}" method="POST" id="editCouponForm">@csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Coupon Code" id="codeEdit" name="code">
                                    <input type="hidden" name="id" id="coupon_id_input" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Coupon Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="type" style="padding: 13px 10px;" id="typeEdit">
                                        <option value="">Coupon Type</option>
                                        <option value="1">Flat</option>
                                        <option value="2">Percentage</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Minumum Order Amount <span class="text-danger">*</span></label>
                                    <input type="number" min="0.1" step="0.1" id="minAmountEdit" class="form-control" placeholder="Minumum Order Amount" name="min_amount">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Coupon Discount Value <span class="text-danger">*</span></label>
                                    <input type="number" min="0.1" step="0.1" id="amountEdit" class="form-control" placeholder="Coupon Discount Amount" name="amount">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Coupon Available for ? <span class="text-danger">*</span></label>
                                    <select class="form-control" name="object_type" disabled style="padding: 13px 10px;">
                                        <option value="">Coupon Available for ?</option>
                                        <option value="1">Courses</option>
                                        <option value="2" selected>Products</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Valid Upto <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" placeholder="" id="dateEdit" name="date" min="{{ date('Y-m-d', strtotime('+3days')) }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" id="descEdit" placeholder="Coupon Description (ex. Get 20% extra amount on timeshare points rent out" name="description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button class="save-btn" type="submit">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {

        $(document).on('click', '.edit-btn', function(){
            let id = $(this).attr('data-id');
            $.ajax({
                url: arkansasUrl + '/super-admin/get-coupon-details',
                method: 'GET',
                data: {
                    id
                },
                success: function (data) {
                    if (data.status) {
                        $("input[name='id']").val(id);
                        $("#codeEdit").val(data.data.coupon_code);
                        $("#typeEdit").val(data.data.coupon_discount_type);
                        $("#minAmountEdit").val(data.data.min_order_amount);
                        $("#amountEdit").val(data.data.coupon_discount_amount);
                        $("#dateEdit").val(data.data.coupon_expiry_date);
                        $("#descEdit").val(data.data.description);
                        $("#editCoupon").modal('show');
                    }
                }
            });
        });

        $("input[name='code']").keyup(function(){
            $(this).val(function(){
                return this.value.toUpperCase();
            })
        })

        $('#addCoupon').validate({
            rules: {
                code: {
                    required: true,
                    minlength: 4,
                    remote: {
                        type: 'get',
                        url: arkansasUrl + '/check_coupon_code',
                        data: {
                            'code': function () { return $("#code").val(); }
                        },
                        dataType: 'json'
                    }
                },
                type: {
                    required: true,
                },
                object_type: {
                    required: true,
                },
                amount: {
                    required: true,
                },
                min_amount: {
                    required: true,
                },
                date: {
                    required: true,
                },
            },
            messages: {
                code: {
                    required: 'Please enter coupon code'
                },
                type: {
                    required: 'Please select the coupon type'
                },
                object_type: {
                    required: 'Please select the coupon available',
                },
                amount: {
                    required: 'Please enter coupon discount value',
                },
                min_amount: {
                    required: 'Please enter minimum order amount',
                },
                date: {
                    required: 'Please enter coupon valid upto date',
                },
            },
            submitHandler: function(form) {
                // This function will be called when the form is valid and ready to be submitted
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

        $('#editCouponForm').validate({
            rules: {
                code: {
                    required: true,
                    minlength: 4,
                    remote: {
                        type: 'get',
                        url: arkansasUrl + '/check_coupon_code',
                        data: {
                            'code': function () { return $("#codeEdit").val(); },
                            'coupon_id': function () { return $("#coupon_id_input").val(); }
                        },
                        dataType: 'json'
                    }
                },
                type: {
                    required: true,
                },
                object_type: {
                    required: true,
                },
                amount: {
                    required: true,
                },
                min_amount: {
                    required: true,
                },
                date: {
                    required: true,
                },
            },
            messages: {
                code: {
                    required: 'Please enter coupon code'
                },
                type: {
                    required: 'Please select the coupon type'
                },
                object_type: {
                    required: 'Please select the coupon available',
                },
                amount: {
                    required: 'Please enter coupon discount value',
                },
                min_amount: {
                    required: 'Please enter minimum order amount',
                },
                date: {
                    required: 'Please enter coupon valid upto date',
                },
            },
            submitHandler: function(form) {
                // This function will be called when the form is valid and ready to be submitted
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
    })
</script>
@endsection