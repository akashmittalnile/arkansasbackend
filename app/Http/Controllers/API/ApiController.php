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
use App\Models\CardDetail;
use App\Models\Notification;
use App\Models\AddToCart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductAttibutes;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ChapterQuiz;
use App\Models\ChapterQuizOption;
use App\Models\CourseChapter;
use App\Models\CourseChapterStep;
use App\Models\Tag;
use App\Models\UserChapterStatus;
use App\Models\UserCourse;
use App\Models\UserQuizAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDF;

class ApiController extends Controller
{
    public function home()
    {
        try {
            $user_id = Auth::user()->id;
            $datas = array();

            $trending_courses = Course::leftJoin('users as u', function($join) {
                $join->on('course.admin_id', '=', 'u.id');
            })
            ->where('course.status', 1)->orderBy('course.id', 'DESC')->select('course.title', 'course.description', 'course.id', 'course.course_fee', 'course.tags', 'course.valid_upto', 'course.certificates', 'course.introduction_image', 'u.first_name', 'u.last_name', 'u.category_name')->get(); /*Get data of Treanding Course*/
            $b1 = array();
            $TrendingCourses = array();
            foreach ($trending_courses as $k => $data) {
                $b1['id'] = isset($data->id) ? $data->id : '';
                $b1['title'] = isset($data->title) ? $data->title : '';
                $b1['content_creator_name'] = $data->first_name.' '.$data->last_name;
                $b1['content_creator_category'] = isset($data->category_name) ? $data->category_name : '';
                $b1['content_creator_id'] = isset($data->admin_id) ? $data->admin_id : '';
                if ($data->profile_image) {
                    $profile_image = url('upload/profile-image/'.$data->profile_image);
                } else {
                    $profile_image = '';
                }
                $b1['content_creator_image'] = $profile_image;
                $b1['description'] = isset($data->description) ? $data->description : '';
                $b1['admin_id'] = isset($data->admin_id) ? $data->admin_id : '';
                $b1['created_at'] = date('d/m/y,H:i', strtotime($data->created_at));
                $b1['rating'] = 4.6;
                $b1['course_fee'] = $data->course_fee;
                $b1['status'] = $data->status;

                $tags = [];
                if(isset($data->tags)){
                    foreach(unserialize($data->tags) as $value){
                        $name = Tag::where('id', $value)->first();
                        if(isset($name->id)){
                            $temparory['name'] = $name->tag_name ?? null;
                            $temparory['id'] = $name->id ?? null;
                            $tags[] = $temparory;
                        }
                    }
                }

                $b1['tags'] = $tags;
                $b1['valid_upto'] = $data->valid_upto;
                if (!empty($data->certificates)) {
                    $b1['certificates_image'] = url('upload/course-certificates/' . $data->certificates);
                } else {
                    $b1['certificates_image'] = '';
                }
                if (!empty($data->introduction_image)) {
                    $b1['introduction_video'] = url('upload/disclaimers-introduction/' . $data->introduction_image);
                } else {
                    $b1['introduction_video'] = '';
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

            $product_category = Category::where('status', 1)->where('type', 2)->orderBy('id', 'DESC')->get(); /*Get data of category*/
            $b7 = array();
            $ProductCategory = array();
            if(count($product_category) > 0)
            {
                foreach ($product_category as $k => $data) {
                    $b7['id'] = isset($data->id) ? $data->id : '';
                    $b7['category_name'] = isset($data->name) ? $data->name : '';
                    if (!empty($data->icon)) {
                        $b7['category_image'] = url('upload/category-image/' . $data->icon);
                    } else {
                        $b7['category_image'] = '';
                    }
                    $b7['cat_status'] = isset($data->status) ? $data->status : '';
                    $b7['created_at'] = date('d/m/y,H:i', strtotime($data->created_date));
                    $ProductCategory[] = $b7;
                }
            }

            $course_category = Category::where('status', 1)->where('type', 1)->orderBy('id', 'DESC')->get(); /*Get data of category*/
            $b8 = array();
            $CourseCategory = array();
            if(count($course_category) > 0)
            {
                foreach ($course_category as $k => $data) {
                    $b8['id'] = isset($data->id) ? $data->id : '';
                    $b8['category_name'] = isset($data->name) ? $data->name : '';
                    if (!empty($data->icon)) {
                        $b8['category_image'] = url('upload/category-image/' . $data->icon);
                    } else {
                        $b8['category_image'] = '';
                    }
                    $b8['cat_status'] = isset($data->status) ? $data->status : '';
                    $b8['created_at'] = date('d/m/y,H:i', strtotime($data->created_date));
                    $CourseCategory[] = $b8;
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
                
                $b3['content_creator_category'] = isset($data->category_name) ? $data->category_name : '';
                $b3['content_creator_id'] = isset($data->admin_id) ? $data->admin_id : '';
                if ($data->profile_image) {
                    $profile_image = url('upload/profile-image/'.$data->profile_image);
                } else {
                    $profile_image = '';
                }
                $b3['content_creator_image'] = $profile_image;
                $b3['content_creator_name'] = $data->first_name.' '.$data->last_name;
                $b3['description'] = isset($data->description) ? $data->description : '';
                $b3['admin_id'] = isset($data->admin_id) ? $data->admin_id : '';
                $b3['created_at'] = date('d/m/y,H:i', strtotime($data->created_at));
                $b3['rating'] = 4.6;
                $b3['course_fee'] = $data->course_fee;
                $b3['status'] = $data->status;
                $tags = [];
                if(isset($data->tags)){
                    foreach(unserialize($data->tags) as $value){
                        $name = Tag::where('id', $value)->first();
                        if(isset($name->id)){
                            $temparory['name'] = $name->tag_name ?? null;
                            $temparory['id'] = $name->id ?? null;
                            $tags[] = $temparory;
                        }
                    }
                }

                $b3['tags'] = $tags;
            
                $b3['valid_upto'] = $data->valid_upto;
                if (!empty($data->certificates)) {
                    $b3['certificates_image'] = url('upload/course-certificates/' . $data->certificates);
                } else {
                    $b3['certificates_image'] = '';
                }
                if (!empty($data->introduction_image)) {
                    $b3['introduction_video'] = url('upload/disclaimers-introduction/' . $data->introduction_image);
                } else {
                    $b3['introduction_video'] = '';
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
                $b4['title'] = isset($data->name) ? $data->name : '';
                $b4['description'] = isset($data->product_desc) ? $data->product_desc : '';
                $User = User::where('id', $data->added_by)->first();
                $b4['creator_name'] = $User->first_name.' '.$data->last_name;
                if ($User->profile_image == '') {
                    $profile_image = '';
                } else {
                    $profile_image = url('upload/profile-image/' . $User->profile_image);
                }

                $tags = [];
                if(isset($data->tags)){
                    foreach(unserialize($data->tags) as $value){
                        $name = Tag::where('id', $value)->first();
                        if(isset($name->id)){
                            $temparory['name'] = $name->tag_name ?? null;
                            $temparory['id'] = $name->id ?? null;
                            $tags[] = $temparory;
                        }
                    }
                }

                $b4['tags'] = $tags;
                $b4['creator_image'] = $profile_image;
                $b4['creator_id'] = $data->added_by;
                $b4['created_at'] = date('d/m/y,H:i', strtotime($data->created_date));
                $b4['rating'] = 4.6;
                $b4['price'] = $data->price;
                $b4['status'] = $data->status;
                $all_products_image = ProductAttibutes::where('product_id', $data->id)->orderBy('id', 'ASC')->get(); /*Get data of All Product*/
                $datas_image = array();
                foreach ($all_products_image as $k => $val) {
                    $datasImage = url('upload/products/' . $val->attribute_value);
                    $datas_image[] = $datasImage;
                }
                $b4['Product_image'] = $datas_image;
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
                $User = User::where('id', $data->added_by)->first();
                $b5['creator_name'] = $User->first_name.' '.$User->last_name;
                if ($User->profile_image == '') {
                    $profile_image = '';
                } else {
                    $profile_image = url('upload/profile-image/' . $User->profile_image);
                }

                $tags = [];
                if(isset($data->tags)){
                    foreach(unserialize($data->tags) as $value){
                        $name = Tag::where('id', $value)->first();
                        if(isset($name->id)){
                            $temparory['name'] = $name->tag_name ?? null;
                            $temparory['id'] = $name->id ?? null;
                            $tags[] = $temparory;
                        }
                    }
                }

                $b5['tags'] = $tags;
                $b5['creator_image'] = $profile_image;
                $b5['creator_id'] = $data->added_by;
                $b5['created_at'] = date('d/m/y,H:i', strtotime($data->created_at));
                $b5['rating'] = 4.6;
                $b5['price'] = $data->price;
                $b5['status'] = $data->status;
                $all_products_image = ProductAttibutes::where('product_id', $data->id)->orderBy('id', 'ASC')->get(); /*Get data of All Product*/
                $datas_image = array();
                foreach ($all_products_image as $k => $val) {
                    $datasImage = url('upload/products/' . $val->attribute_value);
                    $datas_image[] = $datasImage;
                }
                $b5['Product_image'] = $datas_image;
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
            
            $allTags = [];
            $tagsQuery = Tag::all();
            if(isset($tagsQuery)){
                foreach($tagsQuery as $value){
                    $temparory['name'] = $value->tag_name;
                    $temparory['id'] = $value->id;
                    $temparory['type'] = $value->type;
                    $temparory['type_name'] = ($value->type == 1) ? "Course" : "Product";
                    $allTags[] = $temparory;
                }
            }

            $datas['trending_course'] = $TrendingCourses;
            $datas['top_category'] = $TopCategory;
            $datas['course_category'] = $CourseCategory;
            $datas['suggested_course'] = $SuggestedCourses;
            $datas['product_category'] = $ProductCategory;
            $datas['all_product'] = $AllProducts;
            $datas['suggested_product'] = $SugProducts;
            $datas['suggested_category'] = $SugCategory;
            $datas['all_tags'] = $allTags;


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
                                $temp['introduction_video'] = url('upload/disclaimers-introduction/' . $value->introduction_image);
                            } else {
                                $temp['introduction_video'] = '';
                            }
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $temp['title'] = $value->title;
                            if ($value->profile_image) {
                                $profile_image = url('upload/profile-image/'.$value->profile_image);
                            } else {
                                $profile_image = '';
                            }
                            $temp['content_creator_image'] = $profile_image;
                            $temp['content_creator_name'] = $value->first_name.' '.$value->last_name;
                            $temp['content_creator_category'] = isset($value->category_name) ? $value->category_name : '';
                            $temp['content_creator_id'] = isset($value->admin_id) ? $value->admin_id : '';
                        } else {
                            $value = Product::where('status', 1)->where('id', $item->object_id)->orderBy('id', 'DESC')->first();
                            $temp['price'] = $value->price;
                            $all_products_image = ProductAttibutes::where('product_id', $value->id)->orderBy('id', 'ASC')->get(); /*Get data of All Product*/
                            $datas_image = array();
                            foreach ($all_products_image as $k => $val) {
                                $datasImage = url('upload/products/' . $val->attribute_value);
                                $datas_image[] = $datasImage;
                            }
                            $temp['Product_image'] = $datas_image;
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $temp['title'] = $value->name;
                            $User = User::where('id', $value->added_by)->first();
                            $temp['creator_name'] = $User->first_name.' '.$User->last_name;
                            if ($User->profile_image == '') {
                                $profile_image = '';
                            } else {
                                $profile_image = url('upload/profile-image/' . $User->profile_image);
                            }
                            $temp['creator_image'] = $profile_image;
                            $temp['creator_id'] = $value->added_by;
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

            $course = Course::leftJoin('users as u', function($join) {
                $join->on('course.admin_id', '=', 'u.id');
            })->where('course.status', 1);
            if($request->filled('title')){
                $course->where('course.title', 'like' , '%' . $request->title . '%');
            }
            if($request->filled('category')){
                $course->whereIntegerInRaw('course.category_id', $request->category);
            }
            if($request->filled('rating')){
                $course->join('user_review as ur', 'ur.object_id', '=', 'course.id');
                $course->where('ur.rating', $request->rating)->where('ur.object_type', 1);
            }
            if($request->filled('price')){
                if($request->price == 1) $course->orderByDesc('course.course_fee');
                else $course->orderBy('course.course_fee');
            } else{
                $course->orderBy('course.id', 'DESC');
            }
            if ($limit == 0) {
                $course->limit(2);
            }
            $course = $course->select('course.id', 'course.admin_id','course.title', 'course.description', 'course.course_fee', 'course.tags', 'course.valid_upto', 'course.certificates', 'course.introduction_image', 'course.created_date', 'u.first_name', 'u.last_name', 'u.category_name')->get();

            // if ($limit == 0) { /* 0 stand for limit ,1 for all */
            //     $course = Course::leftJoin('users', function($join) {
            //         $join->on('course.admin_id', '=', 'users.id');
            //     })
            //     ->where('course.status', 1)->orderBy('course.id', 'DESC')->limit(2)->get();
            // } else {
            //     $course = Course::leftJoin('users', function($join) {
            //         $join->on('course.admin_id', '=', 'users.id');
            //     })
            //     ->where('course.status', 1)->orderBy('course.id', 'DESC')->get();
            // }


            $response = array();
            if (isset($course)) {
                foreach ($course as $keys => $item) {
                    $temp['id'] = $item->id;
                    $temp['admin_id'] = $item->admin_id;
                    $temp['title'] = $item->title;
                    $temp['description'] = $item->description;
                    $temp['course_fee'] = $item->course_fee;
                    $tags = [];
                    if(isset($item->tags)){
                        foreach(unserialize($item->tags) as $val){
                            $name = Tag::where('id', $val)->first();
                            $temparory['name'] = $name->tag_name;
                            $temparory['id'] = $name->id;
                            $tags[] = $temparory;
                        }
                    }
                    $temp['tags'] = $tags;
                    $temp['valid_upto'] = $item->valid_upto;
                    if (!empty($item->certificates)) {
                        $temp['certificates_image'] = url('upload/course-certificates/' . $item->certificates);
                    } else {
                        $temp['certificates_image'] = '';
                    }
                    if (!empty($item->introduction_image)) {
                        $temp['introduction_video'] = url('upload/disclaimers-introduction/' . $item->introduction_image);
                    } else {
                        $temp['introduction_video'] = '';
                    }
                    $temp['status'] = $item->status;
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
                    $avgRating = DB::table('user_review as ur')->where('object_id', $item->id)->where('object_type', 1)->avg('rating');
                    $temp['avg_rating'] = number_format($avgRating, 1);
                    if($request->filled('rating'))
                        if($avgRating < min($request->rating)) continue;
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
                    $temp['introduction_video'] = $item->introduction_image;
                    $temp['status'] = $item->status;
                    $temp['rating'] = 4.6;
                    $temp['is_like'] = 1;
                    if ($item->profile_image) {
                        $profile_image = url('upload/profile-image/'.$item->profile_image);
                    } else {
                        $profile_image = '';
                    }
                    $temp['content_creator_image'] = $profile_image;
                    $temp['content_creator_name'] = $item->first_name.' '.$item->last_name;
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
                        $temp['type'] = $item->type;
                        $temp['type_name'] = ($item->type==1) ? "Course" : "Product";
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
                })->where('course.status', 1);
                if($request->filled('title')){
                    $datas->where('course.title', 'like' , '%' . $request->title . '%');
                }
                if($request->filled('category')){
                    $datas->whereIntegerInRaw('course.category_id', $request->category);
                }
                if($request->filled('price')){
                    if($request->price == 1) $datas->orderByDesc('course.course_fee');
                    else $datas->orderBy('course.course_fee');
                } else{
                    $datas->orderBy('course.id', 'DESC');
                }
                $datas = $datas->select('course.*', 'users.first_name', 'users.last_name','users.profile_image','users.category_name')->get();
            } else {
                $datas = Product::where('product.status', 1);
                if($request->filled('title')){
                    $datas->where('product.name', 'LIKE' . '%' . $request->title . '%');
                }
                if($request->filled('category')){
                    $datas->whereIntegerInRaw('product.category_id', $request->category);
                }
                if($request->filled('price')){
                    if($request->price == 1) $datas->orderByDesc('product.price');
                    else $datas->orderBy('product.price');
                } else{
                    $datas->orderBy('product.id', 'DESC');
                }
                $datas = $datas->orderBy('product.id', 'DESC')->get();
            }
      
            $response = array();
            if (isset($datas)) {
                foreach ($datas as $keys => $value) {
                    if ($type == 1) { /* 1 stand for course ,2 for product */
                        $temp['course_fee'] = $value->course_fee;
                        $temp['valid_upto'] = $value->valid_upto;
                        if (!empty($value->certificates)) {
                            $temp['certificates_image'] = url('upload/course-certificates/' . $value->certificates);
                        } else {
                            $temp['certificates_image'] = '';
                        }
                        if (!empty($value->introduction_image)) {
                            $temp['introduction_video'] = url('upload/disclaimers-introduction/' . $value->introduction_image);
                        } else {
                            $temp['introduction_video'] = '';
                        }
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $wishlist = Wishlist::where('userid', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->where('status', 1)->first();
                        if (isset($wishlist)) {
                            $temp['isWishlist'] = 1;
                        } else {
                            $temp['isWishlist'] = 0;
                        }
                        $temp['title'] = $value->title;
                        if ($value->profile_image) {
                            $profile_image = url('upload/profile-image/'.$value->profile_image);
                        } else {
                            $profile_image = '';
                        }
                        $temp['content_creator_image'] = $profile_image;
                        $temp['content_creator_name'] = $value->first_name.' '.$value->last_name;
                        $temp['content_creator_category'] = isset($value->category_name) ? $value->category_name : '';
                        $temp['content_creator_id'] = isset($value->admin_id) ? $value->admin_id : '';
                        $avgRating = DB::table('user_review as ur')->where('object_id', $value->id)->where('object_type', 1)->avg('rating');
                        $temp['avg_rating'] = number_format($avgRating, 1);
                        if($request->filled('rating'))
                            if($avgRating < min($request->rating)) continue;
                    } else {
                        $temp['price'] = $value->price;
                        $all_products_image = ProductAttibutes::where('product_id', $value->id)->orderBy('id', 'ASC')->get(); /*Get data of All Product*/
                        $datas_image = array();
                        foreach ($all_products_image as $k => $val) {
                            $datasImage = url('upload/products/' . $val->attribute_value);
                            $datas_image[] = $datasImage;
                        }
                        $temp['Product_image'] = $datas_image;
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $wishlist = Wishlist::where('userid', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->where('status', 1)->first();
                        if (isset($wishlist)) {
                            $temp['isWishlist'] = 1;
                        } else {
                            $temp['isWishlist'] = 0;
                        }
                        $temp['title'] = $value->name;
                        $User = User::where('id', $value->added_by)->first();
                        $temp['creator_name'] = $User->first_name.' '.$User->last_name;
                        if ($User->profile_image == '') {
                            $profile_image = '';
                        } else {
                            $profile_image = url('upload/profile-image/' . $User->profile_image);
                        }
                        $temp['creator_image'] = $profile_image;
                        $temp['creator_id'] = $value->added_by;
                        $avgRating = DB::table('user_review as ur')->where('object_id', $value->id)->where('object_type', 2)->avg('rating');
                        $temp['avg_rating'] = number_format($avgRating, 1);
                        if($request->filled('rating'))
                            if($avgRating < min($request->rating)) continue;
                    }
                    $temp['id'] = $value->id;
                    $temp['description'] = $value->description;
                    $tags = [];
                    if(isset($value->tags)){
                        foreach(unserialize($value->tags) as $val){
                            $name = Tag::where('id', $val)->first();
                            if(isset($name->id)){
                                $temparory['name'] = $name->tag_name ?? null;
                                $temparory['id'] = $name->id ?? null;
                                $tags[] = $temparory;
                            }
                        }
                    }
                    $temp['tags'] = $tags;
                    $temp['status'] = $value->status;
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
                })->where('course.status', 1);
                if($request->filled('title')){
                    $datas->where('course.title', 'like' , '%' . $request->title . '%');
                }
                if($request->filled('category')){
                    $datas->whereIntegerInRaw('course.category_id', $request->category);
                }
                if($request->filled('price')){
                    if($request->price == 1) $datas->orderByDesc('course.course_fee');
                    else $datas->orderBy('course.course_fee');
                } else{
                    $datas->orderBy('course.id', 'DESC');
                }
                $datas = $datas->select('course.*', 'users.first_name', 'users.last_name','users.profile_image','users.category_name')->get();
            } else {
                $datas = Product::where('product.status', 1);
                if($request->filled('title')){
                    $datas->where('product.name', 'like' , '%' . $request->title . '%');
                }
                if($request->filled('category')){
                    $datas->whereIntegerInRaw('product.category_id', $request->category);
                }
                if($request->filled('price')){
                    if($request->price == 1) $datas->orderByDesc('product.price');
                    else $datas->orderBy('product.price');
                } else{
                    $datas->orderBy('product.id', 'DESC');
                }
                $datas = $datas->get();
            }

            $response = array();
            if (isset($datas)) {
                foreach ($datas as $keys => $value) {
                    if($request->filled('tag'))
                        if(!in_array($request->tag, unserialize($value->tags))) continue;
                    if ($type == 1) { /* 1 stand for course ,2 for product */
                            $temp['course_fee'] = $value->course_fee;
                            $temp['valid_upto'] = $value->valid_upto;
                            if (!empty($value->certificates)) {
                                $temp['certificates_image'] = url('upload/course-certificates/' . $value->certificates);
                            } else {
                                $temp['certificates_image'] = '';
                            }
                            if (!empty($value->introduction_image)) {
                                $temp['introduction_video'] = url('upload/disclaimers-introduction/' . $value->introduction_image);
                            } else {
                                $temp['introduction_video'] = '';
                            }
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $wishlist = Wishlist::where('userid', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->where('status', 1)->first();
                            if (isset($wishlist)) {
                                $temp['isWishlist'] = 1;
                            } else {
                                $temp['isWishlist'] = 0;
                            }
                            $temp['title'] = $value->title;
                            if ($value->profile_image) {
                                $profile_image = url('upload/profile-image/'.$value->profile_image);
                            } else {
                                $profile_image = '';
                            }
                            $temp['content_creator_image'] = $profile_image;
                            $temp['content_creator_name'] = $value->first_name.' '.$value->last_name;
                            $temp['content_creator_category'] = isset($value->category_name) ? $value->category_name : '';
                            $temp['content_creator_id'] = isset($value->admin_id) ? $value->admin_id : '';
                            $avgRating = DB::table('user_review as ur')->where('object_id', $value->id)->where('object_type', 1)->avg('rating');
                            $temp['avg_rating'] = number_format($avgRating, 1);
                            if($request->filled('rating'))
                                if($avgRating < min($request->rating)) continue;
                    } else {
                            $temp['price'] = $value->price;
                            $all_products_image = ProductAttibutes::where('product_id', $value->id)->orderBy('id', 'ASC')->get(); /*Get data of All Product*/
                            $datas_image = array();
                            foreach ($all_products_image as $k => $val) {
                                $datasImage = url('upload/products/' . $val->attribute_value);
                                $datas_image[] = $datasImage;
                            }
                            $temp['Product_image'] = $datas_image;
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $wishlist = Wishlist::where('userid', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->where('status', 1)->first();
                            if (isset($wishlist)) {
                                $temp['isWishlist'] = 1;
                            } else {
                                $temp['isWishlist'] = 0;
                            }
                            $temp['title'] = $value->name;
                            $User = User::where('id', $value->added_by)->first();
                            $temp['creator_name'] = $User->first_name.' '.$User->last_name;
                            if ($User->profile_image == '') {
                                $profile_image = '';
                            } else {
                                $profile_image = url('upload/profile-image/' . $User->profile_image);
                            }
                            $temp['creator_image'] = $profile_image;
                            $temp['creator_id'] = $value->added_by;
                            $avgRating = DB::table('user_review as ur')->where('object_id', $value->id)->where('object_type', 2)->avg('rating');
                            $temp['avg_rating'] = number_format($avgRating, 1);
                            if($request->filled('rating'))
                                if($avgRating < min($request->rating)) continue;
                    }
                    $temp['id'] = $value->id;
                    $temp['description'] = $value->description;
                    $temp['status'] = $value->status;
                    $temp['created_date'] = date('d/m/y,H:i', strtotime($value->created_date));
                    $tags = [];
                    if(isset($value->tags)){
                        foreach(unserialize($value->tags) as $val){
                            $name = Tag::where('id', $val)->first();
                            if(isset($name->id)){
                                $temparory['name'] = $name->tag_name ?? null;
                                $temparory['id'] = $name->id ?? null;
                                $tags[] = $temparory;
                            }
                        }
                    }
                    $temp['tags'] = $tags;
                    $response[] = $temp;
                    
                }
            }
            $category = Category::select('id', 'name','description','icon','type')->where('status', 1)->orderByDesc('id')->get();
            if ($type == 1) {
                return response()->json(['status' => true, 'message' => ' Course Listing', 'data' => $response, 'category' => $category]);
            } else {
                return response()->json(['status' => true, 'message' => ' Product Listing', 'data' => $response, 'category' => $category]);
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
                    $item = Course::leftJoin('users as u', function($join) {
                        $join->on('course.admin_id', '=', 'u.id');
                    })
                    ->where('course.status', 1)->where('course.id', $id)->select('u.first_name', 'u.last_name', 'u.profile_image', 'u.category_name', 'course.*')->orderBy('course.id', 'DESC')->first();
                } else {
                    $item = Product::where('status', 1)->where('id', $id)->orderBy('id', 'DESC')->first();
                }

                if (!empty($item)) {
                    if ($type == 1) { /* 1 stand for course ,2 for product */
                        $temp['course_fee'] = $item->course_fee;
                        $temp['valid_upto'] = $item->valid_upto;
                        if (!empty($item->certificates)) {
                            $temp['certificates_image'] = url('upload/course-certificates/' . $item->certificates);
                        } else {
                            $temp['certificates_image'] = '';
                        }
                        if (!empty($item->introduction_image)) {
                            $temp['introduction_video'] = url('upload/disclaimers-introduction/' . $item->introduction_image);
                        } else {
                            $temp['introduction_video'] = '';
                        }
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $item->id)->where('object_type', '=', 1)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $wishlist = Wishlist::where('userid', '=', $user_id)->where('object_id', '=', $item->id)->where('object_type', '=', 1)->where('status', 1)->first();
                        if (isset($wishlist)) {
                            $temp['isWishlist'] = 1;
                        } else {
                            $temp['isWishlist'] = 0;
                        }
                        $temp['title'] = $item->title;
                        if ($item->profile_image) {
                            $profile_image = url('upload/profile-image/'.$item->profile_image);
                        } else {
                            $profile_image = '';
                        }
                        $temp['content_creator_image'] = $profile_image;
                        $temp['content_creator_name'] = $item->first_name.' '.$item->last_name;
                        $temp['content_creator_category'] = isset($item->category_name) ? $item->category_name : '';
                        $temp['content_creator_id'] = isset($item->admin_id) ? $item->admin_id : '';

                        $course_chapter = DB::table('course_chapter as cc')->where('cc.course_id', $id)->get();
                        $chapters = [];
                        $chapter_count = 0;
                        $chapter_quiz_count = 0;

                        $isPurchase = UserCourse::where('course_id', $id)->where('user_id', $user_id)->first();
                        $isPurchased = (isset($isPurchase->id)) ? true : false;
                        $temp['isPurchased'] = $isPurchased;

                        foreach($course_chapter as $keyc => $valc){
                            $arr['id'] = $valc->id;
                            $steps = CourseChapterStep::where('course_chapter_id', $valc->id)->get();
                            if(isset($steps) && count($steps)) $chapter_count++;
                            $chapter_steps = [];
                            foreach($steps as $vals){
                                $arr1['id'] = $vals->id;
                                $arr1['type'] = $vals->type;
                                if($vals->type == 'quiz') $chapter_quiz_count++;

                                $isComplete = UserChapterStatus::where('userid', $user_id)->where('course_id', $id)->where('chapter_id', $valc->id)->where('step_id', $vals->id)->first();
                                $isCompleted = isset($isComplete->id) ? $isComplete->status : 0;

                                if($isPurchased){
                                    if($isCompleted == 1){
                                        $total = ChapterQuiz::where('step_id', $vals->id)->whereIn('type', ['quiz', 'survey'])->sum('marks');
                                        $obtained = UserQuizAnswer::where('quiz_id', $vals->id)->where('userid', auth()->user()->id)->sum('marks_obtained');

                                        $arr1['quiz_url'] = ($vals->type == 'quiz') ? url('/').'/api/contest/'.encrypt_decrypt('encrypt',$valc->id).'/'.encrypt_decrypt('encrypt',$vals->id) . '/' . encrypt_decrypt('encrypt', $user_id) : null;
                                        $arr1['marks_obtained'] = $obtained;
                                        $arr1['marks_out_of'] = $total;
                                    } else {
                                        $arr1['quiz_url'] = null;
                                        $arr1['marks_obtained'] = null;
                                        $arr1['marks_out_of'] = null;
                                    } 
                                    $arr1['is_completed'] = $isCompleted;
                                }else{
                                    $arr1['quiz_url'] = null;
                                    $arr1['marks_obtained'] = null;
                                    $arr1['marks_out_of'] = null;
                                    $arr1['is_completed'] = null;
                                }
                                
                                $arr1['title'] = $vals->title;
                                $arr1['description'] = $vals->description;
                                if($vals->type == 'assignment'){
                                    $arr1['file'] = (isset($isComplete->id) ? (isset($isComplete->file) ? url('upload/course/' . $isComplete->file) : null) : null);
                                }else{
                                    $arr1['file'] = ($vals->details == null || $vals->details == "") ? null : url('upload/course/' . $vals->details);
                                }
                                
                                $arr1['filename'] = $vals->details;
                                $arr1['prerequisite'] = $vals->prerequisite;
                                $arr1['sort_order'] = $vals->sort_order;
                                $question = ChapterQuiz::where('step_id', $vals->id)->get();
                                $chapter_question = [];
                                foreach($question as $valq){
                                    $arr2['id'] = $valq->id;
                                    $arr2['title'] = $valq->title;
                                    $arr2['marks'] = $valq->marks;
                                    $arr2['type'] = $valq->type;

                                    $option = ChapterQuizOption::where('quiz_id', $valq->id)->get();
                                    $chapter_option = [];
                                    foreach($option as $valo){
                                        $arr3['id'] = $valo->id;
                                        $arr3['value'] = $valo->answer_option_key;
                                        $arr3['correct'] = $valo->is_correct;
                                        $arr3['correct_status'] = ($valo->is_correct == 1) ? "Correct" : "Wrong";
                                        $chapter_option[] = $arr3;
                                    }
                                    $arr2['chapter_option'] = $chapter_option;
                                    $chapter_question[] = $arr2;
                                }
                                $arr1['chapter_question'] = $chapter_question;
                                $chapter_steps[] = $arr1;
                            }
                            $arr['chapter_steps'] = $chapter_steps;
                            $chapters[] = $arr;
                        }

                        $temp['chapters'] = $chapters;
                        $temp['chapter_count'] = $chapter_count;
                        $temp['chapter_quiz_count'] = $chapter_quiz_count;
                        $reviewAvg = DB::table('user_review as ur')->where('object_id', $item->id)->where('object_type', 1)->avg('rating');
                    } else {
                        $temp['price'] = $item->price;
                        $all_products_image = ProductAttibutes::where('product_id', $item->id)->orderBy('id', 'ASC')->get(); /*Get data of All Product*/
                        $datas_image = array();
                        
                        foreach ($all_products_image as $k => $val) {
                            $datasImage = url('upload/products/' . $val->attribute_value);
                            $datas_image[] = $datasImage;
                        }
                        $temp['Product_image'] = $datas_image;
                        $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $item->id)->where('object_type', '=', 2)->first();
                        if (isset($exists)) {
                            $temp['isLike'] = 1;
                        } else {
                            $temp['isLike'] = 0;
                        }
                        $wishlist = Wishlist::where('userid', '=', $user_id)->where('object_id', '=', $item->id)->where('object_type', '=', 2)->where('status', 1)->first();
                        if (isset($wishlist)) {
                            $temp['isWishlist'] = 1;
                        } else {
                            $temp['isWishlist'] = 0;
                        }
                        $temp['title'] = $item->name;
                        $User = User::where('id', $item->added_by)->first();
                        $temp['creator_name'] = $User->first_name.' '.$User->last_name;
                        if ($User->profile_image == '') {
                            $profile_image = '';
                        } else {
                            $profile_image = url('upload/profile-image/' . $User->profile_image);
                        }
                        $temp['creator_image'] = $profile_image;
                        $temp['creator_id'] = $item->added_by;
                        $reviewAvg = DB::table('user_review as ur')->where('object_id', $item->id)->where('object_type', 2)->avg('rating');
                    }
                    $temp['id'] = $item->id;
                    
                    $tags = [];
                    if(isset($item->tags)){
                        foreach(unserialize($item->tags) as $value){
                            $name = Tag::where('id', $value)->first();
                            if(isset($name->id)){
                                $temparory['name'] = $name->tag_name ?? null;
                                $temparory['id'] = $name->id ?? null;
                                $tags[] = $temparory;
                            }
                        }
                    }
                    

                    // $chapters = DB::table('course_chapter as cc')->join('course_chapter_steps as ccs', 'cc.id', '=', 'ccs.course_chapter_id')->join('chapter_quiz as cq', 'ccs.id', '=', 'cq.step_id')->join('chapter_quiz_options as cqo', 'cq.id', '=', 'quiz_id')->where('cc.course_id', $id)->get();

                    $reviewCount = Review::where('userid', $user_id)->count();
                    
                    $review = DB::table('user_review as ur')->join('users as u', 'u.id', '=', 'ur.userid')->select('u.first_name', 'u.last_name', 'ur.rating', 'ur.review', 'ur.created_date', 'u.profile_image')->where('ur.object_id', $item->id)->where('object_type', $type)->get();

                    $reviewArr = [];
                    foreach($review as $valReview){
                        $tempReview['first_name'] = $valReview->first_name;
                        $tempReview['last_name'] = $valReview->last_name;
                        $tempReview['rating'] = $valReview->rating;
                        $tempReview['review'] = $valReview->review;
                        if(isset($valReview->profile_image) && $valReview->profile_image != ""){
                            $valReview->profile_image = url('upload/profile-image/' . $valReview->profile_image);
                        } else $valReview->profile_image = null;
                        $tempReview['profile_image'] = $valReview->profile_image;
                        $tempReview['created_date'] = $valReview->created_date;
                        $reviewArr[] = $tempReview;
                    }
                    
                    $temp['description'] = $item->description;
                    $temp['tags'] = $tags;
                    $temp['status'] = $item->status;
                    $temp['avg_rating'] = number_format($reviewAvg, 1);
                    $temp['review_count'] = $reviewCount;
                    $temp['review'] = $reviewArr;
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
                    return response()->json(['status' => false, 'message' => 'Already favourites',]);
                } else {
                    $data = DB::table('user_wishlist')->insert([
                        'object_id' => (int) $item_id,
                        'object_type' => (int) $item_type,
                        'userid' => (int) $u_id,
                        'status' => $status,
                        'created_date' => date('Y-m-d H:i:s')
                    ]);
                    return response()->json(['status' => true, 'message' => 'Added to favourites',]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Please login']);
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
                    return response()->json(['status' => true, 'message' => 'Removed from favourites',]);
                } else {
                    return response()->json(['status' => false, 'message' => 'Something went wrong.',]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Please login']);
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
                        $data[$key]['profile_image'] = isset($user_name->profile_image) ? url('upload/profile-image/' . $user_name->profile_image) : null;
                    }
                    return response()->json([
                        "status" => true,
                        "message" => "Review List",
                        "review_list" => $data
                    ]);
                } else {
                    return response()->json(['status' => false, 'message' => 'No data', 'review_list' => []]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Please login']);
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
                        $temp['profile_image'] = url('upload/profile-image/' . $user->profile_image);
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

    // public function certificates()
    // {
    //     try {
    //         $user_id = Auth::user()->id;
    //         if ($user_id) {
    //             $datas = Certificate::where('user_id', $user_id)->get(); /*Get data of category*/
    //             $response = array();
    //             if (isset($datas)) {
    //                 foreach ($datas as $keys => $item) {
    //                     $temp['id'] = $item->id;
    //                     $temp['user_id'] = $item->user_id;
    //                     if ($item->certificate_image) {
    //                         $temp['certificate_image'] = url('upload/certificate-image/' . $item->certificate_image);
    //                     } else {
    //                         $temp['certificate_image'] = '';
    //                     }
    //                     $temp['rating'] = 4.9;
    //                     $temp['name'] = 'Max bryant';
    //                     $response[] = $temp;
    //                 }
    //             }
    //             return response()->json(['status' => true, 'message' => 'Certificate Listing', 'data' => $response]);
    //         } else {
    //             return response()->json(['status' => false, 'Message' => 'Please login']);
    //         }
    //     } catch (\Exception $e) {
    //         return errorMsg("Exception -> " . $e->getMessage());
    //     }
    // }

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
                            $temp['image'] = url('upload/notification-image/' . $item->image);
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
                $card = CardDetail::where('userid', $user_id)->get();
                if (count($card) > 0) {
                    $response = [];
                    foreach ($card as $key => $value) {
                        $temp['card_id'] = $value->id;
                        $temp['card_number'] = "XXXX XXXX XXXX ".substr(encrypt_decrypt('decrypt', $value->card_no),12);
                        $temp['card_holder_name'] = $value->name_on_card;
                        $temp['cvv'] = encrypt_decrypt('decrypt', $value->CVV);
                        $temp['valid_upto'] = encrypt_decrypt('decrypt', $value->expiry);
                        $temp['card_type'] = $value->card_type;

                        $card_type = $value->card_type;
                        if ($card_type == 'VISA') {
                            $temp['card_image'] = url('upload/notification-image/visa.png');
                        } else {
                            $temp['card_image'] = url('upload/notification-image/m-card.png');
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
            $user_id = Auth::user()->id;
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'card_number' => 'required|numeric',
                    'valid_upto' => 'required',
                    'cvv' => 'required',
                    'card_holder_name' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                } else {
                    $card = new CardDetail;
                    $card->userid = $user_id;
                    $card->card_no = encrypt_decrypt('encrypt', $request->card_number);
                    $card->expiry = encrypt_decrypt('encrypt', $request->valid_upto);
                    $card->CVV = encrypt_decrypt('encrypt', $request->cvv);
                    $card->name_on_card = $request->card_holder_name;
                    $card->card_type = 'VISA';
                    $card->created_date = date('Y-m-d H:i:s');
                    $card->save();
                    return response()->json(['status' => true, 'message' => 'Card Added']);
                }
            } else {
                return response()->json(['status' => false, 'Message' => 'Please login']);
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
                $card = CardDetail::where('id', $request->id)->delete();
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
                }else{
                    if($request->object_type == 1){
                        $orderDetail = Order::join('order_product_detail as opd', 'opd.order_id', '=', 'orders.id')->where('orders.user_id', auth()->user()->id)->where('orders.status', 1)->where('product_id', $request->object_id)->where('product_type', 1)->select('orders.id')->first();
                        if(isset($orderDetail->id)){
                            return response()->json(['status' => false, 'message' => 'Already purchased this course!. Please try another courses.']);
                        }  
                    }
                    
                    $cart = new AddToCart;
                    $cart->userid = $user_id;
                    $cart->object_id = $request->object_id;
                    $cart->object_type = $request->object_type;

                    $cart_value = $request->cart_value;
                    $admin_value = $request->cart_value;
                    if($request->object_type == 1){
                        $course = Course::where('id', $request->object_id)->first();
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
                    if ($cart) {
                        return response()->json(['status' => true, 'message' => 'Cart Added']);
                    } else {
                        return response()->json(['status' => true, 'message' => 'Something went wrong!']);
                    }
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
                $cart_value = AddToCart::where('userid', $user_id)->sum(\DB::raw('cart_value * quantity'));

                $response = array();
                if (isset($datas)) {
                    foreach ($datas as $keys => $item) {
                        $temp['id'] = $item->id;
                        $temp['userid'] = $item->userid;
                        $temp['object_id'] = $item->object_id;
                        $temp['type'] = $item->object_type;
                        $temp['type_name'] = ($item->object_type==1) ? "Course" : "Product";
                        $temp['quantity'] = $item->quantity;
                        if ($item->object_type == 1) { /* 1 stand for course ,2 for product */
                            $value = Course::leftJoin('users as u', function($join) {
                                $join->on('course.admin_id', '=', 'u.id');
                            })
                            ->where('course.id', $item->object_id)->select('course.title', 'course.course_fee', 'u.profile_image', 'u.first_name', 'u.last_name', 'u.category_name', 'course.admin_id', 'course.id', 'course.introduction_image')->first();
                            $temp['title'] = $value->title;
                            $temp['price'] = $value->course_fee;
                            if ($value->profile_image) {
                                $profile_image = url('upload/profile-image/'.$value->profile_image);
                            } else {
                                $profile_image = '';
                            }
                            $temp['content_creator_image'] = $profile_image;
                            $temp['content_creator_name'] = $value->first_name.' '.$value->last_name;
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            if(isset($value->introduction_image)){
                                $temp['Product_image'] = array(url('upload/disclaimers-introduction/'.$value->introduction_image));  
                            } else $temp['Product_image'] = array();
                        } else {
                            $value = Product::where('id', $item->object_id)->first();
                            $temp['title'] = $value->name;
                            $User = User::where('id', $value->added_by)->first();
                            $temp['creator_name'] = $User->first_name.' '.$User->last_name;
                            if ($User->profile_image == '') {
                                $profile_image = '';
                            } else {
                                $profile_image = url('upload/profile-image/' . $User->profile_image);
                            }
                            $temp['creator_image'] = $profile_image;
                            $temp['creator_id'] = $value->added_by;
                            $temp['price'] = $value->price;
                            $exists = Like::where('reaction_by', '=', $user_id)->where('object_id', '=', $value->id)->where('object_type', '=', 2)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $all_products_image = ProductAttibutes::where('product_id', $value->id)->orderBy('id', 'ASC')->get(); /*Get data of All Product*/
                            $datas_image = array();
                            foreach ($all_products_image as $k => $val) {
                                $datasImage = url('upload/products/' . $val->attribute_value);
                                $datas_image[] = $datasImage;
                            }
                            $temp['Product_image'] = $datas_image;
                        }
                        $avgRating = DB::table('user_review as ur')->where('object_id', $item->object_id)->where('object_type', $item->object_type)->avg('rating');
                        $temp['avg_rating'] = number_format($avgRating, 1);
                        $response[] = $temp;
                    }
                    // $shipping_amount = 10;
                    $discount = 0;
                    $total_amount = ($cart_value) - $discount;
                    return response()->json([
                        'status' => true,
                        'message' => 'Cart Listing',
                        'sub_total' => (int)$cart_value,
                        'discount' => $discount,
                        // 'shipping' => $shipping_amount,
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

    public function cart_details_payment_page()
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $cart_value = AddToCart::where('userid', $user_id)->sum(\DB::raw('cart_value * quantity'));
                $cart_count = AddToCart::where('userid', $user_id)->count();
                $discount = 0;
                $total_amount = ($cart_value) - $discount;
                return response()->json([
                    'status' => true,
                    'message' => 'Cart details.',
                    'sub_total' => (int)$cart_value,
                    'order_count' => $cart_count,
                    'discount' => $discount,
                    'total' => $total_amount,
                ]);
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
                $carts = Addtocart::where('userid', $user_id)->get();
                $order_no = "AKS".rand(10000000, 99999999);
                
                
                if (count($carts) > 0) {

                    /*Create Order */
                    $admin_cut_price = Addtocart::where('userid', $user_id)->sum(\DB::raw('admin_cut_value * quantity'));
                    $order_price = Addtocart::where('userid', $user_id)->sum(\DB::raw('cart_value * quantity'));
                    $total_price = $order_price;

                    $insertedId = Order::insertGetId([
                        'user_id' => $user_id,
                        'order_number' => $order_no,
                        'amount' => $order_price - $admin_cut_price,
                        'admin_amount' => $admin_cut_price,
                        'total_amount_paid' => $total_price,/*Total amount of order*/
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
                            $userCourse = new UserCourse;
                            $userCourse->course_id = $cart->object_id;
                            $userCourse->user_id = $user_id;
                            $userCourse->buy_price = $cart->cart_value;
                            $userCourse->payment_id = null;
                            $userCourse->buy_date = date('Y-m-d H:i:s');
                            $userCourse->status = 0;
                            $userCourse->created_date = date('Y-m-d H:i:s');
                            $userCourse->coupon_id = null;
                            $userCourse->save();
                        }
                    }
                    Addtocart::where('userid', $user_id)->delete();

                    $data['status'] = 1;
                    $data['message'] = 'Order placed successfully';
                    $data['order_id'] = $insertedId;
                    $data['total_amount'] = $total_price;
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

    public function my_order(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                $orders = Order::where('user_id', $user_id)->get();
                $response = [];
                if (count($orders) > 0) {
                    foreach ($orders as $key => $value) {
                        if ($request->type == 1) {
                            $OrderDetails = OrderDetail::where('order_id', $value->id)->where('product_type',1)->first();
                            if(!isset($OrderDetails)){
                                return response()->json([
                                    'status' => true,
                                    'message' => 'No Order found',
                                    'data' => $response
                                ]);
                            }
                            $temp['course_valid_date'] = date('d/m/y,H:i', strtotime($value->created_date));
                            $temp['complete_course_on'] = date('d/m/y,H:i', strtotime($value->created_date));
                            $Course = Course::where('id', $OrderDetails->product_id)->first();
                            $temp['title'] = $Course->title;
                            $temp['course_id'] = $Course->id;
                            $temp['rating'] = 4.6;
                            $temp['order_status'] = ($value->status == 1) ? 'Active' : 'Pending';
                            $exists = Like::where('reaction_by', '=', $value->user_id)->where('object_id', '=', $OrderDetails->product_id)->where('object_type', '=', 1)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $ContentCreator = User::where('id', $Course->admin_id)->first();
                            if ($ContentCreator->profile_image == '') {
                                $profile_image = '';
                            } else {
                                $profile_image = url('upload/profile-image/' . $ContentCreator->profile_image);
                            }
                            $temp['content_creator_image'] = $profile_image;
                            $temp['content_creator_name'] = $ContentCreator->first_name.' '.$ContentCreator->last_name;
                            $temp['content_creator_id'] = isset($ContentCreator->id) ? $ContentCreator->id : '';
                        } else {
                            $OrderDetails = OrderDetail::where('order_id', $value->id)->where('product_type',2)->first();
                            if(!isset($OrderDetails)){
                                return response()->json([
                                    'status' => true,
                                    'message' => 'No Order found',
                                    'data' => $response
                                ]);
                            }
                            $temp['created_date'] = date('d/m/y,H:i', strtotime($value->created_date));
                            $Product = Product::where('id', $OrderDetails->product_id)->first();
                            $temp['title'] = $Product->name;
                            $temp['product_id'] = $Product->id;
                            $temp['product_price'] = $Product->price;
                            $temp['rating'] = 4.6;
                            $temp['order_status'] = ($value->status == 1) ? 'Active' : 'Pending';
                            $exists = Like::where('reaction_by', '=', $value->user_id)->where('object_id', '=', $OrderDetails->product_id)->where('object_type', '=', 2)->first();
                            if (isset($exists)) {
                                $temp['isLike'] = 1;
                            } else {
                                $temp['isLike'] = 0;
                            }
                            $ContentCreator = User::where('id', $Product->added_by)->first();
                            $temp['creator_name'] = $ContentCreator->first_name.' '.$ContentCreator->last_name;
                            if ($ContentCreator->profile_image == '') {
                                $profile_image = '';
                            } else {
                                $profile_image = url('upload/profile-image/' . $ContentCreator->profile_image);
                            }
                            $temp['creator_image'] = $profile_image;
                            $temp['creator_id'] = $value->added_by;
                        }
                        $temp['order_id'] = $value->id;
                        $temp['order_number'] = $value->order_number;
                        $response[] = $temp;
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'My Order',
                        'data' => $response
                    ]);
                } else {
                    return response()->json([
                        'status' => true,
                        'message' => 'No Order found',
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

    public function cart_count(Request $request){
        try{
            $cart = AddToCart::where('userid', auth()->user()->id)->count();
            return response()->json([
                'status' => true,
                'message' => 'Cart count',
                'data' => $cart
            ]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function assignment_upload_file(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'file' => 'required|max:2048',
                'chapter_step_id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            } else{
                $fileName = time().'.'.$request->file->extension();  
                $request->file->move(public_path('upload/course'), $fileName);

                $step = CourseChapterStep::where('id', $request->chapter_step_id)->where('type', 'assignment')->first();
                if(isset($step->course_chapter_id)){
                    $courseChapter = CourseChapter::where('id', $step->course_chapter_id)->first();
                    if(isset($courseChapter->course_id)){
                        $check = UserCourse::where('course_id', $courseChapter->course_id)->where('user_id', auth()->user()->id)->first();
                        if(!isset($check->id)) return response()->json(['status' => false, 'message' => 'Please purchase this course first']);
                        $userChapterStatus = new UserChapterStatus;
                        $userChapterStatus->userid = auth()->user()->id;
                        $userChapterStatus->course_id = $courseChapter->course_id;
                        $userChapterStatus->chapter_id = $step->course_chapter_id;
                        $userChapterStatus->step_id = $request->chapter_step_id;
                        $userChapterStatus->step_type = $step->type;
                        $userChapterStatus->file = $fileName;
                        $userChapterStatus->status = 1;
                        $userChapterStatus->created_date = date('Y-m-d H:i:s');
                        $userChapterStatus->save();
                        return response()->json(['status' => true, 'message' => 'File uploaded successfully', 'data' => $fileName]);
                    } else return response()->json(['status' => false, 'message' => 'Something went wrong']);
                } else return response()->json(['status' => false, 'message' => 'Incorrect step id']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function mark_complete(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'chapter_step_id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            } else{
                $courseStep = CourseChapterStep::where('id', $request->chapter_step_id)->first();
                if(isset($courseStep->course_chapter_id)){
                    $courseChapter = CourseChapter::where('id', $courseStep->course_chapter_id)->first();
                    if(isset($courseChapter->course_id)){
                        $check = UserCourse::where('course_id', $courseChapter->course_id)->where('user_id', auth()->user()->id)->first();
                        if(!isset($check->id)) return response()->json(['status' => false, 'message' => 'Please purchase this course first']);
                        $userChapterStatus = new UserChapterStatus;
                        $userChapterStatus->userid = auth()->user()->id;
                        $userChapterStatus->course_id = $courseChapter->course_id;
                        $userChapterStatus->chapter_id = $courseStep->course_chapter_id;
                        $userChapterStatus->step_id = $request->chapter_step_id;
                        $userChapterStatus->step_type = $courseStep->type;
                        $userChapterStatus->file = null;
                        $userChapterStatus->status = 1;
                        $userChapterStatus->created_date = date('Y-m-d H:i:s');
                        $userChapterStatus->save();
                        return response()->json(['status' => true, 'message' => $courseStep->type.' is completed.']);
                    } else return response()->json(['status' => false, 'message' => 'Something went wrong']);
                }else return response()->json(['status' => false, 'message' => 'Incorrect step id']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function contestQuizSurvey(Request $request, $chapterId, $quizId, $userId){
        try{
            $chapterId = encrypt_decrypt('decrypt',$chapterId);
            $quizId = encrypt_decrypt('decrypt',$quizId);
            $userId = encrypt_decrypt('decrypt',$userId);
            $course = CourseChapterStep::where('course_chapter_id', $chapterId)->where('id', $quizId)->whereIn('type', ['quiz'])->first();
            if(isset($course)){
                $data = [];
                $quiz = ChapterQuiz::where('step_id', $course->id)->whereIn('type', ['quiz', 'survey'])->get();
                foreach($quiz as $val1){
                    $temp['step_id'] = $course->id;
                    $temp['question_id'] = $val1->id;
                    $temp['type'] = $val1->type;
                    $temp['title'] = $val1->title;
                    $temp['marks'] = $val1->marks;
                    $optionCount = ChapterQuizOption::where('quiz_id', $val1->id)->where('is_correct', '1')->count();
                    $temp['optionCount'] = $optionCount;
                    $options = ChapterQuizOption::where('quiz_id', $val1->id)->get();
                    $temp['option'] = [];
                    $quizAnswer = UserQuizAnswer::where('quiz_id', $course->id)->where('question_id', $val1->id)->first();
                    $temp['quiz_answer'] = isset($quizAnswer) ? $quizAnswer->answer_option_key : null;
                    foreach($options as $val2){
                        $temp2['id'] = $val2->id;
                        $temp2['answer'] = $val2->answer_option_key;
                        $temp2['correct'] = $val2->is_correct;
                        $temp['option'][] = $temp2;
                    }
                    $data[] = $temp;
                }
                // dd($data);
                $questionCount = ChapterQuiz::where('step_id', $course->id)->whereIn('type', ['quiz', 'survey'])->count();
                return view('home.contest-page')->with(compact('data', 'questionCount', 'userId'));
            } else return response()->json(['status'=> false, 'message'=> 'Invalid URL']);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function contestForm(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'quiz_id' => 'required',
                'question_id' => 'required',
                'option' => 'required',
                'user_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            } else{
                $answer = ChapterQuizOption::where('quiz_id', $request->question_id)->where('id', $request->option)->where('is_correct', '1')->first();
                if(isset($answer->id)){
                    $marks = ChapterQuiz::where('step_id', $request->quiz_id)->where('id', $request->question_id)->first();
                } else $marks = null;
                $option = new UserQuizAnswer;
                $option->userid = $request->user_id;
                $option->quiz_id = $request->quiz_id;
                $option->question_id = $request->question_id;
                $option->marks_obtained = isset($marks) ? $marks->marks : 0;
                $option->answer_option_key = $request->option;
                $option->created_date = date('Y-m-d H:i:s');
                $option->status = isset($answer->id) ? 1 : 0;
                $option->save();
                $correct_answer = ChapterQuizOption::where('quiz_id', $request->question_id)->where('is_correct', '1')->first();
                return response()->json(['status'=> true, 'message'=> 'Answer is save successfully.', 'request'=> $request->all(), 'answer_status' => isset($answer->id) ? 1 : 0, 'correct_answer'=> $correct_answer]);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function resultQuizSurvey(Request $request, $quizId, $userId){
        try{
            $quizId = encrypt_decrypt('decrypt',$quizId);
            $userId = encrypt_decrypt('decrypt',$userId);
            $courseStep = CourseChapterStep::where('id', $quizId)->whereIn('type', ['quiz'])->first();
            if(isset($courseStep->course_chapter_id)){
                $courseChapter = CourseChapter::where('id', $courseStep->course_chapter_id)->first();
                if(isset($courseChapter->course_id)){
                    $userChapterStatus = new UserChapterStatus;
                    $userChapterStatus->userid = $userId;
                    $userChapterStatus->course_id = $courseChapter->course_id;
                    $userChapterStatus->chapter_id = $courseStep->course_chapter_id;
                    $userChapterStatus->step_id = $courseStep->id;
                    $userChapterStatus->step_type = $courseStep->type;
                    $userChapterStatus->file = null;
                    $userChapterStatus->status = 1;
                    $userChapterStatus->created_date = date('Y-m-d H:i:s');
                    $userChapterStatus->save();
                }
            }
            $total = ChapterQuiz::where('step_id', $quizId)->whereIn('type', ['quiz', 'survey'])->sum('marks');
            $obtained = UserQuizAnswer::where('quiz_id', $quizId)->where('userid',9)->sum('marks_obtained');
            $totalQuestion = ChapterQuiz::where('step_id', $quizId)->whereIn('type', ['quiz', 'survey'])->count();
            $totalCorrect = UserQuizAnswer::where('quiz_id', $quizId)->where('userid',9)->where('status', 1)->count();
            $totalWrong = UserQuizAnswer::where('quiz_id', $quizId)->where('userid',9)->where('status', 0)->count();
            return view('home.result-page')->with(compact('obtained', 'total', 'totalWrong', 'totalCorrect', 'totalQuestion'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function generate_pdf($id, $uid) {
        $id = encrypt_decrypt('decrypt', $id);
        $uid = encrypt_decrypt('decrypt', $uid);
        $user = User::where('id', $uid)->first();
        $admin = User::where('role', 3)->first();
        $course = Course::join('users as u', 'u.id', '=', 'course.admin_id')->where('course.id', $id)->select('course.title', 'u.first_name', 'u.last_name', 'u.company_name', 'u.professional_title', 'u.signature', 'u.business_logo')->first();
        // dd($course);
        $date = UserCourse::where('user_id', $uid)->where('course_id', $id)->first();
        $pdf = PDF::loadView('home.certificates', compact('course', 'date', 'user', 'admin'));
        return $pdf->stream($course->title.' certificate.pdf');
    }

    public function certificates(Request $request){
        try{
            $data = UserCourse::join('course as c', 'c.id', '=', 'user_courses.course_id')->where('user_courses.user_id', auth()->user()->id)->where('user_courses.status', 1)->select('c.id as course_id', 'user_courses.user_id as user_id', 'c.title', 'user_courses.status')->get();
            $res = [];
            foreach($data as $val){
                $temp['course_id'] = $val->course_id;
                $temp['user_id'] = $val->user_id;
                $temp['title'] = $val->title;
                $temp['status'] = $val->status;
                $temp['download_pdf'] = url('/')."/api/download-pdf/".encrypt_decrypt('encrypt',$val->course_id)."/".encrypt_decrypt('encrypt',$val->user_id);
                $res[] = $temp;
            }
            return response()->json(['status' => true, 'message' => 'Suggested Course Listing', 'data' => $res]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function special_courses(Request $request){
        try{
            $users = User::where('role', 3)->pluck('id');
            $courses = Course::leftJoin('users', function($join) {
                $join->on('course.admin_id', '=', 'users.id');
            })->where('course.status', 1)->whereIn('course.admin_id', $users);
            if($request->filled('title')){
                $courses->where('course.title', 'like' , '%' . $request->title . '%');
            }
            if($request->filled('category')){
                $courses->whereIntegerInRaw('course.category_id', $request->category);
            }
            if($request->filled('price')){
                if($request->price == 1) $courses->orderByDesc('course.course_fee');
                else $courses->orderBy('course.course_fee');
            } else{
                $courses->orderBy('course.id', 'DESC');
            }
            $courses = $courses->select('course.*', 'users.first_name', 'users.last_name','users.profile_image','users.category_name')->get();
            $data = [];
            foreach($courses as $value){
                if($request->filled('tag'))
                    if(!in_array($request->tag, unserialize($value->tags))) continue;
                $temp['course_fee'] = $value->course_fee;
                $temp['valid_upto'] = $value->valid_upto;
                if (!empty($value->certificates)) {
                    $temp['certificates_image'] = url('upload/course-certificates/' . $value->certificates);
                } else {
                    $temp['certificates_image'] = '';
                }
                if (!empty($value->introduction_image)) {
                    $temp['introduction_video'] = url('upload/disclaimers-introduction/' . $value->introduction_image);
                } else {
                    $temp['introduction_video'] = '';
                }
                $exists = Like::where('reaction_by', '=', auth()->user()->id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->first();
                if (isset($exists)) {
                    $temp['isLike'] = 1;
                } else {
                    $temp['isLike'] = 0;
                }
                $wishlist = Wishlist::where('userid', '=', auth()->user()->id)->where('object_id', '=', $value->id)->where('object_type', '=', 1)->where('status', 1)->first();
                if (isset($wishlist)) {
                    $temp['isWishlist'] = 1;
                } else {
                    $temp['isWishlist'] = 0;
                }
                $temp['title'] = $value->title;
                if ($value->profile_image) {
                    $profile_image = url('upload/profile-image/'.$value->profile_image);
                } else {
                    $profile_image = '';
                }
                $temp['content_creator_image'] = $profile_image;
                $temp['content_creator_name'] = $value->first_name.' '.$value->last_name;
                $temp['content_creator_category'] = isset($value->category_name) ? $value->category_name : '';
                $temp['content_creator_id'] = isset($value->admin_id) ? $value->admin_id : '';
                $temp['id'] = $value->id;
                $temp['description'] = $value->description;
                $temp['status'] = $value->status;
                $avgRating = DB::table('user_review as ur')->where('object_id', $value->id)->where('object_type', 2)->avg('rating');
                $temp['avg_rating'] = number_format($avgRating, 1);
                if($request->filled('rating'))
                    if($avgRating < min($request->rating)) continue;
                $temp['created_date'] = date('d/m/y,H:i', strtotime($value->created_date));
                $tags = [];
                if(isset($value->tags)){
                    foreach(unserialize($value->tags) as $val){
                        $name = Tag::where('id', $val)->first();
                        $temparory['name'] = $name->tag_name;
                        $temparory['id'] = $name->id;
                        $tags[] = $temparory;
                    }
                }
                $temp['tags'] = $tags;
                $data[] = $temp;
            }
            return response()->json(['status' => true, 'message' => 'Special Courses', 'data' => $data]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function update_product_quantity(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'cart_id' => 'required',
                'quantity' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }else{
                if($request->quantity >= 1 && $request->quantity <= 10){
                    $cart = AddToCart::where('id', $request->cart_id)->where('object_type', 2)->update(['quantity' => $request->quantity]);
                    if ($cart) {
                        return response()->json(['status'=> true, 'message' => 'Quantity updated']);
                    } else {
                        return response()->json(['status' => false, 'message' => 'Something went wrong!']);
                    }
                } else return response()->json(['status'=> false, 'message' => 'Quantity must be between in 1 to 10']);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function remove_cart(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'cart_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }else{
                $cart = AddToCart::where('id', $request->cart_id)->delete();
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

}