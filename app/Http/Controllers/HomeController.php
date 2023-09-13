<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseChapter;
use App\Models\ChapterQuiz;
use App\Models\ChapterQuizOption;
use App\Models\CourseChapterStep;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PDO;

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

    public function performance() 
    {
        return view('home.performance');
    }

    public function editCourse($id) 
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
        return view('home.editCourseDetails')->with(compact('course', 'combined'));
    }

    public function updateCourseDetails(Request $request){
        try {
            $course = Course::where('id', encrypt_decrypt('decrypt',$request->hide))->first();
            $imageName = $course->certificates;
            if ($request->certificates) {
                $imageName = time().'.'.$request->certificates->extension();  
                $request->certificates->move(public_path('upload/course-certificates'), $imageName);

                $image_path = app_path("upload/course-certificates/{$course->certificates}");
                if(File::exists($image_path)) {
                    unlink($image_path);
                }
            }
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
                'certificates' => $imageName,
                'introduction_image' => $disclaimers_introduction,
                'status' => 0,
            ]);

            return redirect('/');

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteCourse($id){
        try{
            CourseChapter::where('course_id', encrypt_decrypt('decrypt',$id))->delete();
            Course::where('id', encrypt_decrypt('decrypt',$id))->delete();
            return redirect('/');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function add_video(Request $request){
        try{
            // dd($request->all());
            if ($request->newvideo) {
                $videoName = time().'.'.$request->newvideo->extension();  
                $request->newvideo->move(public_path('upload/course'), $videoName);
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
                $pdfName = time().'.'.$request->newpdf->extension();  
                $request->newpdf->move(public_path('upload/course'), $pdfName);
            }
            $step = CourseChapterStep::where('id', encrypt_decrypt('decrypt',$request->pdfId))->update(['details'=> $pdfName]);
            return redirect()->back()->with('message', 'PDF added successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function helpSupport() 
    {
        return view('home.help-support');
    }

    public function Addcourse2($courseID) 
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
        
        return view('home.addcourse2-new',compact('quizes','datas','chapters','courseID','chapterID'));
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
        $courseID = encrypt_decrypt('encrypt',$value->course_id);
        $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);
        $question_id = $id; /*question_id*/
        $data = ChapterQuiz::where('id',$question_id)->delete();
        ChapterQuizOption::where('quiz_id',$question_id)->delete();
        return redirect('admin/addcourse2/'.$courseID.'/'.$chapterID)->with('message', 'Question deleted successfully');
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
        $value = ChapterQuiz::where('id',$option->quiz_id)->first();
        $courseID = encrypt_decrypt('encrypt',$value->course_id);
        $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);
        $option_id = $id; /*Option Id*/
        ChapterQuizOption::where('id',$option_id)->delete();
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
                                $videoName = time().'.'.$request->video[$keyVideo]->extension();  
                                $request->video[$keyVideo]->move(public_path('upload/course'), $videoName); 

                                $ChapterQuiz = new CourseChapterStep;
                                $ChapterQuiz->type = 'video';
                                $ChapterQuiz->sort_order = $request->queue[$keyVideo] ?? -1;
                                $ChapterQuiz->title = ucwords($type[$key]);
                                $ChapterQuiz->description = $request->video_description[$keyVideo] ?? null;
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
                                $ChapterQuiz->title = ucwords($type[$key]);
                                $ChapterQuiz->description = $request->PDF_description[$keyPdf] ?? null;
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
                                $ChapterQuiz->title = ucwords($type[$key]);
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
                                $Step->title = ucwords($type[$key]);
                                $Step->sort_order = $request->queue[$keyQ] ?? -1;
                                $Step->type = 'quiz';
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
                                        // dd($optionText);
                                        $option = new ChapterQuizOption;
                                        $option->quiz_id = $quiz_id->id;
                                        $option->answer_option_key = $optionText;
                                        $option->is_correct = $valueQVal['correct'][$keyOp] ?? '0';
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
                                $Step->title = ucwords($type[$key]);
                                $Step->sort_order = $request->queue[$keyS] ?? -1;
                                $Step->type = 'survey';
                                $Step->duration = $request->required_field[$keyS] ?? 0;
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
            }elseif($request->type == 'survey'){
                $questionsData = $request->input('questions_survey');
                foreach ($questionsData as $questionData) {
                    $ChapterQuiz = new ChapterQuiz;
                    $ChapterQuiz->title = $questionData['text'];
                    $ChapterQuiz->type = 'survey';
                    $ChapterQuiz->chapter_id = $request->chapter_id;
                    $ChapterQuiz->course_id = $request->courseID;
                    $ChapterQuiz->step_id = 1;
                    $ChapterQuiz->save();
                    $quiz_id = ChapterQuiz::orderBy('id','DESC')->first();
                    foreach ($questionData['options_survey'] as $optionText) {
                        $option = new ChapterQuizOption;
                        $option->quiz_id = $quiz_id->id;
                        $option->answer_option_key = $optionText;
                        $option->created_date = date('Y-m-d H:i:s');
                        $option->status = 1;
                        $option->save();
                    }
                }
            }

            $courseID = encrypt_decrypt('encrypt',$request->courseID);
            $chapter_id = encrypt_decrypt('encrypt',$request->chapter_id);
            return response()->json(['status' => 201, 'message' => 'Question saved successfully']);
            // return redirect('admin/addcourse2/'.$courseID.'/'.$chapter_id)->with('message','Question saved successfully');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 200], 200);
        }
    }

    public function submitcourse(Request $request) 
    {
        try {
            if ($request->certificates) {
                $imageName = time().'.'.$request->certificates->extension();  
                $request->certificates->move(public_path('upload/course-certificates'), $imageName);
            }
            if ($request->disclaimers_introduction) {
                $disclaimers_introduction = time().'.'.$request->disclaimers_introduction->extension();  
                $request->disclaimers_introduction->move(public_path('upload/disclaimers-introduction'), $disclaimers_introduction);
            }
            $course = new Course;
            $course->admin_id = Auth::user()->id;
            $course->title = $request->input('title');
            $course->description = $request->input('description');
            $course->course_fee = $request->input('course_fee');
            $course->valid_upto = $request->input('valid_upto');
            $course->tags = serialize($request->input('tags'));
            $course->certificates = $imageName;
            $course->status = 0;
            $course->introduction_image = $disclaimers_introduction;
            $course->save(); 

            $last_id = Course::orderBy('id','DESC')->first();
            $course = new CourseChapter;
            $course->course_id = $last_id->id;
            $course->save();
            return redirect('/');

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

    public function submitCourseChapter($courseID) 
    {
        try {
            $course = new CourseChapter;
            $course->course_id = $courseID;
            $course->save();
            $encrypt = encrypt_decrypt('encrypt',$courseID);
            $encryptChapter = encrypt_decrypt('encrypt',$course['id ']);
            return redirect()->route('Home.CourseList', ['courseID'=> $encrypt, 'chapterID'=> $encryptChapter])->with('message', 'Chapter created successfully');
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
            $image_path = public_path('upload/course/'.$image_name);
            if(File::exists($image_path)) {
                CourseChapterStep::where('id',$id)->update([
                    'details' => null,
                    ]);
                File::delete($image_path);
            }
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
            $image_path = public_path('upload/course/'.$image_name);
            if(File::exists($image_path)) {
                CourseChapterStep::where('id',$id)->update([
                    'details' => null,
                    ]);
                File::delete($image_path);
            }
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

    public function changeAnswerOption($id, $val) 
    {
        try {
            $chapter = ChapterQuizOption::where('id', $id)->update(['is_correct' => $val]);
            return 1;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}