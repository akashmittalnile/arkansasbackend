<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Address;
use App\Models\AddToCart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductAttibutes;
use App\Models\Setting;
use App\Models\ShippingMethod;
use App\Models\TempData;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use DB;

class CartController extends Controller
{
    public function add_to_cart(Request $request)
    {
        try {
            // return response()->json(['status' => false, 'Message' => 'Api under progress']);
            $user_id = Auth::user()->id;
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'object_id' => 'required',
                    'object_type' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                } else {
                    $isAlready = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                    $isCart = AddToCart::where('userid', $user_id)->count();
                    if($request->object_type==2){
                        if($isCart > 0){
                            return response()->json(['status' => false, 'message' => "You can't add to cart a product now. Only one type of items allow either Course or Product.", 'error' => 2]);
                        }
                        $product = Product::where('id', $request->object_id)->first();
                        $proImg = ProductAttibutes::where('product_id', $request->object_id)->where('attribute_code', 'cover_image')->first();
                        if (isset($isAlready->id)) {
                            $data = $this->updateCart($product, $proImg, $isAlready);
                            TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                                'data' => serialize($data)
                            ]);
                            return response()->json(['status' => true, 'message' => 'Cart updated', 'cart_count' => $data['totalItem'] ?? 0]);
                        } else {
                            $data = $this->newCart($product, $proImg);
                            $cart = new TempData;
                            $cart->user_id = auth()->user()->id;
                            $cart->data = serialize($data);
                            $cart->type = 'cart';
                            $cart->save();
                            return response()->json(['status' => true, 'message' => 'Added to cart', 'cart_count' => $data['totalItem'] ?? 0]);
                        } 
                    }else if($request->object_type==1){
                        // return response()->json(['status' => false, 'message' => "You can't add to cart a courses now. Work in progress"]);
                        if (isset($isAlready->id)) {
                            return response()->json(['status' => false, 'message' => "You can't add to cart a courses now. Only one type of items allow either Course or Product.", 'error' => 1]);
                        }
                        $isAlreadyCart = AddToCart::where('userid', $user_id)->where('object_id', $request->object_id)->first();
                        if(isset($isAlreadyCart->id)){
                            return response()->json(['status' => false, 'message' => 'Already in cart. Please try another courses.']);
                        }
                        $isPurchase = UserCourse::where('course_id', $request->object_id)->where('user_id', $user_id)->where('is_expire', 0)->orderByDesc('id')->first();
                        if(isset($isPurchase->id)){
                            if($isPurchase->status == 1){
                                return response()->json(['status' => false, 'message' => 'This course is completed already. You cannot purchase again.']);
                            }
                            $course_purchase = Setting::where('attribute_code','course_purchase_validity')->first();
                            if(isset($course_purchase->id) && $course_purchase->attribute_value != '' && $course_purchase->attribute_value != 0){
                                $valid = date('d M, Y', strtotime($isPurchase->created_date . '+' . $course_purchase->attribute_value . 'days'));
                                if(!courseExpire(date('d M, Y', strtotime($isPurchase->created_date)), $valid)){
                                    return response()->json(['status' => false, 'message' => 'Already purchased this course!. Please try another courses.']);
                                }
                            }else{
                                return response()->json(['status' => false, 'message' => 'Already purchased this course!. Please try another courses.']);
                            }
                        }  
                        $course = Course::where('id', $request->object_id)->first();
                        $cart = new AddToCart;
                        $cart->userid = $user_id;
                        $cart->object_id = $request->object_id;
                        $cart->object_type = $request->object_type;
                        $cart_value = $course->course_fee;
                        $admin_value = $course->course_fee;
                        if($request->object_type == 1){
                            $user = User::where('id', $course->admin_id)->first();
                            if(isset($user->id) && $user->role == 3){
                                $admin_value = $request->cart_value;
                            } else if(isset($user->id) && $user->role == 2){
                                $admin_value = number_format((float)(($request->cart_value * $user->admin_cut)/100), 2);
                            }
                        }
                        $cart->cart_value = $cart_value;
                        $cart->admin_cut_value = $admin_value;
                        $cart->quantity = 1;
                        $cart->save();
                        $cart_count = AddToCart::where('userid', $user_id)->count();
                        return response()->json(['status' => true, 'message' => 'Added to cart', 'cart_count' => $cart_count ?? 0]);
                    } else {
                        return response()->json(['status' => false, 'message' => 'Something went wrong!']);
                    }
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function newCart($product, $proImg)
    {
        $tax = Setting::where('attribute_code', 'tax')->first();
        $data['products'][0] = [
            'qty' => 1, 'total_amount' => $product->sale_price, 'regular_price' => $product->price, 'product_id' => $product->id, 'name' => $product->name, 'short_description' => $product->product_desc, 'sale_price' => $product->sale_price, 'image' => $proImg->attribute_value ?? '', 'package_weight' => $product->package_weight, 'package_weight_unit' => $product->package_weight_unit, 'package_length' => $product->package_length, 'package_length_unit' => $product->package_length_unit, 'package_width' => $product->package_width, 'package_width_unit' => $product->package_width_unit, 'package_height' => $product->package_height, 'package_height_unit' => $product->package_height_unit, 'content_creator_id' => $product->added_by, 'shipmentId' => null, 'shippingPrice' => 0, 'service_code' => null
        ];
        $data['totalQty'] = 1;
        $data['subTotal'] = $product->sale_price;
        if (isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
            $data['tax'] = ($data['subTotal'] * $tax->attribute_value) / 100;
        else $data['tax'] = 0;
        $data['totalPrice'] = $product->sale_price + $data['tax'];
        $data['totalItem'] = 1;
        $data['shippingId'] = null;
        $data['shippingPrice'] = 0;
        $data['shippingTitle'] = 'Shipping';
        $data['isCouponApplied'] = 0;
        $data['appliedCouponCode'] = null;
        $data['appliedCouponPrice'] = 0;
        $data['discountValue'] = 0;
        $data['couponId'] = null;
        $data['couponType'] = null;
        $data['paymentMethod'] = "STRIPE";
        $data['addedDate'] = date('Y-m-d H:i:s');

        return $data;
    }

    public function updateCart($product, $proImg, $cart)
    {
        $tax = Setting::where('attribute_code', 'tax')->first();
        $oldcart = unserialize($cart->data);
        $length = count($oldcart['products']);
        $qty = 0;
        $price = 0;
        $existingpro = false;
        if ($length > 0) {
            for ($i = 0; $i <= $length; $i++) {
                if ($i < $length) {
                    if ($oldcart['products'][$i]['product_id'] == $product->id) {
                        $oldcart['products'][$i]['qty'] = $oldcart['products'][$i]['qty'] + 1;
                        $oldcart['products'][$i]['total_amount'] = $oldcart['products'][$i]['sale_price'] * $oldcart['products'][$i]['qty'];
                        $existingpro = true;
                    }
                    $data['products'][$i] = $oldcart['products'][$i];
                    $qty += $data['products'][$i]['qty'];
                    $price += $data['products'][$i]['total_amount'];
                } else if (!$existingpro) {
                    $data['products'][$i] = [
                        'qty' => 1, 'total_amount' => $product->sale_price, 'regular_price' => $product->price, 'product_id' => $product->id, 'name' => $product->name, 'short_description' => $product->product_desc, 'sale_price' => $product->sale_price, 'image' => $proImg->attribute_value ?? '', 'package_weight' => $product->package_weight, 'package_weight_unit' => $product->package_weight_unit, 'package_length' => $product->package_length, 'package_length_unit' => $product->package_length_unit, 'package_width' => $product->package_width, 'package_width_unit' => $product->package_width_unit, 'package_height' => $product->package_height, 'package_height_unit' => $product->package_height_unit, 'content_creator_id' => $product->added_by, 'shipmentId' => null, 'shippingPrice' => 0, 'service_code' => null
                    ];
                    $qty += $data['products'][$i]['qty'];
                    $price += $data['products'][$i]['total_amount'];
                }
            }
        }

        if($oldcart['isCouponApplied']){
            $data['isCouponApplied'] = 1;
            $data['appliedCouponPrice'] = $oldcart['appliedCouponPrice'];
            $data['appliedCouponCode'] = $oldcart['appliedCouponCode'];
            $data['discountValue'] = $oldcart['discountValue'];
            $data['couponId'] = $oldcart['couponId'];
            $data['couponType'] = $oldcart['couponType'];
        }else{
            $data['isCouponApplied'] = 0;
            $data['appliedCouponPrice'] = 0;
            $data['appliedCouponCode'] = null;
            $data['discountValue'] = 0;
            $data['couponId'] = null;
            $data['couponType'] = null;
        }

        $data['totalQty'] = $qty;
        $data['subTotal'] = $price;
        if (isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
            $data['tax'] = ($data['subTotal'] * $tax->attribute_value) / 100;
        else $data['tax'] = 0;
        $data['totalPrice'] = $price + $data['tax'] - $data['appliedCouponPrice'];
        $data['totalItem'] = count($data['products']);
        $data['shippingId'] = null;
        $data['shippingPrice'] = 0;
        $data['shippingTitle'] = 'Shipping';
        $data['paymentMethod'] = "STRIPE";
        $data['addedDate'] = date('Y-m-d H:i:s');

        return $data;
    }

    public function updateCartProducts($pro, $old)
    {
        $proImg = ProductAttibutes::where('product_id', $pro->id)->where('attribute_code', 'cover_image')->first();
        $data = [
            'qty' => $old['qty'], 'total_amount' => $pro->sale_price * $old['qty'], 'regular_price' => $pro->price, 'product_id' => $pro->id, 'name' => $pro->name, 'short_description' => $pro->product_desc, 'sale_price' => $pro->sale_price, 'image' => $proImg->attribute_value ?? '', 'package_weight' => $pro->package_weight, 'package_weight_unit' => $pro->package_weight_unit, 'package_length' => $pro->package_length, 'package_length_unit' => $pro->package_length_unit, 'package_width' => $pro->package_width, 'package_width_unit' => $pro->package_width_unit, 'package_height' => $pro->package_height, 'package_height_unit' => $pro->package_height_unit, 'content_creator_id' => $pro->added_by, 'shipmentId' => $old['shipmentId'], 'shippingPrice' => $old['shippingPrice'], 'service_code' => $old['service_code'], 'compare_rate_list' => $old['compare_rate_list'] ?? []
        ];
        return $data;
    }

    public function cart_list()
    {
        try {
            $shopping_cart = AddToCart::where('userid', auth()->user()->id)->where('object_type', 1)->get();
            if(count($shopping_cart)>0){
                $tax = Setting::where('attribute_code', 'tax')->first();
                $response = array();
                $qty = 0;
                $price = 0;
                foreach ($shopping_cart as $keys => $item) {
                    $temp['product_id'] = $item->object_id;
                    $temp['quantity'] = $item->quantity;
                    if ($item->object_type == 1) { /* 1 stand for course ,2 for product */
                        $value = Course::leftJoin('users as u', function($join) {
                            $join->on('course.admin_id', '=', 'u.id');
                        })->leftJoin('category as c', 'c.id', '=', 'course.category_id')
                        ->where('course.id', $item->object_id)->select('course.title', 'course.course_fee', 'u.profile_image', 'u.first_name', 'u.last_name', 'u.category_name', 'course.admin_id', 'course.id', 'course.introduction_image', 'c.id as catid', 'c.name as catname', 'course.description')->first();
                        $temp['name'] = $value->title;
                        $temp['regular_price'] = $value->course_fee;
                        $temp['total_amount'] = $value->course_fee;
                        $temp['short_description'] = $value->description;
                        $temp['sale_price'] = $value->course_fee;
                        if ($value->profile_image) {
                            $profile_image = uploadAssets('upload/profile-image/'.$value->profile_image);
                        } else {
                            $profile_image = '';
                        }
                        $temp['category_id'] = $value->catid ?? null;
                        $temp['category_name'] = $value->catname ?? null;
                        $temp['content_creator_image'] = $profile_image;
                        $temp['content_creator_name'] = $value->first_name.' '.$value->last_name;
                        if(isset($value->introduction_image)){
                            $temp['image'] = uploadAssets('upload/disclaimers-introduction/'.$value->introduction_image);  
                        } else $temp['image'] = null;
                        $avgRating = DB::table('user_review as ur')->where('object_id', $item->object_id)->where('object_type', $item->object_type)->avg('rating');
                        $temp['avg_rating'] = number_format($avgRating, 1);
                    }
                    $qty += $item->quantity;
                    $price += $value->course_fee;
                    $response['items'][] = $temp;
                }
                $response['subTotal'] = $price;
                $response['totalQty'] = $qty;
                $response['tax'] = ($price * $tax->attribute_value) / 100;
                $response['totalPrice'] = $price + $response['tax'];
                $response['totalItem'] = $qty;
                return response()->json(['status' => true, 'message' => 'Cart list', 'data' => $response, 'type' => 1]);
            }else{
                $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                $tax = Setting::where('attribute_code', 'tax')->first();
                $qty = 0;
                $price = 0;
                if (isset($cart->id)) {
                    $old = unserialize($cart->data);
                    for ($i = 0; $i < count($old['products']); $i++) {
                        $pro = Product::where('id', $old['products'][$i]['product_id'])->first();
                        $old['products'][$i] = $this->updateCartProducts($pro, $old['products'][$i]);
                        $res['items'][$i]['image'] = uploadAssets('upload/products/' . $old['products'][$i]['image']);
                        $res['items'][$i]['quantity'] = $old['products'][$i]['qty'];
                        $res['items'][$i]['total_amount'] = $old['products'][$i]['total_amount'];
                        $res['items'][$i]['regular_price'] = $old['products'][$i]['regular_price'];
                        $res['items'][$i]['sale_price'] = $old['products'][$i]['sale_price'];
                        $res['items'][$i]['product_id'] = $old['products'][$i]['product_id'];
                        $res['items'][$i]['name'] = $old['products'][$i]['name'];
                        $res['items'][$i]['short_description'] = $old['products'][$i]['short_description'];
                        $res['items'][$i]['compare_rate_list'] = $old['products'][$i]['compare_rate_list'] ?? [];
                        $res['items'][$i]['shippment_id'] = $old['products'][$i]['shipmentId'] ?? null;
                        $res['items'][$i]['shipping_price'] = $old['products'][$i]['shippingPrice'] ?? 0;
                        $res['items'][$i]['service_code'] = $old['products'][$i]['service_code'] ?? null;

                        $category = Category::where('id', $pro->category_id)->first();
                        $added = User::where('id', $pro->added_by)->first();
                        $avgRating = DB::table('user_review as ur')->where('object_id', $pro->id)->where('object_type', 2)->avg('rating');
                        
                        if ($added->profile_image == '' || $added->profile_image == null) {
                            $profile_image = null;
                        } else {
                            $profile_image = uploadAssets('upload/profile-image/' . $added->profile_image);
                        }
                        $res['items'][$i]['category_id'] = $category->id ?? null;
                        $res['items'][$i]['category_name'] = $category->name ?? null;
                        $res['items'][$i]['content_creator_image'] = $profile_image;
                        $res['items'][$i]['content_creator_name'] = $added->first_name.' '.$added->last_name;
                        $res['items'][$i]['avg_rating'] = number_format($avgRating, 1);
    
                        $qty += $old['products'][$i]['qty'];
                        $price += $old['products'][$i]['total_amount'];
                    }
    
                    if($old['couponType'] == 1){
                        $old['appliedCouponPrice'] = $old['appliedCouponPrice'];
                    }else if($old['couponType'] == 2){
                        $old['appliedCouponPrice'] = ($old['subTotal'] * $old['discountValue'])/100;
                    }
    
                    $res['subTotal'] = $old['subTotal'] = $price;
                    $res['totalQty'] = $old['totalQty'] = $qty;
                    $res['tax'] = $old['tax'] = ($old['subTotal'] * $tax->attribute_value) / 100;
                    $res['totalPrice'] = $old['totalPrice'] = $old['subTotal'] + $old['tax'] - $old['appliedCouponPrice'] + $old['shippingPrice'];
                    $res['totalItem'] = $old['totalItem'];
                    $res['shippingPrice'] = $old['shippingPrice'];
                    $res['shippingAddressId'] = $old['shipping_address']['address_id'] ?? null;
                    $res['isCouponApplied'] = $old['isCouponApplied'];
                    $res['couponCode'] = $old['appliedCouponCode'];
                    $res['couponPrice'] = $old['appliedCouponPrice'];
                    TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                        'data' => serialize($old)
                    ]);
                    $address = Address::where('user_id', auth()->user()->id)->get();
                    return response()->json(['status' => true, 'message' => 'Cart list', 'data' => $res, 'address' => $address, 'type' => 2]);
                } else return response()->json(['status' => false, 'message' => 'Cart empty!']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function update_product_quantity(Request $request)
    {
        try {
            // return response()->json(['status' => false, 'Message' => 'Api under progress']);
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'quantity' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            } else {
                $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                $tax = Setting::where('attribute_code', 'tax')->first();
                $qty = 0;
                $price = 0;
                $ship_price = 0;
                if (isset($cart->id)) {
                    $old = unserialize($cart->data);
                    for ($i = 0; $i < count($old['products']); $i++) {
                        if ($old['products'][$i]['product_id'] == $request->product_id) {
                            $old['products'][$i]['qty'] = $request->quantity;
                            $old['products'][$i]['total_amount'] = $old['products'][$i]['qty'] * $old['products'][$i]['sale_price'];
                            $old['products'][$i]['shipmentId'] = null;
                            $old['products'][$i]['shippingPrice'] = 0;
                        }
                        $old['products'][$i]['service_code'] = null;

                        $qty += $old['products'][$i]['qty'];
                        $price += $old['products'][$i]['total_amount'];
                        $ship_price += $old['products'][$i]['shippingPrice'];
                    }

                    if($old['couponType'] == 1){
                        $old['appliedCouponPrice'] = $old['appliedCouponPrice'];
                    }else if($old['couponType'] == 2){
                        $old['appliedCouponPrice'] = ($old['subTotal'] * $old['discountValue'])/100;
                    }

                    $old['totalQty'] = $qty;
                    $old['subTotal'] = $price;
                    if (isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
                        $old['tax'] = ($old['subTotal'] * $tax->attribute_value) / 100;
                    else $old['tax'] = 0;
                    $old['shippingPrice'] = 0;
                    $old['totalPrice'] = $old['subTotal'] + $old['tax'] - $old['appliedCouponPrice'] + $old['shippingPrice'];
                    $old['totalItem'] = count($old['products']);
                    TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                        'data' => serialize($old)
                    ]);
                    return response()->json(['status' => true, 'message' => 'Quantity updated']);
                } else return response()->json(['status' => false, 'message' => 'Cart empty!']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function shipping_address(Request $request)
    {
        try {
            // return response()->json(['status' => false, 'Message' => 'Api under progress']);
            $validator = Validator::make($request->all(), [
                'address_id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            } else {
                $address = Address::where('user_id', auth()->user()->id)->where('id', $request->address_id)->first();
                if (isset($address->id)) {
                    $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                    if (isset($cart->id)) {
                        $old = unserialize($cart->data);
                        $data = $old;
                        for ($i = 0; $i < count($old['products']); $i++) {
                            $data['products'][$i]['compare_rate_list'] = [];
                            $data['products'][$i]['shipmentId'] = null;
                            $data['products'][$i]['shippingPrice'] = 0;
                            $data['products'][$i]['service_code'] = null;
                        }
                        $data['totalPrice'] = $data['totalPrice'] - $data['shippingPrice'];
                        $data['shippingPrice'] = 0;
                        $data['shipping_address'] = [
                            'address_id' => $address->id, 'first_name' => $address->first_name, 'middle_name' => $address->middle_name, 'last_name' => $address->last_name, 'email' => $address->email, 'phone' => $address->phone, 'company_name' => $address->company_name, 'address_line_1' => $address->address_line_1, 'address_line_2' => $address->address_line_2 ?? null, 'city' => $address->city, 'state' => $address->state, 'country' => $address->country, 'zip_code' => $address->zip_code, 'latitude' => $address->latitude, 'longitude' => $address->longitude, 'address_type' => $address->address_type, 'is_default_address' => $address->default_address
                        ];
                        $data['checkout'] = [
                            'address' => 1
                        ];
                        TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                            'data' => serialize($data)
                        ]);
                        return response()->json(['status' => true, 'message' => 'Shipping address save successfully']);
                    } else return response()->json(['status' => false, 'message' => 'Cart empty!']);
                } else return response()->json(['status' => false, 'message' => 'No address found']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function empty_cart(){
        try{
            $count = DB::table('shopping_cart')->where('userid', auth()->user()->id)->count();
            if($count > 0){
                DB::table('shopping_cart')->where('userid', auth()->user()->id)->delete();
                return response()->json(['status' => true, 'message' => 'Cart empty successfully']);
            }else{
                $cart = DB::table('temp_data')->where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                if (isset($cart->id)) {
                    DB::table('temp_data')->where('user_id', auth()->user()->id)->where('type', 'cart')->delete();
                    return response()->json(['status' => true, 'message' => 'Cart empty successfully']);
                }else{
                    return response()->json(['status' => false, 'message' => 'Cart empty already!']);
                }
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function remove_cart(Request $request)
    {
        try {
            // return response()->json(['status' => false, 'Message' => 'Api under progress']);
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            } else {
                $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                $tax = Setting::where('attribute_code', 'tax')->first();
                $qty = 0;
                $price = 0;
                $ship_price = 0;
                if (isset($cart->id)) {
                    $old = unserialize($cart->data);
                    $data = $old;
                    for ($i = 0; $i < count($old['products']); $i++) {
                        if ($old['products'][$i]['product_id'] == $request->product_id) {
                            array_splice($data['products'], $i, 1);
                        } else {
                            $data['products'][$i]['shippingPrice'] = 0;
                            $data['products'][$i]['shipmentId'] = null;
                            $data['products'][$i]['service_code'] = null;
                            $qty += $old['products'][$i]['qty'];
                            $price += $old['products'][$i]['total_amount'];
                            $ship_price += $old['products'][$i]['shippingPrice'];
                        }
                    }

                    if($data['couponType'] == 1){
                        $data['appliedCouponPrice'] = $data['appliedCouponPrice'];
                    }else if($data['couponType'] == 2){
                        $data['appliedCouponPrice'] = ($data['subTotal'] * $data['discountValue'])/100;
                    }

                    $data['totalQty'] = $qty;
                    $data['subTotal'] = $price;
                    if (isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
                        $data['tax'] = ($data['subTotal'] * $tax->attribute_value) / 100;
                    else $data['tax'] = 0;
                    $data['shippingPrice'] = $ship_price;
                    $data['totalPrice'] = $data['subTotal'] + $data['tax'] - $data['appliedCouponPrice'] + $data['shippingPrice'];
                    $data['totalItem'] = count($data['products']);

                    if(count($data['products']) == 0){
                        TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->delete();
                        return response()->json(['status' => true, 'message' => 'Item removed from cart.']);
                    }

                    TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                        'data' => serialize($data)
                    ]);
                } else return response()->json(['status' => false, 'message' => 'Cart empty!']);
                return response()->json(['status' => true, 'message' => 'Item removed from cart.']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function remove_cart_course(Request $request){
        try{    
            $validator = Validator::make($request->all(), [
                'course_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }else{
                $cart = AddToCart::where('object_id', $request->course_id)->where('object_type', 1)->where('userid', auth()->user()->id)->delete();
                if ($cart) {
                    return response()->json(['status'=> true, 'message' => 'Item removed from cart.']);
                } else {
                    return response()->json(['status' => false, 'message' => 'Something went wrong!']);
                }
            }

        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function save_order(Request $request)
    {
        try {
            $data = array();
            $user_id = auth()->user()->id;
            if ($user_id) {
                $carts = Addtocart::where('userid', $user_id)->get();
                $order_no = "AKS".rand(1000000000, 9999999999);
                
                
                if (count($carts) > 0) {
                    /*Create Order */
                    $admin_cut_price = Addtocart::where('userid', $user_id)->sum(\DB::raw('admin_cut_value * quantity'));
                    $order_price = Addtocart::where('userid', $user_id)->sum(\DB::raw('cart_value * quantity'));
                    $total_price = $order_price;

                    $tax = Setting::where('attribute_code','tax')->first();
                    if(isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
                        $tax_amount = ($total_price*$tax->attribute_value)/100;
                    else $tax_amount = 0;

                    $insertedId = Order::insertGetId([
                        'user_id' => $user_id,
                        'order_number' => $order_no,
                        'amount' => $order_price - $admin_cut_price,
                        'admin_amount' => $admin_cut_price,
                        'taxes' => number_format((float)$tax_amount, 2, '.', ''),
                        'total_amount_paid' => number_format((float)($total_price+$tax_amount), 2, '.', ''),/*Total amount of order*/
                        'payment_id' => null,
                        'payment_type' => null,
                        'created_date' => date('Y-m-d H:i:s'),
                        'status' => 0,
                    ]);

                    foreach ($carts as $cart) {
                        $OrderDetail = new OrderDetail;
                        $OrderDetail->order_id = $insertedId;
                        $OrderDetail->product_id = $cart->object_id;
                        $OrderDetail->product_type = $cart->object_type;
                        $OrderDetail->quantity = $cart->quantity;
                        $OrderDetail->amount = $cart->cart_value;
                        $OrderDetail->admin_amount = $cart->admin_cut_value;
                        $OrderDetail->created_date = date('Y-m-d H:i:s');
                        $OrderDetail->save();
                        if($cart->object_type == 1){
                            $isPurchase = UserCourse::where('course_id', $cart->object_id)->where('user_id', $user_id)->where('is_expire', 0)->orderByDesc('id')->first();
                            if(isset($isPurchase->id)){
                                $isPurchase->is_expire = 1;
                                $isPurchase->save();
                            }
                            $userCourse = new UserCourse;
                            $userCourse->course_id = $cart->object_id;
                            $userCourse->user_id = $user_id;
                            $userCourse->buy_price = $cart->cart_value;
                            $userCourse->payment_id = null;
                            $userCourse->buy_date = date('Y-m-d H:i:s');
                            $userCourse->status = 0;
                            $userCourse->is_expire = 0;
                            $userCourse->created_date = date('Y-m-d H:i:s');
                            $userCourse->coupon_id = null;
                            $userCourse->save();
                        }
                    }
                    Addtocart::where('userid', $user_id)->delete();
                    
                    return response()->json(['status' => true, 'message' => 'Order placed successfully.', 'order_id' => $insertedId, 'total_amount' => number_format((float)($total_price+$tax_amount), 2, '.', '')]);
                } else {
                    $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                    if (isset($cart->id)) {
                        $old = unserialize($cart->data);
                        $data = $old;
                        $admin_cut = 0;
                        for ($i = 0; $i < count($old['products']); $i++) {
                            $admin_cut += $old['products'][$i]['total_amount'] ?? 0;
                        }
                        $insertedId = Order::insertGetId([
                            'user_id' => $user_id,
                            'order_number' => $order_no,
                            'amount' => $old['subTotal'] ?? 0,
                            'admin_amount' => $admin_cut,
                            'taxes' => number_format((float)$old['tax'] ?? 0, 2, '.', ''),
                            'delivery_charges' => number_format((float)$old['shippingPrice'] ?? 0, 2, '.', ''),
                            'coupon_discount_price' => number_format((float)$old['appliedCouponPrice'] ?? 0, 2, '.', ''),
                            'coupon_id' => $old['couponId'] ?? 0,
                            'total_amount_paid' => number_format((float)($old['totalPrice']), 2, '.', ''),/*Total amount of order*/
                            'shipping_address_id' => $old['shipping_address']['shipping_address_id'] ?? null,
                            'cart_json' => serialize($data),
                            'payment_id' => null,
                            'payment_type' => null,
                            'created_date' => date('Y-m-d H:i:s'),
                            'status' => 0,
                        ]);
                        for ($i = 0; $i < count($old['products']); $i++) {
                            $OrderDetail = new OrderDetail;
                            $OrderDetail->order_id = $insertedId;
                            $OrderDetail->product_id = $old['products'][$i]['product_id'];
                            $OrderDetail->product_type = 2;
                            $OrderDetail->quantity = $old['products'][$i]['qty'];
                            $OrderDetail->amount = $old['products'][$i]['total_amount'];
                            $OrderDetail->admin_amount = $old['products'][$i]['total_amount'];
                            $OrderDetail->shipment_id = $old['products'][$i]['shipmentId'];
                            $OrderDetail->shipping_price = $old['products'][$i]['shippingPrice'];
                            $OrderDetail->created_date = date('Y-m-d H:i:s');
                            $OrderDetail->save();
                        }
                        TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->delete();
                       
                        return response()->json(['status' => true, 'message' => 'Order placed successfully.', 'order_id' => $insertedId, 'total_amount' => number_format((float)($old['totalPrice'] ?? 0), 2, '.', '')]);
                    } else {
                        return response()->json(['status' => true, 'message' => 'Opps! Cart is Empty']);
                    }

                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function get_coupons(Request $request){
        try{
            $now = Carbon::now();
            $coupon = Coupon::where('status', 1)->where('coupon_expiry_date', '>', $now)->orderByDesc('id')->get();
            $response = [];
            foreach($coupon as $val){
                $temp['id'] = $val->id;
                $temp['code'] = $val->coupon_code;
                $temp['expiry_date'] = date('d M Y', strtotime($val->coupon_expiry_date));
                $temp['discount_type'] = $val->	coupon_discount_type;
                $temp['discount_type_name'] = ($val->coupon_discount_type==1) ? 'Flat' : 'Percentage';
                $temp['min_order'] = $val->min_order_amount;
                $temp['discount_amount'] = $val->coupon_discount_amount;
                $temp['description'] = $val->description;
                $temp['created_at'] = date('d M Y, h:iA', strtotime($val->created_at));
                $response[] = $temp;
            }
            if(count($coupon) == 0){
                return response()->json(['status' => true, 'message' => 'No coupon found']);
            }
            return response()->json(['status' => true, 'message' => 'Coupons', 'data' => $response]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function coupon_applied(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'code' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            } else {
                $exist = Coupon::where('coupon_code', strtoupper($request->code))->first();
                if(isset($exist->id)){
                    $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                    if (isset($cart->id)) {
                        $old = unserialize($cart->data);
                        $couponPrice = 0;
                        $totalPrice = $old['subTotal'];
                        if($totalPrice < $exist->min_order_amount) 
                            return response()->json(['status' => false, 'message' => 'Minimum $'.$exist->min_order_amount.' order amount is needed for apply this coupon!']);
                        if($exist->coupon_discount_type == 1){
                            $couponPrice = $exist->coupon_discount_amount ?? 0;
                        }else{
                            $couponPrice = ($totalPrice * $exist->coupon_discount_amount)/100;
                        }
                        $old['isCouponApplied'] = 1;
                        $old['appliedCouponCode'] = strtoupper($request->code);
                        $old['appliedCouponPrice'] = $couponPrice;
                        $old['couponId'] = $exist->id ?? null;
                        $old['couponType'] = $exist->coupon_discount_type;
                        $old['discountValue'] = $exist->coupon_discount_amount;
                        $old['totalPrice'] = $old['totalPrice'] - $old['appliedCouponPrice'];
                        TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                            'data' => serialize($old)
                        ]);
                        return response()->json(['status' => true, 'message' => 'Coupon applied.']);
                    } else return response()->json(['status' => false, 'message' => 'Cart empty!']);
                } else return response()->json(['status' => false, 'message' => 'Invalid coupon code!']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function remove_coupon_applied(Request $request)
    {
        try {
            $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
            if (isset($cart->id)) {
                $old = unserialize($cart->data);
                if($old['isCouponApplied']){
                    $old['totalPrice'] = $old['totalPrice'] + $old['appliedCouponPrice'];
                    $old['isCouponApplied'] = 0;
                    $old['appliedCouponCode'] = null;
                    $old['appliedCouponPrice'] = 0;
                    $old['couponId'] = null;
                    $old['couponType'] = null;
                    $old['discountValue'] = 0;
                    TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                        'data' => serialize($old)
                    ]);
                    return response()->json(['status' => true, 'message' => 'Coupon removed.']);
                } else return response()->json(['status' => false, 'message' => 'No coupon applied on cart!']);
            } else return response()->json(['status' => false, 'message' => 'Cart empty!']);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function get_shipping_rates(Request $request)
    {
        try {
            // return response()->json(['status' => false, 'Message' => 'Api under progress']);
            $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
            $tax = Setting::where('attribute_code', 'tax')->first();
            $qty = 0;
            $price = 0;
            if (isset($cart->id)) {
                $old = unserialize($cart->data);
                $data = $old;
                for ($i = 0; $i < count($old['products']); $i++) {
                    $pro = Product::where('id', $old['products'][$i]['product_id'])->first();
                    $rates = $this->compare_rates($pro, $old['shipping_address'] ?? null);
                    // return $rates;
                    $data['products'][$i]['compare_rate_list'] = $rates;
                    // dd($data);
                }
                $data['checkout'] = [
                    'address' => 1,
                    'shipping' => 1,
                ];
                TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                    'data' => serialize($data)
                ]);
                return response()->json(['status' => true, 'message' => 'Choose shipping options']);
            } else return response()->json(['status' => false, 'message' => 'Cart empty!']);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function compare_rates($pro, $address)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.shipengine.com/v1/rates',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "rate_options": {
                    "carrier_ids": [
                        "se-5451953",
                        "se-5451955",
                        "se-5451954"
                    ],
                    "service_codes": [
                        "usps_priority_mail",
                        "fedex_ground",
                        "ups_ground"
                    ]
                },
                "shipment": {
                    "insurance_provider": "shipsurance",
                    "ship_from": {
                        "name": "Arkansas",
                        "company_name": "Arkansas",
                        "address_line1": "4625 Windfern Rd",
                        "city_locality": "Houston",
                        "state_province": "TX",
                        "postal_code": "77041",
                        "country_code": "US",
                        "phone": "(713) 329-3503"
                    },
                    "ship_to": {
                        "name": "'.$address['first_name']. '' . $address['last_name'] .'",
                        "address_line1": "' . $address['address_line_1'] . '",
                        "city_locality": "' . $address['city'] . '",
                        "state_province": "' . $address['state'] . '",
                        "postal_code": "' . $address['zip_code'] . '",
                        "country_code": "US",
                        "address_residential_indicator":"yes"
                    },
                    "packages": [
                        {
                            "weight": {
                                "value": ' . $pro->package_weight . ',
                                "unit": "pound"
                            },
                            "dimensions": {
                                "length": ' . $pro->package_length . ',
                                "width": ' . $pro->package_width . ',
                                "height": ' . $pro->package_height . ',
                                "unit": "inch"
                            }
                        }
                    ]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'API-Key: '.env('SHIP_ENGINE_KEY')
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $jsonData = json_decode($response, true);

        $shippings = ShippingMethod::where('status', 1)->get();
        $new_arr = [];
        $shipping_cost_arr = [];

        foreach ($shippings as $key => $value) {
            $fetch = $this->carrierCodeNew($value->serviceCode);
            // dd($fetch);
            $final = [];
            $selected_shipping_rate = 0;
            $temp_arr_neww = [];
            $final_temp = [];
            if (isset($jsonData) && count($jsonData) > 0) {
                foreach ($jsonData as $key => $rate) {
                    // return $rate['rates'];
                    if (isset($rate['rates'])) {
                        foreach ($rate['rates'] as $key1 => $value1) {
                            // return $value1['rate_details'];
                            if (count($value1['rate_details']) > 0) {
                                // dd($value1['service_type']);
                                if (in_array($value1['service_type'], $fetch)) {
                                    $temp_arr_neww[$value1['shipping_amount']['amount']] = $value1;
                                    // array_push($final_temp, $temp_arr_neww);
                                    array_push($final, $value1);
                                }
                            }
                        }
                    } else {
                        continue;
                    }
                }
                // dd($final);
            } else {
                return response()->json(['status' => false, 'msg' => 'API not working!', 'final_arr' => $final]);
            }
            ksort($temp_arr_neww);
            if (isset($final) && (count($final) > 0)) {
                $arr = reset($final);
                $reset_temp = reset($temp_arr_neww);
                foreach ($reset_temp['rate_details'] as  $price) {
                    $selected_shipping_rate += $price['amount']['amount'];
                }
                if ($reset_temp['estimated_delivery_date'] != NULL) {
                    $estimated_delivery_date =  date('m/d/Y h:i', strtotime($reset_temp['estimated_delivery_date']));
                } else {
                    $estimated_delivery_date = 'NA';
                }
                $shipping_cost_arr[] = $selected_shipping_rate;
                $temp = ['id' => $value->id, 'name' => $value->name, 'code' => $value->serviceCode, 'service_type' => $reset_temp['service_type'], 'service_code' => $reset_temp['service_code'], 'carrier_id' => $reset_temp['carrier_id'], 'estimated_delivery_date' => $estimated_delivery_date, 'price' => $selected_shipping_rate];
                array_push($new_arr, $temp);
            }
            if ($selected_shipping_rate == 0) {
                continue;
            }
        }
        return $new_arr;
    }

    public function carrierCodeNew($key){
        $arr = [
            'next_day' => [
                'FedEx Standard Overnight®',
                'USPS Priority Mail Express',
                'UPS Next Day Air Saver®'
            ],
            'two_day' => [
                'FedEx 2Day®',
                'USPS Priority Mail'
            ],
            'ground' => [
                'FedEx Ground®',
                'UPS® Ground'
            ]
        ];

        if (isset($arr[$key])) {
            return $arr[$key];
        } else return [];
    }

    public function choose_shipping_option(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'service_code' => 'required',
                'shipping_price' => 'required',
                'carrier_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }else{
                $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
                $tax = Setting::where('attribute_code', 'tax')->first();
                $ship_price = 0;
                if (isset($cart->id)) {
                    $old = unserialize($cart->data);
                    for ($i = 0; $i < count($old['products']); $i++) {
                        if ($old['products'][$i]['product_id'] == $request->product_id) {
                            $pro = Product::where('id', $old['products'][$i]['product_id'])->first();
                            $shipment_id = $this->create_shipment($pro, $old['shipping_address'] ?? null, $request->service_code, $request->carrier_id);
                            if($shipment_id==null || $shipment_id == '') {
                                return response()->json(['status' => false, 'message' => 'Unable to create shipment!']);
                            }
                            $old['products'][$i]['service_code'] = $request->service_code;
                            $old['products'][$i]['shippingPrice'] = $request->shipping_price;
                            $old['products'][$i]['shipmentId'] = $shipment_id;
                            $ship_price += $old['products'][$i]['shippingPrice'];
                        }
                    }
                    $old['shippingPrice'] = $old['shippingPrice'] + $ship_price;
                    $old['totalPrice'] = $old['totalPrice'] + $ship_price;
                    TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                        'data' => serialize($old)
                    ]);
                    return response()->json(['status' => true, 'message' => 'Shipping rate save successfully.']);
                } else return response()->json(['status' => false, 'message' => 'Cart empty!']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function create_shipment($pro, $address, $code, $carrier_id)
    {
        // dd($carrier_id);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.shipengine.com/v1/shipments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "shipments": [{
                    "service_code": "' .$code. '",
                    "carrier_id": "' . $carrier_id . '",
                    "ship_from": {
                        "name": "Arkansas",
                        "company_name": "Arkansas",
                        "address_line1": "4625 Windfern Rd",
                        "city_locality": "Houston",
                        "state_province": "TX",
                        "postal_code": "77041",
                        "country_code": "US",
                        "phone": "(713) 329-3503"
                    },
                    "ship_to": {
                        "name": "'.$address['first_name']. '' . $address['last_name'] .'",
                        "address_line1": "' . $address['address_line_1'] . '",
                        "city_locality": "' . $address['city'] . '",
                        "state_province": "' . $address['state'] . '",
                        "postal_code": "' . $address['zip_code'] . '",
                        "country_code": "US",
                        "address_residential_indicator":"yes"
                    },
                    "packages": [
                        {
                            "weight": {
                                "value": ' . $pro->package_weight . ',
                                "unit": "pound"
                            },
                            "dimensions": {
                                "length": ' . $pro->package_length . ',
                                "width": ' . $pro->package_width . ',
                                "height": ' . $pro->package_height . ',
                                "unit": "inch"
                            }
                        }
                    ]
                }]
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'API-Key: '.env('SHIP_ENGINE_KEY')
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $jsonData = json_decode($response, true);

        // dd($jsonData);

        if ((isset($jsonData['shipments'])) && (count($jsonData['shipments']) > 0)) {
            return $jsonData['shipments'][0]['shipment_id'];
        } else {
            return null;
        }
    }
}
