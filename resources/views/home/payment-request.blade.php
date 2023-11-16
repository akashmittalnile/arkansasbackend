@extends('layouts.app-master')
@section('title', 'Permanent Makeup University - Payment Request')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Payment Requests</h2>
        </div>
        <div class="pmu-search-filter wd80">
            <form action="">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <select name="status" class="form-control" id="">
                                <option @if(request()->status == "") selected @endif value="">Select status</option>
                                <option @if(request()->status == "0") selected @endif value="0">Pending</option>
                                <option @if(request()->status == "1") selected @endif value="1">Accepted</option>
                                <option @if(request()->status == "2") selected @endif value="2">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="date" name="order_date" class="form-control" value="{{ request()->order_date }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="download-btn" style="padding: 12px 0px;" type="">Search</button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <a href="{{ route('Home.payment.request') }}" style="padding: 12px 0px;" class="download-btn"><i class="las la-sync"></i></a>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <a class="download-btn" style="padding: 13.5px 0px;" href="{{ route('Home.earnings') }}">Back</a>
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
        <div class="pmu-table-content">
            <div class="pmu-card-table pmu-table-card">
                <div class="pmu-table-filter">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="pmu-table-info-card">
                                <h2>Total Earning</h2>
                                <div class="pmu-table-value">${{ number_format((float)$amount, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="pmu-table-info-card">
                                <h2>My Wallet</h2>
                                <div class="pmu-table-value">${{ number_format((float)$mymoney['balance'], 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="pmu-table-form-card">
                                <button class="download-btn" data-bs-toggle="modal" data-bs-target="#Editcourses">Create Payout Request</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Requested Amount</th>
                            <th>Requested Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payment as $index => $val)
                        <tr>
                            <td><span class="sno">{{ $index+1 }}</span> </td>
                            <td>${{ number_format((float)$val->balance, 2) }}</td>
                            <td>{{ date('d M, Y H:iA', strtotime($val->added_date)) }}</td>
                            <td>
                                @if($val->status == 0) Pending
                                @elseif($val->status == 1) Approved
                                @elseif($val->status == 2) Rejected
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr class="text-center">
                            <td colspan="8">No record found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pmu-table-pagination">
                    {{$payment->appends(Request::except('page'))->links('pagination::bootstrap-4')}}
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal ro-modal fade" id="Editcourses" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="PaymentRequest-form-info">
                    <h2>Payout Request</h2>
                    <div class="row">
                        <!-- <div class="col-md-12">
                            <div class="modal-settled-info-text">
                                <img src="{!! url('assets/superadmin-images/dollar-circle.svg')!!}">
                                <p>Total Course Payment Received</p>
                                <h4>3207.55</h4>
                            </div>
                        </div> -->
                        <form method="POST" action="{{ route('Home.payment.request.store') }}" id="amount-form" autocomplete="off"> @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="number" name="amount" class="form-control" placeholder="Enter amount here..." min="1">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
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
<style>
    a:hover{
        color: #fff;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $.validator.addMethod("greaterThan", function (value) {
                return parseFloat(value, 2) <= parseFloat("{{$amount-$requestedAmount ?? 0}}", 2);
        }, "Amount must be equal or smaller than the {{$amount-$requestedAmount ?? 0}}");
        $('#amount-form').validate({
            rules: {
                amount: {
                    required: true,
                    greaterThan: true
                }
            },
            messages: {
                amount: {
                    required: 'Please enter amount',
                }
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