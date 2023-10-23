<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CardDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\UserCourse;
use App\Models\WalletBalance;
use App\Models\WalletHistory;
use App\Models\AddToCart;
use Illuminate\Http\Request;
use Stripe;
use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
{
    public function makePayment(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'order_id' => 'required',
                'total_amount' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 202);
            }else{
                $total_amount = number_format((float)$request->total_amount,2);
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $charge = Stripe\Charge::create ([
                        "amount" => $request->total_amount*100,
                        "currency" => "INR",
                        "source" => $request->stripeToken,
                        "description" => "Arkansas order payment",
                ]);
                if ($charge['status'] == 'succeeded') {
                    $cardExist = CardDetail::where('userid', auth()->user()->id)->where('card_no', $charge['source']['last4'])->where('expiry', $charge['source']['exp_month'] . '/' .$charge['source']['exp_year'])->first();
                    if(isset($cardExist->id)) {
                        $cardId = $cardExist->id;
                    }else{
                       $cardId = CardDetail::insertGetId([
                            'userid' => auth()->user()->id,
                            'method_type' => $charge['source']['object'] ?? "NA",
                            'card_no' => $charge['source']['last4'] ?? '0000',
                            'card_type' => $charge['source']['brand'] ?? "NA",
                            'expiry' => $charge['source']['exp_month'] . '/' .$charge['source']['exp_year'],
                            'modified_date' => date('Y-m-d H:i:s')
                        ]); 
                    }
                    $transactionId = Transaction::insertGetId([
                        'user_id' => auth()->user()->id,
                        'card_id' => $cardId ?? 0,
                        'status' => 1,
                        'transaction_id' => $charge['id'],
                        'amount' => $request->total_amount,
                        'card_receipt' =>  $charge->receipt_url,
                        'created_date' =>  date('Y-m-d H:i:s'),
                    ]);
                    $order = Order::where('id', $request->order_id)->update([
                        'status' => 1,
                        'payment_id' => $transactionId,
                        'payment_type' => 'stripe'
                    ]);
                    $orderDetails = OrderDetail::where('order_id', $request->order_id)->where('product_type', 1)->get();
                    foreach($orderDetails as $val){
                        $userCourse = UserCourse::where('course_id', $val->product_id)->where('user_id', auth()->user()->id)->update(['payment_id'=>$transactionId]);
                    }
                    $walletBalance = WalletBalance::where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->first();
                    $orderAdminAmount = Order::where('id', $request->order_id)->first();
                    if(isset($walletBalance->id)){
                        WalletBalance::where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->update([
                            'balance' => $walletBalance->balance + $orderAdminAmount->admin_amount,
                            'updated_date' => date('Y-m-d H:i:s')
                        ]);
                        $history = new WalletHistory;
                        $history->wallet_id = $walletBalance->id;
                        $history->balance = $orderAdminAmount->admin_amount ?? 0;
                        $history->added_date = date('Y-m-d H:i:s');
                        $history->added_by = auth()->user()->id;
                        $history->payment_id = $transactionId;
                        $history->status = 1;
                        $history->save();
                    }else{
                        $balance = new WalletBalance;
                        $balance->owner_id = auth()->user()->id;
                        $balance->owner_type = auth()->user()->role;
                        $balance->balance = $orderAdminAmount->admin_amount ?? 0;
                        $balance->created_date = date('Y-m-d H:i:s');
                        $balance->updated_date = date('Y-m-d H:i:s');
                        $balance->save();
                        $history = new WalletHistory;
                        $history->wallet_id = $balance['id '];
                        $history->balance = $orderAdminAmount->admin_amount ?? 0;
                        $history->added_date = date('Y-m-d H:i:s');
                        $history->added_by = auth()->user()->id;
                        $history->payment_id = $transactionId;
                        $history->status = 1;
                        $history->save();
                    }
                    $cart_count = AddToCart::where('userid', auth()->user()->id)->count();
                    return response()->json(["status" => true, "message" => "Payment successfully done.", 'receipt URL' => $charge->receipt_url,
                    'cart_count' => $cart_count ?? 0]);
                } else {
                    return response()->json(["status" => false, "message" => "Something went wrong.", 'receipt URL' => $charge->receipt_url ]);
                }
            }
            
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
        
    }
}
