@extends('layouts.app-master')
@section('title', 'Permanent Makeup University - Order Details')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <a href="{{ route('Home.earnings') }}" class="newcourse-btn">Back</a>
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
                        <a class="newcourse-btn" href="{{ route('Home.download.invoice', encrypt_decrypt('encrypt',$order->id)) }}" target="_blank" id="invoicePrint">Download Invoice</a>
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
                                <img width="50" height="40" src="{{ assets('assets/website-images/order.svg') }}">
                            </div>
                            <div class="added-bank-info-text mx-2">
                                <h2>{{ $order->order_number }}</h2>
                                <ul class="added-summary-list d-flex flex-column mt-2" style="gap: 0px">
                                    <li>Order Date: <span>{{ date('d M, Y H:i A', strtotime($order->created_date)) }}</span></li>
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
                                <img src="{{ uploadAssets('upload/profile-image/'.$order->profile_image) }}">
                                @else
                                <img src="{{ assets('assets/website-images/user.jpg') }}">
                                @endif
                            </div>
                            <div class="side-profile-text">
                                <h2 class="text-capitalize">{{ $order->first_name ?? "NA" }} {{ $order->last_name ?? "" }}</h2>
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
                                <!-- <div class="col-md-12">
                                    <div class="side-profile-total-order">
                                        <div class="side-profile-total-icon">
                                            <img src="{{ assets('assets/website-images/email1.svg') }}">
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
                                            <img src="{{ assets('assets/website-images/buliding-1.svg') }}">
                                        </div>
                                        <div class="side-profile-total-content">
                                            <h2>Phone No.</h2>
                                            <p>{{ $order->phone ?? "NA" }}</p>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-12">
                                    <div class="side-profile-total-order">
                                        <div class="side-profile-total-icon">
                                            <img src="{{ assets('assets/website-images/accountstatus.svg') }}">
                                        </div>
                                        <div class="side-profile-total-content">
                                            <h2>Account Status</h2>
                                            <p>
                                                @if($order->ustatus==0) Pending
                                                @elseif($order->ustatus==1) Active
                                                @elseif($order->ustatus==2) Inactive
                                                @elseif($order->ustatus==3) Rejected
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
                                    @forelse($orderDetails as $key => $val)
                                    <div class="pmu-course-details-item">
                                        <div class="pmu-course-details-media" style="width: 210px;">
                                            @if($val->product_type == 2)
                                            <img src="{{ uploadAssets('upload/products/'.$val->image) }}">
                                            @elseif ($val->product_type == 1)
                                            <a data-fancybox data-type="iframe"
                                            data-src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fapciedu%2Fvideos%2F203104562693996%2F&show_text=false&width=560&t=0"
                                            href="javascript:;">
                                                <video class="w-100 h-100" controls controlslist="nodownload noplaybackrate" disablepictureinpicture volume>
                                                    <source src="{{ uploadAssets('upload/disclaimers-introduction/' . $val->image) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </a>
                                            @endif
                                        </div>
                                        <div class="pmu-course-details-content">
                                            <div class="coursestatus"><img src="{{ assets('assets/website-images/tick.svg') }}">
                                                @if ($val->status == 0)
                                                Unpublished
                                                @else
                                                Published
                                                @endif
                                            </div>
                                            <h2 class="text-capitalize">{{ $val->title ?? "NA" }}</h2>
                                            @if($val->product_type == 1)
                                            <div class="pmu-course-details-price">
                                                @if($val->amount == $val->course_fee)
                                                ${{ number_format((float)$val->course_fee, 2, '.', '') }}
                                                @else
                                                <del style="font-size: 1rem; font-weight: 500;">${{ number_format($val->course_fee,2) ? : 0}}</del> &nbsp; ${{ number_format($val->amount,2) ? : 0}}
                                                @endif
                                            </div>
                                            @else
                                            <div class="pmu-course-details-price mb-2">${{ number_format((float)$val->amount, 2, '.', '') }}</div>
                                                @if(!isset($val->shipengine_label_id))
                                                <a class="newcourse-btn" href="{{ route('SA.Generate.Label', ['id' => encrypt_decrypt('encrypt', $val->product_id), 'orderId' => encrypt_decrypt('encrypt',$order->id)]) }}">Generate Label</a>
                                                @else
                                                <a class="newcourse-btn" href="{{ $val->shipengine_label_url }}" target="_blank">View Label</a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    @php $amount += $val->amount; $admin += $val->admin_amount; @endphp
                                    @empty
                                    @endforelse
                                </div>

                                <div class="col-md-4">

                                    <div class="cart-summary-info">
                                        <div class="cart-summary-item">
                                            <div class="cart-summary-text">Sub Total</div>
                                            @if($order->order_for == 1)
                                            <div class="cart-summary-value" id="total-amount">${{ number_format((float)($order->amount + $order->coupon_discount_price ?? 0), 2, '.', '') }}</div>
                                            @else
                                            <div class="cart-summary-value" id="total-amount">${{ number_format((float)($order->amount ?? 0), 2, '.', '') }}</div>
                                            @endif
                                        </div>
                                        @if($order->delivery_charges != null && $order->delivery_charges != '' && $order->delivery_charges != 0)
                                        <div class="cart-summary-item">
                                            <div class="cart-summary-text">Shipping Fee</div>
                                            <div class="cart-summary-value" id="admin-fee">+${{$order->delivery_charges ?? 0}}</div>
                                        </div>
                                        @endif
                                        <div class="cart-summary-item">
                                            <div class="cart-summary-text">Tax</div>
                                            <div class="cart-summary-value">+${{ number_format((float)$order->taxes ?? 0, 2, '.', '') }}</div>
                                        </div>
                                        <div class="cart-summary-item">
                                            <div class="cart-summary-text">Coupon Discount</div>
                                            <div class="cart-summary-value">-${{ number_format((float)$order->coupon_discount_price ?? 0, 2, '.', '') }}</div>
                                        </div>
                                        <div class="cart-summary-total-item">
                                            <div class="cart-summary-total-text">Total Fee Paid</div>
                                            <div class="cart-summary-total-value" id="total-cost">${{$order->total_amount_paid ?? 0}}</div>
                                        </div>
                                    </div>

                                    @if($order->status == 1)
                                    <div class="cart-summary-info">
                                        <div class="added-bank-info-card">
                                            <div class="added-bank-info-ico">
                                                @if(strtolower($transaction->card_type)=='visa')
                                                <img width="50" src="{{ assets('assets/website-images/visa-logo.png') }}">
                                                @else
                                                <img width="50" src="{{ assets('assets/website-images/mastercard.png') }}">
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
<script>

</script>
@endsection