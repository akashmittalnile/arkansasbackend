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
use App\Models\Notify;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Tag;
use App\Models\UserChapterStatus;
use App\Models\UserCourse;
use App\Models\UserQuizAnswer;
use App\Models\WalletBalance;
use App\Models\WalletHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use PDO;
use PDF;
use DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function check_status(Request $request) 
    {
        $user = User::where('email',$request['admin_email'])->first();
        if($user)
        {
            if($user->status == 0)
            {
                $status = 1;
            }else{
                $status = 0;
            }
            
        }else{
            $status = 0;
        }
        return $status;
    }

    public function check_email(Request $request){
        try{
            $email = User::where('email', $request->email)->first();
            if(isset($email->id))
            {
                echo json_encode('This Email Address is already exist.');
            }else{
                echo json_encode(true);
            }
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

    public function myAccount() 
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();
            $bank = CardDetail::where('userid', auth()->user()->id)->first();
            return view('home.myaccount')->with(compact('user', 'bank'));
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
                'CreatorType' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                $data = User::where('id', auth()->user()->id)->first();
                if ($request->profile) {
                    $profile = fileUpload($request->profile, 'upload/profile-image');  
                    removeFile("upload/profile-image/".$data->profile_image);
                } else $profile = $data->profile_image;
                if ($request->logo) {
                    $logo = fileUpload($request->logo, 'upload/business-logo');  
                    removeFile("upload/business-logo/".$data->business_logo);
                } else $logo = $data->business_logo;
                if ($request->signature) {
                    $signature = fileUpload($request->signature, 'upload/signature');  
                    removeFile("upload/signature/".$data->signature);
                } else $signature = $data->signature;

                $user = User::where('id', auth()->user()->id)->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name ?? null,
                    'phone' => $request->phone,
                    'CreatorType' => $request->CreatorType,
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

    public function bankInfo(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'acc_number' => 'required',
                'routine' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                $user = CardDetail::where('userid', auth()->user()->id)->first();
                if(isset($user->id)){
                    CardDetail::where('userid', auth()->user()->id)->update([
                        'account_number' => $request->acc_number,
                        'routine_number' => $request->routine,
                        'name_on_card' => $request->name ?? null,
                        'is_default' => 1,
                        'is_active' => 1,
                    ]);
                } else {
                    $card = new CardDetail;
                    $card->userid = auth()->user()->id;
                    $card->account_number = $request->acc_number;
                    $card->routine_number = $request->routine;
                    $card->name_on_card = $request->name ?? null;
                    $card->is_default = 1;
                    $card->is_active = 1;
                    $card->save();
                }

                return redirect()->back()->with('message', 'Bank details updated successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function index(Request $request) 
    {
        $courses = Course::where('admin_id',Auth::user()->id);
        if($request->filled('status')){
            $courses->where('status', $request->status);
        }
        if($request->filled('course')){
            $courses->where('title', 'like', '%' . $request->course . '%');
        }
        $courses = $courses->orderBy('id','DESC')->get();
        return view('home.index',compact('courses'));
    }

    public function performance(Request $request) 
    {
        $tab = $request->tab ?? encrypt_decrypt('encrypt', 1);
        if($request->filled('page')) $tab = encrypt_decrypt('encrypt', 2);
        $over_month = $request->month ?? date('Y-m');
        $earn = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->whereMonth('opd.created_date', date('m',strtotime($over_month)))->whereYear('opd.created_date', date('Y',strtotime($over_month)))->sum(\DB::raw('opd.amount - opd.admin_amount'));

        $course = Course::where('admin_id', auth()->user()->id)->whereMonth('course.created_date', date('m',strtotime($over_month)))->whereYear('course.created_date', date('Y',strtotime($over_month)))->count();

        $rating = Course::join('user_review as ur', 'ur.object_id', '=', 'course.id')->where('admin_id', auth()->user()->id)->where('ur.object_type', 1)->whereMonth('ur.created_date', date('m',strtotime($over_month)))->whereYear('ur.created_date', date('Y',strtotime($over_month)))->avg('ur.rating');

        $over_graph_data = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->select(
            DB::raw('sum(opd.amount - opd.admin_amount) as y'), 
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

        $user_month = $request->usermonth ?? date('Y-m');
        $orders = DB::table('order_product_detail as opd')
            ->leftJoin('course as c', 'c.id', '=', 'opd.product_id')
            ->leftJoin('orders as o', 'o.id', '=', 'opd.order_id')
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id')->select('opd.admin_amount', 'opd.amount', 'u.first_name','u.last_name', 'o.created_date', 'c.title')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->whereMonth('opd.created_date', date('m', strtotime($user_month)))->whereYear('opd.created_date', date('Y',strtotime($user_month)))->orderByDesc('opd.id')->paginate(5);

        $user = DB::table('course as c')->leftJoin('user_courses as uc', 'uc.course_id', '=', 'c.id')->where('c.admin_id', auth()->user()->id)->whereMonth('uc.created_date', date('m', strtotime($user_month)))->whereYear('uc.created_date', date('Y',strtotime($user_month)))->where('is_expire', 0)->distinct('uc.user_id')->count();


        $course_month = $request->coursemonth ?? date('Y-m');

        $total_course = Course::where('admin_id', auth()->user()->id)->count();
        $unpublish_course = Course::where('admin_id', auth()->user()->id)->where('status', 0)->count();
        $courses = Course::where('admin_id', auth()->user()->id)->orderByDesc('id')->get();

        $course_graph_data = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id);
        if($request->filled('course'))
            $course_graph_data = $course_graph_data->where('c.id', encrypt_decrypt('decrypt', $request->course));
        $course_graph_data = $course_graph_data->select(
            DB::raw('sum(opd.amount - opd.admin_amount) as y'), 
            DB::raw("DATE_FORMAT(opd.created_date,'%d') as x")
            )->whereMonth('opd.created_date', date('m',strtotime($course_month)))->whereYear('opd.created_date', date('Y',strtotime($course_month)))->groupBy('x')->orderByDesc('x')->get()->toArray();
            
        $course_graph = [];
        $daysC = get_days_in_month(date('m',strtotime($course_month)), date('Y',strtotime($course_month)));
        $xc = collect($course_graph_data)->pluck('x')->toArray();
        $yc = collect($course_graph_data)->pluck('y')->toArray();
        for($i=1; $i<=$daysC; $i++){
            if(in_array( $i, $xc )){
                $indx = array_search($i, $xc);
                // dd($xc[$indx]);
                $course_graph[$i-1]['x'] = (string) $i;
                $course_graph[$i-1]['y'] = number_format($yc[$indx], 2, '.', '');
            }else{
                $course_graph[$i-1]['x'] = (string) $i;
                $course_graph[$i-1]['y'] = 0;
            }
        }

        return view('home.performance', compact('tab', 'earn', 'course', 'rating', 'orders', 'over_graph', 'user', 'over_month', 'total_course', 'courses', 'unpublish_course', 'course_graph'));
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
        return view('home.editCourseDetails')->with(compact('course', 'combined'));
    }

    public function updateCourseDetails(Request $request){
        try {
            $course = Course::where('id', encrypt_decrypt('decrypt',$request->hide))->first();
            
            $disclaimers_introduction = $course->introduction_image;
            if ($request->disclaimers_introduction) {
                $disclaimers_introduction = fileUpload($request->disclaimers_introduction, 'upload/disclaimers-introduction'); 
                removeFile("upload/disclaimers-introduction/".$course->introduction_image);
            }

            Course::where('id', encrypt_decrypt('decrypt',$request->hide))->update([
                'title' => $request->title,
                'description' => $request->description,
                'course_fee' => $request->course_fee,
                'max_discount' =>  $request->discount,
                'valid_upto' => $request->valid_upto ?? null,
                'tags' => serialize($request->tags),
                'certificates' => null,
                'category_id' => $request->course_category,
                'introduction_image' => $disclaimers_introduction,
                'status' => 0,
            ]);

            return redirect()->route('home.index')->with('message','Course updated successfully');

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
            return redirect()->route('home.index')->with('message','Course deleted successfully');
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
        $review = DB::table('user_review as ur')->join('users as u', 'u.id', '=', 'ur.userid')->select('u.first_name', 'u.last_name', 'ur.rating', 'ur.review', 'ur.created_date', 'u.profile_image')->where('object_id', $id)->where('object_type', 1)->get();
        return view('home.viewCourseDetails')->with(compact('course', 'combined', 'review', 'reviewAvg'));
    }

    public function add_video(Request $request){
        try{
            // dd($request->all());
            if ($request->newvideo) {
                $videoName = fileUpload($request->newvideo, 'upload/course');  
            }
            $step = CourseChapterStep::where('id', encrypt_decrypt('decrypt',$request->vidId))->update(['details'=> $videoName]);
            return redirect()->back()->with('message', 'Video added successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function add_pdf(Request $request){
        try{
            // dd($request->all());
            if ($request->newpdf) {
                $pdfName = fileUpload($request->newpdf, 'upload/course');  
            }
            $step = CourseChapterStep::where('id', encrypt_decrypt('decrypt',$request->pdfId))->update(['details'=> $pdfName]);
            return redirect()->back()->with('message', 'PDF added successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function helpSupport() 
    {
        $user =  User::where('role', 3)->first();
        return view('home.help-support')->with(compact('user'));
    }

    public function add_course2($courseID) 
    {
        $courseID = encrypt_decrypt('decrypt',$courseID);
        $chapters = CourseChapter::where('course_id',$courseID)->get();
        if (count($chapters)>0) {
            $chapterID = CourseChapter::where('course_id',$courseID)->first();
            return redirect()->route('Home.CourseList', ['courseID'=> encrypt_decrypt('encrypt', $courseID), 'chapterID'=> encrypt_decrypt('encrypt', $chapterID->id)]);
        } else {
            $chapterID = null;
            $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->get();
            $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->get();
            return view('home.addcourse2-new',compact('quizes','datas','chapters','courseID','chapterID'));
        }
    }

    public function course_list($courseID,$chapterID) 
    {
        $courseID = encrypt_decrypt('decrypt',$courseID);
        $chapterID = encrypt_decrypt('decrypt',$chapterID);
        // dd($chapterID);
        $chapters = CourseChapter::where('course_id',$courseID)->get();
        // $quizes = ChapterQuiz::orderBy('ordering')->where('type','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        // $datas = ChapterQuiz::orderBy('ordering')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        $datas = CourseChapterStep::where('course_chapter_id', $chapterID)->orderBy('sort_order')->get();
        return view('home.addcourse2-new',compact('datas','chapters','courseID','chapterID'));
    }

    public function delete_question($id) 
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

    public function delete_section($id) 
    {
        $value = ChapterQuiz::where('id',$id)->first();
        // $courseID = encrypt_decrypt('encrypt',$value->course_id);
        // $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);
        // $question_id = $id; /*question_id*/

        $step = CourseChapterStep::where('id',$id)->first();
        $msg = ucwords($step->type);
        CourseChapterStep::where('id',$id)->delete();
        return redirect()->back()->with('message', $msg.' deleted successfully');
    }

    public function deleteQuiz($id) 
    {
        // $id =  encrypt_decrypt('decrypt',$id);
        $step = CourseChapterStep::where('id', $id)->whereIn('type', ['quiz', 'survey'])->first();
        if($step->type == 'quiz' || $step->type == 'survey'){
            $question = ChapterQuiz::where('step_id',$id)->get();
            foreach($question as $val){
                ChapterQuizOption::where('quiz_id',$val->id)->delete();
                ChapterQuiz::where('id',$val->id)->delete();
            }
        }
        CourseChapterStep::where('id', $id)->whereIn('type', ['quiz', 'survey'])->delete();
        return redirect()->back()->with('message', 'Quiz deleted successfully');
    }

    public function delete_option2($id) 
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
        return redirect('admin/addcourse2/'.$courseID.'/'.$chapterID)->with('message','Option deleted successfully');
    }

    public function submitquestion(Request $request) {
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
                                $videoName = fileUpload($request->video[$keyVideo], 'upload/course'); 

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
                                $pdfName = fileUpload($request->pdf[$keyPdf], 'upload/course');  

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
                                $Step->title = $request->video_description[$keyS] ?? null;
                                $Step->sort_order = $request->queue[$keyS] ?? -1;
                                $Step->type = 'survey';
                                $Step->description = null;
                                $Step->prerequisite = $request->prerequisite[$keyS] ?? 0;
                                $Step->course_chapter_id = $request->chapter_id;
                                $Step->save();
                                foreach($valueQ as $keyQVal => $valueQVal){
                                    $ChapterQuiz = new ChapterQuiz;
                                    $ChapterQuiz->title = $valueQVal['text'];
                                    $ChapterQuiz->type = 'survey';
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
            // return redirect('admin/addcourse2/'.$courseID.'/'.$chapter_id)->with('message','Question saved successfully');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 200], 200);
        }
    }

    public function submitcourse(Request $request) 
    {
        try {
            if ($request->disclaimers_introduction) {
                $disclaimers_introduction = fileUpload($request->disclaimers_introduction, 'upload/disclaimers-introduction'); 
            }
            $course = new Course;
            $course->admin_id = Auth::user()->id;
            $course->title = $request->input('title');
            $course->description = $request->input('description');
            $course->course_fee = $request->input('course_fee');
            $course->max_discount = $request->input('discount');
            $course->valid_upto = $request->input('valid_upto') ?? null;
            $course->tags = serialize($request->input('tags'));
            $course->certificates = null;
            $course->category_id = $request->course_category;
            $course->status = 0;
            $course->introduction_image = $disclaimers_introduction;
            $course->save(); 

            $last_id = Course::orderBy('id','DESC')->first();
            $course = new CourseChapter;
            $course->course_id = $last_id->id;
            $course->save();

            $user = User::where('role', 3)->get();
            if(count($user) > 0){
                foreach($user as $val){
                    $notify = new Notify;
                    $notify->added_by = auth()->user()->id;
                    $notify->user_id = $val->id;
                    $notify->module_name = 'course';
                    $notify->title = 'New Course';
                    $notify->message = 'New Course ('.$request->input('title') . ') added by ' . auth()->user()->first_name . ' ' . auth()->user()->last_name;
                    if(auth()->user()->profile_image == "" || auth()->user()->profile_image == null){
                        $profile_image = null;
                    } else $profile_image = uploadAssets('upload/profile-image/'.auth()->user()->profile_image);
                    $notify->image = $profile_image;
                    $notify->is_seen = '0';
                    $notify->redirect_url = route('SA.Content-Creator.Course');
                    $notify->created_at = date('Y-m-d H:i:s');
                    $notify->updated_at = date('Y-m-d H:i:s');
                    $notify->save();
                }
            }
            
            return redirect()->route('Home.CourseList', ['courseID'=> encrypt_decrypt('encrypt', $last_id->id), 'chapterID'=> encrypt_decrypt('encrypt', $course['id '])])->with('message', 'Course created successfully');

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function Addcourse() 
    {
        //dd(Auth::user()->id)
        $CourseChapters = CourseChapter::orderBy('id','DESC')->get();
        return view('home.addcourse',compact('CourseChapters'));
    }

    public function submitCourseChapter(Request $request) 
    {
        try {
            $course = new CourseChapter;
            $course->course_id = $request->courseID;
            $course->chapter = $request->name;
            $course->save();
            $encrypt = encrypt_decrypt('encrypt',$request->courseID);
            $encryptChapter = encrypt_decrypt('encrypt',$course['id ']);
            return redirect()->route('Home.CourseList', ['courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter created successfully');
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
            return redirect()->route('Home.CourseList', ['courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter updated successfully');
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
            return redirect('admin/addcourse2/'.$encrypt.'/'.$chapterID)->with('message','Chapter deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete_video($id) 
    {
        try {
            $value = CourseChapterStep::where('id',$id)->first();
            $chapterID = encrypt_decrypt('encrypt',$value->course_chapter_id);
            $courseID = CourseChapter::where('id',$value->course_chapter_id)->first();
            $courseID = encrypt_decrypt('encrypt',$courseID->course_id);

            $quiz = CourseChapterStep::where('id',$id)->first();
            $image_name = $quiz->details;
            removeFile("upload/course/".$image_name);
            CourseChapterStep::where('id',$id)->update([
                'details' => null,
            ]);
            return redirect('admin/addcourse2/'.$courseID.'/'.$chapterID)->with('message','Video deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete_pdf($id) 
    {
        try {
            $value = CourseChapterStep::where('id',$id)->first();
            $chapterID = encrypt_decrypt('encrypt',$value->course_chapter_id);
            $courseID = CourseChapter::where('id',$value->course_chapter_id)->first();
            $courseID = encrypt_decrypt('encrypt',$courseID->course_id);

            $quiz = CourseChapterStep::where('id',$id)->first();
            $image_name = $quiz->details;
            removeFile("upload/course/".$image_name);
            CourseChapterStep::where('id',$id)->update([
                'details' => null,
            ]);
            return redirect('admin/addcourse2/'.$courseID.'/'.$chapterID)->with('message','PDF deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update_option_list(Request $request) 
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

    public function update_question_list(Request $request) 
    {
        try {
            ChapterQuiz::where('id',$request['question_id'])->update([
                'title' => $request['question'],
                'marks' => $request['marks'] ?? 0,
            ]);
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

    public function changeOrdering(Request $request, $chapterid) 
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

    public function changeAnswerOption($id) 
    {
        try {
            $chapterQuiz = ChapterQuizOption::where('id', $id)->first();
            if(isset($chapterQuiz->id)){
                ChapterQuizOption::where('quiz_id', $chapterQuiz->quiz_id)->update(['is_correct' => '0']);
                $chapter = ChapterQuizOption::where('id', $id)->update(['is_correct' => '1']);
                return response()->json(['status' => 200, 'message'=> "Answer changed."]);
            }else return response()->json(['status' => 201, 'message'=> "Invalid Request"]); 
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function earnings(Request $request) 
    {
        try {
            // $orders = DB::table('order_product_detail as opd')
            //     ->leftJoin('course as c', 'c.id', '=', 'opd.product_id')
            //     ->leftJoin('orders as o', 'o.id', '=', 'opd.order_id')
            //     ->leftJoin('users as u', 'u.id', '=', 'o.user_id');
            //     if($request->filled('name')){
            //         $orders->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ");
            //     }
            //     if($request->filled('number')){
            //         $orders->where('o.order_number', 'like', '%'.$request->number.'%');
            //     }
            //     if($request->filled('order_date')){
            //         $orders->whereDate('o.created_date', date('Y-m-d', strtotime($request->order_date)));
            //     }
            // $orders = $orders->select('o.order_number', 'opd.id', 'opd.admin_amount', 'opd.amount', 'o.status', 'o.created_date', 'u.first_name', 'u.last_name', 'opd.quantity', 'o.id as order_id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->orderByDesc('opd.id')->paginate(10);

            $orders = Order::join('users as u', 'u.id', '=', 'orders.user_id')->join('order_product_detail as opd', 'opd.order_id', '=', 'orders.id')->leftJoin('course as c', 'c.id', '=', 'opd.product_id');
            if($request->filled('name')){
                $orders->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ");
            }
            if($request->filled('number')){
                $orders->where('orders.order_number', 'like', '%'.$request->number.'%');
            }
            if($request->filled('order_date')){
                $orders->whereDate('orders.created_date', date('Y-m-d', strtotime($request->order_date)));
            }
            $orders = $orders->select('orders.order_number', 'orders.id as order_id', 'orders.admin_amount', 'orders.amount', 'orders.total_amount_paid', 'orders.status', 'orders.created_date', 'u.first_name', 'u.last_name')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->orderByDesc('orders.id')->distinct('orders.id')->paginate(10);

            $myWallet = WalletBalance::where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->first();
            $requestedAmount = 0;
            $payment = DB::table('order_product_detail as opd')->leftJoin('course as c', 'c.id', '=', 'opd.product_id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->sum(\DB::raw('opd.amount - opd.admin_amount'));
            if(isset($myWallet->id)){
                $mymoney = $myWallet->balance ?? 0;
                $requestedAmount = WalletHistory::join('wallet_balance as wb', 'wb.id', '=', 'wallet_history.wallet_id')->where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->whereIn('wallet_history.status', [1,0])->sum('wallet_history.balance');
            }else $mymoney = 0;

            return view('home.earnings',compact('orders', 'mymoney', 'payment', 'requestedAmount'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function downloadEarnings(Request $request) 
    {
        try {
            $orders = DB::table('order_product_detail as opd')
                ->leftJoin('course as c', 'c.id', '=', 'opd.product_id')
                ->leftJoin('orders as o', 'o.id', '=', 'opd.order_id')
                ->leftJoin('users as u', 'u.id', '=', 'o.user_id');
                if($request->filled('name')){
                    $orders->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ");
                }
                if($request->filled('number')){
                    $orders->where('o.order_number', 'like', '%'.$request->number.'%');
                }
                if($request->filled('order_date')){
                    $orders->whereDate('o.created_date', date('Y-m-d', strtotime($request->order_date)));
                }
            $orders = $orders->select('o.order_number', 'opd.id', 'opd.admin_amount', 'opd.amount', 'o.status', 'o.created_date', 'u.first_name', 'u.last_name', 'opd.quantity')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->orderByDesc('opd.id')->paginate(10);

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
                    number_format((float)($row->amount-$row->admin_amount), 2),
                    number_format((float)$row->amount, 2),
                    ($row->status == 1) ? "Active" : "Pending"
                ];

                fputcsv($output, $final);
            }
        }
    }

    public function paymentRequest(Request $request) 
    {
        try {
            $amount = DB::table('order_product_detail as opd')
                ->leftJoin('course as c', 'c.id', '=', 'opd.product_id')
                ->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->sum(\DB::raw('opd.amount - opd.admin_amount'));
            
            $payment = WalletHistory::join('wallet_balance as wb', 'wb.id', '=', 'wallet_history.wallet_id');
            if($request->filled('status')){
                $payment->where('wallet_history.status', $request->status);
            }
            if($request->filled('order_date')){
                $payment->whereDate('wallet_history.added_date', $request->order_date);
            }
            $payment = $payment->where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->select('wallet_history.*')->orderByDesc('wallet_history.id')->paginate(10);

            $requestedAmount = 0;
            $mymoney['balance'] = 0;
            if(isset($payment[0]->id)){
                $requestedAmount = WalletHistory::join('wallet_balance as wb', 'wb.id', '=', 'wallet_history.wallet_id')->where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->whereIn('wallet_history.status', [1,0])->sum('wallet_history.balance');
                $mymoney = WalletBalance::where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->first();
            }

            // dd($payment[0]->id);
            // dd($requestedAmount);

            return view('home.payment-request',compact('amount', 'payment', 'requestedAmount', 'mymoney'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function paymentRequestStore(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $walletBalance = WalletBalance::where('owner_id', auth()->user()->id)->where('owner_type', auth()->user()->role)->first();
                if(isset($walletBalance->id)){
                    $history = new WalletHistory;
                    $history->wallet_id = $walletBalance->id;
                    $history->balance = $request->amount ?? 0;
                    $history->added_date = date('Y-m-d H:i:s');
                    $history->added_by = auth()->user()->id;
                    $history->payment_id = null;
                    $history->status = 0;
                    $history->save();
                } else {
                    $balance = new WalletBalance;
                    $balance->owner_id = auth()->user()->id;
                    $balance->owner_type = auth()->user()->role;
                    $balance->balance = 0;
                    $balance->created_date = date('Y-m-d H:i:s');
                    $balance->updated_date = date('Y-m-d H:i:s');
                    $balance->save();
                    $history = new WalletHistory;
                    $history->wallet_id = $balance['id '];
                    $history->balance = $request->amount ?? 0;
                    $history->added_date = date('Y-m-d H:i:s');
                    $history->added_by = auth()->user()->id;
                    $history->payment_id = null;
                    $history->status = 0;
                    $history->save();
                }
                return redirect()->back()->with('message', 'Request submitted successfully. Wait for admin approval.');
            }   
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function orderDetails(Request $request, $id) {
        try{    
            $id = encrypt_decrypt('decrypt', $id);
            $order = Order::where('orders.id', $id)->leftJoin('users as u', 'u.id', '=', 'orders.user_id')->select('u.first_name', 'u.last_name', 'u.email', 'u.profile_image', 'u.phone', 'u.role', 'u.status as ustatus', 'orders.id', 'orders.order_number', 'orders.created_date', 'orders.status', 'orders.taxes', 'orders.total_amount_paid', 'orders.delivery_charges', 'orders.amount', 'orders.coupon_discount_price', 'orders.order_for')->first();

            $orderDetails = DB::table('orders')->select(DB::raw("ifnull(c.title,p.name) title, c.course_fee, order_product_detail.quantity, order_product_detail.product_id, order_product_detail.product_type, ifnull(c.status,p.status) status, order_product_detail.amount, order_product_detail.admin_amount, ifnull(c.introduction_image,(select attribute_value from product_details pd where p.id = pd.product_id and attribute_code = 'cover_image' limit 1))  as image, order_product_detail.shipengine_label_id, order_product_detail.shipengine_label_url"))->join('users as u', 'orders.user_id', '=', 'u.id')->join('order_product_detail', 'orders.id', '=', 'order_product_detail.order_id')->leftjoin('course as c', 'c.id','=', DB::raw('order_product_detail.product_id AND order_product_detail.product_type = 1'))->leftjoin('product as p', 'p.id','=', DB::raw('order_product_detail.product_id AND order_product_detail.product_type = 2'))->where('orders.id', $id)->orderByDesc('order_product_detail.id')->get();

            $transaction = Order::where('orders.id', $id)->leftJoin('payment_detail as pd', 'pd.id', '=', 'orders.payment_id')->leftJoin('payment_methods as pm', 'pm.id', '=', 'pd.card_id')->select('pm.card_no', 'pm.card_type', 'pm.method_type', 'pm.expiry')->first();

            return view('home.order-details', compact('order', 'transaction', 'orderDetails'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function downloadInvoice(Request $request, $id) {
        try{    
            $id = encrypt_decrypt('decrypt', $id);
            $order = Order::where('orders.id', $id)->leftJoin('users as u', 'u.id', '=', 'orders.user_id')->select('u.first_name', 'u.last_name', 'u.email', 'u.profile_image', 'u.phone', 'u.role', 'u.status as ustatus', 'orders.id', 'orders.order_number', 'orders.created_date', 'orders.status', 'orders.taxes', 'orders.total_amount_paid', 'orders.delivery_charges', 'orders.amount', 'orders.coupon_discount_price', 'orders.order_for')->first();

            $orderDetails = DB::table('orders')->select(DB::raw("ifnull(c.title,p.name) title, c.course_fee, order_product_detail.shipping_price, order_product_detail.quantity, order_product_detail.product_id, order_product_detail.product_type, ifnull(c.status,p.status) status, order_product_detail.amount, order_product_detail.admin_amount, ifnull(c.introduction_image,(select attribute_value from product_details pd where p.id = pd.product_id and attribute_code = 'cover_image' limit 1))  as image"))->join('users as u', 'orders.user_id', '=', 'u.id')->join('order_product_detail', 'orders.id', '=', 'order_product_detail.order_id')->leftjoin('course as c', 'c.id','=', DB::raw('order_product_detail.product_id AND order_product_detail.product_type = 1'))->leftjoin('product as p', 'p.id','=', DB::raw('order_product_detail.product_id AND order_product_detail.product_type = 2'))->where('orders.id', $id)->orderByDesc('order_product_detail.id')->get();

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

    public function clearNotification(Request $request) {
        try{    
            Notify::where('user_id', auth()->user()->id)->delete();
            return redirect()->back()->with('message', 'All notification cleared.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function students(Request $request) {
        try{    
            $datas = DB::table('order_product_detail as opd')
            ->leftJoin('course as c', 'c.id', '=', 'opd.product_id')
            ->leftJoin('orders as o', 'o.id', '=', 'opd.order_id')
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id');
            if($request->filled('name')) {
                $datas->whereRaw("concat(first_name, ' ', last_name) like '%$request->name%'");
            }  
            if($request->filled('status')) {
                $datas->where("u.status", $request->status);
            } 
            $datas = $datas->select('u.first_name', 'u.last_name', 'u.status', 'u.profile_image', 'u.email', 'u.phone', 'u.id')->where('opd.product_type', 1)->where('c.admin_id', auth()->user()->id)->distinct('u.id')->orderByDesc('u.id')->paginate(10);
            // dd($datas);
            return view('home.student')->with(compact('datas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function studentDetails(Request $request, $id) {
        try{    
            $id = encrypt_decrypt('decrypt',$id);
            $data = User::where('id',$id)->first();

            $course = DB::table('user_courses as uc')->join('course as c', 'c.id', '=', 'uc.course_id');
            if($request->filled('status')) $course->where('uc.status', $request->status);
            if($request->filled('title')) $course->where('c.title', 'like', '%' . $request->title . '%');
            if($request->filled('date')) $course->whereDate('uc.buy_date', $request->date);
            $course = $course->where('uc.user_id', $id)->where('is_expire', 0)->select('c.course_fee', 'c.id', 'uc.status', 'uc.created_date', 'uc.updated_date', 'uc.buy_price', 'c.title', 'c.valid_upto', 'c.introduction_image', DB::raw('(select COUNT(*) FROM course_chapter WHERE course_chapter.course_id = uc.course_id) as chapter_count'), DB::raw("(SELECT orders.id FROM orders INNER JOIN order_product_detail ON orders.id = order_product_detail.order_id WHERE orders.user_id = $id AND order_product_detail.product_id = c.id AND order_product_detail.product_type = 1) as order_id"))->where('c.admin_id', auth()->user()->id)->orderByDesc('uc.id')->distinct('uc.id')->paginate(3);

            return view('home.student-details')->with(compact('data', 'course', 'id'));
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
            $reviewAvg = DB::table('user_review as ur')->where('object_id', $courseId)->where('object_type', 1)->avg('rating');
            $review = DB::table('user_review as ur')->join('users as u', 'u.id', '=', 'ur.userid')->select('u.first_name', 'u.last_name', 'ur.rating', 'ur.review', 'ur.created_date', 'u.profile_image')->where('object_id', $courseId)->where('object_type', 1)->get();

            $userCourse = UserCourse::where('user_id', $id)->where('course_id', $courseId)->where('status', 1)->orderByDesc('id')->first();
            if(isset($userCourse->id)){
                $complete = true;
                $chapters = UserChapterStatus::leftJoin('course_chapter as cc', 'cc.id', '=', 'user_chapter_status.chapter_id')->where('user_chapter_status.userid', $id)->where('user_chapter_status.course_id', $courseId)->select('cc.chapter', 'cc.id')->distinct('cc.id')->get();
            }else{
                $complete = false;
                $chapters = CourseChapter::where('course_chapter.course_id', $courseId)->select('course_chapter.chapter', 'course_chapter.id')->distinct('course_chapter.id')->get();
            }

            return view('home.progress-course-details')->with(compact('course', 'combined', 'review', 'reviewAvg', 'id', 'chapters'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function studentResult($id, Request $request){
        try{
            $quizId = encrypt_decrypt('decrypt',$request->quizId);
            $userId = encrypt_decrypt('decrypt',$id);
            $total = ChapterQuiz::where('step_id', $quizId)->whereIn('type', ['quiz', 'survey'])->sum('marks');
            $obtained = UserQuizAnswer::where('quiz_id', $quizId)->where('userid',$userId)->sum('marks_obtained');
            $courseStep = CourseChapterStep::where('id', $quizId)->whereIn('type', ['quiz'])->first();
            $passingPercentage = $courseStep->passing ?? 33;
            return response()->json(['status'=> true, 'total' => $total, 'obtained' => $obtained, 'percen' => $passingPercentage]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function help_support_save_img(Request $request){
        try {
            $name = fileUpload($request->image, 'upload/chat');  
            return response()->json(['status' => true, 'url' => $name, 'message' => 'image upload successfully.']);
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

    public function notifySeen(Request $request){
        try{
            Notify::where('user_id', auth()->user()->id)->update(['is_seen' => '1']);
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}