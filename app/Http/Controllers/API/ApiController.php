<?php

namespace App\Http\Controllers\API;

use DB;
use App\Models\Like;
use App\Models\User;
use App\Models\Course;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Certificate;
use App\Models\CardDetails;
use App\Models\Notification;
use App\Models\AddToCart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function home()
    {
        try {
            $user_id = Auth::user()->id;
            $datas = array();

            $trending_courses = Course::leftJoin('users', function($join) {
                $join->on('course.admin_id', '=', 'users.id');
            })
            ->where('course.status', 1)->orderBy('course.id', 'DESC')->get(); /*Get data of Treanding Course*/
            $b1 = array();
            $TrendingCourses = array();
            foreach ($trending_courses as $k => $data) {
                $b1['id'] = isset($data->id) ? $data->id : '';
                $b1['title'] = isset($data->title) ? $data->title : '';
                $b1['content_creator_name'] = isset($data->admin_name) ? $data->admin_name : '';
                $b1['content_creator_category'] = isset($data->category_name) ? $data->category_name : '';
                $b1['content_creator_id'] = isset($data->admin_id) ? $data->admin_id : '';
                $b1['description'] = isset($data->description) ? $data->description : '';
                $b1['admin_id'] = isset($data->admin_id) ? $data->admin_id : '';
                $b1['created_at'] = date('d/m/y,H:i', strtotime($data->created_at));
                $b1['rating'] = 4.6;
                $b1['course_fee'] = $data->course_fee;
                $b1['status'] = $data->status;
                $b1['tags'] = isset($data->tags) ? $data->tags : '';
                $b1['valid_upto'] = $data->valid_upto;
                if (!empty($data->certificates)) {
                    $b1['certificates_image'] = url('upload/course-certificates/' . $data->certificates);
                } else {
                    $b1['certificates_image'] = '';
                }
                if (!empty($data->introduction_image)) {
                    $b1['introduction_image'] = url('upload/disclaimers-introduction/' . $data->introduction_image);
                } else {
                    $b1['introduction_image'] = '';
                }
                $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $data->id)->where('object_type', '=', 1)->first();
                if (isset($exists)) {
                    $b1['isLike'] = 1;
                } else {
                    $b1['isLike'] = 0;
                }
                $TrendingCourses[] = $b1;
            }

            $top_category = Category::where('status', 1)->orderBy('id', 'DESC')->get(); /*Get data of category*/
            $b2 = array();
            $TopCategory = array();
            if(count($top_category) > 0)
            {
                foreach ($top_category as $k => $data) {
                    $b2['id'] = isset($data->id) ? $data->id : '';
                    $b2['category_name'] = isset($data->name) ? $data->name : '';
                    if (!empty($data->icon)) {
                        $b2['category_image'] = url('upload/category-image/' . $data->icon);
                    } else {
                        $b2['category_image'] = '';
                    }
                    $b2['cat_status'] = isset($data->status) ? $data->status : '';
                    $b2['created_at'] = date('d/m/y,H:i', strtotime($data->created_date));
                    $TopCategory[] = $b2;
                }
            }
            

            $suggested_courses = Course::leftJoin('users', function($join) {
                $join->on('course.admin_id', '=', 'users.id');
            })
            ->where('course.status', 1)->orderBy('course.id', 'DESC')->get(); /*Get data of Suggested Course*/
            $b3 = array();
            $SuggestedCourses = array();
            foreach ($suggested_courses as $k => $data) {
                $b3['id'] = isset($data->id) ? $data->id : '';
                $b3['title'] = isset($data->title) ? $data->title : '';
                $b3['content_creator_name'] = isset($data->admin_name) ? $data->admin_name : '';
                $b3['content_creator_category'] = isset($data->category_name) ? $data->category_name : '';
                $b3['content_creator_id'] = isset($data->admin_id) ? $data->admin_id : '';
                $b3['description'] = isset($data->description) ? $data->description : '';
                $b3['admin_id'] = isset($data->admin_id) ? $data->admin_id : '';
                $b3['created_at'] = date('d/m/y,H:i', strtotime($data->created_at));
                $b3['rating'] = 4.6;
                $b3['course_fee'] = $data->course_fee;
                $b3['status'] = $data->status;
                $b3['tags'] = isset($data->tags) ? $data->tags : '';
                $b3['valid_upto'] = $data->valid_upto;
                if (!empty($data->certificates)) {
                    $b3['certificates_image'] = url('upload/course-certificates/' . $data->certificates);
                } else {
                    $b3['certificates_image'] = '';
                }
                if (!empty($data->introduction_image)) {
                    $b3['introduction_image'] = url('upload/disclaimers-introduction/' . $data->introduction_image);
                } else {
                    $b3['introduction_image'] = '';
                }
                $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $data->id)->where('object_type', '=', 1)->first();
                if (isset($exists)) {
                    $b3['isLike'] = 1;
                } else {
                    $b3['isLike'] = 0;
                }
                $SuggestedCourses[] = $b3;
            }

            $all_products = Product::where('status', 1)->orderBy('id', 'DESC')->get(); /*Get data of All Product*/
            $b4 = array();
            $AllProducts = array();
            foreach ($all_products as $k => $data) {
                $b4['id'] = isset($data->id) ? $data->id : '';
                $b4['title'] = isset($data->title) ? $data->name : '';
                $b4['description'] = isset($data->description) ? $data->description : '';
                $b4['admin_id'] = isset($data->admin_id) ? $data->admin_id : '';
                $b4['created_at'] = date('d/m/y,H:i', strtotime($data->created_at));
                $b4['rating'] = 4.6;
                $b4['price'] = $data->price;
                $b4['status'] = $data->status;
                if (!empty($data->product_image)) {
                    $b4['Product_image'] = url('upload/products/' . $data->product_image);
                } else {
                    $b4['Product_image'] = '';
                }

                $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $data->id)->where('object_type', '=', 2)->first();
                if (isset($exists)) {
                    $b4['isLike'] = 1;
                } else {
                    $b4['isLike'] = 0;
                }
                $AllProducts[] = $b4;
            }

            $sug_products = Product::where('status', 1)->orderBy('id', 'DESC')->get(); /*Get data of Suggested Product*/
            $b5 = array();
            $SugProducts = array();
            foreach ($sug_products as $k => $data) {
                $b5['id'] = isset($data->id) ? $data->id : '';
                $b5['title'] = isset($data->title) ? $data->name : '';
                $b5['description'] = isset($data->description) ? $data->description : '';
                $b5['admin_id'] = isset($data->admin_id) ? $data->admin_id : '';
                $b5['created_at'] = date('d/m/y,H:i', strtotime($data->created_at));
                $b5['rating'] = 4.6;
                $b5['price'] = $data->price;
                $b5['status'] = $data->status;
                if (!empty($data->product_image)) {
                    $b5['Product_image'] = url('upload/products/' . $data->product_image);
                } else {
                    $b5['Product_image'] = '';
                }
                $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $data->id)->where('object_type', '=', 2)->first();
                if (isset($exists)) {
                    $b5['isLike'] = 1;
                } else {
                    $b5['isLike'] = 0;
                }
                $SugProducts[] = $b5;
            }

            $Sug_category = Category::where('status', 1)->orderBy('id', 'DESC')->get(); /*Get data of Suggested category*/
            $b6 = array();
            $SugCategory = array();
            if(count($Sug_category)>0){
                foreach ($Sug_category as $k => $data) {
                    $b6['id'] = isset($data->id) ? $data->id : '';
                    $b6['category_name'] = isset($data->name) ? $data->name : '';
                    if (!empty($data->icon)) {
                        $b6['category_image'] = url('upload/category-image/' . $data->icon);
                    } else {
                        $b6['category_image'] = '';
                    }
                    $b6['cat_status'] = isset($data->status) ? $data->status : '';
                    $b6['created_at'] = date('d/m/y,H:i', strtotime($data->created_date));
                    $SugCategory[] = $b6;
                }
            }
            

            $datas['trending_course'] = $TrendingCourses;
            $datas['top_category'] = $TopCategory;
            $datas['suggested_course'] = $SuggestedCourses;
            $datas['all_product'] = $AllProducts;
            $datas['suggested_product'] = $SugProducts;
            $datas['suggested_category'] = $SugCategory;
            return response()->json(['status' => true, 'message' => 'Home Page Listing', 'data' => $datas]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function wishlist_listing(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            if($user_id)
            {
                $validator = Validator::make($request->all(), [
                    'type' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                
                $type = $request->type;
                if ($type == 1) { /* 1 stand for course ,2 for product */
                    $datas = Wishlist::where('userid', $user_id)->where('status', 1)->where('object_type', 1)->orderBy('id', 'DESC')->get();
                } else {
                    $datas = Wishlist::where('userid', $user_id)->where('status', 1)->where('object_type', 2)->orderBy('id', 'DESC')->get();
                }
    
                $response = array();
                if (isset($datas)) {
                    foreach ($datas as $keys => $item) {
                        if ($type == 1) { /* 1 stand for course ,2 for product */
                            $value = Course::leftJoin('users', function($join) {
                                $join->on('course.admin_id', '=', 'users.id');
                            })
                            ->where('course.status', 1)->where('course.id', $item->object_id)->orderBy('id', 'DESC')->first();
                            $temp['course_fee'] = $value->course_fee;
                            $temp['valid_upto'] = $value->valid_upto;
                            if (!empty($value->certificates)) {
                                $temp['certificates_image'] = url('upload/course-certificates/' . $value->certificates);
                            } else {
                                $temp['certificates_image'] = '';
                            }
                            if (!empty($value->introduction_image)) {
                                $temp['introduction_image'] = url('upload/disclaimers-introduction/' . $value->introduction_image);
                            } else {
                                $temp['introduction_image'] = '';
                            }
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $temp['title'] = $value->title;
                            $temp['content_creator_name'] = isset($value->admin_name) ? $value->admin_name : '';
                            $temp['content_creator_category'] = isset($value->category_name) ? $value->category_name : '';
                            $temp['content_creator_id'] = isset($value->admin_id) ? $value->admin_id : '';
                        } else {
                            $value = Product::where('status', 1)->where('id', $item->object_id)->orderBy('id', 'DESC')->first();
                            $temp['price'] = $value->price;
                            if (!empty($value->product_image)) {
                                $temp['Product_image'] = url('upload/products/' . $value->product_image);
                            } else {
                                $temp['Product_image'] = '';
                            }
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $temp['title'] = $value->name;
                            $temp['content_creator_name'] = isset($value->added_name) ? $value->added_name : '';
                            $temp['content_creator_category'] = '';
                            $temp['content_creator_id'] = '';
                        }
                        $temp['id'] = $value->id;
                        $temp['description'] = $value->description;
                        $temp['tags'] = $value->tags;
                        $temp['status'] = $value->status;
                        $temp['rating'] = 4.6;
                        $temp['created_date'] = date('d/m/y,H:i', strtotime($value->created_date));
                        $response[] = $temp;
                    }
                }
                if ($type == 1) {
                    return response()->json(['status' => true, 'message' => 'Course Listing', 'data' => $response]);
                } else {
                    return response()->json(['status' => true, 'message' => 'Product Listing', 'data' => $response]);
                }
            }else{
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function trending_course(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }
            $user_id = Auth::user()->id;
            $limit = $request->limit;
            if ($limit == 0) { /* 0 stand for limit ,1 for all */
                $course = Course::leftJoin('users', function($join) {
                    $join->on('course.admin_id', '=', 'users.id');
                })
                ->where('course.status', 1)->orderBy('course.id', 'DESC')->limit(2)->get();
            } else {
                $course = Course::leftJoin('users', function($join) {
                    $join->on('course.admin_id', '=', 'users.id');
                })
                ->where('course.status', 1)->orderBy('course.id', 'DESC')->get();
            }


            $response = array();
            if (isset($course)) {
                foreach ($course as $keys => $item) {
                    $temp['id'] = $item->id;
                    $temp['admin_id'] = $item->admin_id;
                    $temp['title'] = $item->title;
                    $temp['description'] = $item->description;
                    $temp['course_fee'] = $item->course_fee;
                    $temp['tags'] = $item->tags;
                    $temp['valid_upto'] = $item->valid_upto;
                    if (!empty($item->certificates)) {
                        $temp['certificates_image'] = url('assets/upload/course-certificates/' . $item->certificates);
                    } else {
                        $temp['certificates_image'] = '';
                    }
                    if (!empty($value->introduction_image)) {
                        $temp['introduction_image'] = url('assets/upload/disclaimers-introduction/' . $item->introduction_image);
                    } else {
                        $temp['introduction_image'] = '';
                    }
                    $temp['status'] = $item->status;
                    $temp['rating'] = 4.6;
                    $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $item->id)->where('object_type', '=', 1)->first();
                    if (isset($exists)) {
                        $temp['isLike'] = 1;
                    } else {
                        $temp['isLike'] = 0;
                    }
                    $temp['content_creator_name'] = isset($iten->admin_name) ? $item->admin_name : '';
                    $temp['content_creator_category'] = isset($item->category_name) ? $item->category_name : '';
                    $temp['content_creator_id'] = isset($item->admin_id) ? $item->admin_id : '';
                    $temp['created_date'] = date('d/m/y,H:i', strtotime($item->created_date));
                    $response[] = $temp;
                }
            }
            return response()->json(['status' => true, 'message' => 'Trending Couse Listing', 'data' => $response]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function course_listing()
    {
        try {
            $user_id = Auth::user()->id;
            $course = Course::leftJoin('users', function($join) {
                $join->on('course.admin_id', '=', 'users.id');
            })
            ->where('course.status', 1)->orderBy('course.id', 'DESC')->get();
            $response = array();
            if (isset($course)) {
                foreach ($course as $keys => $item) {
                    $temp['id'] = $item->id;
                    $temp['admin_id'] = $item->admin_id;
                    $temp['title'] = $item->title;
                    $temp['description'] = $item->description;
                    $temp['course_fee'] = $item->course_fee;
                    $temp['tags'] = $item->tags;
                    $temp['valid_upto'] = $item->valid_upto;
                    $temp['certificates_image'] = $item->certificates;
                    $temp['introduction_image'] = $item->introduction_image;
                    $temp['status'] = $item->status;
                    $temp['rating'] = 4.6;
                    $temp['is_like'] = 1;
                    $temp['content_creator_name'] = isset($iten->admin_name) ? $item->admin_name : '';
                    $temp['content_creator_category'] = isset($item->category_name) ? $item->category_name : '';
                    $temp['content_creator_id'] = isset($item->admin_id) ? $item->admin_id : '';
                    $temp['created_date'] = date('d/m/y,H:i', strtotime($item->created_date));
                    $response[] = $temp;
                }
            }
            return response()->json(['status' => true, 'message' => 'Couse Listing', 'data' => $response]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function all_category()
    {
        try {
            $user_id = Auth::user();
            if ($user_id) {
                $category = Category::where('status', 1)->orderBy('id', 'DESC')->get(); /*Get data of category*/
                $response = array();
                if (isset($category)) {
                    foreach ($category as $keys => $item) {
                        $temp['id'] = $item->id;
                        $temp['category_name'] = $item->name;
                        $temp['category_image'] = url('upload/category-image/' .$item->icon);
                        $temp['status'] = $item->status;
                        $temp['created_date'] = date('d/m/y,H:i', strtotime($item->created_date));
                        $response[] = $temp;
                    }
                }
                return response()->json(['status' => true, 'message' => 'Category Listing', 'data' => $response]);
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function search_category(Request $request)
    {
        try {
            $user_id = Auth::user();
            $data = array();
            if ($user_id) {
                $datas = array();
                if ($request->has('query')) {
                    $search = $request->q;
                    $datas = Category::where('name', 'LIKE', "%$search%")
                        ->where('cat_status', 1)
                        ->get();
                } else {
                    $datas = Category::where('status', 1)->get();
                }
                return response()->json(['status' => true, 'message' => 'Category Listing', 'data' => $datas]);
            } else {
                $data['status'] = false;
                $data['message'] = "Please Login";
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function search_object_type_all(Request $request)
    {
        try {
            $user_id = Auth::user();
            $data = array();
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required', /*1:course,2:Product*/
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $datas = array();
                if ($request->has('query')) {
                    $search = $request->q;
                    if ($request->type == 1) {
                        $datas = Course::leftJoin('users', function($join) {
                            $join->on('course.admin_id', '=', 'users.id');
                        })
                        ->where('course.status', 1)->where('course.title', 'LIKE', "%$search%")
                        ->orWhere('course.admin_name', 'LIKE', "%$search%")->orderBy('course.id', 'DESC')->get();
                    } else {
                        $datas = Product::where('status', 1)->where('name', 'LIKE', "%$search%")
                            ->orWhere('creator_name', 'LIKE', "%$search%")
                            ->orderBy('id', 'DESC')->get();
                    }


                } else {
                    if ($request->type == 1) {
                        $datas = Course::leftJoin('users', function($join) {
                            $join->on('course.admin_id', '=', 'users.id');
                        })
                        ->where('course.status', 1)->orderBy('course.id', 'DESC')->get();
                    } else {
                        $datas = Product::where('status', 1)
                            ->orderBy('id', 'DESC')->get();
                    }
                }
                if ($request->type == 1) {
                    return response()->json(['status' => true, 'message' => 'Course Listing', 'data' => $datas]);
                } else {
                    return response()->json(['status' => true, 'message' => 'Product Listing', 'data' => $datas]);
                }


            } else {
                $data['status'] = false;
                $data['message'] = "Please Login";
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function search_object_type_trending(Request $request)
    {
        try {
            $user_id = Auth::user();
            $data = array();
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required', /*1:course,2:Product*/
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $datas = array();
                if ($request->has('query')) {
                    $search = $request->q;
                    if ($request->type == 1) {
                        $datas = Course::leftJoin('users', function($join) {
                            $join->on('course.admin_id', '=', 'users.id');
                        })
                        ->where('course.status', 1)->where('course.title', 'LIKE', "%$search%")
                            ->orWhere('course.admin_name', 'LIKE', "%$search%")->orderBy('course.id', 'DESC')->get();
                    } else {
                        $datas = Product::where('status', 1)->where('name', 'LIKE', "%$search%")
                            ->orWhere('creator_name', 'LIKE', "%$search%")
                            ->orderBy('id', 'DESC')->get();
                    }


                } else {
                    if ($request->type == 1) {
                        $datas = Course::leftJoin('users', function($join) {
                            $join->on('course.admin_id', '=', 'users.id');
                        })
                        ->where('course.status', 1)->orderBy('course.id', 'DESC')->get();
                    } else {
                        $datas = Product::where('status', 1)
                            ->orderBy('id', 'DESC')->get();
                    }
                }
                if ($request->type == 1) {
                    return response()->json(['status' => true, 'message' => 'Course Listing', 'data' => $datas]);
                } else {
                    return response()->json(['status' => true, 'message' => 'Product Listing', 'data' => $datas]);
                }


            } else {
                $data['status'] = false;
                $data['message'] = "Please Login";
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function search_object_type_suggest(Request $request)
    {
        try {
            $user_id = Auth::user();
            $data = array();
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required', /*1:course,2:Product*/
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $datas = array();
                if ($request->has('query')) {
                    $search = $request->q;
                    if ($request->type == 1) {
                        $datas = Course::leftJoin('users', function($join) {
                            $join->on('course.admin_id', '=', 'users.id');
                        })
                        ->where('course.status', 1)->where('course.title', 'LIKE', "%$search%")
                            ->orWhere('course.admin_name', 'LIKE', "%$search%")->orderBy('course.id', 'DESC')->get();
                    } else {
                        $datas = Product::where('status', 1)->where('name', 'LIKE', "%$search%")
                            ->orWhere('creator_name', 'LIKE', "%$search%")
                            ->orderBy('id', 'DESC')->get();
                    }


                } else {
                    if ($request->type == 1) {
                        $datas = Course::leftJoin('users', function($join) {
                            $join->on('course.admin_id', '=', 'users.id');
                        })
                        ->where('course.status', 1)->orderBy('course.id', 'DESC')->get();
                    } else {
                        $datas = Product::where('status', 1)
                            ->orderBy('id', 'DESC')->get();
                    }
                }
                if ($request->type == 1) {
                    return response()->json(['status' => true, 'message' => 'Course Listing', 'data' => $datas]);
                } else {
                    return response()->json(['status' => true, 'message' => 'Product Listing', 'data' => $datas]);
                }


            } else {
                $data['status'] = false;
                $data['message'] = "Please Login";
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function suggested_list(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }
            $user_id = Auth::user()->id;
            $type = $request->type;
            if ($type == 1) { /* 1 stand for course ,2 for product */
                $datas = Course::leftJoin('users', function($join) {
                    $join->on('course.admin_id', '=', 'users.id');
                })
                ->where('course.status', 1)->orderBy('course.id', 'DESC')->get();
            } else {
                $datas = Product::where('status', 1)->orderBy('id', 'DESC')->get();
            }
      
            $response = array();
            if (isset($datas)) {
                foreach ($datas as $keys => $value) {
                    if ($type == 1) { /* 1 stand for course ,2 for product */
                        $temp['course_fee'] = $value->course_fee;
                        $temp['valid_upto'] = $value->valid_upto;
                        if (!empty($value->certificates)) {
                            $temp['certificates_image'] = url('assets/upload/course-certificates/' . $value->certificates);
                        } else {
                            $temp['certificates_image'] = '';
                        }
                        if (!empty($value->introduction_image)) {
                            $temp['introduction_image'] = url('assets/upload/disclaimers-introduction/' . $value->introduction_image);
                        } else {
                            $temp['introduction_image'] = '';
                        }
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $temp['title'] = $value->title;
                        $temp['content_creator_name'] = isset($value->admin_name) ? $value->admin_name : '';
                        $temp['content_creator_category'] = isset($value->category_name) ? $value->category_name : '';
                        $temp['content_creator_id'] = isset($value->admin_id) ? $value->admin_id : '';
                    } else {
                        $temp['price'] = $value->price;
                        if (!empty($value->product_image)) {
                            $temp['Product_image'] = url('assets/upload/products/' . $value->product_image);
                        } else {
                            $temp['Product_image'] = '';
                        }
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $temp['title'] = $value->name;
                        $temp['added_by'] = $value->added_by;
                    }
                    $temp['id'] = $value->id;
                    
                    
                    $temp['description'] = $value->description;
                    $temp['tags'] = $value->tags;
                    $temp['status'] = $value->status;
                    $temp['rating'] = 4.6;
                    $temp['created_date'] = date('d/m/y,H:i', strtotime($value->created_date));
                    $response[] = $temp;
                }
            }
            if ($type == 1) {
                return response()->json(['status' => true, 'message' => 'Suggested Course Listing', 'data' => $response]);
            } else {
                return response()->json(['status' => true, 'message' => 'Suggested Product Listing', 'data' => $response]);
            }


        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Listing of Course & Product*/
    public function all_type_listing(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }
            $user_id = Auth::user()->id;
            $type = $request->type;
            if ($type == 1) {
                $datas = Course::leftJoin('users', function($join) {
                    $join->on('course.admin_id', '=', 'users.id');
                })
                ->where('course.status', 1)->orderBy('course.id', 'DESC')->get();
            } else {
                $datas = Product::where('status', 1)->orderBy('id', 'DESC')->get();
            }

            $response = array();
            if (isset($datas)) {
                foreach ($datas as $keys => $value) {
                    if ($type == 1) { /* 1 stand for course ,2 for product */

                        $temp['course_fee'] = $value->course_fee;
                        $temp['valid_upto'] = $value->valid_upto;
                        if (!empty($value->certificates)) {
                            $temp['certificates_image'] = url('assets/upload/course-certificates/' . $value->certificates);
                        } else {
                            $temp['certificates_image'] = '';
                        }
                        if (!empty($value->introduction_image)) {
                            $temp['introduction_image'] = url('assets/upload/disclaimers-introduction/' . $value->introduction_image);
                        } else {
                            $temp['introduction_image'] = '';
                        }
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $temp['title'] = $value->title;
                        $temp['content_creator_name'] = isset($value->admin_name) ? $value->admin_name : '';
                        $temp['content_creator_category'] = isset($value->category_name) ? $value->category_name : '';
                        $temp['content_creator_id'] = isset($value->admin_id) ? $value->admin_id : '';
                    } else {
                        $temp['price'] = $value->price;
                        if (!empty($value->product_image)) {
                            $temp['Product_image'] = url('assets/upload/products/' . $value->product_image);
                        } else {
                            $temp['Product_image'] = '';
                        }
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $temp['title'] = $value->name;
                        $temp['added_by'] = $value->admin_id;
                        $temp['added_name'] = $value->admin_id;
                    }
                    $temp['id'] = $value->id;
                    
                    
                    $temp['description'] = $value->description;
                    $temp['tags'] = $value->tags;
                    $temp['status'] = $value->status;
                    $temp['rating'] = 4.6;
                    $temp['created_date'] = date('d/m/y,H:i', strtotime($value->created_date));
                    $response[] = $temp;
                }
            }
            if ($type == 1) {
                return response()->json(['status' => true, 'message' => ' Course Listing', 'data' => $response]);
            } else {
                return response()->json(['status' => true, 'message' => ' Product Listing', 'data' => $response]);
            }


        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Course details & Product details*/
    public function object_type_details(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'id' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $type = $request->type;
                $id = $request->id;
                if ($type == 1) { /* 1 stand for course ,2 for product */
                    $item = Course::leftJoin('users', function($join) {
                        $join->on('course.admin_id', '=', 'users.id');
                    })
                    ->where('course.status', 1)->where('course.id', $id)->orderBy('course.id', 'DESC')->first();
                } else {
                    $item = Product::where('status', 1)->where('id', $id)->orderBy('id', 'DESC')->first();
                }

                if (!empty($item)) {
                    if ($type == 1) { /* 1 stand for course ,2 for product */
                        $temp['course_fee'] = $item->course_fee;
                        $temp['valid_upto'] = $item->valid_upto;
                        if (!empty($item->certificates)) {
                            $temp['certificates_image'] = url('assets/upload/course-certificates/' . $item->certificates);
                        } else {
                            $temp['certificates_image'] = '';
                        }
                        if (!empty($item->introduction_image)) {
                            $temp['introduction_image'] = url('assets/upload/disclaimers-introduction/' . $item->introduction_image);
                        } else {
                            $temp['introduction_image'] = '';
                        }
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $item->id)->where('object_type', '=', 1)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $temp['title'] = $item->title;
                        $temp['content_creator_name'] = isset($item->admin_name) ? $item->admin_name : '';
                        $temp['content_creator_category'] = isset($item->category_name) ? $item->category_name : '';
                        $temp['content_creator_id'] = isset($item->admin_id) ? $item->admin_id : '';
                    } else {
                        $temp['price'] = $item->price;
                        if (!empty($item->product_image)) {
                            $temp['Product_image'] = url('assets/upload/products/' . $item->product_image);
                        } else {
                            $temp['Product_image'] = '';
                        }
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $item->id)->where('object_type', '=', 2)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $temp['title'] = $item->name;
                        $temp['added_by'] = $item->admin_id;
                        $temp['added_name'] = $item->admin_id;
                    }
                    $temp['id'] = $item->id;
                   
                    
                    $temp['description'] = $item->description;
                    $temp['tags'] = $item->tags;
                    $temp['status'] = $item->status;
                    $temp['rating'] = 4.6;
                    $temp['created_date'] = date('d/m/y,H:i', strtotime($item->created_date));
                    if ($type == 1) {
                        return response()->json(['status' => true, 'message' => ' Course Listing', 'data' => $temp]);
                    } else {
                        return response()->json(['status' => true, 'message' => ' Product Listing', 'data' => $temp]);
                    }
                } else {
                    if ($type == 1) {
                        return response()->json(['status' => true, 'message' => ' Course Listing', 'data' => '']);
                    } else {
                        return response()->json(['status' => true, 'message' => ' Product Listing', 'data' => '']);
                    }
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }

        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function add_wishlist(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->id) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    /* Type for 1 = Course, 2:Product (Object Type)*/
                    'id' => 'required',
                    /* Id of Course Or Product (Object ID)*/
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $u_id = $user->id;
                $item_id = $request->id;
                $item_type = $request->type;
                $status = 1;
                $exist = Wishlist::where('userid', $u_id)->where('object_type', $item_type)->where('object_id', $item_id)->first();
                /* Status check for liked post 1 = Already liked , 2 = Create new liked post */
                if ($exist) {
                    return response()->json(['status' => false, 'Message' => 'Already favourites',]);
                } else {
                    $data = DB::table('user_wishlist')->insert([
                        'object_id' => (int) $item_id,
                        'object_type' => (int) $item_type,
                        'userid' => (int) $u_id,
                        'status' => $status,
                    ]);
                    return response()->json(['status' => true, 'Message' => 'Added to favourites',]);
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function remove_wishlist(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->id) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    /* Type for 1 = Course, 2:Product (Object Type)*/
                    'id' => 'required',
                    /* Id of Course Or Product (Object ID)*/
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $u_id = $user->id;
                $item_id = $request->id;
                $item_type = $request->type;
                $status = 0;
                $exist = Wishlist::where('userid', $u_id)->where('object_type', $item_type)->where('object_id', $item_id)->first();
                /* Status check for liked post 1 = Already liked , 2 = Create new liked post */
                if ($exist) {
                    Wishlist::where('userid', $u_id)->where('object_type', $item_type)->where('object_id', $item_id)->delete();
                    return response()->json(['status' => true, 'Message' => 'Removed to favourites',]);
                } else {
                    return response()->json(['status' => false, 'Message' => 'Something went wrong.',]);
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function submit_review(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->id) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    /* Type for 1 = Course, 2:Product (Object Type)*/
                    'id' => 'required',
                    /* Id of Course Or Product (Object ID)*/
                    'rating' => 'required',
                    'comment' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $user_id = $user->id;
                $object_id = $request->id;
                $object_type = $request->type;
                $comment = $request->comment;
                $rating = $request->rating;
                $exist = Review::where('userid', $user_id)->where('object_id', $object_id)->where('object_type', $object_type)->first();
                if (isset($exist)) {
                    Review::where('id', $exist->id)->update(['rating' => $rating, 'review' => $comment]);
                    return response()->json(['status' => true, 'Message' => 'Updated to Reviews']);
                } else {
                    $save = Review::create([
                        'userid' => $user_id,
                        'object_id' => $object_id,
                        'object_type' => $object_type,
                        'rating' => $rating,
                        'review' => $comment,
                        'status' => 1,
                    ]);
                    if ($save) {
                        return response()->json(['status' => true, 'Message' => 'Review added successfully']);
                    } else {
                        return response()->json(['status' => false, 'Message' => 'Already reviewed']);
                    }
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function review_list(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->id) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'type' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $user_id = $user->id;
                $object_id = $request->id;
                $object_type = $request->type;
                $review = Review::where('object_id', $object_id)->where('object_type', $object_type)->get();
                if (count($review) > 0) {
                    $data = [];
                    foreach ($review as $key => $c) {
                        $data[$key] = $c->toArray();
                        $data[$key]['review'] = $c->review;
                        $user_name = User::where('id', $c->userid)->first();
                        $data[$key]['user_name'] = $user_name->first_name . ' ' . $user_name->last_name;
                    }
                    return response()->json([
                        "status" => true,
                        "message" => "Review List",
                        "review_list" => $data
                    ]);
                } else {
                    return response()->json(['status' => false, 'Message' => 'No data', 'review_list' => []]);
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function profile()
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $user = User::where('id', $user_id)->first(); /*Get data of category*/
                if (isset($user)) {
                    $temp['id'] = $user->id;
                    $temp['email'] = $user->email;
                    $temp['first_name'] = ucfirst($user->first_name);
                    $temp['last_name'] = ucfirst($user->last_name);
                    $temp['phone'] = $user->phone;
                    if ($user->profile_image) {
                        $temp['profile_image'] = url('assets/upload/profile-image/' . $user->profile_image);
                    } else {
                        $temp['profile_image'] = url('assets/superadmin-images/no-image.png');
                    }
                    $temp['company'] = $user->company;
                    $temp['professional_title'] = 'Tatto Artist';
                    $temp['timezone'] = 'Arkansas';
                    $temp['created_date'] = date('d/m/y', strtotime($user->created_date));
                }
                return response()->json(['status' => true, 'message' => 'User Details', 'data' => $temp]);
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function change_password(Request $request)
    {
        try {
            $user = Auth::user();
            $u_id = $user->id;
            $data = array();
            if ($u_id) {
                $old_password = $request->current_password;
                $new_password = $request->new_password;
                $datas = User::where('id', $u_id)->first();
                $u_password = $datas->password;
                if (Hash::check($old_password, $u_password)) {
                    $updatedata = array('password' => bcrypt($new_password));
                    $id = User::where('id', $u_id)->update($updatedata);
                    if ($id) {
                        $data['status'] = true;
                        $data['message'] = "Password change successfully";
                        return response()->json($data);
                    } else {
                        $data['status'] = false;
                        $data['message'] = "Something went wrong";
                        return response()->json($data);
                    }
                } else {
                    $data['status'] = true;
                    $data['message'] = "Password does not match";
                    return response()->json($data);
                }
            } else {
                $data['status'] = false;
                $data['message'] = "Please Login";
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function certificates()
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $datas = Certificate::where('user_id', $user_id)->get(); /*Get data of category*/
                $response = array();
                if (isset($datas)) {
                    foreach ($datas as $keys => $item) {
                        $temp['id'] = $item->id;
                        $temp['user_id'] = $item->user_id;
                        if ($item->certificate_image) {
                            $temp['certificate_image'] = url('upload/certificate-image/' . $item->certificate_image);
                        } else {
                            $temp['certificate_image'] = '';
                        }
                        $temp['rating'] = 4.9;
                        $temp['name'] = 'Max bryant';
                        $response[] = $temp;
                    }
                }
                return response()->json(['status' => true, 'message' => 'Certificate Listing', 'data' => $response]);
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function notifications()
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $datas = Notification::where('user_id', $user_id)->get(); /*Get data of category*/
                $response = array();
                if (isset($datas)) {
                    foreach ($datas as $keys => $item) {
                        $temp['id'] = $item->id;
                        $temp['user_id'] = $item->user_id;
                        $temp['title'] = $item->title;
                        $temp['message'] = $item->message;
                        $temp['type'] = $item->type;
                        $temp['is_read'] = $item->is_read;
                        if ($item->image) {
                            $temp['image'] = url('assets/upload/notification-image/' . $item->image);
                        } else {
                            $temp['image'] = '';
                        }
                        $temp['created_date'] = date('d/m/y,H:i', strtotime($item->created_at));
                        $response[] = $temp;
                    }
                }
                return response()->json(['status' => true, 'message' => 'Notifications Listing', 'data' => $response]);
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function save_card_listing(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $card = CardDetails::where('user_id', $user_id)->get();
                if (count($card) > 0) {
                    $response = [];
                    foreach ($card as $key => $value) {
                        $temp['card_id'] = $value->id;
                        $temp['card_number'] = encrypt_decrypt('decrypt', $value->card_number);
                        $temp['card_holder_name'] = $value->card_holder_name;
                        $temp['cvv'] = encrypt_decrypt('decrypt', $value->cvv);
                        $temp['valid_upto'] = encrypt_decrypt('decrypt', $value->valid_upto);
                        $temp['card_type'] = $value->card_type;

                        $card_type = $value->card_type;
                        if ($card_type == 'VISA') {
                            $temp['card_image'] = url('assets/upload/notification-image/visa.png');
                        } else {
                            $temp['card_image'] = url('assets/upload/notification-image/m-card.png');
                        }
                        $response[] = $temp;
                    }
                    return response()->json(['status' => true, 'message' => 'Card list found.', 'data' => $response]);
                } else
                    return response()->json(['status' => true, 'message' => 'You have no card.']);
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Exception => ' . $e->getMessage()]);
        }
    }

    public function add_card(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'card_number' => 'required|numeric',
                'valid_upto' => 'required',
                'cvv' => 'required',
                'card_holder_name' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            } else {
                $card = new CardDetails;
                $card->user_id = auth()->user()->id;
                $card->card_number = encrypt_decrypt('encrypt', $request->card_number);
                $card->valid_upto = encrypt_decrypt('encrypt', $request->valid_upto);
                $card->cvv = encrypt_decrypt('encrypt', $request->cvv);
                $card->card_holder_name = $request->card_holder_name;
                $card->card_type = 'VISA';
                $card->created_at = date('Y-m-d H:i:s');
                $card->save();
                return response()->json(['status' => true, 'message' => 'Card Added']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function delete_card(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $card = CardDetails::where('id', $request->id)->delete();
                return response()->json(['status' => true, 'message' => 'Card deleted']);
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function add_to_cart(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'object_id' => 'required',
                    'object_type' => 'required',
                    'cart_value' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $type = $request->object_type;
                $object_type = $request->object_type;
                $object_id = $request->object_id;
                if ($object_type == 1) { /* 1 stand for course ,2 for product */
                    $datas = Course::leftJoin('users', function($join) {
                        $join->on('course.admin_id', '=', 'users.id');
                    })
                    ->where('course.id', $object_id)->first();
                } else {
                    $datas = Product::where('id', $object_id)->first();
                }
                $cart = new AddToCart;
                $cart->userid = $user_id;
                $cart->object_id = $object_id;
                $cart->object_type = $object_type;
                $cart->cart_value = $request->cart_value;
                $cart->save();
                if ($cart) {
                    return response()->json(['status' => true, 'message' => 'Cart Added']);
                } else {
                    return response()->json(['status' => true, 'message' => 'Something went wrong!']);
                }

            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function cart_list(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $datas = AddToCart::where('userid', $user_id)->orderBy('id', 'DESC')->get();
                $cart_value = AddToCart::where('userid', $user_id)->sum('cart_value');

                $response = array();
                if (isset($datas)) {
                    foreach ($datas as $keys => $item) {
                        $temp['id'] = $item->id;
                        $temp['userid'] = $item->userid;
                        $temp['object_id'] = $item->object_id;
                        if ($item->object_type == 1) { /* 1 stand for course ,2 for product */
                            $value = Course::leftJoin('users', function($join) {
                                $join->on('course.admin_id', '=', 'users.id');
                            })
                            ->where('course.id', $item->object_id)->first();
                            $temp['title'] = $value->title;
                            $temp['added_by'] = $value->admin_id;
                            $temp['added_name'] = $value->admin_name;
                            $temp['price'] = $value->course_fee;
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                        } else {
                            $value = Product::where('id', $item->object_id)->first();
                            $temp['title'] = $value->name;
                            $temp['added_by'] = $value->added_by;
                            $temp['added_name'] = $value->added_name;
                            $temp['price'] = $value->price;
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                        }
                        $temp['rating'] = 4.6;
                        $response[] = $temp;
                    }
                    $shipping_amount = 10;
                    $discount = 0;
                    $total_amount = ($cart_value + $shipping_amount) - $discount;
                    return response()->json([
                        'status' => true,
                        'message' => 'Cart Listing',
                        'sub_total' => $cart_value,
                        'discount' => $discount,
                        'shipping' => $shipping_amount,
                        'total' => $total_amount,
                        'data' => $response
                    ]);
                } else {
                    return response()->json([
                        'status' => true,
                        'message' => 'Cart Listing',
                        'sub_total' => 0,
                        'discount' => 0,
                        'shipping' => 0,
                        'total' => 0,
                        'data' => $response
                    ]);
                }

            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function cart_details_payment_page(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $cart_value = AddToCart::where('userid', $user_id)->sum('cart_value');
                $shipping_amount = 10;
                $discount = 0;
                $total_amount = ($cart_value + $shipping_amount) - $discount;
                $card = CardDetails::where('user_id', $user_id)->get();
                $response = [];
                if (count($card) > 0) {

                    foreach ($card as $key => $value) {
                        $temp['card_id'] = $value->id;
                        $temp['card_number'] = encrypt_decrypt('decrypt', $value->card_number);
                        $temp['card_holder_name'] = $value->card_holder_name;
                        $temp['cvv'] = encrypt_decrypt('decrypt', $value->cvv);
                        $temp['valid_upto'] = encrypt_decrypt('decrypt', $value->valid_upto);
                        $temp['card_type'] = $value->card_type;

                        $card_type = $value->card_type;
                        if ($card_type == 'VISA') {
                            $temp['card_image'] = url('assets/upload/notification-image/visa.png');
                        } else {
                            $temp['card_image'] = url('assets/upload/notification-image/m-card.png');
                        }
                        $response[] = $temp;
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'Card list found.',
                        'sub_total' => $cart_value,
                        'discount' => $discount,
                        'shopping' => $shipping_amount,
                        'total' => $total_amount,
                        'data' => $response
                    ]);
                } else {
                    return response()->json([
                        'status' => true,
                        'message' => 'You have no card.',
                        'sub_total' => $cart_value,
                        'discount' => $discount,
                        'shipping' => $shipping_amount,
                        'total' => $total_amount,
                        'data' => $response
                    ]);
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function save_order(Request $request)
    {
        try {
            $data = array();
            $user_id = Auth::user()->id;
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'shipping_address' => 'required|string|max:255|min:1',
                    //'transaction_id' => 'required',
                    'amount' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 202);
                }
                $user_id = $this->hasUser->id;
                $carts = Addtocart::where('user_id', $user_id)->get();
                $transaction_id = $request->transaction_id ? $request->transaction_id : rand(1000000000, 9999999999);
                $order_no = rand(10000000, 99999999);
                $website = Website::where('id', 1)->first();
                $tax = $website->tax;
                $shipping_cost = $website->shipping_cost;
                $order_price = Addtocart::where('user_id', $user_id)->sum('total_price');
                $extra = $tax + $shipping_cost;
                $order_items = Addtocart::where('user_id', $user_id)->count();
                $today = date('y:m:d');
                $expected_date = Date('d-F-Y', strtotime('+10 days'));
                if (!empty($request->coupon_id)) {
                    $ApplyCoupon = ApplyCoupon::where('user_id', $this->hasUser->id)->where('id', $request->coupon_id)->first();
                    $coupon_id = isset($ApplyCoupon->coupon_id) ? $ApplyCoupon->coupon_id : 0;
                    $coupon_price = isset($ApplyCoupon->coupon_amount) ? $ApplyCoupon->coupon_amount : 0;
                    $price = $order_price + $tax + $shipping_cost;
                    $total_price = $price - $coupon_price;
                } else {
                    $coupon_id = 0;
                    $coupon_price = 0;
                    $total_price = $order_price + $tax + $shipping_cost;
                }
                if (count($carts) > 0) {
                    /*Create Order */
                    $order = new Order;
                    $order->user_id = $user_id;
                    $User = User::where('id', $user_id)->where('status', 1)->orderBy('id', 'DESC')->first();
                    $order->user_name = $User->first_name . '' . $User->last_name;
                    $order->user_email = $User->email;
                    $order->user_country = Country($User->country_code);
                    $order->order_product = $request->order_item;
                    $order->order_no = $order_no;
                    $order->tax = $tax;
                    $order->shipping_cost = $shipping_cost;
                    $order->coupon_id = $coupon_id;
                    $order->coupon_price = $coupon_price;
                    $order->total_price = $total_price;
                    $order->shipping_address = $request->shipping_address;
                    $order->transaction_id = $transaction_id;
                    $order->qty = $order_items; //Count of Order Items
                    $order->expected_date = $expected_date;
                    $order->delivery_inst = isset($request->delivery_inst) ? $request->delivery_inst : '';
                    $order->order_status = 1;
                    $order->payment_status = 'Complete';
                    $order_id = $order->save();
                    $taxes = $extra / $order_items;
                    foreach ($carts as $cart) {
                        $order = new Orderitem;
                        $order->user_id = $cart->user_id;
                        $order->order_no = $order_no;
                        $order->item_no = rand(10000000, 99999999);
                        $order->product_id = $cart->product_id;
                        $products = Product::where('id', $cart->product_id)->first();
                        $order->shop_id = $products->shop_id;
                        $order->product_price = $cart->product_price;
                        $order->qty = $cart->qty;
                        $order->expected_date = $expected_date;
                        $order->order_status = 1;
                        $order->op_date = date('d F Y, g:i A');
                        $order->item_totalPrice = ($cart->total_price + $taxes);
                        $order->transaction_id = $transaction_id;
                        $order_id = $order->save();
                    }
                    if (!empty($request->coupon_id)) {
                        $input = [
                            'order_id' => $order_no,
                            'use_status' => 1,
                        ];
                        ApplyCoupon::where('user_id', $this->hasUser->id)->where('id', $request->coupon_id)->update($input);
                    }
                    $transaction = new Transaction;
                    $transaction->user_id = $this->hasUser->id;
                    $User = User::where('id', $this->hasUser->id)->where('status', 1)->orderBy('id', 'DESC')->first();
                    $transaction->user_name = $User->first_name . '' . $User->last_name;
                    $transaction->user_country = Country($User->country_code);
                    $transaction->transaction_id = $transaction_id;
                    $transaction->order_no = $order_no;
                    $transaction->payment_status = 'Complete';
                    $transaction->amount = $total_price;
                    $transaction->payment_recevied = 1;
                    $transaction_id = $transaction->save();
                    Addtocart::where('user_id', $user_id)->delete();
                    $data['status'] = 1;
                    $data['message'] = 'Order create successfully';
                    return response()->json($data);
                } else {
                    $data['status'] = 0;
                    $data['message'] = 'Opps!Order Cart is Empty';
                    return response()->json($data);
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function coupon_list()
    {
        $data = array();
        $user_id = Auth::user()->id;
        if ($user_id) {
            $val = Coupon::where('status', 1)->get();
            if (!empty($val)) {
                $data['status'] = 1;
                $data['message'] = 'Coupon data';
                $data['data'] = $val;
                return response()->json($data);
            } else {
                $data['status'] = 0;
                $data['message'] = 'No Records';
                $data['data'] = '';
                return response()->json($data);
            }
        } else {
            $data['status'] = 0;
            $data['message'] = 'Please login';
            $data['data'] = '';
            return response()->json($data);
        }
    }

    // public function applyed_coupon(Request $request)
    // {
    //     $data = array();
    //     if($this->hasUser)
    //     {
    //         $validator = Validator::make($request->all() , [
    //             'coupon_id' =>  'required',
    //             'order_amount' => 'required',
    //         ]);
    //         if ($validator->fails())
    //         {
    //             return response()->json($validator->errors() , 202);
    //         }
    //         $today = date("Y-m-d");
    //         $coupon = Coupon::where('id',$request->coupon_id)->first();
    //         if($coupon->end_date >= $today)
    //         {
    //             $ApplyCoupon = ApplyCoupon::where('user_id',$this->hasUser->id)
    //             ->where('coupon_id',$request->coupon_id)->get();
    //             $ApplyCoupon = count($ApplyCoupon);
    //             $use_limit = $coupon->use_total;
    //             if($use_limit >= $ApplyCoupon)
    //             {
    //                 if($coupon->type == 0)
    //                 {
    //                     $amount = $request->order_amount*$coupon->discount/100; /* Coupon Amount By Percentage */
    //                     $coupon_amount = (int)$amount;
    //                 }else{
    //                     $amount = (int)$coupon->discount; /* Coupon Amount By Flate */
    //                     $coupon_amount = $amount;
    //                 }
    //                 $val = new ApplyCoupon;
    //                 $val->user_id = $this->hasUser->id;
    //                 $val->order_id = 0;
    //                 $val->coupon_id = $request->coupon_id;
    //                 $val->coupon_amount = $coupon_amount;
    //                 $val->promo_code = $coupon->promo_code;
    //                 $val->order_amount = $request->order_amount;
    //                 $val->use_status = 0;
    //                 $val->remaing_amount = $request->order_amount - $coupon_amount;
    //                 if($coupon->min_order_value <= $request->order_amount)
    //                 {
    //                     $val->save();
    //                     $coupon_id = $val->id;
    //                     if(!empty($coupon_id))
    //                     {
    //                         $data['status'] = 1;
    //                         $data['message'] = 'Coupon Added Successfully';
    //                         $data['coupon_id'] = $coupon_id;
    //                         return response()->json($data);
    //                     }else{
    //                         $data['status'] = 1;
    //                         $data['message'] = 'Somethings went Wrong!';
    //                         return response()->json($data);
    //                     }
    //                 }else{
    //                     $data['status'] = 1;
    //                     $data['message'] = 'Sorry! Not valid for this order';
    //                     return response()->json($data);
    //                 }
    //             }else{
    //                 $data['status'] = 1;
    //                 $data['message'] = 'Already redeemed';
    //                 return response()->json($data);
    //             }
    //         }else{
    //             $data['status'] = 1;
    //             $data['message'] = 'Coupon has been expired!';
    //             return response()->json($data);
    //         }
    //     }else{
    //         $data['status'] = 0;
    //         $data['message'] = 'Please login';
    //         return response()->json($data);
    //     }
    // }

    // public function remove_coupon(Request $request)
    // {
    //     $data = array();
    //     if ($this->hasUser)
    //     {
    //         $validator = Validator::make($request->all() , [
    //             //'cart_id' => 'required|integer|min:1',
    //             'coupon_id' =>  'required',
    //         ]);
    //         if ($validator->fails())
    //         {
    //             return response()->json($validator->errors() , 202);
    //         }
    //         $coupons = ApplyCoupon::where('id','=',$request->coupon_id)->where('user_id','=',$this->hasUser->id)->first();
    //         if(!empty($coupons))
    //         {
    //             $coupons = ApplyCoupon::where('id','=',$request->coupon_id)->where('user_id','=',$this->hasUser->id)->delete();
    //             $data['status']=1;
    //             $data['message']='Coupon remove sucessfully';
    //             return response()->json($data);
    //         }else{
    //             $data['status']=1;
    //             $data['message']='No available coupon cart';
    //             return response()->json($data);
    //         }
    //     }else
    //     {
    //         $data['status'] = 0;
    //         $data['message'] = 'Please login';
    //         return response()->json($data);
    //     }
    // }
}