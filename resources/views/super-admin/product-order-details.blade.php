@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Product Order Details')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <a href="{{ route('SA.Product.Orders') }}" class="newcourse-btn">Back</a>
        </div>
        <div class="pmu-search-filter wd20">
            <div class="row g-2">
                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <select class="form-control">
                            <option>Change Order Status</option>
                            <option>Out for Delivery</option>
                        </select>
                    </div>
                </div> -->

                <div class="col-md-12">
                    <div class="form-group">
                        @if($order->status == 1)
                        <a class="newcourse-btn" href="{{ route('SA.download.invoice', encrypt_decrypt('encrypt',$order->id)) }}" target="_blank" id="invoicePrint">Download Invoice</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pmu-content-list">
        <div class="pmu-content">
            <div class="row">
                <div class="col-md-3">

                    <div class="cart-summary-info">
                        <div class="added-bank-info-card">
                            <div class="added-bank-info-ico">
                                <img width="50" height="40" src="{{ asset('assets/website-images/order.svg') }}">
                            </div>
                            <div class="added-bank-info-text mx-2">
                                <h2>{{ $order->order_number }}</h2>
                                <ul class="added-summary-list d-flex flex-column mt-2" style="gap: 0px">
                                    <li>Order Date: <span>{{ date('d M, Y H:iA', strtotime($order->created_date)) }}</span></li>
                                    <li>Status:
                                        <span>
                                            @if($order->status == 1) Payment Done
                                            @else Payment Pending
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="added-bank-action">
                                <a class="edit-icon" href="https://www.google.com/"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a class="delete-icon" href="https://www.google.com/"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </div>
                            <div class="added-bank-info-action">
                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                    <div class="user-side-profile">
                        <div class="side-profile-item">
                            <div class="side-profile-media">
                                @if(isset($order->profile_image) && $order->profile_image != "")
                                <img src="{{ url('upload/profile-image/'.$order->profile_image) }}">
                                @else
                                <img src="{{ asset('assets/website-images/user.jpg') }}">
                                @endif
                            </div>
                            <div class="side-profile-text">
                                <h2>{{ $order->first_name ?? "NA" }} {{ $order->last_name ?? "" }}</h2>
                                <p>
                                    @if($order->role==1) Student
                                    @elseif($order->role==2) Content Creator
                                    @elseif($order->role==3) Arkansas
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="side-profile-overview-info">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <div class="side-profile-total-order">
                                        <div class="side-profile-total-icon">
                                            <img src="{{ asset('assets/website-images/email1.svg') }}">
                                        </div>
                                        <div class="side-profile-total-content">
                                            <h2>Email Address</h2>
                                            <p>{{ $order->email ?? "NA" }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="side-profile-total-order">
                                        <div class="side-profile-total-icon">
                                            <img src="{{ asset('assets/website-images/buliding-1.svg') }}">
                                        </div>
                                        <div class="side-profile-total-content">
                                            <h2>Phone No.</h2>
                                            <p>{{ $order->phone ?? "NA" }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="side-profile-total-order">
                                        <div class="side-profile-total-icon">
                                            <img src="{{ asset('assets/website-images/accountstatus.svg') }}">
                                        </div>
                                        <div class="side-profile-total-content">
                                            <h2>Account Status</h2>
                                            <p>
                                                @if($order->ustatus==0) Pending
                                                @elseif($order->ustatus==1) Approved
                                                @elseif($order->ustatus==2) Rejected
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="pmu-content-list">
                        <div class="pmu-content">
                            <div class="row">

                                <div class="col-md-8">
                                    @php $amount = 0; $admin = 0; @endphp
                                    @forelse($order->orderProduct as $key => $val)
                                    <div class="pmu-course-details-item">
                                        <div class="pmu-course-details-media">
                                            <img src="{{ url('upload/products/'.$val->image) }}">
                                        </div>
                                        <div class="pmu-course-details-content">
                                            <div class="coursestatus"><img src="{{ asset('assets/website-images/tick.svg') }}">
                                                @if ($val->status == 0)
                                                Unpublished
                                                @else
                                                Published
                                                @endif
                                            </div>
                                            <h2 class="text-capitalize">{{ $val->title ?? "NA" }}</h2>
                                            <div class="pmu-course-details-price">${{ number_format((float)$val->amount, 2) }}</div>
                                        </div>
                                    </div>
                                    @php $amount += $val->amount; $admin += $val->admin_amount; @endphp
                                    @empty
                                    @endforelse

                                </div>

                                <div class="col-md-4">

                                    <div class="cart-summary-info">
                                        <div class="cart-summary-item">
                                            <div class="cart-summary-text">Total Amount</div>
                                            <div class="cart-summary-value" id="total-amount">${{$amount-$admin}}</div>
                                        </div>
                                        <div class="cart-summary-item">
                                            <div class="cart-summary-text">Admin Fee</div>
                                            <div class="cart-summary-value" id="admin-fee">${{$admin}}</div>
                                        </div>
                                        <div class="cart-summary-item">
                                            <div class="cart-summary-text">Tax</div>
                                            <div class="cart-summary-value">${{ $order->taxes ?? 0 }}</div>
                                        </div>
                                        <div class="cart-summary-item">
                                            <div class="cart-summary-text">Discount</div>
                                            <div class="cart-summary-value">$0</div>
                                        </div>
                                        <div class="cart-summary-total-item">
                                            <div class="cart-summary-total-text">Total Fee Paid</div>
                                            <div class="cart-summary-total-value" id="total-cost">${{$amount+$order->taxes}}</div>
                                        </div>
                                    </div>

                                    @if($order->status == 1)
                                    <div class="cart-summary-info">
                                        <div class="added-bank-info-card">
                                            <div class="added-bank-info-ico">
                                                @if(strtolower($transaction->card_type)=='visa')
                                                <img width="50" src="{{ asset('assets/website-images/visa-logo.png') }}">
                                                @else
                                                <img width="50" src="{{ asset('assets/website-images/mastercard.png') }}">
                                                @endif
                                            </div>
                                            <div class="added-bank-info-text mx-2">
                                                <h2>XXXX XXXX XXXX {{ $transaction->card_no ?? "7878" }}</h2>
                                                <ul class="added-summary-list d-flex flex-column mt-2" style="gap: 0px">
                                                    <li class="text-capitalize">{{ $transaction->method_type ?? "Card" }} Type :
                                                        <span>
                                                            {{ $transaction->card_type ?? "Mastercard" }}
                                                        </span>
                                                    </li>
                                                    <li>Expiry : <span>{{ $transaction->expiry ?? "12/2026" }}</span></li>
                                                </ul>
                                            </div>
                                            <div class="added-bank-action">
                                                <a class="edit-icon" href="https://www.google.com/"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a class="delete-icon" href="https://www.google.com/"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </div>
                                            <div class="added-bank-info-action">
                                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

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