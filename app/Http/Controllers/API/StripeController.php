<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\UserCourse;
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
                        "amount" => $total_amount*100,
                        "currency" => "INR",
                        "source" => $request->stripeToken,
                        "description" => "Arkansas order payment",
                ]);
                if ($charge['status'] == 'succeeded') {
                    $transactionId = Transaction::insertGetId([
                        'user_id' => auth()->user()->id,
                        'status' => 1,
                        'transaction_id' => $charge['id'],
                        'amount' => $total_amount,
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
                    return response()->json(["status" => true, "message" => "Payment successfully done.", 'receipt URL' => $charge->receipt_url,
                    ]);
                } else {
                    return response()->json(["status" => false, "message" => "Something went wrong.", 'receipt URL' => $charge->receipt_url ]);
                }
            }
            
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
        
    }
}
