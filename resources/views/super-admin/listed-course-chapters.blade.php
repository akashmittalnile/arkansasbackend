@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Courses')
@section('content')
<link rel="stylesheet" type="text/css" href="{!! assets('assets/superadmin-css/course.css') !!}">
<input type="hidden" name="courseID" value="{{ $courseID }}" />
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Courses</h2>
        </div>
        <div class="pmu-filter">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('SA.ListedCourse', $userID) }}" class="add-more">Back</a>
                    {{-- <a class="add-more" data-bs-toggle="modal" data-bs-target="#SaveContinue">Save &
                            Continue</a> --}}
                    {{-- <a class="add-more" id="form">Save & Continue</a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="pmu-content-list">
        <div class="pmu-content">
            <div class="row">
                <div class="col-md-3">
                    <div class="chapter-card">
                        <h3>Chapter list</h3>
                        @if ($chapters->isEmpty())
                        <tr>
                            <td colspan="10">
                                <h5 style="text-align: left">No Chapter</h5>
                            </td>
                        </tr>
                        @else
                        <?php $v = 1; ?>
                        @foreach ($chapters as $chapterKey => $chapter)
                        <div class="chapter-list">
                            @if ($chapter->id == $chapterID)
                            <div class="chapter-item active" data-index="{{ $chapter->chapter ?? 'NA' }}">
                                @else
                                <div class="chapter-item">
                                    @endif
                                    <a href="{{ route('SA.Addcourse2', ['userID' => $userID, 'courseID'=>encrypt_decrypt('encrypt',$chapter->course_id), 'chapterID'=> encrypt_decrypt('encrypt',$chapter->id)] ) }}"><span>{{ $chapter->chapter ?? "NA" }}</span></a>
                                    <a href="{{ url('super-admin/listed-course/delete-chapter/' . $chapter->id . '/' . $userID) }}" onclick="return confirm('Are you sure you want to delete this chapter?');"><img src="{!! assets('assets/website-images/close-circle.svg') !!}">
                                    </a>
                                </div>
                            </div>
                            <?php $v++; ?>
                            @endforeach
                            @endif
                            <div class="chapter-action">
                                <a class="add-chapter-btn" data-bs-toggle="modal" data-bs-target="#Editcourses">Add Chapter</a>
                            </div>
                        </div>
                    </div>


                    <input type="hidden" name="type_mode" id="type_mode" value="" />
                    <div class="col-md-9">
                        @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ session()->get('message') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="pmu-courses-form-section pmu-addcourse-form">

                            @if (!$chapters->isEmpty() && isset($chapterID))
                            <div class="d-flex">
                                <h2 id="chapterName" class="text-capitalize">Chapter </h2>
                                <a href="" data-bs-toggle="modal" data-bs-target="#EditChapter" id="edit-chapter-modal-open" data-chapter-id="{{ $chapterID }}"><img width="17" height="17" style="cursor: pointer; margin-top: 20px" src="{{ assets('assets/superadmin-images/edit.png') }}" alt=""></a>
                            </div>
                            @endif
                            
                            <div class="pmu-courses-form column">
                                @if ($datas->isEmpty())
                                <tr>
                                    <td colspan="10">
                                        <h5 style="text-align: center">No Record Found</h5>
                                    </td>
                                </tr>
                                @else
                                @if ($datas->isEmpty())
                                @else
                                @php
                                $randomNums = rand(0000, 9999);
                                @endphp
                                @endif

                                @foreach ($datas as $data)
                                <?php $v = 1; ?>
                                @if ($data->type == 'video')
                                @php
                                $randomNum = rand(0000, 9999);
                                @endphp
                                <div class="edit-pmu-form-item" data-id="{{ $data->id }}">
                                    <div class="edit-pmu-heading">
                                        <div class="edit-pmu-text d-flex flex-row align-items-center">
                                            <div>
                                                <img width="24" src="{{ assets('assets/superadmin-images/drag.png') }}" alt="" class="drag" draggable="true" data-id="{{ $data->id }}">
                                            </div>
                                            <div class="edit-pmu-text-title mx-2">
                                                <h3 data-bs-toggle="collapse" data-bs-target="#{{ 'CPDIV' . $randomNum }}">Video<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-checkbox-list">
                                            <ul>
                                                <li>
                                                    <div class="pmucheckbox">
                                                        <input type="checkbox" @if($data->prerequisite) checked @endif id="Prerequisite{{$data->id}}"
                                                        name="prerequisite" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                                        <label for="Prerequisite{{$data->id}}">
                                                            Prerequisite
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="edit-pmu-text">
                                            <div class="pmu-edit-questionnaire-ans1">
                                                <div class="pmu-edit-questionnaire-input">
                                                    <select name="order[]" data-id="{{ $data->id }}" data-chapter-id="{{ $chapterID }}" id="" class="form-control ordering-select-function-{{$data->id}}" disabled>
                                                        @foreach ($datas as $keydata => $valuedata)
                                                        <option @if($valuedata->sort_order == $data->sort_order) selected @endif value="{{ $valuedata->sort_order }}">{{ $valuedata->sort_order }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="pmu-add-questionnaire-tooltip">
                                                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Assign serial order">

                                                        <img src="{!! assets('assets/website-images/info-icon.svg') !!}">
                                                    </div> 
                                                    <script>
                                                    $(function() {
                                                        $('[data-bs-toggle="tooltip"]').tooltip();
                                                    });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-action">
                                            <a href="{{ url('super-admin/delete-section/' . $data->id) }}" onclick="return confirm('Are you sure you want to delete this question?');">
                                                Delete Section</a>
                                        </div>
                                    </div>
                                    <div class="edit-pmu-section collapse-course-form collapse" id="{{ 'CPDIV' . $randomNum }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h4>Uploaded Video</h4>
                                                    <div class="upload-signature">
                                                        @if ($data->details!="" && $data->details!=null)
                                                        <div class="upload-file-item">
                                                            <div class="upload-file-icon">
                                                                <img src="{!! assets('assets/website-images/video-icon.svg') !!}">
                                                            </div>
                                                            <div class="upload-file-text">
                                                                <h3>video</h3>
                                                                <video width="165" height="90" controls controlslist="nodownload noplaybackrate" disablepictureinpicture volume>
                                                                    <source src="{{ assets( 'upload/course/' . $data->details) }}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            </div>
                                                            <div class="upload-file-action">
                                                                <a class="delete-btn" href="{{ url('super-admin/delete-video/' . $data->id) }}" onclick="return confirm('Are you sure you want to delete this video?');"><img src="{!! assets('assets/website-images/close-circle.svg') !!}"></a>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <tr>
                                                            <td colspan="10">
                                                                <h5 style="text-align: center">No Video
                                                                    Found</h5>
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        {{-- <video src="{!! assets('assets/upload/course/' . $data->details) !!}" controls>
                                                                    </video> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <form action="{{ route('SA.update.title.percentage', encrypt_decrypt('encrypt', $data->id)) }}" method="POST">@csrf
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <h4>Video Title</h4>
                                                            <input type="text" name="description" placeholder="Video Title" value="{{ $data->title ?: '' }}" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group edit-pmu-action mt-3 pt-1">
                                                            <button class="edit-question-first" type="submit"> &nbsp; &nbsp; &nbsp; &nbsp;Update&nbsp; &nbsp; &nbsp; &nbsp; </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                @elseif($data->type == 'quiz')
                                @php
                                $randomNum = rand(0000, 9999);
                                @endphp
                                <div class="edit-pmu-form-item" data-id="{{ $data->id }}">
                                    <div class="edit-pmu-heading">
                                        <div class="edit-pmu-text d-flex flex-row align-items-center">
                                            <div>
                                                <img width="24" src="{{ assets('assets/superadmin-images/drag.png') }}" alt="" class="drag" draggable="true" data-id="{{ $data->id }}">
                                            </div>
                                            <div class="edit-pmu-text-title mx-2">
                                                <h3 data-bs-toggle="collapse" data-bs-target="#collapseExample{{ $data->id }}">
                                                    Quiz<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-checkbox-list">
                                            <ul>
                                                <li>
                                                    <div class="pmucheckbox">
                                                        <input type="checkbox" @if($data->prerequisite) checked @endif id="Prerequisite{{$data->id}}"
                                                        name="prerequisite" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                                        <label for="Prerequisite{{$data->id}}">
                                                            Prerequisite
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul> 
                                        </div>
                                        <div class="edit-pmu-text">
                                            <div class="pmu-edit-questionnaire-ans1">
                                                <div class="pmu-edit-questionnaire-input">
                                                    <select name="order[]" id="" data-id="{{ $data->id }}" data-chapter-id="{{ $chapterID }}" class="form-control ordering-select-function-{{$data->id}}" disabled>
                                                        @foreach ($datas as $keydata => $valuedata)
                                                        <option @if($valuedata->sort_order == $data->sort_order) selected @endif value="{{$valuedata->sort_order }}">{{ $valuedata->sort_order }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="pmu-add-questionnaire-tooltip">
                                                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Assign serial order">
                                                        <img src="{!! assets('assets/website-images/info-icon.svg') !!}">
                                                    </div> 
                                                    <script>
                                                    $(function() {
                                                        $('[data-bs-toggle="tooltip"]').tooltip();
                                                    });
                                                    </script>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="edit-pmu-action">
                                            <a href="{{ url('super-admin/delete-quiz/' . $data->id) }}" onclick="return confirm('Are you sure you want to delete this quiz?');">
                                                Delete Section</a>
                                        </div>
                                    </div>
                                    <div class="edit-pmu-section collapse-course-form collapse" id="collapseExample{{ $data->id }}">

                                        <form action="{{ route('SA.update.title.percentage', encrypt_decrypt('encrypt', $data->id)) }}" method="POST">@csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h4>Quiz Title</h4>
                                                        <input type="text" name="description" placeholder="Quiz Title" value="{{ $data->title ?: '' }}" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <h4>Quiz Minimum Passing Percentage</h4>
                                                        <input type="number" min="33" step="0.1" name="passing_per" placeholder="Quiz Minimum Passing Percentage" value="{{ $data->passing ?: '' }}" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group edit-pmu-action mt-3 pt-1">
                                                        <button class="edit-question-first" type="submit"> &nbsp; &nbsp; &nbsp; &nbsp;Update&nbsp; &nbsp; &nbsp; &nbsp; </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        
                                        
                                        <?php $v = 'AA'; ?>
                                        @foreach ($data->quiz as $quiz)
                                        <div class="pmu-edit-questionnaire-box">
                                            <div class="pmu-edit-label">
                                                <div class="pmu-q-badge">Q</div>
                                            </div>
                                            <div class="pmu-edit-questionnaire-content">
                                                <input type="text" class="form-control {{ $v . $quiz->id }}" placeholder="Enter Question Title" name="quiz_question" value="{{ $quiz->title }}">
                                            </div>
                                            <div class="pmu-edit-questionnaire-marks" style="margin-right: 5px; width: 10%">
                                                <input type="number" class="form-control " placeholder="Enter marks" name="questions[2][0][marks]" required="" value="{{ $quiz->marks ?? '' }}">
                                            </div>
                                            <div class="edit-pmu-action">
                                                <a class="edit-question-first" data-id="{{ $quiz->id }}" data-param="{{ $v }}">Update
                                                    Question</a>
                                                <a href="{{ url('super-admin/delete-question/' . $quiz->id) }}" onclick="return confirm('Are you sure you want to delete this question?');">Delete
                                                    Question</a>
                                            </div>
                                        </div>

                                       
                                        <div class="pmu-answer-option-list">

                                            <?php $s_no = 'A'; ?>
                                            @foreach ($quiz->quizOption as $item)
                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-ans-label">
                                                        <div class="a-badge">{{ $s_no }}</div>
                                                    </div>
                                                    <div class="pmu-edit-questionnaire-text d-flex">
                                                        <input type="text" class="form-control {{ $s_no . $item->id }}" placeholder="Type Here..." name="answer" value="{{ $item->answer_option_key }}">
                                                        <div class="update-remove-action1">
                                                            <a class="update-text edit-option" id="edit-option" data-id="{{ $item->id }}" data-param="{{ $s_no }}">Update</a>
                                                            <a class="remove-text" href="{{ url('super-admin/delete-option/' . $item->id) }}" onclick="return confirm('Are you sure you want to delete this option?');">Remove</a>
                                                        </div>
                                                        <div class="pmu-answer-check-item">
                                                            <div class="pmucheckbox1">
                                                                <input type="radio" class="answerEditCheckbox" data-answer-id="{{ $item->id }}" name="answer-{{$quiz->id}}" id="answer-option-{{ $item->id }}" @if($item->is_correct) checked @endif value="1">
                                                                <label for="answer-option-{{ $item->id }}">&nbsp</label>
                                                            </div>
                                                            <div class="pmu-add-questionnaire-tooltip">
                                                                <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Select Correct Answer">
                                                                    <img src="{!! assets('assets/website-images/info-icon.svg') !!}">
                                                                </div> 
                                                                <script>
                                                                $(function() {
                                                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                                                });
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $s_no++; ?>
                                            @endforeach
                                            <div id="newinputquizListing{{ $quiz->id }}"></div>
                                            <div class="pmu-add-answer-info">
                                                <a class="add-answer SaveOption" data-quiz-id="{{ $quiz->id }}" id="SaveOption{{ $quiz->id }}">Save</a>
                                                <a class="add-answer" data-id="{{ $quiz->id }}" id="addListingOption">Add Answer</a>
                                            </div>
                                        </div>
                                        <?php $v = 'AA'; ?>
                                        @endforeach
                                        <form action="{{ route('SA.add.new.Question') }}" method="POST" id="addNewQuestionOptionForm">@csrf
                                            <div id="newQuestionQuizListing{{ $quiz->id }}"></div>
                                            <div class="pmu-add-answer-info">
                                                <button type="submit" style="padding: 5px 2%;" class="d-none add-more saveQuestionQuiz{{$quiz->id}}">Save</button>
                                                <a class="add-answer" data-id="{{ $quiz->id }}" id="addListingQuestionQuiz">Add Question</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @elseif($data->type == 'pdf')
                                @php
                                $randomNum = rand(0000, 9999);
                                @endphp
                                <div class="edit-pmu-form-item" data-id="{{ $data->id }}">
                                    <div class="edit-pmu-heading">
                                        <div class="edit-pmu-text d-flex flex-row align-items-center">
                                            <div>
                                                <img width="24" src="{{ assets('assets/superadmin-images/drag.png') }}" alt="" class="drag" draggable="true" data-id="{{ $data->id }}">
                                            </div>
                                            <div class="edit-pmu-text-title mx-2">
                                                <h3 data-bs-toggle="collapse" data-bs-target="#{{ 'CPDIV' . $randomNum }}">PDF<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-checkbox-list">
                                            <ul>
                                                <li>
                                                    <div class="pmucheckbox">
                                                        <input type="checkbox" @if($data->prerequisite) checked @endif id="Prerequisite{{$data->id}}"
                                                        name="prerequisite" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                                        <label for="Prerequisite{{$data->id}}">
                                                            Prerequisite
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="edit-pmu-text">
                                            <div class="pmu-edit-questionnaire-ans1">
                                                <div class="pmu-edit-questionnaire-input">
                                                    <select name="order[]" id="" data-id="{{ $data->id }}" data-chapter-id="{{ $chapterID }}" class="form-control ordering-select-function-{{$data->id}}" disabled>
                                                        @foreach ($datas as $keydata => $valuedata)
                                                        <option @if($valuedata->sort_order == $data->sort_order) selected @endif value="{{ $valuedata->sort_order }}">{{ $valuedata->sort_order }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="pmu-add-questionnaire-tooltip">
                                                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Assign serial order">

                                                        <img src="{!! assets('assets/website-images/info-icon.svg') !!}">
                                                    </div> 
                                                    <script>
                                                    $(function() {
                                                        $('[data-bs-toggle="tooltip"]').tooltip();
                                                    });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-action">
                                            <a href="{{ url('super-admin/delete-section/' . $data->id) }}" onclick="return confirm('Are you sure you want to delete this question?');">
                                                Delete Section</a>
                                        </div>
                                    </div>
                                    <div class="edit-pmu-section collapse-course-form  collapse" id="{{ 'CPDIV' . $randomNum }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h4>Uploaded PDF</h4>
                                                    @if ($data->details!="" && $data->details!=null)
                                                    <div class="upload-file-item">
                                                        <div class="upload-file-icon">
                                                            <img src="{!! assets('assets/website-images/document-text.svg') !!}">
                                                        </div>
                                                        <div class="upload-file-text">
                                                            <h3>Document</h3>
                                                            <h5>
                                                                <a target="_black" href="{{ uploadAssets('upload/course/'.$data->details) }}">
                                                                    <img src="{{ assets('assets/website-images/pdf.svg') }}" class="mx-3" alt="No pdf found">
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div class="upload-file-action">
                                                            <a class="delete-btn" href="{{ url('super-admin/delete-pdf/' . $data->id) }}" onclick="return confirm('Are you sure you want to delete this pdf?');"><img src="{!! assets('assets/website-images/close-circle.svg') !!}"></a>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <tr>
                                                        <td colspan="10">
                                                            <h5 style="text-align: center">No PDF Found
                                                            </h5>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </div>
                                            </div>

                                            <form action="{{ route('SA.update.title.percentage', encrypt_decrypt('encrypt', $data->id)) }}" method="POST">@csrf
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <h4>PDF Title</h4>
                                                            <input type="text" name="description" placeholder="PDF Title" value="{{ $data->title ?: '' }}" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group edit-pmu-action mt-3 pt-1">
                                                            <button class="edit-question-first" type="submit"> &nbsp; &nbsp; &nbsp; &nbsp;Update&nbsp; &nbsp; &nbsp; &nbsp; </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                @elseif($data->type == 'assignment')
                                @php
                                $randomNum = rand(0000, 9999);
                                @endphp
                                <div class="edit-pmu-form-item" data-id="{{ $data->id }}">
                                    <div class="edit-pmu-heading">
                                        <div class="edit-pmu-text d-flex flex-row align-items-center">
                                            <div>
                                                <img width="24" src="{{ assets('assets/superadmin-images/drag.png') }}" alt="" class="drag" draggable="true" data-id="{{ $data->id }}">
                                            </div>
                                            <div class="edit-pmu-text-title mx-2">
                                                <h3 data-bs-toggle="collapse" data-bs-target="#{{ 'ASDIV' . $randomNum }}">Assignment<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-checkbox-list">
                                            <ul>
                                                <li>
                                                    <div class="pmucheckbox">
                                                        <input type="checkbox" @if($data->prerequisite) checked @endif id="Prerequisite{{$data->id}}"
                                                        name="prerequisite" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                                        <label for="Prerequisite{{$data->id}}">
                                                            Prerequisite
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="edit-pmu-text">
                                            <div class="pmu-edit-questionnaire-ans1">
                                                <div class="pmu-edit-questionnaire-input">
                                                    <select name="order[]" id="" data-id="{{ $data->id }}" data-chapter-id="{{ $chapterID }}" class="form-control ordering-select-function-{{$data->id}}" disabled>
                                                        @foreach ($datas as $keydata => $valuedata)
                                                        <option @if($valuedata->sort_order == $data->sort_order) selected @endif value="{{ $valuedata->sort_order }}">{{ $valuedata->sort_order }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="pmu-add-questionnaire-tooltip">
                                                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Assign serial order">

                                                        <img src="{!! assets('assets/website-images/info-icon.svg') !!}">
                                                    </div> 
                                                    <script>
                                                    $(function() {
                                                        $('[data-bs-toggle="tooltip"]').tooltip();
                                                    });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-action">
                                            <a href="{{ url('super-admin/delete-section/' . $data->id) }}" onclick="return confirm('Are you sure you want to delete this question?');">
                                                Delete Section</a>
                                        </div>
                                    </div>
                                    <div class="collapse collapse-course-form" id="{{ 'ASDIV' . $randomNum }}">
                                        <form action="{{ route('SA.update.title.percentage', encrypt_decrypt('encrypt', $data->id)) }}" method="POST">@csrf
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <h4>Assignment Title</h4>
                                                        <input type="text" name="description" placeholder="Assignment Title" value="{{ $data->title ?: '' }}" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group edit-pmu-action mt-3 pt-1">
                                                        <button class="edit-question-first" type="submit"> &nbsp; &nbsp; &nbsp; &nbsp;Update&nbsp; &nbsp; &nbsp; &nbsp; </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @elseif($data->type == 'survey')
                                @php
                                $randomNum = rand(0000, 9999);
                                @endphp
                                <div class="edit-pmu-form-item" data-id="{{ $data->id }}">
                                    <div class="edit-pmu-heading">
                                        <div class="edit-pmu-text d-flex flex-row align-items-center">
                                            <div>
                                                <img width="24" src="{{ assets('assets/superadmin-images/drag.png') }}" alt="" class="drag" draggable="true" data-id="{{ $data->id }}">
                                            </div>
                                            <div class="edit-pmu-text-title mx-2">
                                                <h3 data-bs-toggle="collapse" data-bs-target="#{{ 'CPDIV' . $randomNum }}">Survey<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-checkbox-list">
                                            <ul>
                                                <li>
                                                    <div class="pmucheckbox">
                                                        <input type="checkbox" @if($data->prerequisite) checked @endif id="Prerequisite{{$data->id}}"
                                                        name="prerequisite" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                                        <label for="Prerequisite{{$data->id}}">
                                                            Prerequisite
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="edit-pmu-text">
                                            <div class="pmu-edit-questionnaire-ans1">
                                                <div class="pmu-edit-questionnaire-input">
                                                    <select name="order[]" id="" data-id="{{ $data->id }}" data-chapter-id="{{ $chapterID }}" class="form-control ordering-select-function-{{$data->id}}" disabled>
                                                        @foreach ($datas as $keydata => $valuedata)
                                                        <option @if($valuedata->sort_order == $data->sort_order) selected @endif value="{{ $valuedata->sort_order }}">{{ $valuedata->sort_order }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="pmu-add-questionnaire-tooltip">
                                                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Assign serial order">

                                                        <img src="{!! assets('assets/website-images/info-icon.svg') !!}">
                                                    </div> 
                                                    <script>
                                                    $(function() {
                                                        $('[data-bs-toggle="tooltip"]').tooltip();
                                                    });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="edit-pmu-action">
                                            <a href="{{ url('super-admin/delete-quiz/' . $data->id) }}" onclick="return confirm('Are you sure you want to delete this survey?');">
                                                Delete Section</a>
                                        </div>
                                    </div>
                                    <div class="collapse collapse-course-form" id="{{ 'CPDIV' . $randomNum }}">
                                        <form action="{{ route('SA.update.title.percentage', encrypt_decrypt('encrypt', $data->id)) }}" method="POST">@csrf
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <h4>Survey Title</h4>
                                                        <input type="text" name="description" placeholder="Survey Title" value="{{ $data->title ?: '' }}" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group edit-pmu-action mt-3 pt-1">
                                                        <button class="edit-question-first" type="submit"> &nbsp; &nbsp; &nbsp; &nbsp;Update&nbsp; &nbsp; &nbsp; &nbsp; </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        
                                        <?php $sur = 'SSS'; ?>
                                        @foreach ($data->quiz as $survey)
                                        <div class="pmu-edit-questionnaire-box">
                                            <div class="pmu-edit-label">
                                                <div class="pmu-q-badge">Q</div>
                                            </div>
                                            <div class="pmu-edit-questionnaire-content">
                                                <input type="text" class="form-control {{ $sur . $survey->id }}" placeholder="Enter Question Title" name="survey_question" value="{{ $survey->title }}">
                                            </div>
                                            <div class="edit-pmu-action">
                                                <a class="edit-question-first" data-id="{{ $survey->id }}" data-param="{{ $sur }}">Update
                                                    Question</a>
                                                <a href="{{ url('super-admin/delete-question/' . $survey->id) }}" onclick="return confirm('Are you sure you want to delete this question?');">Delete
                                                    Question</a>
                                            </div>
                                        </div>

                                        <div class="pmu-answer-option-list">
                                           
                                            <?php $sno = 'A'; ?>
                                            @foreach ($survey->quizOption as $item)
                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-ans-label">
                                                        <div class="a-badge">{{ $sno }}</div>
                                                    </div>

                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control {{ $sno . $item->id }}" placeholder="Type Here..." name="answer" value="{{ $item->answer_option_key }}" required>
                                                       
                                                        <div class="update-remove-action">
                                                            <a class="update-text edit-option" id="edit-option" data-id="{{ $item->id }}" data-param="{{ $sno }}">Update</a>
                                                            <a class="remove-text" href="{{ url('super-admin/delete-option/' . $item->id) }}" onclick="return confirm('Are you sure you want to delete this option?');">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $sno++; ?>
                                            @endforeach
                                            <div id="newinputSurveyListing{{ $survey->id }}"></div>
                                            <div class="pmu-add-answer-info">
                                                <a class="add-answer SaveOption" data-quiz-id="{{ $survey->id }}" id="SaveOption{{ $survey->id }}">Save</a>
                                                <a class="add-answer" data-id="{{ $survey->id }}" id="addListingSurveyOption">Add Answer</a>
                                            </div>
                                        </div>
                                        <?php $sur = 'SSS'; ?>
                                        @endforeach

                                        <form action="{{ route('SA.add.new.survey.Question') }}" method="POST" id="addNewQuestionSurveyForm">@csrf
                                            <div id="newQuestionSurveyListing{{ $survey->id }}"></div>
                                            <div class="pmu-add-answer-info">
                                                <button type="submit" style="padding: 5px 2%;" class="d-none add-more saveQuestionSurvey{{$survey->id}}">Save</button>
                                                <a class="add-answer" data-id="{{ $survey->id }}" id="addListingQuestionSurvey">Add Question</a>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                @else
                                @endif

                                <?php $v++; ?>
                                @endforeach
                                @endif

                                <form method="POST" action="{{ route('SA.Course.Addchapter') }}" class="pt-4 frm-submi" id="formAddCourse" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="courseID" value="{{ $courseID }}" />
                                    <input type="hidden" name="chapter_id" id="chapter_id" value="{{$chapterID}}" />

                                    <div id="add-course-form">

                                    </div>

                                    <button type="submit" class="btn btn-primary survey-btn add-more mb-3 mx-3 d-none">Submit</button>

                                </form>

                                @if (!$chapters->isEmpty() && isset($chapterID))
                                <div class="edit-questionnairetype-item">
                                    <h2>Questionnaire Type:</h2>
                                    <div class="edit-questionnairetype-list">
                                        <ul>
                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="Video" name="questionnairetype" value="Video">
                                                    <label for="Video">
                                                        Video
                                                    </label>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="PDF" name="questionnairetype" value="PDF">
                                                    <label for="PDF">
                                                        PDF
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="Quiz" name="questionnairetype" value="Quiz">
                                                    <label for="Quiz">
                                                        Quiz
                                                    </label>
                                                </div>
                                            </li>
                                            {{-- <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="Exam" name="questionnairetype">
                                                    <label for="Exam">
                                                        Exam
                                                    </label>
                                                </div>
                                            </li> --}}
                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="Assignment" name="questionnairetype" value="Assignment">
                                                    <label for="Assignment">
                                                        Assignment
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="Survey" name="questionnairetype" value="Survey">
                                                    <label for="Survey">
                                                        Survey
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                        <button class="add-answer" id="radio">Add Section</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add card -->
    <div class="modal ro-modal fade" id="SaveContinue" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="becomeacreator-form-info">
                        <img src="images/tick-circle.svg">
                        <h2>Great!!</h2>
                        <p>Your uploaded content is now under process, We will sent you a notification once it been approved
                            from system adminitration via Email</p>
                        <div class="becomeacreator-btn-action">
                            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">Close</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal ro-modal fade" id="Editcourses" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="becomeacreator-form-info">
                        <h2>Add Chapter</h2>
                        <div class="row">
                            <form method="POST" action="{{ route('SA.Listed-Course.SubmitChapter') }}" id="add-chapter-form" autocomplete="off"> @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" required name="name" class="form-control" placeholder="Enter chapter name here..." >
                                        <input type="hidden" name="courseID" value="{{ $courseID }}" >
                                        <input type="hidden" name="userID" value="{{ $userID }}" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="cancel-btn mx-2" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                        <button class="save-btn" type="submit">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal ro-modal fade" id="EditChapter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="becomeacreator-form-info">
                        <h2>Edit Chapter</h2>
                        <div class="row">
                            <form method="POST" action="{{ route('SA.Listed-Course.EditSubmitChapter') }}" id="add-chapter-form" autocomplete="off"> @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" required name="chaptername" class="form-control" placeholder="Enter chapter name here..." >
                                        <input type="hidden" name="courseID" value="{{ $courseID }}" >
                                        <input type="hidden" name="chapterID" value="" >
                                        <input type="hidden" name="userID" value="{{ $userID }}" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="cancel-btn mx-2" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                        <button class="save-btn" type="submit">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script src= "https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

    <script type="text/javascript" src="{{ assets('assets/superadmin-js/addcourse.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-plugins/fancybox/jquery.fancybox.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-css/home.css') !!}">
    <script src="{!! assets('assets/website-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>

    
    <script type="text/javascript">
        $('.column').sortable({
            connectWith: '.column',
            ghostClass: "blue-background-class",
        });
        $('.drag').mouseup(function (event) {
            event.preventDefault();
            var id = $(this).attr('data-id');
            var chapterid = $(".ordering-select-function-"+id).attr("data-chapter-id");
            var val = $(".ordering-select-function-"+id).val();
            var order_no = new Array();
            setTimeout(function() {
                $('.column .drag').each(function() {
                    order_no.push($(this).data("id"));
                });
                console.log(order_no);
                $.ajax({
                    url: arkansasUrl + '/super-admin/change-ordering/' + chapterid,
                    method: 'GET',
                    data:{
                        order_no
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data == 1) {
                            toastr.success("Sort order changed successfully");
                            setInterval(function () {location.reload();}, 2000);
                        }
                    }
                });
            },500);
        });
    </script>

    <!-- Style of Remove button -->
    <style>
        /* Style for the Remove Option button */

        .add-more:hover {
            background: #261313;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }

        .remove-option {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
            /* Add top margin */
        }

        .remove-survey-option {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
            /* Add top margin */
        }

        /* Style for the Add Option button */
        .add-option {
            margin-top: 5px;
            /* Add some top margin to separate from options */
            /* background: var(--yellow);
            color: var(--white); */
            background-color: var(--yellow);
            color: var(--white);
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .add-option-for-new-question, .add-survey-option, .add-survey-option-for-question {
            margin-top: 5px;
            /* Add some top margin to separate from options */
            /* background: var(--yellow);
            color: var(--white); */
            background-color: var(--yellow);
            color: var(--white);
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        /* .add-option:hover {
            background-color: #45a049;
        } */

        /* Style for the remove question button */
        .remove-question, .remove-new-question-survey, .remove-new-question {
            margin-top: 10px;
            /* Adjust the value as needed */
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .remove-question:hover {
            background-color: #d32f2f;
        }
    </style>

    @endsection