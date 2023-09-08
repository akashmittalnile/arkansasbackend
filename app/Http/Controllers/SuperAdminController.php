<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseChapter;
use App\Models\ChapterQuiz;
use App\Models\Tag;
use App\Models\Product;
use App\Models\Category;
use Auth;
use Illuminate\Support\Facades\Validator;
use VideoThumbnail;

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
            		->get();
        }else{
            $movies =Tag::select("id", "tag_name")
            		->get();
        }
        return response()->json($movies);
    }

    public function dashboard() 
    {
        try {
            $courses = Course::orderBy('id','DESC')->get();
        return view('super-admin.dashboard',compact('courses'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function help_support() 
    {
        try {
            $courses = Course::orderBy('id','DESC')->get();
        return view('super-admin.help-support',compact('courses'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function performance() 
    {
        try {
            $courses = Course::orderBy('id','DESC')->get();
        return view('super-admin.performance',compact('courses'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function content_creators() 
    {
        try {
            $users = User::where('status',1)->where('role',2)->orderBy('id','DESC')->get();
        return view('super-admin.content-creators',compact('users'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function course() 
    {
        try {
            $courses = Course::orderBy('id','DESC')->where('admin_id',1)->get();
            return view('super-admin.course',compact('courses'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function students() 
    {
        try {
            $datas = User::where('role',1)->orderBy('id','DESC')->get();
        return view('super-admin.students',compact('datas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function student_detail($id) 
    {
        try {
            $user_id = encrypt_decrypt('decrypt',$id);
            $data = User::where('id',$user_id)->first();
        return view('super-admin.student-detail',compact('data'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function earnings() 
    {
        try {
            $courses = Course::orderBy('id','DESC')->get();
        return view('super-admin.earnings',compact('courses'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function notifications() 
    {
        try {
            $courses = Course::orderBy('id','DESC')->get();
        return view('super-admin.notifications',compact('courses'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function listed_course($id) 
    {
        try {
            $id = encrypt_decrypt('decrypt',$id);
            $user = User::where('id',$id)->first();
            $courses = Course::where('admin_id',$id)->orderBy('id','DESC')->get();
            return view('super-admin.listed-course',compact('courses','user'));
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
            return redirect('super-admin/listed-course/'.$adminID)->with('message','Status Changed successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function tag_listing() 
    {
        try {
            $datas = Tag::orderBy('id','DESC')->get();
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
            User::where('id',$admin_id)->update(['course_fee' => $course_fee]);
            return redirect('super-admin/listed-course/'.$adminID)->with('message','Status Changed successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function add_course() 
    {
        return view('super-admin.add-course');
    }

    public function submitcourse(Request $request) 
    {
        try {
            VideoThumbnail::createThumbnail(
                public_path('upload/disclaimers-introduction/1692868718.mp4'), 
                public_path('thumbnail/'), 
                'movie.jpg', 
                2, 
                1920, 
                1080
            );
            dd(4);
            $validator = Validator::make($request->all(), [
                //'certificates' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
                //'disclaimers_introduction' => 'required|mimes:mp4',
            ]);

            // if ($validator->fails()) {
            //     return redirect()->back()->withErrors($validator)->withInput();
            // }else{
            //     return 'validator failed';
            // }
            
            $user = User::where('role',3)->first();
            $USERID = $user->id;

            if ($request->certificates) {
                $imageName = time().'.'.$request->certificates->extension();  
                $request->certificates->move(public_path('upload/course-certificates'), $imageName);
                if($imageName)
                {
                    $imageName = $imageName;
                }else{
                    $imageName = '';
                }
            }
            if ($request->disclaimers_introduction) {
                $disclaimers_introduction = time().'.'.$request->disclaimers_introduction->extension();  
                $request->disclaimers_introduction->move(public_path('upload/disclaimers-introduction'), $disclaimers_introduction);
                if($disclaimers_introduction)
                {
                    $disclaimers_introduction = $disclaimers_introduction;
                }else{
                    $disclaimers_introduction = '';
                }

                
            }

            
            
            $course = Course::create([
                // 'admin_id' => Auth::user()->id,
                'admin_id' => 1,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'fee_type' => $request->input('fee_type'),
                //'course_fee' => $request->input('course_fee'),
                'valid_upto' => $request->input('valid_upto'),
                'tags' => $request->input('tags'),
                'certificates' => $imageName,
                'status' => 1,
                'introduction_image' => $disclaimers_introduction
            ]);
            return redirect('/super-admin/course')->with('message','Course created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function products() 
    {
        try {
            $datas = Product::orderBy('id','DESC')->get();
        return view('super-admin.products',compact('datas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function add_product() 
    {
        return view('super-admin.add-product');
    }

    public function submitproduct(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|array|min:1',
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'title' => 'required',
                'price' => 'required',
                'qnt' => 'required',
                'description' => 'required',
                'livesearch' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $user = User::where('role',3)->first();
            $USERID = $user->id;

            $imageName = array();
            if ($files=$request->file('image')){
                $type_a = false;
                $type_b = false;
                foreach ($files as $j => $file){
                    $destination = public_path('upload/products/');
                    $name = time().'.'.$file->extension();
                    $file->move($destination, $name);
                    $profile_image_url = $name;
                    $imageName[]= $profile_image_url;
                }
            }

            $arr_tag = $request->input('livesearch');
            $tag = implode(",",$arr_tag);
            $course = Product::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'qnt' => $request->input('qnt'),
                'tags' => $tag,
                'Product_image' => ($imageName)?json_encode($imageName):$imageName,
                'status' => 1,
            ]);
            return redirect('/super-admin/products')->with('message','Product created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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

    public function Addcourse2($userID,$courseID) 
    {
        $courseID = encrypt_decrypt('decrypt',$courseID);
        $chapters = CourseChapter::where('course_id',$courseID)->get();
        if (count($chapters)>0) {
            $chapterID = $chapters[0]->id;
            $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
            $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        } else {
            $chapterID = '';
            $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->get();
            $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->get();
        }
        
        return view('super-admin.addcourse2',compact('quizes','datas','chapters','courseID','chapterID','userID'));
    }

    public function course_list($userID,$courseID,$chapterID) 
    {
        $courseID = encrypt_decrypt('decrypt',$courseID);
        $chapterID = encrypt_decrypt('decrypt',$chapterID);
        $chapters = CourseChapter::where('course_id',$courseID)->get();
        $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        return view('super-admin.addcourse2',compact('quizes','datas','chapters','courseID','chapterID','userID'));
    }

    public function category() 
    {
        try {
            $datas = Category::orderBy('id','DESC')->get();
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
                'category_image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
                'cat_status' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
           
            $Category = Category::where('id',$request->id)->first();
            if ($request->category_image) {
                $imageName = time().'.'.$request->category_image->extension();  
                $request->category_image->move(public_path('upload/category-image'), $imageName);
                if($imageName)
                {
                    $imageName = $imageName;
                    $category_image = public_path() . '/upload/category-image/'. $Category->category_image;
                    unlink($category_image);
                    $Category->icon =  $imageName;
                }else{
                    $imageName = '';
                }
            }
            
            $Category->name = $request->category_name;
            $Category->status = $request->cat_status;
            $Category->save();
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

}
