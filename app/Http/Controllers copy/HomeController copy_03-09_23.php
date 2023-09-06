<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\ChapterQuiz;
use App\Models\ChapterQuizOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\User;
class HomeController extends Controller
{
    public function index() 
    {
        $admin_id = Auth::user()->id;
        $courses = Course::where('admin_id',$admin_id)->orderBy('id','DESC')->get();
        return view('home.index',compact('courses'));
    }

    public function performance() 
    {
        return view('home.performance');
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
            $chapterID = $chapters[0]->id;
            $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
            $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        } else {
            $chapterID = '';
            $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->get();
            $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->get();
        }
        
        return view('home.addcourse2',compact('quizes','datas','chapters','courseID','chapterID'));
    }

    public function course_list($courseID,$chapterID) 
    {
        $courseID = encrypt_decrypt('decrypt',$courseID);
        $chapterID = encrypt_decrypt('decrypt',$chapterID);
        $chapters = CourseChapter::where('course_id',$courseID)->get();
        $quizes = ChapterQuiz::orderBy('id','DESC')->where('type','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        $datas = ChapterQuiz::orderBy('id','DESC')->where('type','!=','quiz')->where('course_id',$courseID)->where('chapter_id',$chapterID)->get();
        return view('home.addcourse2',compact('quizes','datas','chapters','courseID','chapterID'));
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

    // public function deleteQuiz($id) 
    // {
    //     // $id =  encrypt_decrypt('decrypt',$id);
    //     $datas = ChapterQuiz::where('chapter_id',$id)->where('type','quiz')->get();
    //     if ($datas) {
    //         foreach ($datas as $key => $value) {
    //             ChapterQuizOption::where('quiz_id',$value->id)->delete();
    //         }
            
    //     } else {
    //         # code...
    //     }
        
    //     ChapterQuiz::where('chapter_id',$id)->where('type','quiz')->delete();
        
    //     return redirect('addcourse2')->with('message', 'Quiz deleted successfully');
    // }

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
            $options = $request->option;
            $question = $request->question;
            if ($request->video) {
                $videoName = time().'.'.$request->video->extension();  
                $request->video->move(public_path('upload/course'), $videoName);
            }

            if ($request->pdf) {
                $pdfName = time().'.'.$request->pdf->extension();  
                $request->pdf->move(public_path('upload/course'), $pdfName);
            }

            
            if($request->type == 'video'){
                $ChapterQuiz = new ChapterQuiz;
                $ChapterQuiz->title = 'video';
                $ChapterQuiz->type = 'video';
                $ChapterQuiz->desc = $request->input('video_decription');
                $ChapterQuiz->file = $videoName;
                $ChapterQuiz->chapter_id = $request->chapter_id;
                $ChapterQuiz->course_id = $request->courseID;
                $ChapterQuiz->save();
            }elseif($request->type == 'pdf'){
                $ChapterQuiz = new ChapterQuiz;
                $ChapterQuiz->title = 'pdf';
                $ChapterQuiz->type = 'pdf';
                $ChapterQuiz->desc = $request->input('PDF_decription');
                $ChapterQuiz->file = $pdfName;
                $ChapterQuiz->chapter_id = $request->chapter_id;
                $ChapterQuiz->course_id = $request->courseID;
                $ChapterQuiz->save();
            }elseif($request->type == 'quiz'){

                // if ($request->input('prerequisite') == 'on') {
                //     $prerequisite = 1;
                // } else {
                //     $prerequisite = 0;
                // }

                $questionsData = $request->input('questions');
                //dd($questionsData[1]);
                foreach ($questionsData as $questionData) {
                    $ChapterQuiz = new ChapterQuiz;
                    $ChapterQuiz->title = $questionData['text'];
                    $ChapterQuiz->type = 'quiz';
                    $ChapterQuiz->chapter_id = $request->chapter_id;
                    $ChapterQuiz->course_id = $request->courseID;
                    $ChapterQuiz->step_id = 1;
                    $ChapterQuiz->save();
                    $quiz_id = ChapterQuiz::orderBy('id','DESC')->first();
                    //dd($questionData['options']);
                    foreach ($questionData['options'] as $optionText) {
                        $option = new ChapterQuizOption;
                        $option->quiz_id = $quiz_id->id;
                        $option->answer_option_key = $optionText;
                        $option->created_date = date('Y-m-d H:i:s');
                        $option->status = 1;
                        $option->save();
                    }
                    //dd($questionData['options']);
                }

                /*if ($request->input('prerequisite') == 'on') {
                    $prerequisite = 1;
                } else {
                    $prerequisite = 0;
                }
                
                $ChapterQuiz->title = $request->input('quiz_question');
                $ChapterQuiz->type = 'quiz';
                $ChapterQuiz->chapter_id = 1;
                $ChapterQuiz->step_id = $prerequisite;
                $ChapterQuiz->save();
                $quiz_id = ChapterQuiz::orderBy('id','DESC')->first();

                if(isset($options))
                {
                    if(count($options)>0)
                    {
                        foreach ($options as $key => $value) {
                            if(!empty($value))
                            {
                                $option = new ChapterQuizOption;
                                $option->quiz_id = $quiz_id->id;
                                $points_value = $request->option[$key];
                                $option->answer_option_key = $points_value;
                                $option->created_date = date('Y-m-d H:i:s');
                                $option->status = 1;
                                $option->save();
                            }else{
                                $d_json = 0;
                                return $d_json;
                            }
                        }
                    }
                }*/
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
            }elseif($request->type == 'assignment'){
                $ChapterQuiz = new ChapterQuiz;
                $ChapterQuiz->title = 'assignment';
                $ChapterQuiz->type = 'assignment';
                $ChapterQuiz->chapter_id = $request->chapter_id;
                $ChapterQuiz->course_id = $request->courseID;
                $ChapterQuiz->save();
            }else{

            }
            $courseID = encrypt_decrypt('encrypt',$request->courseID);
            $chapter_id = encrypt_decrypt('encrypt',$request->chapter_id);
            // return redirect('admin/addcourse2/'.$courseID.'/'.$chapter_id)->with('message','Question saved successfully');
            return response()->json(['message' => 'Saved successfully.', 'status' => 201], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 200], 200);
        }
    }

    public function check_status(Request $request) 
    {
        $user = User::where('email',$request['admin_email'])->first();
        if(!empty($user))
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

    public function submitcourse(Request $request) 
    {
        try {

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
            if ($request->certificates) {
                $disclaimers_introduction = time().'.'.$request->disclaimers_introduction->extension();  
                $request->disclaimers_introduction->move(public_path('upload/disclaimers-introduction'), $disclaimers_introduction);
                if($disclaimers_introduction)
                {
                    $disclaimers_introduction = $disclaimers_introduction;
                }else{
                    $disclaimers_introduction = '';
                }
            }
            $user_id = Auth::user()->id;
            $course = new Course;
            $course->admin_id = $user_id;
            $course->title = $request->input('title');
            $course->description = $request->input('description');
            $course->course_fee = $request->input('course_fee');
            $course->valid_upto = $request->input('valid_upto');
            $course->tags = $request->input('tags');
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
            return redirect('admin/addcourse2/'.$encrypt)->with('message','Chapter created successfully');
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
            return redirect('admin/addcourse2/'.$encrypt)->with('message','Chapter deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete_video($id) 
    {
        try {
            $value = ChapterQuiz::where('id',$id)->first();
            $courseID = encrypt_decrypt('encrypt',$value->course_id);
            $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);

            $quiz = ChapterQuiz::where('id',$id)->first();
            $image_name = $quiz->file;
            $image_path = public_path('upload/course/'.$image_name);
            if(File::exists($image_path)) {
                ChapterQuiz::where('id',$id)->update([
                    'file' => null,
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
            $value = ChapterQuiz::where('id',$id)->first();
            $courseID = encrypt_decrypt('encrypt',$value->course_id);
            $chapterID = encrypt_decrypt('encrypt',$value->chapter_id);

            $quiz = ChapterQuiz::where('id',$id)->first();
            $image_name = $quiz->file;
            $image_path = public_path('upload/course/'.$image_name);
            if(File::exists($image_path)) {
                ChapterQuiz::where('id',$id)->update([
                    'file' => null,
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

}