<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Product;
use App\Models\ProductAttibutes;
use App\Models\Setting;
use App\Models\TempData;
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
            return response()->json(['status' => false, 'Message' => 'Api under progress']);
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
            'qty' => 1, 'total_amount' => $product->price, 'regular_price' => $product->price, 'product_id' => $product->id, 'name' => $product->name, 'short_description' => $product->product_desc, 'sale_price' => $product->sale_price, 'image' => $proImg->attribute_value ?? '', 'package_weight' => $product->package_weight, 'package_weight_unit' => $product->package_weight_unit, 'package_length' => $product->package_length, 'package_length_unit' => $product->package_length_unit, 'package_width' => $product->package_width, 'package_width_unit' => $product->package_width_unit, 'package_height' => $product->package_height, 'package_height_unit' => $product->package_height_unit, 'content_creator_id' => $product->added_by, 'shippingId' => null, 'shippingPrice' => null, 'service_code' => null
        ];
        $data['totalQty'] = 1;
        $data['subTotal'] = $product->price;
        if (isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
            $data['tax'] = ($data['subTotal'] * $tax->attribute_value) / 100;
        else $data['tax'] = 0;
        $data['totalPrice'] = $product->price + $data['tax'];
        $data['totalItem'] = 1;
        $data['shippingId'] = 0;
        $data['shippingPrice'] = 0;
        $data['shippingTitle'] = 'Shipping';
        $data['isCouponApplied'] = null;
        $data['appliedCouponCode'] = null;
        $data['appliedCouponPrice'] = null;
        $data['couponId'] = null;
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
                        $oldcart['products'][$i]['total_amount'] = $oldcart['products'][$i]['regular_price'] * $oldcart['products'][$i]['qty'];
                        $existingpro = true;
                    }
                    $data['products'][$i] = $oldcart['products'][$i];
                    $qty += $data['products'][$i]['qty'];
                    $price += $data['products'][$i]['total_amount'];
                } else if (!$existingpro) {
                    $data['products'][$i] = [
                        'qty' => 1, 'total_amount' => $product->price, 'regular_price' => $product->price, 'product_id' => $product->id, 'name' => $product->name, 'short_description' => $product->product_desc, 'sale_price' => $product->sale_price, 'image' => $proImg->attribute_value ?? '', 'package_weight' => $product->package_weight, 'package_weight_unit' => $product->package_weight_unit, 'package_length' => $product->package_length, 'package_length_unit' => $product->package_length_unit, 'package_width' => $product->package_width, 'package_width_unit' => $product->package_width_unit, 'package_height' => $product->package_height, 'package_height_unit' => $product->package_height_unit, 'content_creator_id' => $product->added_by, 'shippingId' => null, 'shippingPrice' => null, 'service_code' => null
                    ];
                    $qty += $data['products'][$i]['qty'];
                    $price += $data['products'][$i]['total_amount'];
                }
            }
        }

        $data['totalQty'] = $qty;
        $data['subTotal'] = $price;
        if (isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
            $data['tax'] = ($data['subTotal'] * $tax->attribute_value) / 100;
        else $data['tax'] = 0;
        $data['totalPrice'] = $price + $data['tax'];
        $data['totalItem'] = count($data['products']);
        $data['shippingId'] = 0;
        $data['shippingPrice'] = 0;
        $data['shippingTitle'] = 'Shipping';
        $data['isCouponApplied'] = null;
        $data['appliedCouponCode'] = null;
        $data['appliedCouponPrice'] = null;
        $data['couponId'] = null;
        $data['paymentMethod'] = "STRIPE";
        $data['addedDate'] = date('Y-m-d H:i:s');

        return $data;
    }

    public function updateCartProducts($pro, $old)
    {
        $proImg = ProductAttibutes::where('product_id', $pro->id)->where('attribute_code', 'cover_image')->first();
        $data = [
            'qty' => $old['qty'], 'total_amount' => $pro->price * $old['qty'], 'regular_price' => $pro->price, 'product_id' => $pro->id, 'name' => $pro->name, 'short_description' => $pro->product_desc, 'sale_price' => $pro->sale_price, 'image' => $proImg->attribute_value ?? '', 'package_weight' => $pro->package_weight, 'package_weight_unit' => $pro->package_weight_unit, 'package_length' => $pro->package_length, 'package_length_unit' => $pro->package_length_unit, 'package_width' => $pro->package_width, 'package_width_unit' => $pro->package_width_unit, 'package_height' => $pro->package_height, 'package_height_unit' => $pro->package_height_unit, 'content_creator_id' => $pro->added_by, 'shippingId' => null, 'shippingPrice' => null, 'service_code' => null
        ];
        return $data;
    }

    public function cart_list()
    {
        try {
            return response()->json(['status' => false, 'Message' => 'Api under progress']);
            $cart = TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->first();
            $tax = Setting::where('attribute_code', 'tax')->first();
            $qty = 0;
            $price = 0;
            if (isset($cart->id)) {
                $old = unserialize($cart->data);
                for ($i = 0; $i < count($old['products']); $i++) {
                    $pro = Product::where('id', $old['products'][$i]['product_id'])->first();
                    $old['products'][$i] = $this->updateCartProducts($pro, $old['products'][$i]);
                    $res['items'][$i]['image'] = url('upload/products/' . $old['products'][$i]['image']);
                    $res['items'][$i]['quantity'] = $old['products'][$i]['qty'];
                    $res['items'][$i]['total_amount'] = $old['products'][$i]['total_amount'];
                    $res['items'][$i]['regular_price'] = $old['products'][$i]['regular_price'];
                    $res['items'][$i]['sale_price'] = $old['products'][$i]['sale_price'];
                    $res['items'][$i]['product_id'] = $old['products'][$i]['product_id'];
                    $res['items'][$i]['name'] = $old['products'][$i]['name'];
                    $res['items'][$i]['short_description'] = $old['products'][$i]['short_description'];

                    $qty += $old['products'][$i]['qty'];
                    $price += $old['products'][$i]['total_amount'];
                }
                $res['subTotal'] = $old['subTotal'] = $price;
                $res['totalQty'] = $old['totalQty'] = $qty;
                $res['tax'] = $old['tax'] = ($old['subTotal'] * $tax->attribute_value) / 100;
                $res['totalPrice'] = $old['totalPrice'] = $old['subTotal'] + $old['tax'];
                $res['totalItem'] = $old['totalItem'];
                TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                    'data' => serialize($old)
                ]);
                $address = Address::where('user_id', auth()->user()->id)->get();
                return response()->json(['status' => true, 'message' => 'Cart list', 'data' => $res, 'address' => $address]);
            } else return response()->json(['status' => false, 'message' => 'No items found']);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function update_product_quantity(Request $request)
    {
        try {
            return response()->json(['status' => false, 'Message' => 'Api under progress']);
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
                if (isset($cart->id)) {
                    $old = unserialize($cart->data);
                    for ($i = 0; $i < count($old['products']); $i++) {
                        if ($old['products'][$i]['product_id'] == $request->product_id) {
                            $old['products'][$i]['qty'] = $request->quantity;
                            $old['products'][$i]['total_amount'] = $old['products'][$i]['qty'] * $old['products'][$i]['regular_price'];
                        }

                        $qty += $old['products'][$i]['qty'];
                        $price += $old['products'][$i]['total_amount'];

                        $old['totalQty'] = $qty;
                        $old['subTotal'] = $price;
                        if (isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
                            $old['tax'] = ($old['subTotal'] * $tax->attribute_value) / 100;
                        else $old['tax'] = 0;
                        $old['totalPrice'] = $old['subTotal'] + $old['tax'];
                        $old['totalItem'] = count($old['products']);
                    }
                    TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                        'data' => serialize($old)
                    ]);
                    return response()->json(['status' => true, 'message' => 'Quantity updated']);
                } else return response()->json(['status' => false, 'message' => 'No items found']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function shipping_address(Request $request)
    {
        try {
            return response()->json(['status' => false, 'Message' => 'Api under progress']);
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
                    } else return response()->json(['status' => false, 'message' => 'No items found']);
                } else return response()->json(['status' => false, 'message' => 'No address found']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function remove_cart(Request $request)
    {
        try {
            return response()->json(['status' => false, 'Message' => 'Api under progress']);
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
                if (isset($cart->id)) {
                    $old = unserialize($cart->data);
                    $data = $old;
                    for ($i = 0; $i < count($old['products']); $i++) {
                        if ($old['products'][$i]['product_id'] == $request->product_id) {
                            array_splice($data['products'], $i, 1);
                        } else {
                            $qty += $old['products'][$i]['qty'];
                            $price += $old['products'][$i]['total_amount'];
                        }
                        $data['totalQty'] = $qty;
                        $data['subTotal'] = $price;
                        if (isset($tax->id) && $tax->attribute_value != '' && $tax->attribute_value != 0)
                            $data['tax'] = ($data['subTotal'] * $tax->attribute_value) / 100;
                        else $data['tax'] = 0;
                        $data['totalPrice'] = $data['subTotal'] + $data['tax'];
                        $data['totalItem'] = count($data['products']);
                    }
                    TempData::where('user_id', auth()->user()->id)->where('type', 'cart')->update([
                        'data' => serialize($data)
                    ]);
                }
                return response()->json(['status' => true, 'message' => 'Item removed from cart.']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function choose_shipping(Request $request)
    {
        try {
            return response()->json(['status' => false, 'Message' => 'Api under progress']);
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
                    dd($rates);
                }
            }
            return response()->json(['status' => true, 'message' => 'Shipping options', 'data' => $data]);
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
                        "se-4941122",
                        "se-4941079",
                        "se-4970170"
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
                'API-Key: '
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $jsonData = json_decode($response, true);
        return $jsonData;
    }
}
