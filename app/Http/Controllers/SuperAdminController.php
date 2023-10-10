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
use App\Models\Notification;
use App\Models\NotificationCreator;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductAttibutes;
use App\Models\WalletBalance;
use App\Models\WalletHistory;
use Auth;
use Illuminate\Support\Facades\Validator;
use VideoThumbnail;
use Illuminate\Support\Facades\File;
use DB;
use Maatwebsite\Excel\Facades\Excel;

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
            		->get();
        }else{
            $movies =Tag::select("id", "tag_name")->where('type', '1')->get();
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

    public function myAccount() 
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();
            return view('super-admin.my-account')->with(compact('user'));
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
                'valid_upto' => 'required',
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
            $course->valid_upto = $request->valid_upto;
            $course->category_id = $request->course_category;
            $course->tags = serialize($request->tags);
            $course->certificates = null;
            $course->introduction_image = $disclaimers_introduction;
            $course->status = 1;
            $course->save();

            return redirect('/super-admin/course')->with('message','Course created successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editCourse($id) 
    {
        $id = encrypt_decrypt('decrypt', $id);
        $course = Course::where('id', $id)->first();
        $course->tags = unserialize($course->tags);
        $tags = Tag::where('type', 1)->get();
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
            // $imageName = $course->certificates;
            // if ($request->certificates) {
            //     $imageName = time().'.'.$request->certificates->extension();  
            //     $request->certificates->move(public_path('upload/course-certificates'), $imageName);

            //     $image_path = app_path("upload/course-certificates/{$course->certificates}");
            //     if(File::exists($image_path)) {
            //         unlink($image_path);
            //     }
            // }
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
                'valid_upto' => $request->valid_upto,
                'tags' => serialize($request->tags),
                'certificates' => null,
                'category_id' => $request->course_category,
                'introduction_image' => $disclaimers_introduction,
                'status' => 0,
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
        $tags = Tag::all();
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
            if($chapterID != null && isset($chapterID)) $chapterID = encrypt_decrypt('decrypt',$chapterID);
            else $chapterID = null;
            $chapters = CourseChapter::where('course_id',$courseID)->get();
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
                                    $optionCount = 0;
                                    foreach ($valueQVal['options'] as $keyOp => $optionText) {
                                        $isCorrect = '0';
                                        if($optionCount == 0 && !isset($valueQVal['correct'])){
                                            $isCorrect = '1';
                                        }else {
                                            $isCorrect = '0';
                                        }
                                        $option = new ChapterQuizOption;
                                        $option->quiz_id = $quiz_id->id;
                                        $option->answer_option_key = $optionText;
                                        $option->is_correct = $valueQVal['correct'][$keyOp] ?? $isCorrect;
                                        $option->created_date = date('Y-m-d H:i:s');
                                        $option->status = 1;
                                        $option->save();
                                        $optionCount++;
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
                                $Step->duration = $request->required_field[$keyS] ?? 0;
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
            return response()->json(['status' => 201, 'message' => 'Question saved successfully']);
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

    public function deleteCourseChapter($id) 
    {
        try {
            $course_id = CourseChapter::where('id',$id)->first();
            $encrypt = encrypt_decrypt('encrypt',$course_id->course_id);
            CourseChapter::where('id',$id)->delete();
            $chapter = CourseChapter::where('course_id',$course_id->course_id)->orderByDesc('id')->first();
            if(isset($chapter->id)) $chapterID = encrypt_decrypt('encrypt',$chapter->id);
            else $chapterID = "";
            return redirect('super-admin/course/'.$encrypt.'/'.$chapterID)->with('message','Chapter deleted successfully');
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
        $courseID = encrypt_decrypt('encrypt',$value->course_id);
        $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);
        $question_id = $id; /*question_id*/
        $data = ChapterQuiz::where('id',$question_id)->delete();
        ChapterQuizOption::where('quiz_id',$question_id)->delete();
        return redirect('super-admin/course/'.$courseID.'/'.$chapterID)->with('message', 'Question deleted successfully');
    }

    public function deleteOption($id) 
    {
        $option = ChapterQuizOption::where('id',$id)->first();
        $value = ChapterQuiz::where('id',$option->quiz_id)->first();
        $courseID = encrypt_decrypt('encrypt',$value->course_id);
        $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);
        $option_id = $id; /*Option Id*/
        ChapterQuizOption::where('id',$option_id)->delete();
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
                CourseChapterStep::where('id',$id)->update([
                    'details' => null,
                    ]);
                File::delete($image_path);
            }
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
                CourseChapterStep::where('id',$id)->update([
                    'details' => null,
                    ]);
                File::delete($image_path);
            }
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

    public function changeAnswerOption($id, $val) 
    {
        try {
            $chapterQuiz = ChapterQuizOption::where('id', $id)->first();
            $quizOptionCount = ChapterQuizOption::where('quiz_id', $chapterQuiz->quiz_id)->where('is_correct', '1')->count();
            // $quizOptionsId = ChapterQuizOption::where('quiz_id', $$chapterQuiz->quiz_id)->where('is_correct', 1)->pluck('id');
            if($quizOptionCount == 1 && $val == 0){
                return response()->json(['status' => 201, 'message'=> "Question should have atleast one correct answer. Don't try to uncheck."]);
            }else{
                $chapter = ChapterQuizOption::where('id', $id)->update(['is_correct' => $val]);
                return response()->json(['status' => 200, 'message'=> "Answer changed."]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function changeOrdering($chapterid, $id, $val) 
    {
        try {
            $chapter = CourseChapterStep::where('course_chapter_id', $chapterid)->where('id', $id)->first();
            $orderingNum = $chapter->sort_order;
            // return $orderingNum;
            CourseChapterStep::where('id',$id)->where('course_chapter_id', $chapterid)->update([
                'sort_order' => $val,
                    ]);
            CourseChapterStep::where('sort_order', $val)->where('course_chapter_id', $chapterid)->where('id', '!=', $id)->update([
                        'sort_order' => $orderingNum,
                            ]);
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
            $orders = $orders->select('orders.order_number', 'orders.id', 'orders.admin_amount', 'orders.amount', 'orders.total_amount_paid', 'orders.status', 'orders.created_date', 'u.first_name', 'u.last_name')->paginate(10);

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
                    number_format((float)$row->admin_amount, 2),
                    number_format((float)$row->total_amount_paid, 2),
                    ($row->status == 1) ? "Active" : "Pending"
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

            if($request->ChooseContenttype == 'S'){
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

            return redirect()->route('SA.Notifications')->with('message', 'New notification added successfully.');
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
            $user = User::where('id', $id)->first();

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
            return redirect()->back()->with('message','Status Changed successfully');
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
            $tags = Tag::where('type', 2)->get();
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
            $attr = ProductAttibutes::where('product_id', $id)->get();
            return view('super-admin.editProductDetails')->with(compact('product', 'combined', 'attr'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateProduct(Request $request) 
    {
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'price' => 'required',
                'qnt' => 'required',
                'product_category' => 'required',
                'description' => 'required',
                //'livesearch' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $id = encrypt_decrypt('decrypt', $request->id);
            $product = Product::where('id', $id)->update([
                'name' => $request->title,
                'price' => $request->price,
                'unit' => $request->qnt,
                'tags' => serialize($request->tags),
                'category_id' => $request->product_category,
                'product_desc' => $request->description,
            ]);
            
            
            if ($files=$request->file('image')){
                foreach ($files as $j => $file){
                    $destination = public_path('upload/products/');
                    $name = time().'.'.$file->extension();
                    $file->move($destination, $name);
                    $profile_image_url = $name;
                    $course = ProductAttibutes::create([
                        'product_id' => $id,
                        'attribute_type' => 'Image',
                        'attribute_code' => 'Image',
                        'attribute_value' => $profile_image_url,
                    ]);
                }
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
                'image' => 'required|array|min:1',
                'image.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'title' => 'required',
                'price' => 'required',
                'qnt' => 'required',
                'product_category' => 'required',
                'description' => 'required',
                //'livesearch' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $user = User::where('role',3)->first();
            $USERID = $user->id;
            $course = Product::create([
                'name' => $request->input('title'),
                'product_desc' => $request->input('description'),
                'price' => $request->input('price'),
                'category_id' => $request->product_category,
                'unit' => $request->input('qnt'),
                'tags' => serialize($request->tags),
                //'Product_image' => ($imageName)?json_encode($imageName):$imageName,
                'status' => 1,
                'added_by' => 1
            ]);
            $product_id = Product::orderBy('id','DESC')->first();
            $imageName = array();
            if ($files=$request->file('image')){
                $type_a = false;
                $type_b = false;
                foreach ($files as $j => $file){
                    $destination = public_path('upload/products/');
                    $name = time().'.'.$file->extension();
                    $file->move($destination, $name);
                    $profile_image_url = $name;
                    $course = ProductAttibutes::create([
                        'product_id' => $product_id->id,
                        'attribute_type' => 'Image',
                        'attribute_code' => 'Image',
                        'attribute_value' => $profile_image_url,
                    ]);
                    //$imageName[]= $profile_image_url;
                }
            }

            // $arr_tag = $request->input('livesearch');
            // $tag = implode(",",$arr_tag);
            
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
            $chapterID = null;
            $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
            $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        } else {
            $chapterID = null;
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
        return view('super-admin.course-chapter-list',compact('quizes','datas','chapters','courseID','chapterID','userID'));
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
            $payment = $payment->where('owner_id', $user->id)->where('owner_type', $user->role)->select('wallet_history.*')->paginate(10);
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



}
