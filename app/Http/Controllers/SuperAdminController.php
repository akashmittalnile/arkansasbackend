<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseChapter;
use App\Models\ChapterQuiz;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use VideoThumbnail;

class SuperAdminController extends Controller
{

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
            $courses = Course::orderBy('id','DESC')->get();
        return view('super-admin.students',compact('courses'));
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

    public function products() 
    {
        try {
            $courses = Course::orderBy('id','DESC')->get();
        return view('super-admin.products',compact('courses'));
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
            $user->status = 2;
            $user->save();
            $courses = Course::where('admin_id',$id)->orderBy('id','DESC')->get();
            return redirect('/super-admin/content-creators')->with('message', 'Status changed successfully');;
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
}
