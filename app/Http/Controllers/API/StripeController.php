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
use App\Models\Notify;
use App\Models\Product;
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
                        "currency" => "USD",
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
                    OrderDetail::where('order_id', $request->order_id)->update(['order_status' => 1]);
                    $orderDetails = OrderDetail::where('order_id', $request->order_id)->where('product_type', 1)->get();
                    foreach($orderDetails as $val){
                        $userCourse = UserCourse::where('course_id', $val->product_id)->where('user_id', auth()->user()->id)->update(['payment_id'=>$transactionId]);
                    }
                    $stockQuantity = OrderDetail::where('order_id', $request->order_id)->where('product_type', 2)->get();
                    foreach($stockQuantity as $stock){
                        $quan = Product::where('id', $stock->product_id)->first();
                        Product::where('id', $stock->product_id)->update([
                            'unit' => $quan->unit-$stock->quantity,
                            'stock_available' => (($quan->unit-$stock->quantity) <= 0) ? 0 : 1
                        ]);
                    }
                    $walletBalance = WalletBalance::where('owner_id', 1)->where('owner_type', 3)->first();
                    $orderAdminAmount = Order::where('id', $request->order_id)->first();
                    if(isset($walletBalance->id)){
                        WalletBalance::where('owner_id', 1)->where('owner_type', 3)->update([
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
                        $balance->owner_id = 1;
                        $balance->owner_type = 3;
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
                    $notify = new Notify;
                    $notify->added_by = auth()->user()->id;
                    $notify->user_id = auth()->user()->id;
                    $notify->module_name = 'order';
                    $notify->title = 'Order Placed';
                    $notify->message = 'Hello, ' . auth()->user()->first_name . "\nOrder number " . $orderAdminAmount->order_number . ' has been successfully placed.';
                    if(auth()->user()->profile_image == "" || auth()->user()->profile_image == null){
                        $profile_image = null;
                    } else $profile_image = uploadAssets('upload/profile-image/'.auth()->user()->profile_image);
                    $notify->image = $profile_image;
                    $notify->is_seen = '0';
                    $notify->redirect_url = env('APP_URL').'/api/my-order';
                    $notify->created_at = date('Y-m-d H:i:s');
                    $notify->updated_at = date('Y-m-d H:i:s');
                    $notify->save();

                    $data = array(
                        'msg' => 'Hello, ' . auth()->user()->first_name . "\nOrder number " . $orderAdminAmount->order_number . ' has been successfully placed.',
                        'title' => 'Order Placed'
                    );
                    sendNotification(auth()->user()->fcm_token ?? "", $data); 

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
