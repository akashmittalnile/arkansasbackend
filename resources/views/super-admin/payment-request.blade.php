@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Payment Request')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Payment Request</h2>
        </div>
        <div class="pmu-search-filter wd80">
            <form action="">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <select name="status" class="form-control" id="">
                                <option @if(request()->status == "") selected @endif value="">Select Request Status</option>
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
                    <div class="col-md-1">
                        <div class="form-group">
                            <a class="download-btn" style="padding: 12px 0px;" href="{{ route('SA.Payment.Request', encrypt_decrypt('encrypt', $userID)) }}"><i class="las la-sync"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="download-btn" style="padding: 12px 0px;" type="">Search</button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <a class="download-btn" style="padding: 13.5px 0px;" href="{{ route('SA.ListedCourse', encrypt_decrypt('encrypt', $userID)) }}">Back</a>
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
                        <div class="col-md-12">
                            <div class="pmu-table-info-card">
                                <h2>Total Settled amount</h2>
                                <div class="pmu-table-value">${{ number_format((float)$amount, 2) }}</div>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="pmu-table-form-card">
                                <button class="download-btn" data-bs-toggle="modal" data-bs-target="#Editcourses">Payout Request</button>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-3">
                            <div class="pmu-table-form-card">
                                <a href="#" class="download-btn">Download Payment Log</a>
                            </div>
                        </div> -->
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
                                @if($val->status == 0)
                                    <a title="Approve Request" class="approve-btn" href="{{ route('SA.Change.Payout.Status', ['id' => encrypt_decrypt('encrypt', $val->id), 'status' => encrypt_decrypt('encrypt', 1)]) }}"><img src="{!! url('assets/superadmin-images/approve.svg') !!}"></a>
                                    <a title="Reject Request" onclick="return confirm('Are you sure you want to reject this payout request?');" class="reject-btn"href="{{ route('SA.Change.Payout.Status', ['id' => encrypt_decrypt('encrypt', $val->id), 'status' => encrypt_decrypt('encrypt', 2)]) }}"><img src="{!! url('assets/superadmin-images/reject.svg') !!}"></a>
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

<style>
    a:hover{
        color: #fff;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        
    })
</script>
@endsection