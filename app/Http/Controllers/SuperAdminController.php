<?php

namespace App\Http\Controllers;

use App\Models\CardDetail;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseChapter;
use App\Models\ChapterQuiz;
use App\Models\ChapterQuizOption;
use App\Models\CourseChapterStep;
use App\Models\Tag;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\NotificationCreator;
use App\Models\Notify;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Page;
use App\Models\ProductAttibutes;
use App\Models\Setting;
use App\Models\UserChapterStatus;
use App\Models\UserCourse;
use App\Models\WalletBalance;
use App\Models\WalletHistory;
use Auth;
use Illuminate\Support\Facades\Validator;
use VideoThumbnail;
use Illuminate\Support\Facades\File;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class SuperAdminController extends Controller
{
    public function show() 
    {
        try {
            return view('super-admin.login');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(Request $request)
    {   
        $input = $request->all();
     
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
     
        if(auth()->attempt(array('email' => $input['email'], 'password' => bcrypt($input['password']))))
        {
            Auth::login();
            return redirect()->route('SA.Dashboard');
        }else{
            return redirect()->route('SA.LoginShow')
                ->with('error','Email-Address And Password Are Wrong.');
        }
          
    }

    public function loadSectors(Request $request)
    {
        $movies = [];

        if($request->has('q')){
            $search = $request->q;
            $movies =Tag::select("id", "tag_name")
            		->where('tag_name', 'LIKE', "%$search%")
                    ->where('type', '1')
                    ->where('status', 1)
            		->get();
        }else{
            $movies =Tag::select("id", "tag_name")->where('status', 1)->where('type', '1')->get();
        }
        return response()->json($movies);
    }

    public function dashboard() 
    {
        try {
            $cc = User::where('role', 2)->whereIn('status', [1,2])->count();
            $stu = User::where('role', 1)->count();
            $pro = Product::count();
            $course = Course::count();

            $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $wallet = WalletBalance::join('wallet_history as wh', 'wh.wallet_id', '=', 'wallet_balance.id')->select(
                DB::raw('sum(wh.balance) as y'), 
                DB::raw("DATE_FORMAT(added_date,'%m') as x")
            )->whereYear('wallet_balance.created_date', date('Y'))->where('owner_id', auth()->user()->id)->where('owner_type', 3)->groupBy('x')->orderByDesc('x')->get()->toArray();
            $xw = collect($wallet)->pluck('x')->toArray();
            $yw = collect($wallet)->pluck('y')->toArray();
            $walletArr = [];
            for ($i = 0; $i < 12; $i++) {
                if(in_array( $i+1, $xw )){
                    $indx = array_search($i+1, $xw);
                    $walletArr[$i]['y'] = number_format($yw[$indx], 2, '.', '');
                }else
                    $walletArr[$i]['y'] = 0;
                $walletArr[$i]['x'] = $month[($i+1) - 1];
            }

            $users = User::select(
                DB::raw('count(id) as y'), 
                DB::raw("DATE_FORMAT(created_at,'%m') as x")
            )->whereYear('created_at', date('Y'))->groupBy('x')->orderByDesc('x')->get()->toArray();
            $x = collect($users)->pluck('x')->toArray();
            $y = collect($users)->pluck('y')->toArray();
            $userArr = [];
            for ($i = 0; $i < 12; $i++) {
                if(in_array( $i+1, $x )){
                    $indx = array_search($i+1, $x);
                    $userArr[$i]['y'] = $y[$indx];
                }else{
                    $userArr[$i]['y'] = 0;
                }
                $userArr[$i]['x'] = $month[($i+1) - 1];
            }

            $over_graph_data = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->select(
                DB::raw('sum(opd.admin_amount) as y'), 
                DB::raw("DATE_FORMAT(opd.created_date,'%d') as x")
                )->whereMonth('opd.created_date', date('m'))->whereYear('opd.created_date', date('Y'))->groupBy('x')->orderByDesc('x')->get()->toArray(); 
            $over_graph = [];
            $days = get_days_in_month(date('m'), date('Y'));
            $xo = collect($over_graph_data)->pluck('x')->toArray();
            $yo = collect($over_graph_data)->pluck('y')->toArray();
            for($i=1; $i<=$days; $i++){
                if(in_array( $i, $xo )){
                    $indx = array_search($i, $xo);
                    // dd($xo[$indx]);
                    $over_graph[$i-1]['x'] = (string) $i;
                    $over_graph[$i-1]['y'] = number_format($yo[$indx], 2, '.', '');
                }else{
                    $over_graph[$i-1]['x'] = (string) $i;
                    $over_graph[$i-1]['y'] = 0;
                }
            }

            $creator_over_graph_data = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', '!=', auth()->user()->id)->select(
                DB::raw('sum(opd.amount - opd.admin_amount) as y'), 
                DB::raw("DATE_FORMAT(opd.created_date,'%d') as x")
                )->whereMonth('opd.created_date', date('m'))->whereYear('opd.created_date', date('Y'))->groupBy('x')->orderByDesc('x')->get()->toArray(); 
            $creator_over_graph = [];
            $creator_days = get_days_in_month(date('m'), date('Y'));
            $creator_x = collect($creator_over_graph_data)->pluck('x')->toArray();
            $creator_y = collect($creator_over_graph_data)->pluck('y')->toArray();
            for($i=1; $i<=$creator_days; $i++){
                if(in_array( $i, $creator_x )){
                    $indx = array_search($i, $creator_x);
                    // dd($x[$indx]);
                    $creator_over_graph[$i-1]['x'] = (string) $i;
                    $creator_over_graph[$i-1]['y'] = number_format($creator_y[$indx], 2, '.', '');
                }else{
                    $creator_over_graph[$i-1]['x'] = (string) $i;
                    $creator_over_graph[$i-1]['y'] = 0;
                }
            }

            $user = User::where('role', 1)->orderByDesc('id')->limit(3)->get();
            $contentcreator = User::where('role', 2)->orderByDesc('id')->limit(3)->get();
            $newCourse = Course::leftJoin('users as u', 'u.id', '=', 'course.admin_id')->where('u.role', 2)->select('course.title', 'course.course_fee', 'u.id', 'u.first_name', 'u.last_name', 'u.profile_image', 'course.id as courseid')->orderByDesc('course.id')->limit(3)->get();

            return view('super-admin.dashboard',compact('course', 'pro', 'stu', 'cc', 'userArr', 'walletArr', 'creator_over_graph', 'over_graph', 'user', 'contentcreator', 'newCourse'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function myAccount() 
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();
            $tax = Setting::where('attribute_code', 'tax')->first();
            $course = Setting::where('attribute_code', 'course_purchase_validity')->first();
            return view('super-admin.my-account')->with(compact('user', 'tax', 'course'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function storeMyData(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'phone' => 'required',
                'bus_name' => 'required',
                'bus_title' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                $data = User::where('id', auth()->user()->id)->first();
                if ($request->profile) {
                    $profile = time().'.'.$request->profile->extension();  
                    $request->profile->move(public_path('upload/profile-image'), $profile);
                    $image_path = app_path("upload/profile-image/{$data->profile_image}");
                    if(File::exists($image_path)) {
                        unlink($image_path);
                    }
                } else $profile = $data->profile_image;
                if ($request->logo) {
                    $logo = time().'.'.$request->logo->extension();  
                    $request->logo->move(public_path('upload/business-logo'), $logo);
                    $image_path = app_path("upload/business-logo/{$data->business_logo}");
                    if(File::exists($image_path)) {
                        unlink($image_path);
                    }
                } else $logo = $data->business_logo;
                if ($request->signature) {
                    $signature = time().'.'.$request->signature->extension();  
                    $request->signature->move(public_path('upload/signature'), $signature);
                    $image_path = app_path("upload/signature/{$data->signature}");
                    if(File::exists($image_path)) {
                        unlink($image_path);
                    }
                } else $signature = $data->signature;

                $user = User::where('id', auth()->user()->id)->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name ?? null,
                    'phone' => $request->phone,
                    'company_name' => $request->bus_name,
                    'professional_title' => $request->bus_title,
                    'profile_image' => $profile,
                    'business_logo' => $logo,
                    'signature' => $signature,
                ]);

                return redirect()->back()->with('message', 'Profile updated successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function changePassword(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'old_pswd' => 'required',
                'new_pswd' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                User::where('id', auth()->user()->id)->update([
                    'password' => Hash::make($request->new_pswd)
                ]);
                return redirect()->back()->with('message', 'Password changed successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function storeSetting(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'value' => 'required',
                'attribute' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                $attr = encrypt_decrypt('decrypt', $request->attribute);
                if($attr=='tax' || $attr=='course'){
                    if($attr=='tax'){
                        $attr_name = 'Tax';
                        $attr_code = 'tax';
                    }else{
                        $attr_name = 'Course Purchase Validity';
                        $attr_code = 'course_purchase_validity';
                    }
                    $isExist = Setting::where('attribute_code', $attr_code)->first();
                    if(isset($isExist->id)){
                        Setting::where('attribute_code', $attr_code)->update([
                            'attribute_value'=> $request->value,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }else{
                        $setting = new Setting;
                        $setting->attribute_name = $attr_name;
                        $setting->attribute_code = $attr_code;
                        $setting->attribute_value = $request->value;
                        $setting->save();
                    }
                    
                    return redirect()->back()->with(['message'=> 'Settings changed successfully.', 'tab'=> 2]);
                } return redirect()->back()->with(['message'=> 'Invalid Request.', 'tab'=> 2]);
            }
        return view('super-admin.help-support',compact('courses', 'user'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function help_support() 
    {
        try {
            $user = User::where('role', 2)->where('status', 1)->get();
            $courses = Course::orderBy('id','DESC')->get();
        return view('super-admin.help-support',compact('courses', 'user'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function performance(Request $request) 
    {
        try {
            // dd(auth()->user()->id);
            $tab = $request->tab ?? encrypt_decrypt('encrypt', 1);
            if($request->filled('page')) $tab = encrypt_decrypt('encrypt', 3);
            $over_month = $request->month ?? date('Y-m');
            $earn = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->whereMonth('opd.created_date', date('m',strtotime($over_month)))->whereYear('opd.created_date', date('Y',strtotime($over_month)))->sum(\DB::raw('opd.admin_amount'));
            $course = Course::where('admin_id', auth()->user()->id)->whereMonth('course.created_date', date('m',strtotime($over_month)))->whereYear('course.created_date', date('Y',strtotime($over_month)))->count();
            $rating = Course::join('user_review as ur', 'ur.object_id', '=', 'course.id')->where('course.admin_id', auth()->user()->id)->where('ur.object_type', 1)->whereMonth('ur.created_date', date('m',strtotime($over_month)))->whereYear('ur.created_date', date('Y',strtotime($over_month)))->avg('ur.rating');
            $over_graph_data = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->select(
                DB::raw('sum(opd.admin_amount) as y'), 
                DB::raw("DATE_FORMAT(opd.created_date,'%d') as x")
                )->whereMonth('opd.created_date', date('m',strtotime($over_month)))->whereYear('opd.created_date', date('Y',strtotime($over_month)))->groupBy('x')->orderByDesc('x')->get()->toArray(); 
            $over_graph = [];
            $days = get_days_in_month(date('m',strtotime($over_month)), date('Y',strtotime($over_month)));
            $x = collect($over_graph_data)->pluck('x')->toArray();
            $y = collect($over_graph_data)->pluck('y')->toArray();
            for($i=1; $i<=$days; $i++){
                if(in_array( $i, $x )){
                    $indx = array_search($i, $x);
                    // dd($x[$indx]);
                    $over_graph[$i-1]['x'] = (string) $i;
                    $over_graph[$i-1]['y'] = number_format($y[$indx], 2, '.', '');
                }else{
                    $over_graph[$i-1]['x'] = (string) $i;
                    $over_graph[$i-1]['y'] = 0;
                }
            }



            $creator_month = $request->creatormonth ?? date('Y-m');
            $creator_earn = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', '!=', auth()->user()->id)->whereMonth('opd.created_date', date('m',strtotime($creator_month)))->whereYear('opd.created_date', date('Y',strtotime($creator_month)))->sum(\DB::raw('opd.amount - opd.admin_amount'));
            $creator_course = Course::where('course.admin_id', '!=', auth()->user()->id)->whereMonth('course.created_date', date('m',strtotime($creator_month)))->whereYear('course.created_date', date('Y',strtotime($creator_month)))->count();
            $creator_rating = Course::join('user_review as ur', 'ur.object_id', '=', 'course.id')->where('course.admin_id', '!=', auth()->user()->id)->where('ur.object_type', 1)->whereMonth('ur.created_date', date('m',strtotime($creator_month)))->whereYear('ur.created_date', date('Y',strtotime($creator_month)))->avg('ur.rating');
            $creator_over_graph_data = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', '!=', auth()->user()->id)->select(
                DB::raw('sum(opd.amount - opd.admin_amount) as y'), 
                DB::raw("DATE_FORMAT(opd.created_date,'%d') as x")
                )->whereMonth('opd.created_date', date('m',strtotime($creator_month)))->whereYear('opd.created_date', date('Y',strtotime($creator_month)))->groupBy('x')->orderByDesc('x')->get()->toArray(); 
            $creator_over_graph = [];
            $creator_days = get_days_in_month(date('m',strtotime($creator_month)), date('Y',strtotime($creator_month)));
            $creator_x = collect($creator_over_graph_data)->pluck('x')->toArray();
            $creator_y = collect($creator_over_graph_data)->pluck('y')->toArray();
            for($i=1; $i<=$creator_days; $i++){
                if(in_array( $i, $creator_x )){
                    $indx = array_search($i, $creator_x);
                    // dd($x[$indx]);
                    $creator_over_graph[$i-1]['x'] = (string) $i;
                    $creator_over_graph[$i-1]['y'] = number_format($creator_y[$indx], 2, '.', '');
                }else{
                    $creator_over_graph[$i-1]['x'] = (string) $i;
                    $creator_over_graph[$i-1]['y'] = 0;
                }
            }
            



            $user_month = $request->usermonth ?? date('Y-m');
            $user_type = $request->type ?? 0;
            $orders = DB::table('order_product_detail as opd')
                ->leftJoin('course as c', 'c.id', '=', 'opd.product_id')
                ->leftJoin('orders as o', 'o.id', '=', 'opd.order_id')
                ->leftJoin('users as cc', 'cc.id', '=', 'c.admin_id')
                ->leftJoin('users as u', 'u.id', '=', 'o.user_id')->select('opd.admin_amount', 'opd.amount', 'u.first_name','u.last_name', 'o.created_date', 'c.title', 'cc.first_name as ccf_name', 'cc.last_name as ccl_name', 'u.email')->where('opd.product_type', 1);
            if($user_type==0) $orders->where('c.admin_id', auth()->user()->id);
            else $orders->where('c.admin_id', '!=', auth()->user()->id);
            $orders = $orders->whereMonth('opd.created_date', date('m', strtotime($user_month)))->whereYear('opd.created_date', date('Y',strtotime($user_month)))->orderByDesc('opd.id')->paginate(5);
            $user = DB::table('course as c')->leftJoin('user_courses as uc', 'uc.course_id', '=', 'c.id');
            if($user_type==0) $user->where('c.admin_id', auth()->user()->id);
            else $user->where('c.admin_id', '!=', auth()->user()->id);
            $user = $user->whereMonth('uc.created_date', date('m', strtotime($user_month)))->whereYear('uc.created_date', date('Y',strtotime($user_month)))->distinct('uc.user_id')->count();



            $product_month = $request->productmonth ?? date('Y-m');
            $total_product = Product::count();
            $unpublish_product = Product::where('status', 0)->count();
            $product = Product::orderByDesc('id')->get();
            $product_graph_data = DB::table('order_product_detail as opd')->leftJoin('product as p', 'p.id', '=', 'opd.product_id')->where('opd.product_type', 2);
            if($request->filled('product')) $product_graph_data->where('opd.product_id', encrypt_decrypt('decrypt', $request->product));
            $product_graph_data = $product_graph_data->select(
                DB::raw('sum(opd.amount) as y'), 
                DB::raw("DATE_FORMAT(opd.created_date,'%d') as x")
                )->whereMonth('opd.created_date', date('m',strtotime($product_month)))->whereYear('opd.created_date', date('Y',strtotime($product_month)))->groupBy('x')->orderByDesc('x')->get()->toArray(); 
            $product_graph = [];
            $product_days = get_days_in_month(date('m',strtotime($product_month)), date('Y',strtotime($product_month)));
            $product_x = collect($product_graph_data)->pluck('x')->toArray();
            $product_y = collect($product_graph_data)->pluck('y')->toArray();
            for($i=1; $i<=$product_days; $i++){
                if(in_array( $i, $product_x )){
                    $indx = array_search($i, $product_x);
                    // dd($x[$indx]);
                    $product_graph[$i-1]['x'] = (string) $i;
                    $product_graph[$i-1]['y'] = number_format($product_y[$indx], 2, '.', '');
                }else{
                    $product_graph[$i-1]['x'] = (string) $i;
                    $product_graph[$i-1]['y'] = 0;
                }
            }

            return view('super-admin.performance',compact('tab', 'earn', 'course', 'rating', 'orders', 'over_graph', 'user', 'over_month', 'creator_earn', 'creator_course', 'creator_rating','creator_over_graph', 'creator_month', 'user_type', 'total_product', 'unpublish_product', 'product', 'product_graph'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function content_creators(Request $request) 
    {
        try {
            $users = User::where('role',2);
            if($request->filled('name')) $users->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ");
            if($request->filled('status')) $users->where('status', $request->status);
            else $users->whereIn('status', [1,2]);
            $users = $users->orderBy('id','DESC')->paginate(10);
        return view('super-admin.content-creators',compact('users'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function course(Request $request) 
    {
        try {
            $courses = Course::orderBy('id','DESC');
            if($request->filled('status')){
                $courses->where('status', $request->status);
            }
            if($request->filled('course')){
                $courses->where('title', 'like', '%' . $request->course . '%');
            }
            $courses = $courses->where('admin_id',1)->get();
            return view('super-admin.course',compact('courses'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function submitcourse(Request $request) 
    {
        try {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'disclaimers_introduction' => 'required',
                'title' => 'required',
                'description' => 'required',
                'tags' => 'required',
                'course_fee' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // if ($request->certificates) {
            //     $imageName = time().'.'.$request->certificates->extension();  
            //     $request->certificates->move(public_path('upload/course-certificates'), $imageName);
            // }

            if ($request->disclaimers_introduction) {
                $disclaimers_introduction = time().'.'.$request->disclaimers_introduction->extension();  
                $request->disclaimers_introduction->move(public_path('upload/disclaimers-introduction'), $disclaimers_introduction);
            }
            
            $course = new Course;
            $course->admin_id = auth()->user()->id;
            $course->title = $request->title;
            $course->description = $request->description;
            $course->course_fee = $request->course_fee;
            $course->valid_upto = $request->valid_upto ?? null;
            $course->category_id = $request->course_category;
            $course->tags = serialize($request->tags);
            $course->certificates = null;
            $course->introduction_image = $disclaimers_introduction;
            $course->status = 1;
            $course->save();

            $last_id = Course::orderBy('id','DESC')->first();
            $course = new CourseChapter;
            $course->course_id = $last_id->id;
            $course->save();

            return redirect()->route('SA.Course.Chapter', ['courseID'=> encrypt_decrypt('encrypt', $last_id->id), 'chapterID'=> encrypt_decrypt('encrypt', $course['id '])])->with('message', 'Chapter created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editCourse($id) 
    {
        $id = encrypt_decrypt('decrypt', $id);
        $course = Course::where('id', $id)->first();
        $course->tags = unserialize($course->tags);
        $tags = Tag::where('status', 1)->where('type', 1)->get();
        $combined = array();
        foreach ($tags as $arr) {
            $comb = array('id' => $arr['id'], 'name' => $arr['tag_name'], 'selected' => false);
            foreach ($course->tags as $arr2) {
                if ($arr2 == $arr['id']) {
                    $comb['selected'] = true;
                    break;
                }
            }
            $combined[] = $comb;
        }
        return view('super-admin.editCourseDetails')->with(compact('course', 'combined'));
    }

    public function updateCourseDetails(Request $request){
        try {
            $course = Course::where('id', encrypt_decrypt('decrypt',$request->hide))->first();
            
            $disclaimers_introduction = $course->introduction_image;
            if ($request->disclaimers_introduction) {
                $disclaimers_introduction = time().'.'.$request->disclaimers_introduction->extension();  
                $request->disclaimers_introduction->move(public_path('upload/disclaimers-introduction'), $disclaimers_introduction);

                $image_path = app_path("upload/disclaimers-introduction/{$course->introduction_image}");
                if(File::exists($image_path)) {
                    unlink($image_path);
                }
            }

            Course::where('id', encrypt_decrypt('decrypt',$request->hide))->update([
                'title' => $request->title,
                'description' => $request->description,
                'course_fee' => $request->course_fee,
                'valid_upto' => $request->valid_upto ?? null,
                'tags' => serialize($request->tags),
                'certificates' => null,
                'category_id' => $request->course_category,
                'introduction_image' => $disclaimers_introduction,
                'status' => 1,
            ]);

            return redirect()->route('SA.Course');

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteCourse($id){
        try{
            Course::where('id', encrypt_decrypt('decrypt',$id))->delete();
            $courseChapter = CourseChapter::where('course_id', encrypt_decrypt('decrypt',$id))->get();
            foreach($courseChapter as $val){
                CourseChapterStep::where('course_chapter_id', $val->id)->delete();
            }
            CourseChapter::where('course_id', encrypt_decrypt('decrypt',$id))->delete();
            return redirect()->route('SA.Course')->with('message','Course deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function viewCourse($id) 
    {
        $id = encrypt_decrypt('decrypt', $id);
        $course = Course::where('id', $id)->first();
        $course->tags = unserialize($course->tags);
        $tags = Tag::where('status', 1)->where('type', 1)->get();
        $combined = array();
        foreach ($tags as $arr) {
            $comb = array('id' => $arr['id'], 'name' => $arr['tag_name'], 'selected' => false);
            foreach ($course->tags as $arr2) {
                if ($arr2 == $arr['id']) {
                    $comb['selected'] = true;
                    break;
                }
            }
            $combined[] = $comb;
        }
        $reviewAvg = DB::table('user_review as ur')->where('object_id', $id)->where('object_type', 1)->avg('rating');
        $review = DB::table('user_review as ur')->join('users as u', 'u.id', '=', 'ur.userid')->select('u.first_name', 'u.last_name', 'ur.rating', 'ur.review', 'ur.created_date')->where('object_id', $id)->where('object_type', 1)->get();
        return view('super-admin.viewCourseDetails')->with(compact('course', 'combined', 'review', 'reviewAvg'));
    }

    public function courseChapter(Request $request, $courseID, $chapterID=null){
        try {
            $courseID = encrypt_decrypt('decrypt',$courseID);
            $chapters = CourseChapter::where('course_id',$courseID)->get();
            if($chapterID != null && isset($chapterID)) {
                $chapterID = encrypt_decrypt('decrypt',$chapterID);
            } else {
                if(count($chapters)>0){
                   $firstChapter = CourseChapter::where('course_id',$courseID)->first();
                    $chapterID = $firstChapter->id;  
                } else $chapterID = null;
            } 
            $datas = CourseChapterStep::where('course_chapter_id', $chapterID)->orderBy('sort_order')->get();
            return view('super-admin.course-chapter-list',compact('datas','chapters','courseID','chapterID'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addChapter(Request $request){
        try {
            $type = array_unique($request->type);

            if(array_has_dupes($request->queue)) {
                return response()->json(['status' => 200, 'message' => "Two sections cannot have the same serial order please check and change the serial order."]);
            }
                
            if(isset($type) && count($type) > 0){
                foreach($type as $key => $value){
                    if($type[$key] == 'video'){
                        if(count($request->video) > 0){
                            foreach($request->video as $keyVideo => $valueVideo){
                                $videoName = time().'.'.$request->video[$keyVideo]->extension();  
                                $request->video[$keyVideo]->move(public_path('upload/course'), $videoName); 

                                $ChapterQuiz = new CourseChapterStep;
                                $ChapterQuiz->type = 'video';
                                $ChapterQuiz->sort_order = $request->queue[$keyVideo] ?? -1;
                                $ChapterQuiz->title = $request->video_description[$keyVideo] ?? null;
                                $ChapterQuiz->description = null;
                                $ChapterQuiz->details = $videoName;
                                $ChapterQuiz->prerequisite = $request->prerequisite[$keyVideo] ?? 0;
                                $ChapterQuiz->course_chapter_id = $request->chapter_id;
                                $ChapterQuiz->save();
                            }
                        }
                    }
                    else if($type[$key] == 'pdf'){
                        if(count($request->pdf) > 0){
                            foreach($request->pdf as $keyPdf => $valuePdf){
                                $pdfName = time().'.'.$request->pdf[$keyPdf]->extension();  
                                $request->pdf[$keyPdf]->move(public_path('upload/course'), $pdfName);

                                $ChapterQuiz = new CourseChapterStep;
                                $ChapterQuiz->type = 'pdf';
                                $ChapterQuiz->sort_order = $request->queue[$keyPdf] ?? -1;
                                $ChapterQuiz->title = $request->PDF_description[$keyPdf] ?? null;
                                $ChapterQuiz->description = null;
                                $ChapterQuiz->details = $pdfName;
                                $ChapterQuiz->prerequisite = $request->prerequisite[$keyPdf] ?? 0;
                                $ChapterQuiz->course_chapter_id = $request->chapter_id;
                                $ChapterQuiz->save();
                            }
                        }
                    }
                    else if($type[$key] == 'assignment'){
                        if(count($request->assignment) > 0){
                            foreach($request->assignment as $keyAssignment => $valueAssignment){
                                $ChapterQuiz = new CourseChapterStep;
                                $ChapterQuiz->type = 'assignment';
                                $ChapterQuiz->sort_order = $request->queue[$keyAssignment] ?? -1;
                                $ChapterQuiz->title = $request->assignment_description[$keyAssignment] ?? null;
                                $ChapterQuiz->description = null;
                                $ChapterQuiz->details = null;
                                $ChapterQuiz->prerequisite = $request->prerequisite[$keyAssignment] ?? 0;
                                $ChapterQuiz->course_chapter_id = $request->chapter_id;
                                $ChapterQuiz->save();
                            }
                        }
                    }
                    else if($type[$key] == 'quiz'){
                        if(count($request->questions) > 0){
                            foreach($request->questions as $keyQ => $valueQ){
                                $Step = new CourseChapterStep;
                                $Step->title = $request->quiz_description[$keyQ] ?? null;
                                $Step->sort_order = $request->queue[$keyQ] ?? -1;
                                $Step->type = 'quiz';
                                $Step->description = null;
                                $Step->passing = $request->quiz_passing_per_[$keyQ] ?? null;
                                $Step->prerequisite = $request->prerequisite[$keyQ] ?? 0;
                                $Step->course_chapter_id = $request->chapter_id;
                                $Step->save();
                                foreach($valueQ as $keyQVal => $valueQVal){
                                    $ChapterQuiz = new ChapterQuiz;
                                    $ChapterQuiz->title = $valueQVal['text'];
                                    $ChapterQuiz->type = 'quiz';
                                    $ChapterQuiz->chapter_id = $request->chapter_id;
                                    $ChapterQuiz->course_id = $request->courseID;
                                    $ChapterQuiz->step_id = $Step['id '];
                                    $ChapterQuiz->marks = $valueQVal['marks'] ?? 0;
                                    $ChapterQuiz->save();
                                    $quiz_id = ChapterQuiz::orderBy('id','DESC')->first();
                                    foreach ($valueQVal['options'] as $keyOp => $optionText) {
                                        $isCorrect = '0';
                                        if(isset($valueQVal['correct'])){
                                            $isCorrect = ($valueQVal['correct']==$keyOp) ? '1' : '0';
                                        }
                                        $option = new ChapterQuizOption;
                                        $option->quiz_id = $quiz_id->id;
                                        $option->answer_option_key = $optionText;
                                        $option->is_correct = $isCorrect;
                                        $option->created_date = date('Y-m-d H:i:s');
                                        $option->status = 1;
                                        $option->save();
                                    }
                                    
                                }
                            }
                        }
                    }
                    else if($type[$key] == 'survey'){
                        if(count($request->survey_question) > 0){
                            foreach($request->survey_question as $keyS => $valueQ){
                                $Step = new CourseChapterStep;
                                $Step->title = $request->survey_description[$keyS] ?? null;
                                $Step->sort_order = $request->queue[$keyS] ?? -1;
                                $Step->type = 'survey';
                                $Step->description = null;
                                $Step->prerequisite = $request->prerequisite[$keyS] ?? 0;
                                $Step->course_chapter_id = $request->chapter_id;
                                $Step->save();
                                foreach($valueQ as $keyQVal => $valueQVal){
                                    $ChapterQuiz = new ChapterQuiz;
                                    $ChapterQuiz->title = $valueQVal['text'];
                                    $ChapterQuiz->type = 'quiz';
                                    $ChapterQuiz->chapter_id = $request->chapter_id;
                                    $ChapterQuiz->course_id = $request->courseID;
                                    $ChapterQuiz->step_id = $Step['id '];
                                    $ChapterQuiz->save();
                                    $quiz_id = ChapterQuiz::orderBy('id','DESC')->first();
                                    foreach ($valueQVal['options'] as $keyOp => $optionText) {
                                        // dd($optionText);
                                        $option = new ChapterQuizOption;
                                        $option->quiz_id = $quiz_id->id;
                                        $option->answer_option_key = $optionText;
                                        $option->is_correct = '0';
                                        $option->created_date = date('Y-m-d H:i:s');
                                        $option->status = 1;
                                        $option->save();
                                    }
                                    
                                }
                            }
                        }
                    }
                }
            }

            $courseID = encrypt_decrypt('encrypt',$request->courseID);
            $chapter_id = encrypt_decrypt('encrypt',$request->chapter_id);
            return response()->json(['status' => 201, 'message' => 'Course has been saved successfully.']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function newCourseChapter(Request $request) 
    {
        try {
            $course = new CourseChapter;
            $course->course_id = $request->courseID;
            $course->chapter = $request->name;
            $course->save();
            $encrypt = encrypt_decrypt('encrypt',$request->courseID);
            $encryptChapter = encrypt_decrypt('encrypt',$course['id ']);
            return redirect()->route('SA.Course.Chapter', ['courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function newContentCourseChapter(Request $request) 
    {
        try {
            $course = new CourseChapter;
            $course->course_id = $request->courseID;
            $course->chapter = $request->name;
            $course->save();
            $encrypt = encrypt_decrypt('encrypt',$request->courseID);
            $encryptChapter = encrypt_decrypt('encrypt',$course['id ']);
            return redirect()->route('SA.Content-Creator.Course.Chapter', ['courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function newListedCourseChapter(Request $request) 
    {
        try {
            $course = new CourseChapter;
            $course->course_id = $request->courseID;
            $course->chapter = $request->name;
            $course->save();
            $encrypt = encrypt_decrypt('encrypt',$request->courseID);
            $encryptChapter = encrypt_decrypt('encrypt',$course['id ']);
            return redirect()->route('SA.Addcourse2', ['userID'=> $request->userID, 'courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editCourseChapter(Request $request) 
    {
        try {
            $course = CourseChapter::where('id', $request->chapterID)->update([
                'chapter' => $request->chaptername ?? null
            ]);
            $encrypt = encrypt_decrypt('encrypt',$request->courseID);
            $encryptChapter = encrypt_decrypt('encrypt',$request->chapterID);
            return redirect()->route('SA.Course.Chapter', ['courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editContentCourseChapter(Request $request) 
    {
        try {
            $course = CourseChapter::where('id', $request->chapterID)->update([
                'chapter' => $request->chaptername ?? null
            ]);
            $encrypt = encrypt_decrypt('encrypt',$request->courseID);
            $encryptChapter = encrypt_decrypt('encrypt',$request->chapterID);
            return redirect()->route('SA.Content-Creator.Course.Chapter', ['courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editListedCourseChapter(Request $request) 
    {
        try {
            $course = CourseChapter::where('id', $request->chapterID)->update([
                'chapter' => $request->chaptername ?? null
            ]);
            $encrypt = encrypt_decrypt('encrypt',$request->courseID);
            $encryptChapter = encrypt_decrypt('encrypt',$request->chapterID);
            return redirect()->route('SA.Addcourse2', ['userID' => $request->userID, 'courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteCourseChapter($id) 
    {
        try {
            $course_id = CourseChapter::where('id',$id)->first();
            $encrypt = encrypt_decrypt('encrypt',$course_id->course_id);

            $step = CourseChapterStep::where('course_chapter_id', $id)->get();
            foreach($step as $val){
                $quiz = ChapterQuiz::where('step_id', $val->id)->get();
                foreach($quiz as $item){
                    ChapterQuizOption::where('quiz_id', $item->id)->delete();
                }
                ChapterQuiz::where('step_id', $val->id)->delete();
            }
            CourseChapterStep::where('course_chapter_id', $id)->delete();
            CourseChapter::where('id',$id)->delete();

            $chapter = CourseChapter::where('course_id',$course_id->course_id)->orderByDesc('id')->first();
            if(isset($chapter->id)) $chapterID = encrypt_decrypt('encrypt',$chapter->id);
            else $chapterID = "";
            return redirect('super-admin/course/'.$encrypt.'/'.$chapterID)->with('message','Chapter deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteContentCourseChapter($id) 
    {
        try {
            $course_id = CourseChapter::where('id',$id)->first();
            $encrypt = encrypt_decrypt('encrypt',$course_id->course_id);
            CourseChapter::where('id',$id)->delete();
            $chapter = CourseChapter::where('course_id',$course_id->course_id)->orderByDesc('id')->first();
            if(isset($chapter->id)) $chapterID = encrypt_decrypt('encrypt',$chapter->id);
            else $chapterID = "";
            return redirect('super-admin/content-creator-course/chapters/'.$encrypt.'/'.$chapterID)->with('message','Chapter deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteListedCourseChapter($id, $userID) 
    {
        try {
            $course_id = CourseChapter::where('id',$id)->first();
            $encrypt = encrypt_decrypt('encrypt',$course_id->course_id);
            CourseChapter::where('id',$id)->delete();
            $chapter = CourseChapter::where('course_id',$course_id->course_id)->orderByDesc('id')->first();
            if(isset($chapter->id)) $chapterID = encrypt_decrypt('encrypt',$chapter->id);
            else $chapterID = "";
            return redirect('super-admin/addcourse2/'.$userID.'/'.$encrypt.'/'.$chapterID)->with('message','Chapter deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteChapterQuiz($id) 
    {
        $step = CourseChapterStep::where('id', $id)->where('type', 'quiz')->first();
        if($step->type == 'quiz'){
            $question = ChapterQuiz::where('step_id',$id)->get();
            foreach($question as $val){
                ChapterQuizOption::where('quiz_id',$val->id)->delete();
                ChapterQuiz::where('id',$val->id)->delete();
            }
        }
        CourseChapterStep::where('id', $id)->where('type', 'quiz')->delete();
        return redirect()->back()->with('message', 'Quiz deleted successfully');
    }

    public function deleteChapterSection($id) 
    {
        $step = CourseChapterStep::where('id',$id)->first();
        $msg = ucwords($step->type);
        CourseChapterStep::where('id',$id)->delete();
        return redirect()->back()->with('message', $msg.' deleted successfully');
    }

    public function deleteChapterQuestion($id) 
    {
        $value = ChapterQuiz::where('id',$id)->first();
        $quiz = ChapterQuiz::where('step_id',$value->step_id)->count();
        if($quiz = 1){
            return redirect()->back()->with('error', 'Atleast one question should be required in quiz selection');
        }
        $courseID = encrypt_decrypt('encrypt',$value->course_id);
        $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);
        $question_id = $id; /*question_id*/
        $data = ChapterQuiz::where('id',$question_id)->delete();
        ChapterQuizOption::where('quiz_id',$question_id)->delete();
        return redirect()->back()->with('message', 'Question deleted successfully');
    }

    public function deleteOption($id) 
    {
        $option = ChapterQuizOption::where('id',$id)->first();
        if(isset($option)){
            if($option->is_correct == 1) return redirect()->back()->with('message',"Correct option can't remove.");
            $value = ChapterQuiz::where('id',$option->quiz_id)->first();
            $courseID = encrypt_decrypt('encrypt',$value->course_id);
            $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);
            $option_id = $id; /*Option Id*/
            ChapterQuizOption::where('id',$option_id)->delete();
        }
        return redirect('super-admin/course/'.$courseID.'/'.$chapterID)->with('message','Option deleted successfully');
    }

    public function deleteVideo($id) 
    {
        try {
            $value = CourseChapterStep::where('id',$id)->first();
            $chapterID = encrypt_decrypt('encrypt',$value->course_chapter_id);
            $courseID = CourseChapter::where('id',$value->course_chapter_id)->first();
            $courseID = encrypt_decrypt('encrypt',$courseID->course_id);

            $quiz = CourseChapterStep::where('id',$id)->first();
            $image_name = $quiz->details;
            $image_path = public_path('upload/course/'.$image_name);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            CourseChapterStep::where('id',$id)->update([
                'details' => null,
            ]);
            return redirect('super-admin/course/'.$courseID.'/'.$chapterID)->with('message','Video deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deletePdf($id) 
    {
        try {
            $value = CourseChapterStep::where('id',$id)->first();
            $chapterID = encrypt_decrypt('encrypt',$value->course_chapter_id);
            $courseID = CourseChapter::where('id',$value->course_chapter_id)->first();
            $courseID = encrypt_decrypt('encrypt',$courseID->course_id);

            $quiz = CourseChapterStep::where('id',$id)->first();
            $image_name = $quiz->details;
            $image_path = public_path('upload/course/'.$image_name);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            CourseChapterStep::where('id',$id)->update([
                'details' => null,
            ]);
            return redirect('super-admin/course/'.$courseID.'/'.$chapterID)->with('message','PDF deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateOptionList(Request $request) 
    {
        try {
            ChapterQuizOption::where('id',$request['option_id'])->update([
                'answer_option_key' => $request['option'],
                    ]);
            return 1;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateQuestionList(Request $request) 
    {
        try {
            ChapterQuiz::where('id',$request['question_id'])->update([
                'title' => $request['question'],
                    ]);
            return 1;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function changeAnswerOption($id) 
    {
        try {
            $chapterQuiz = ChapterQuizOption::where('id', $id)->first();
            if(isset($chapterQuiz->id)){
                ChapterQuizOption::where('quiz_id', $chapterQuiz->quiz_id)->update(['is_correct' => '0']);
                $chapter = ChapterQuizOption::where('id', $id)->update(['is_correct' => '1']);
                return response()->json(['status' => 200, 'message'=> "Answer changed."]);
            } else return response()->json(['status' => 201, 'message'=> "Invalid Request"]); 
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function changeOrdering(Request $request,$chapterid) 
    {
        try {
            // dd($request->all());
            $num = $request->order_no;
            foreach($num as $key => $val){
                CourseChapterStep::where('id', $val)->where('course_chapter_id', $chapterid)->update([
                    'sort_order' => $key+1,
                ]);
            }
            return 1;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addOption(Request $request) 
    {
        try {
            // dd($request->all());
            if($request->filled('option_val') && count($request['option_val'])){
                foreach($request['option_val'] as $key => $val){
                    $option = new ChapterQuizOption;
                    $option->quiz_id = $request['quiz_id'];
                    $option->answer_option_key = $val;
                    $option->is_correct = $request['answer_val'][$key] ?? '0';
                    $option->created_date = date('Y-m-d H:i:s');
                    $option->status = 1;
                    $option->save();
                }
            }
            return 1;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function SaveAnswer(Request $request) 
    {
        try {
            $value = ChapterQuiz::where('id',$request['questionID'])->first();
            $courseID = encrypt_decrypt('encrypt',$value->course_id);
            $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);

            ChapterQuiz::where('id',$request['questionID'])->update([
                'correct_answer' => $request['answerID'],
                    ]);
            return redirect('admin/addcourse2/'.$courseID.'/'.$chapterID)->with('message','Answer saved successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function students(Request $request) 
    {
        try {
            $datas = User::where('role',1);
            if($request->filled('name')) $datas->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ");
            if($request->filled('status')) $datas->where('status', $request->status);
            $datas = $datas->orderBy('id','DESC')->paginate(10);
        return view('super-admin.students',compact('datas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function student_detail($id, Request $request) 
    {
        try {
            $user_id = encrypt_decrypt('decrypt',$id);
            $data = User::where('id',$user_id)->first();

            $course = DB::table('user_courses as uc')->join('course as c', 'c.id', '=', 'uc.course_id');
            if($request->filled('status')) $course->where('uc.status', $request->status);
            if($request->filled('title')) $course->where('c.title', 'like', '%' . $request->title . '%');
            if($request->filled('date')) $course->whereDate('uc.buy_date', $request->date);
            $course = $course->where('uc.user_id', $user_id)->select('c.id', 'uc.status', 'uc.created_date', 'uc.updated_date', 'uc.buy_price', 'c.title', 'c.valid_upto', 'c.introduction_image', DB::raw('(select COUNT(*) FROM course_chapter WHERE course_chapter.course_id = c.id) as chapter_count'), DB::raw("(SELECT orders.id FROM orders INNER JOIN order_product_detail ON orders.id = order_product_detail.order_id WHERE orders.user_id = $user_id AND product_id = c.id AND product_type = 1) as order_id"))->orderByDesc('uc.id')->distinct('c.id')->paginate(3);

        return view('super-admin.student-detail',compact('data', 'course', 'id'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function earnings(Request $request) 
    {
        try {
            $walletBalance = WalletBalance::where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->first();
            $orders = Order::join('users as u', 'u.id', '=', 'orders.user_id');
            if($request->filled('name')){
                $orders->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ");
            }
            if($request->filled('number')){
                $orders->where('orders.order_number', 'like', '%'.$request->number.'%');
            }
            if($request->filled('order_date')){
                $orders->whereDate('orders.created_date', date('Y-m-d', strtotime($request->order_date)));
            }
            $orders = $orders->select('orders.order_number', 'orders.id', 'orders.admin_amount', 'orders.amount', 'orders.total_amount_paid', 'orders.status', 'orders.created_date', 'u.first_name', 'u.last_name')->orderByDesc('orders.id')->paginate(10);
            return view('super-admin.earnings',compact('orders', 'walletBalance'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function downloadEarnings(Request $request) 
    {
        try {
            $orders = Order::join('users as u', 'u.id', '=', 'orders.user_id');
            if($request->filled('name')){
                $orders->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ");
            }
            if($request->filled('number')){
                $orders->where('orders.order_number', 'like', '%'.$request->number.'%');
            }
            if($request->filled('order_date')){
                $orders->whereDate('orders.created_date', date('Y-m-d', strtotime($request->order_date)));
            }
            $orders = $orders->select('orders.order_number', 'orders.id', 'orders.admin_amount', 'orders.amount', 'orders.total_amount_paid', 'orders.status', 'orders.created_date', 'u.first_name', 'u.last_name')->orderByDesc('orders.id')->get();
            return $this->downloadEarningExcelFile($orders);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function downloadEarningExcelFile($data)
    {

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Earnings"' . time() . '.csv');
        $output = fopen("php://output", "w");

        fputcsv($output, array('S.no', 'Name', 'Order Number', 'Date Of Payment', 'Payment Mode', 'Admin Cut', 'Total Fee Paid', 'Status'));

        if (count($data) > 0) {
            foreach ($data as $key => $row) {

                $final = [
                    $key + 1,
                    $row->first_name . ' ' . $row->last_name,
                    $row->order_number,
                    date('d M, Y H:iA', strtotime($row->created_date)),
                    'STRIPE',
                    '$'.number_format((float)$row->admin_amount, 2),
                    '$'.number_format((float)$row->total_amount_paid, 2),
                    ($row->status == 1) ? "Paid" : "Payment Pending"
                ];

                fputcsv($output, $final);
            }
        }
    }

    public function notifications(Request $request) 
    {
        try {
            $notify = Notification::orderByDesc('id');
            if($request->filled('title')){
                $notify->where('title', 'like' , '%' . $request->title . '%');
            }
            if($request->filled('date')){
                $notify->whereDate('created_date', $request->date);
            }
            $notify = $notify->paginate(5);
        return view('super-admin.notifications',compact('notify'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function createNotifications() 
    {
        try {
            $user = User::where('role', 2)->get();
            return view('super-admin.create-notification', compact('user'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function storeNotifications(Request $request) 
    {
        try {

            if ($request->img) {
                $img = time().'.'.$request->img->extension();  
                $request->img->move(public_path('upload/notification'), $img);
            }

            $notify = new Notification;
            $notify->push_target = $request->PushNotificationTo;
            $notify->notification_type = null;
            $creator = ($request->PushNotificationTo==1) ? null : $request->ChooseContenttype;
            $notify->creators = $creator;
            $notify->title = $request->title;
            $notify->description = $request->description;
            $notify->image = $img;
            $notify->status = 1;
            $notify->created_date = date('Y-m-d H:i:s');
            $notify->created_by = auth()->user()->id;
            $notify->save();

            if($request->PushNotificationTo==2 && $request->ChooseContenttype == 'S'){
                if($request->filled('cc')){
                    if(count($request->cc) > 0){
                        foreach($request->cc as $val){
                            $notifyCreator = new NotificationCreator;
                            $notifyCreator->notification_id = $notify['id '];
                            $notifyCreator->creator_id = $val;
                            $notifyCreator->created_date = date('Y-m-d H:i:s');
                            $notifyCreator->save();
                        }
                    }
                }
            }

            if(($request->PushNotificationTo == 1) || ($request->PushNotificationTo==2 && $request->ChooseContenttype == 'A')){
                if($request->PushNotificationTo == 1) $user = User::where('role', 1)->where('status', 1)->orderByDesc('id')->get();
                if($request->PushNotificationTo == 2) $user = User::where('role', 2)->where('status', 1)->orderByDesc('id')->get();
                foreach($user as $val){
                    $data = array(
                        'msg' => $request->description,
                        'title' => $request->title
                    );
                    if($request->PushNotificationTo == 1){
                        sendNotification($val->fcm_token ?? "", $data);  
                    }
                    $notify = new Notify;
                    $notify->added_by = auth()->user()->id;
                    $notify->user_id = $val->id ?? null;
                    $notify->module_name = 'course';
                    $notify->title = $request->title;
                    $notify->message = $request->description;
                    $notify->is_seen = '0';
                    $notify->created_at = date('Y-m-d H:i:s');
                    $notify->updated_at = date('Y-m-d H:i:s');
                    $notify->save();
                }
            } else if($request->PushNotificationTo==2 && $request->ChooseContenttype == 'S'){
                if(count($request->cc) > 0){
                    foreach($request->cc as $val){
                        $notify = new Notify;
                        $notify->added_by = auth()->user()->id;
                        $notify->user_id = $val;
                        $notify->module_name = 'course';
                        $notify->title = $request->title;
                        $notify->message = $request->description;
                        $notify->is_seen = '0';
                        $notify->created_at = date('Y-m-d H:i:s');
                        $notify->updated_at = date('Y-m-d H:i:s');
                        $notify->save();
                    }
                }
            }

            return redirect()->route('SA.Notifications')->with('message', 'New notification added successfully.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteNotifications($id) 
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            NotificationCreator::where('notification_id', $id)->delete();
            $user = Notification::where('id', $id)->delete();
            return redirect()->back()->with('message', 'Notification deleted successfully.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function listed_course($id, Request $request) 
    {
        try {
            $id = encrypt_decrypt('decrypt',$id);
            $user = User::where('id',$id)->first();
            $courses = Course::where('admin_id',$id);
            if($request->filled('name')) $courses->where('title', 'like', '%'.$request->name.'%');
            if($request->filled('date')) $courses->whereDate('title', $request->date);
            $courses = $courses->orderBy('id','DESC')->get();

            $payment = WalletHistory::join('wallet_balance as wb', 'wb.id', '=', 'wallet_history.wallet_id')->where('owner_id', $user->id)->where('owner_type', $user->role)->select('wb.id')->first();
            $amount = 0;
            $count = 0;
            if(isset($payment->id)){
                $amount = WalletHistory::where('wallet_id', $payment->id)->where('status', 1)->sum('wallet_history.balance');
                $count = WalletHistory::where('wallet_id', $payment->id)->where('status', 0)->count();
            }

            $account = CardDetail::where('userid', $id)->first();
            return view('super-admin.listed-course',compact('courses','user', 'amount', 'count', 'account'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function InactiveStatus($id) 
    {
        try {
            $user_id = encrypt_decrypt('decrypt',$id);
            $user = User::where('id',$user_id)->first();
            if($user->status == 1)
            {
                $user->status = 2;
            }else{
                $user->status = 1;
            }
            
            $user->save();
            $courses = Course::where('admin_id',$id)->orderBy('id','DESC')->get();
            return redirect()->back()->with('message', 'Status changed successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete_tags($id) 
    {
        try {
            $tag_id = encrypt_decrypt('decrypt',$id);
            $tag = Tag::where('id',$tag_id)->delete();
            return redirect('/super-admin/tag-listing')->with('message', 'Tag deleted successfully');;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update_approval_request($id,$status) 
    {
        try {
            $id = encrypt_decrypt('decrypt',$id);
            $status = encrypt_decrypt('decrypt',$status);
            $user = User::where('id',$id)->first();
            $user->status = $status;
            $user->save();
            $courses = Course::where('admin_id',$id)->orderBy('id','DESC')->get();
            return redirect('super-admin/content-creators')->with('message', 'Status changed successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function SaveStatusCourse(Request $request) 
    {
        try {
            $status = $request->status;
            $course_id = $request->course_id;
            $admin_id = $request->admin_id;
            $adminID = encrypt_decrypt('encrypt',$admin_id);
            Course::where('id',$course_id)->update(['status' => $status]);
            $cc = Course::where('id',$course_id)->first();
            if(isset($cc->id) && $request->status==1){
                $ccUser = User::where('id', $cc->admin_id)->first();
                $user = User::where('role', 1)->where('status', 1)->get();
                if(count($user) > 0){
                    foreach($user as $val){
                        $notify = new Notify;
                        $notify->added_by = auth()->user()->id;
                        $notify->user_id = $val->id;
                        $notify->module_name = 'course';
                        $notify->title = 'New Course';
                        $notify->message = 'New Course ('.$cc->title . ') added by ' . $ccUser->first_name . ' ' . $ccUser->last_name;
                        $notify->is_seen = '0';
                        $notify->created_at = date('Y-m-d H:i:s');
                        $notify->updated_at = date('Y-m-d H:i:s');
                        $notify->save();

                        $data = array(
                            'msg' => 'New Course ('.$cc->title . ') added by ' . $ccUser->first_name . ' ' . $ccUser->last_name,
                            'title' => 'New Course'
                        );
                        sendNotification($val->fcm_token ?? "", $data);
                    }
                }  
            }
            

            return redirect()->back()->with('message','Status Changed successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function tag_listing(Request $request) 
    {
        try {
            $datas = Tag::orderBy('id','DESC');
            if($request->filled('name')) $datas->where('tag_name', 'like', '%'.$request->name.'%');
            if($request->filled('status')) $datas->where('status', $request->status);
            $datas = $datas->paginate(10);
            return view('super-admin.tag-listing',compact('datas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function SaveTag(Request $request) 
    {
        try {
            $tag = Tag::create([
                'tag_name' => $request->input('tag_name'),
                'status' => $request->input('status'),
                'type' => $request->input('type'),
            ]);
            return redirect('super-admin/tag-listing')->with('message','Tag created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function UpdateTag(Request $request) 
    {
        try {
            $tag = Tag::where('id',$request->input('tag_id'))->first();
            $tag->tag_name = $request->input('tag_name');
            $tag->status = $request->input('status');
            $tag->type = $request->input('type');
            $tag->save();
            return redirect('super-admin/tag-listing')->with('message','Tag updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function save_course_fee(Request $request) 
    {
        try {
            $course_fee = $request->course_fee;
            $admin_id = $request->admin_id;
            $adminID = encrypt_decrypt('encrypt',$admin_id);
            User::where('id',$admin_id)->update(['admin_cut' => $course_fee]);
            return redirect('super-admin/listed-course/'.$adminID)->with('message','Status Changed successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function add_course() 
    {
        return view('super-admin.add-course');
    }

    public function products(Request $request) 
    {
        try {
            $datas = Product::orderBy('id','DESC');
            if($request->filled('name')) $datas->where('name', 'like', '%'.$request->name.'%');
            if($request->filled('status')) $datas->where('status', $request->status);
            $datas = $datas->paginate(6);
        return view('super-admin.products',compact('datas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function productViewDetails($id) 
    {
        $id = encrypt_decrypt('decrypt', $id);
        $pro = Product::where('id', $id)->first();
        $cover = ProductAttibutes::where('product_id', $id)->where('attribute_code', 'cover_image')->first();

        $pro->tags = unserialize($pro->tags);
        $tags = Tag::where('status', 1)->where('type', 2)->get();
        $combined = array();

        foreach ($tags as $arr) {
            $comb = array('id' => $arr['id'], 'name' => $arr['tag_name'], 'selected' => false);
            foreach ($pro->tags as $arr2) {
                if ($arr2 == $arr['id']) {
                    $comb['selected'] = true;
                    break;
                }
            }
            $combined[] = $comb;
        }

        $reviewAvg = DB::table('user_review as ur')->where('object_id', $id)->where('object_type',2)->avg('rating');
        $review = DB::table('user_review as ur')->join('users as u', 'u.id', '=', 'ur.userid')->select('u.first_name', 'u.last_name', 'ur.rating', 'ur.review', 'ur.created_date', 'u.profile_image')->where('object_id', $id)->where('object_type', 2)->get();

        $revenue = OrderDetail::where('product_id', $id)->where('product_type', 2)->sum('admin_amount');
        $nooforder = OrderDetail::where('product_id', $id)->where('product_type', 2)->distinct('order_id')->count();

        return view('super-admin.product-details')->with(compact('cover', 'pro', 'combined', 'review', 'reviewAvg', 'revenue', 'nooforder'));
    }

    public function add_product() 
    {
        return view('super-admin.add-product');
    }

    public function deleteProduct($id) 
    {
        try{
            $id = encrypt_decrypt('decrypt', $id);
            Product::where('id', $id)->delete();
            $attr = ProductAttibutes::where('product_id', $id)->get();
            foreach($attr as $val){
                $image_path = app_path("upload/products/{$val->attribute_value}");
                    if(File::exists($image_path)) {
                        unlink($image_path);
                }
            }
            $attr = ProductAttibutes::where('product_id', $id)->delete();
            return redirect()->back()->with('message', 'Product deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteProductImage($id) 
    {
        try{
            $id = encrypt_decrypt('decrypt', $id);
            $attr = ProductAttibutes::where('id', $id)->first();
            $count = ProductAttibutes::where('product_id', $attr->product_id)->count();
            if($count == 1) return redirect()->back()->with('message', "Minimum one product image must be required. Can't Remove");
            $image_path = app_path("upload/products/{$attr->attribute_value}");
                if(File::exists($image_path)) {
                    unlink($image_path);
            }
            ProductAttibutes::where('id', $id)->delete();
            return redirect()->back()->with('message', 'Product image successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editProduct($id) 
    {
        try{
            $id = encrypt_decrypt('decrypt', $id);
            $product = Product::where('id', $id)->first();
            $product->tags = unserialize($product->tags);
            $tags = Tag::where('status', 1)->where('type', 2)->get();
            $combined = array();
            foreach($tags as $arr) {
                $comb = array('id' => $arr['id'], 'name' => $arr['tag_name'], 'selected' => false);
                foreach ($product->tags as $arr2) {
                    if ($arr2 == $arr['id']) {
                        $comb['selected'] = true;
                        break;
                    }
                }
                $combined[] = $comb;
            }
            $coverimg = ProductAttibutes::where('product_id', $id)->where('attribute_code', 'cover_image')->first();
            $attr = ProductAttibutes::where('product_id', $id)->where('attribute_code', 'slide_image')->get();
            return view('super-admin.editProductDetails')->with(compact('product', 'combined', 'coverimg', 'attr'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateProduct(Request $request) 
    {
        try{
            $validator = Validator::make($request->all(), [
                'tags' => 'required|array',
                'name' => 'required',
                'short_description' => 'required',
                'product_weight' => 'required',
                'product_length' => 'required',
                'product_width' => 'required',
                'product_height' => 'required',
                'product_weight_unit' => 'required',
                'product_length_unit' => 'required',
                'product_width_unit' => 'required',
                'product_height_unit' => 'required',
                'package_weight' => 'required',
                'package_length' => 'required',
                'package_width' => 'required',
                'package_height' => 'required',
                'package_weight_unit' => 'required',
                'package_length_unit' => 'required',
                'package_width_unit' => 'required',
                'package_height_unit' => 'required',
                'product_image' => 'image:jpeg,png,jpg',
                'category' => 'required',
                'sku_code' => 'required',
                'status' => 'required',
                'regular_price' => 'required',
                'stock_quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $id = encrypt_decrypt('decrypt', $request->id);
            $product = Product::where('id', $id)->update([
                'name' => $request->input('name'),
                'product_desc' => $request->input('short_description'),
                'price' => $request->input('regular_price'),
                'sale_price' => $request->sale_price ?? null,
                'category_id' => $request->category,
                'unit' => $request->input('stock_quantity'),
                'tags' => serialize($request->tags),
                'status' => $request->status,
                'full_description' => $request->full_description ?? null,
                'refund_policy' => $request->refund_policy ?? null,
                'product_weight' => $request->product_weight,
                'product_length' => $request->product_length,
                'product_width' => $request->product_width,
                'product_height' => $request->product_height,
                'product_weight_unit' => $request->product_weight_unit,
                'product_length_unit' => $request->product_length_unit,
                'product_width_unit' => $request->product_width_unit,
                'product_height_unit' => $request->product_height_unit,
                'package_weight' => $request->package_weight,
                'package_length' => $request->package_length,
                'package_width' => $request->package_width,
                'package_height' => $request->package_height,
                'package_weight_unit' => $request->package_weight_unit,
                'package_length_unit' => $request->package_length_unit,
                'package_width_unit' => $request->package_width_unit,
                'package_height_unit' => $request->package_height_unit,
                'sku_code' => $request->sku_code,
                'stock_available' => $request->stock_avail ?? 0
            ]);
            
            
            if(isset($request->product_image)){
                $destination = public_path('upload/products/');
                $name = time().'.'.$request->product_image->extension();
                $request->product_image->move($destination, $name);
                $profile_image_url = $name;
                $course = ProductAttibutes::where('product_id', $id)->update([
                    'attribute_value' => $profile_image_url,
                ]);
            }
            
            return redirect()->route('SA.Products')->with('message','Product updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function submitproduct(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'tags' => 'required|array',
                'name' => 'required',
                'short_description' => 'required',
                'product_weight' => 'required',
                'product_length' => 'required',
                'product_width' => 'required',
                'product_height' => 'required',
                'product_weight_unit' => 'required',
                'product_length_unit' => 'required',
                'product_width_unit' => 'required',
                'product_height_unit' => 'required',
                'package_weight' => 'required',
                'package_length' => 'required',
                'package_width' => 'required',
                'package_height' => 'required',
                'package_weight_unit' => 'required',
                'package_length_unit' => 'required',
                'package_width_unit' => 'required',
                'package_height_unit' => 'required',
                'product_image' => 'required|image:jpeg,png,jpg',
                'category' => 'required',
                'sku_code' => 'required',
                'status' => 'required',
                'regular_price' => 'required',
                'stock_quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                // dd($request->all());
                $user = User::where('role',3)->first();

                $product = new Product;
                $product->name = $request->input('name');
                $product->product_desc = $request->input('short_description');
                $product->price = $request->input('regular_price');
                $product->sale_price = $request->sale_price ?? null;
                $product->category_id = $request->category;
                $product->unit = $request->input('stock_quantity');
                $product->tags = serialize($request->tags);
                $product->status = $request->status;
                $product->added_by = $user->id;
                $product->full_description = $request->full_description ?? null;
                $product->refund_policy = $request->refund_policy ?? null;
                $product->product_weight = $request->product_weight;
                $product->product_length = $request->product_length;
                $product->product_width = $request->product_width;
                $product->product_height = $request->product_height;
                $product->product_weight_unit = $request->product_weight_unit;
                $product->product_length_unit = $request->product_length_unit;
                $product->product_width_unit = $request->product_width_unit;
                $product->product_height_unit = $request->product_height_unit;
                $product->package_weight = $request->package_weight;
                $product->package_length = $request->package_length;
                $product->package_width = $request->package_width;
                $product->package_height = $request->package_height;
                $product->package_weight_unit = $request->package_weight_unit;
                $product->package_length_unit = $request->package_length_unit;
                $product->package_width_unit = $request->package_width_unit;
                $product->package_height_unit = $request->package_height_unit;
                $product->sku_code = $request->sku_code;
                $product->stock_available = $request->stock_avail ?? 0;
                $product->save();
                
                $product_id = Product::orderBy('id','DESC')->first();
                if(isset($request->product_image)){
                    $destination = public_path('upload/products/');
                    $name = time().'.'.$request->product_image->extension();
                    $request->product_image->move($destination, $name);
                    $profile_image_url = $name;
                    $course = ProductAttibutes::create([
                        'product_id' => $product_id->id,
                        'attribute_type' => 'Cover Image',
                        'attribute_code' => 'cover_image',
                        'attribute_value' => $profile_image_url,
                        'created_date' => date('Y-m-d H:i:s')
                    ]);
                }

                $array_of_image = json_decode($request->array_of_image);
                if(is_array($array_of_image) && count($array_of_image)>0){
                    foreach($array_of_image as $val){
                        ProductAttibutes::where('attribute_value', $val)->update([
                            'product_id' => $product_id->id,
                        ]);
                    }
                }
                    
                return redirect('/super-admin/products')->with('message','Product created successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function imageUpload(Request $request) 
    { 
        $image = $request->file('file');
        $name = $image->getClientOriginalName();
        
        $image->move(public_path('upload/products/'),$name);
        $pro_id = isset($request->id) ? encrypt_decrypt('decrypt', $request->id) : null;
            
        $course = ProductAttibutes::create([
            'product_id' => $pro_id,
            'attribute_type' => 'Slide Image',
            'attribute_code' => 'slide_image',
            'attribute_value' => $name,
            'created_date' => date('Y-m-d H:i:s')
        ]);

        return response()->json(['status'=>true, 'file_name'=> $name, 'key'=> 1]);  
    }

    public function destroy(Request $request)
    {
        $filename =  $request->get('filename');

        $pro = ProductAttibutes::where('attribute_value',$filename);
        if($request->filled('id')) $pro->where('product_id', encrypt_decrypt('decrypt', $request->id));
        else $pro->where('product_id', null);
        $pro = $pro->delete();

        $path=public_path('upload/products/').$filename;
        if (file_exists($path)) {
            unlink($path);
        }
        return response()->json(['status'=>true, 'file_name'=> $filename, 'key'=> 2]);  
    }

    public function account_approval_request() 
    {
        try {
            $users = User::where('status',0)->where('role',2)->orderBy('id','DESC')->get();
            return view('super-admin.account-approval-request',compact('users'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function Addcourse2($userID,$courseID, $chapterID=null) 
    {
        $courseID = encrypt_decrypt('decrypt',$courseID);
        $chapters = CourseChapter::where('course_id',$courseID)->get();
        if($chapterID != null && isset($chapterID)) {
            $chapterID = encrypt_decrypt('decrypt',$chapterID);
        } else {
            if(count($chapters)>0){
               $firstChapter = CourseChapter::where('course_id',$courseID)->first();
                $chapterID = $firstChapter->id;  
            } else $chapterID = null;
        } 
        $datas = CourseChapterStep::where('course_chapter_id', $chapterID)->orderBy('sort_order')->get();
        $ccreator = true;
        
        return view('super-admin.listed-course-chapters',compact('datas','chapters','courseID','chapterID','userID', "ccreator"));
    }

    public function course_list($userID,$courseID,$chapterID) 
    {
        $courseID = encrypt_decrypt('decrypt',$courseID);
        $chapterID = encrypt_decrypt('decrypt',$chapterID);
        $chapters = CourseChapter::where('course_id',$courseID)->get();
        $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        return view('super-admin.course-chapter-list',compact('quizes','datas','chapters','courseID','chapterID','userID'));
    }

    public function category(Request $request) 
    {
        try {
            $datas = Category::orderBy('id','DESC');
            if($request->filled('name')) $datas->where('name', 'like', '%'.$request->name.'%');
            if($request->filled('status')) $datas->where('status', $request->status);
            $datas = $datas->paginate(10);
            return view('super-admin.category-listing',compact('datas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function add_category() 
    {
        return view('super-admin.add-category');
    }

    public function edit_category($id) 
    {
        $id = encrypt_decrypt('decrypt',$id);
        $data = Category::where('id',$id)->first();
        return view('super-admin.edit-category',compact('data'));
    }

    public function submit_category(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
                'cat_status' => 'required',
                'cat_type' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            //dd($request->category_name);

            if ($request->category_image) {
                $imageName = time().'.'.$request->category_image->extension();  
                $request->category_image->move(public_path('upload/category-image'), $imageName);
                if($imageName)
                {
                    $imageName = $imageName;
                }else{
                    $imageName = '';
                }
            }
            
            $Category = new Category;
            $Category->name = $request->category_name;
            $Category->icon =  $imageName;
            $Category->status = $request->cat_status;
            $Category->type = $request->cat_type;
            $Category->save();
            return redirect('/super-admin/category')->with('message','Category created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update_category(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_image' => 'image:jpeg,png,jpg,gif,svg|max:2048',
                'cat_status' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
           
            if ($request->category_image) {
                $imageName = time().'.'.$request->category_image->extension();  
                $request->category_image->move(public_path('upload/category-image'), $imageName);
                    // $icon = public_path() . '/upload/category-image/'. $Category->icon;
                    // unlink($icon);
                    Category::where('id', $request->id)->update(['icon' => $imageName]);
            }
            Category::where('id', $request->id)->update(['name' => $request->category_name,'status'=>$request->cat_status, 'type' => $request->cat_type]);
            return redirect('/super-admin/category')->with('message','Category updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete_categoty($id) 
    {
        try {
            $cat_id = encrypt_decrypt('decrypt',$id);
            $Category = Category::where('id',$cat_id)->first();
            if(!empty($Category->category_image)){
                $category_image = public_path() . '/upload/category-image/'. $Category->category_image;
                unlink($category_image);
            }
            $cat_id = Category::where('id',$cat_id)->delete();
            return redirect('/super-admin/category')->with('message', 'Category deleted successfully');;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function payment_request($userID, Request $request) 
    {
        try {
            $userID = encrypt_decrypt('decrypt',$userID);
            $user = User::where('id', $userID)->first();

            $payment = WalletHistory::join('wallet_balance as wb', 'wb.id', '=', 'wallet_history.wallet_id');
            if($request->filled('status')){
                $payment->where('wallet_history.status', $request->status);
            }
            if($request->filled('order_date')){
                $payment->whereDate('wallet_history.added_date', $request->order_date);
            }
            $payment = $payment->where('owner_id', $user->id)->where('owner_type', $user->role)->select('wallet_history.*')->orderByDesc('wallet_history.id')->paginate(10);
            $amount = 0;
            if(isset($payment[0]->id)){
                $amount = WalletHistory::where('wallet_id', $payment[0]->wallet_id)->where('status', 1)->sum('wallet_history.balance');
            }
            return view('super-admin.payment-request')->with(compact('payment', 'amount', 'userID'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function change_payout_status($id, $status) 
    {
        try {
            $id = encrypt_decrypt('decrypt',$id);
            $status = encrypt_decrypt('decrypt',$status);
            
            $wallet = WalletHistory::where('id', $id)->first();
            if(isset($wallet->id)){
                if($status == 1){
                    WalletHistory::where('id', $id)->update(['status' => $status]);
                    $walletBalance = WalletBalance::where('id', $wallet->wallet_id)->first();
                    WalletBalance::where('id', $wallet->wallet_id)->update([
                        'balance' => $walletBalance->balance + $wallet->balance
                    ]);
                    $msg = 'Payout request approved successfully.';
                } else{
                    WalletHistory::where('id', $id)->update(['status' => $status]);
                    $msg = 'Payout request rejected successfully.';
                } 
            }else return redirect()->back()->with('message', 'Something went wrong!');
            return redirect()->back()->with('message', $msg);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateTitlePercentage(Request $request, $id) {
        try{
            $validator = Validator::make($request->all(), [
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $id = encrypt_decrypt('decrypt', $id);
                $step = CourseChapterStep::where('id', $id)->first();
                CourseChapterStep::where('id', $id)->update([
                    'title' => $request->description ?? null,
                    'passing' => $request->passing_per ?? null,
                ]);
                return redirect()->back()->with('message', ucwords($step->type)." details updated successfully.");
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    public function changePrerequisite(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'val' => 'required',
                'answer' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $id = encrypt_decrypt('decrypt', $request->val);
                $step = CourseChapterStep::where('id', $id)->first();
                CourseChapterStep::where('id', $id)->update([
                    'prerequisite' => $request->answer ?? 0,
                ]);
                return response()->json(['status' => 200, 'message' => "Prerequisite " . ($request->answer==1 ? 'added' : 'removed') . " for this section"]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    public function addNewQuestion(Request $request) {
        try{

            if(isset($request->questions) && count($request->questions) > 0){
                foreach($request->questions as $key => $value){
                    $quiz = ChapterQuiz::where('id', $key)->first();
                    if(count($value) > 0){
                        foreach($value as $keyQ => $valQ){
                            $ChapterQuiz = new ChapterQuiz;
                            $ChapterQuiz->title = $valQ['text'];
                            $ChapterQuiz->type = 'quiz';
                            $ChapterQuiz->chapter_id = $request->chapter_id;
                            $ChapterQuiz->course_id = $request->courseID;
                            $ChapterQuiz->step_id = $quiz->step_id;
                            $ChapterQuiz->marks = $valQ['marks'] ?? 0;
                            $ChapterQuiz->save();
                            $quiz_id = ChapterQuiz::orderBy('id','DESC')->first();
                            foreach ($valQ['options'] as $keyOp => $optionText) {
                                $isCorrect = '0';
                                if(isset($valQ['correct'])){
                                    $isCorrect = ($valQ['correct']==$keyOp) ? '1' : '0';
                                }
                                $option = new ChapterQuizOption;
                                $option->quiz_id = $quiz_id->id;
                                $option->answer_option_key = $optionText;
                                $option->is_correct = $isCorrect;
                                $option->created_date = date('Y-m-d H:i:s');
                                $option->status = 1;
                                $option->save();
                            }
                        }
                    }
                }
            }
            return redirect()->back()->with('message', 'New question has been added successfully.');
            // return response()->json(['status' => 200, 'message' => 'New question has been added successfully.']);
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    public function addNewSurveyQuestion(Request $request) {
        try{

            if(isset($request->survey_question) && count($request->survey_question) > 0){
                foreach($request->survey_question as $key => $value){
                    $quiz = ChapterQuiz::where('id', $key)->first();
                    if(count($value) > 0){
                        foreach($value as $keyQ => $valQ){
                            $ChapterQuiz = new ChapterQuiz;
                            $ChapterQuiz->title = $valQ['text'];
                            $ChapterQuiz->type = 'survey';
                            $ChapterQuiz->chapter_id = $request->chapter_id;
                            $ChapterQuiz->course_id = $request->courseID;
                            $ChapterQuiz->step_id = $quiz->step_id;
                            $ChapterQuiz->save();
                            $quiz_id = ChapterQuiz::orderBy('id','DESC')->first();
                            foreach ($valQ['options'] as $keyOp => $optionText) {
                                // dd($optionText);
                                $option = new ChapterQuizOption;
                                $option->quiz_id = $quiz_id->id;
                                $option->answer_option_key = $optionText;
                                $option->is_correct = '0';
                                $option->created_date = date('Y-m-d H:i:s');
                                $option->status = 1;
                                $option->save();
                            }
                        }
                    }
                }
            }
            return redirect()->back()->with('message', 'New question has been added successfully.');
            // return response()->json(['status' => 200, 'message' => 'New question has been added successfully.']);
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    public function downloadInvoice(Request $request, $id) {
        try{    
            $id = encrypt_decrypt('decrypt', $id);
            $order = Order::where('orders.id', $id)->leftJoin('users as u', 'u.id', '=', 'orders.user_id')->select('u.first_name', 'u.last_name', 'u.email', 'u.profile_image', 'u.phone', 'u.role', 'u.status as ustatus', 'orders.id', 'orders.order_number', 'orders.created_date', 'orders.status', 'orders.taxes', 'orders.total_amount_paid')->first();

            $orderDetails = DB::table('orders')->select(DB::raw("ifnull(c.title,p.name) title, order_product_detail.quantity, order_product_detail.product_id, order_product_detail.product_type, ifnull(c.status,p.status) status, order_product_detail.amount, order_product_detail.admin_amount, ifnull(c.introduction_image,(select attribute_value from product_details pd where p.id = pd.product_id and attribute_type = 'cover_image' limit 1))  as image"))->join('users as u', 'orders.user_id', '=', 'u.id')->join('order_product_detail', 'orders.id', '=', 'order_product_detail.order_id')->leftjoin('course as c', 'c.id','=', DB::raw('order_product_detail.product_id AND order_product_detail.product_type = 1'))->leftjoin('product as p', 'p.id','=', DB::raw('order_product_detail.product_id AND order_product_detail.product_type = 2'))->where('orders.id', $id)->get();

            $transaction = Order::where('orders.id', $id)->leftJoin('payment_detail as pd', 'pd.id', '=', 'orders.payment_id')->leftJoin('payment_methods as pm', 'pm.id', '=', 'pd.card_id')->select('pm.card_no', 'pm.card_type', 'pm.method_type', 'pm.expiry')->first();
            
            $pdf = PDF::loadView('home.pdf-invoice', compact('order', 'orderDetails', 'transaction'), [], [ 
                'mode' => 'utf-8',
                'title' => 'Order Invoice',
                'format' => 'Legal',
            ]);
            return $pdf->stream($order->order_number.'-invoice.pdf');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function clearNotification(Request $request) {
        try{    
            Notify::where('user_id', auth()->user()->id)->delete();
            return redirect()->back()->with('message', 'All notification cleared.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function progressReport(Request $request, $courseId, $id) {
        try{    
            $courseId = encrypt_decrypt('decrypt',$courseId);
            $id = encrypt_decrypt('decrypt',$id);

            $course = Course::where('id', $courseId)->first();
            $course->tags = unserialize($course->tags);
            $tags = Tag::where('status', 1)->where('type', 2)->get();
            $combined = array();
            foreach ($tags as $arr) {
                $comb = array('id' => $arr['id'], 'name' => $arr['tag_name'], 'selected' => false);
                foreach ($course->tags as $arr2) {
                    if ($arr2 == $arr['id']) {
                        $comb['selected'] = true;
                        break;
                    }
                }
                $combined[] = $comb;
            }
            $reviewAvg = DB::table('user_review as ur')->where('object_id', $courseId)->where('object_type', 1)->avg('rating');
            $review = DB::table('user_review as ur')->join('users as u', 'u.id', '=', 'ur.userid')->select('u.first_name', 'u.last_name', 'ur.rating', 'ur.review', 'ur.created_date')->where('object_id', $courseId)->where('object_type', 1)->get();

            $userCourse = UserCourse::where('user_id', $id)->where('course_id', $courseId)->where('status', 1)->first();
            if(isset($userCourse->id)){
                $complete = true;
                $chapters = UserChapterStatus::leftJoin('course_chapter as cc', 'cc.id', '=', 'user_chapter_status.chapter_id')->where('user_chapter_status.userid', $id)->where('user_chapter_status.course_id', $courseId)->select('cc.chapter', 'cc.id')->distinct('cc.id')->get();
            }else{
                $complete = false;
                $chapters = CourseChapter::where('course_chapter.course_id', $courseId)->select('course_chapter.chapter', 'course_chapter.id')->distinct('course_chapter.id')->get();
            }

            return view('super-admin.progress-course-details')->with(compact('course', 'combined', 'review', 'reviewAvg', 'id', 'chapters'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function checkSkuCode(Request $request)
    {
        $check_user = Product::where('sku_code','=',$request->sku_code);
        if($request->filled('pro_id')){
            $check_user->where('id', '!=', $request->pro_id);
        }
        $check_user = $check_user->first();
        if($check_user)
        {
            echo json_encode('This SKU Code is already exist.');
        }else{
            echo json_encode(true);
        }
    }

    public function checkPassword(Request $request){
        try{
            $user = User::where('id', auth()->user()->id)->first();
            if(!(Hash::check($request->old_pswd, $user->password))){
                echo json_encode("Old Password doesn't match with Current Password.");
            } else echo json_encode(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function coupons(Request $request){
        try{
            $coupon = DB::table('coupons as c');
            if($request->filled('coupon_code')) $coupon->where('coupon_code', 'like', '%'.$request->coupon_code.'%');
            $coupon = $coupon->orderByDesc('id')->paginate(12);
            return view('super-admin.coupons')->with(compact('coupon'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function checkCouponCode(Request $request){
        try{
            $id = encrypt_decrypt('decrypt', $request->coupon_id);
            $coupon = Coupon::where('coupon_code', $request->code);
            if($request->filled('coupon_id')) $coupon->where('id', '!=', $id);
            $coupon = $coupon->first();
            if(isset($coupon->id))
            {
                echo json_encode('This Coupon Code is already exist.');
            }else{
                echo json_encode(true);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function add_coupon(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'type' => 'required',
                'amount' => 'required',
                'min_amount' => 'required',
                'date' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $coupon = new Coupon;
                $coupon->coupon_code = $request->code;
                $coupon->object_type = $request->object_type ?? 2;
                $coupon->coupon_expiry_date = $request->date;
                $coupon->coupon_discount_type = $request->type;
                $coupon->min_order_amount = $request->min_amount;
                $coupon->coupon_discount_amount = $request->amount;
                $coupon->description = $request->description ?? null;
                $coupon->status = 1;
                $coupon->save();
                return redirect()->back()->with('message', 'New coupon added successfully.');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function get_coupon_details(Request $request){
        try{
            $id = encrypt_decrypt('decrypt', $request->id);
            $coupon = Coupon::where('id', $id)->first();
            if(isset($coupon->id)){
                return response()->json(['status'=> true, 'message'=> 'Coupon found', 'data'=> $coupon]);
            } else return response()->json(['status'=> false, 'message'=> 'No coupon found']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update_coupon(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'code' => 'required',
                'type' => 'required',
                'amount' => 'required',
                'min_amount' => 'required',
                'date' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                Coupon::where('id', $id)->update([
                    'coupon_code' => $request->code,
                    'object_type' => $request->object_type ?? 2,
                    'coupon_expiry_date' => $request->date,
                    'coupon_discount_type' => $request->type,
                    'min_order_amount' => $request->min_amount,
                    'coupon_discount_amount' => $request->amount,
                    'description' => $request->description ?? null,
                    'status' => 1
                ]);
                return redirect()->back()->with('message', 'Coupon updated successfully.');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete_coupon($id){
        try{
            $id = encrypt_decrypt('decrypt', $id);
            Coupon::where('id', $id)->delete();
            return redirect()->back()->with('message', 'Coupon deleted successfully.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function product_orders(Request $request){
        try{
            $fee = Order::join('order_product_detail as opd', 'opd.order_id', '=', 'orders.id')->where('opd.product_type', $request->type ?? 1)->where('orders.status', 1)->select([DB::raw("SUM(orders.admin_amount) as sum")])->distinct('orders.order_number')->first();

            $orders = Order::join('users as u', 'u.id', '=', 'orders.user_id')->join('order_product_detail as opd', 'opd.order_id', '=', 'orders.id');
            if($request->filled('name')){
                $orders->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ");
            }
            if($request->filled('number')){
                $orders->where('orders.order_number', 'like', '%'.$request->number.'%');
            }
            if($request->filled('order_date')){
                $orders->whereDate('orders.created_date', date('Y-m-d', strtotime($request->order_date)));
            }
            if($request->filled('type')){
                $orders->where('opd.product_type', $request->type);
            }else $orders->where('opd.product_type', 1);
            $orders = $orders->select('orders.order_number', 'orders.id', 'orders.admin_amount', 'orders.amount', 'orders.total_amount_paid', 'orders.status', 'orders.created_date', 'u.first_name', 'u.last_name')->where('orders.status', 1)->distinct('orders.order_number')->orderByDesc('orders.id')->paginate(10);

            return view('super-admin.product-orders')->with(compact('orders', 'fee'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function product_order_details(Request $request, $id){
        try{
            $id = encrypt_decrypt('decrypt', $id);
            $order = Order::where('orders.id', $id)->leftJoin('users as u', 'u.id', '=', 'orders.user_id')->select('u.first_name', 'u.last_name', 'u.email', 'u.profile_image', 'u.phone', 'u.role', 'u.status as ustatus', 'orders.id', 'orders.order_number', 'orders.created_date', 'orders.status', 'orders.taxes')->first();

            $transaction = Order::where('orders.id', $id)->leftJoin('payment_detail as pd', 'pd.id', '=', 'orders.payment_id')->leftJoin('payment_methods as pm', 'pm.id', '=', 'pd.card_id')->select('pm.card_no', 'pm.card_type', 'pm.method_type', 'pm.expiry')->first();

            return view('super-admin.product-order-details')->with(compact('order', 'transaction'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function posts(Request $request){
        try{
            $pages = DB::table('pages');
            if($request->filled('title')) $pages->where('title', 'like', '%'.$request->title.'%');
            if($request->filled('status')) $pages->where('status', $request->status); 
            $pages = $pages->paginate(10);
            return view('super-admin.posts')->with(compact('pages'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create_post(Request $request){
        try{
            return view('super-admin.create-post');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function store_post(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors()->first())->withInput();
            } else {
                $page = new Page;
                $page->title = $request->title ?? null;
                $page->description = $request->description ?? null;
                $page->status = $request->status ?? 0;
                $page->save();
                return redirect()->route('SA.Posts')->with('message', 'New post added successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete_post($id){
        try{
            $id = encrypt_decrypt('decrypt', $id);
            Page::where('id', $id)->delete();
            return redirect()->back()->with('message', 'Post deleted successfully.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit_post($id){
        try{
            $id = encrypt_decrypt('decrypt', $id);
            $data = Page::where('id', $id)->first();
            return view('super-admin.edit-post')->with(compact('data'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update_post(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors()->first())->withInput();
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                Page::where('id', $id)->update([
                    'title' => $request->title ?? null,
                    'description' => $request->description ?? null,
                    'status' => $request->status ?? 0,
                ]);
                return redirect()->route('SA.Posts')->with('message', 'Post updated successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function content_creator_course(Request $request){
        try{
            $course = Course::join('users as u', 'u.id', '=', 'course.admin_id');
            if($request->filled('name')) $course->where('title', 'like', '%'.$request->name.'%');
            if($request->filled('status')) $course->where('course.status', $request->status);
            if($request->filled('creator')) $course->where('u.id', encrypt_decrypt('decrypt', $request->creator));
            $course = $course->where('admin_id', '!=', '1')->select('u.first_name', 'u.last_name', 'u.profile_image', 'course.*')->orderByDesc('course.id')->paginate(10);
            return view('super-admin.content-creator-course')->with(compact('course'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function content_creator_course_chapters(Request $request, $courseID, $chapterID=null){
        try {
            $courseID = encrypt_decrypt('decrypt',$courseID);
            $chapters = CourseChapter::where('course_id',$courseID)->get();
            if($chapterID != null && isset($chapterID)) {
                $chapterID = encrypt_decrypt('decrypt',$chapterID);
            } else {
                if(count($chapters)>0){
                   $firstChapter = CourseChapter::where('course_id',$courseID)->first();
                    $chapterID = $firstChapter->id;  
                } else $chapterID = null;
            } 
            $datas = CourseChapterStep::where('course_chapter_id', $chapterID)->orderBy('sort_order')->get();
            return view('super-admin.content-creator-course-chapter',compact('datas','chapters','courseID','chapterID'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function help_support_save_img(Request $request){
        try {
            $name = time().'.'.$request->image->extension();  
            $request->image->move(public_path('upload/chat'), $name); 
            return response()->json(['status' => true, 'url' => $name, 'message' => 'image upload successfully.']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
