@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Courses')
@section('content')
    <input type="hidden" name="courseID" value="{{ $courseID }}" />
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Courses</h2>
            </div>
            <div class="pmu-filter">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('super-admin/listed-course/'.encrypt_decrypt('encrypt',$userID)) }}" class="add-more">Back</a>
                        {{-- <a class="add-more" data-bs-toggle="modal" data-bs-target="#SaveContinue">Save &
                            Continue</a> --}}
                        <a class="add-more" id="form">Save &
                            Continue</a>
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
                                @foreach ($chapters as $chapter)
                                    <div class="chapter-list">
                                        @if ($chapter->id == $chapterID)
                                            <div class="chapter-item active">
                                        @else
                                            <div class="chapter-item">
                                        @endif
                                            <a href="{{ url('super-admin/addcourse2/'.$userID .'/'. encrypt_decrypt('encrypt',$chapter->course_id).'/'.encrypt_decrypt('encrypt',$chapter->id)) }}" ><span>Chapter {{ $v }}</span></a>
                                            <a href="{{ url('admin/delete-chapter/' . $chapter->id) }}"
                                                onclick="return confirm('Are you sure you want to delete this chapter?');"><img
                                                src="{!! assets('assets/website-images/close-circle.svg') !!}">
                                            </a>
                                        </div>
                                    </div>
                                <?php $v++; ?>
                                @endforeach
                            @endif
                            <div class="chapter-action">
                                <a class="add-chapter-btn" href="{{ url('admin/submit-chapter/'.$courseID) }}">Add Chapter</a>
                            </div>
                        </div>
                    </div>


                    <input type="hidden" name="type_mode" id="type_mode" value="" />
                    <div class="col-md-9">
                        @if (session()->has('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session()->get('message') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="pmu-courses-form-section pmu-addcourse-form">
                            <h2>Chapter </h2>
                            {{-- <p>Chapter 1 Video: What Is Permanent Cosmetics?</p> --}}
                            <div class="pmu-courses-form">
                                @if ($datas->isEmpty())
                                    <tr>
                                        <td colspan="10">
                                            <h5 style="text-align: center">No Record Found</h5>
                                        </td>
                                    </tr>
                                @else
                                    @if ($quizes->isEmpty())
                                    @else
                                        @php
                                            $randomNums = rand(0000, 9999);
                                        @endphp
                                        <div class="edit-pmu-form-item">
                                            <div class="edit-pmu-heading">
                                                <div class="edit-pmu-text">
                                                    <h3 data-bs-toggle="collapse" data-bs-target="#collapseExample">
                                                        Quiz<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                                    <div class="edit-pmu-checkbox-list">
                                                        <ul>
                                                            <li>
                                                                <div class="pmucheckbox">
                                                                    <input type="checkbox" id="Prerequisite"
                                                                        name="prerequisite" value="">
                                                                    <label for="Prerequisite">
                                                                        Prerequisite
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $v = 'AA'; ?>
                                            @foreach ($quizes as $quiz)
                                                <div class="collapse" id="collapseExample">
                                                    <div class="pmu-edit-questionnaire-box">
                                                        <div class="pmu-edit-label">
                                                            <div class="pmu-q-badge">Q</div>
                                                        </div>
                                                        <div class="pmu-edit-questionnaire-content">
                                                            <input type="text" class="form-control {{ $v }}"
                                                                placeholder="Enter Question Title" name="quiz_question"
                                                                value="{{ $quiz->title }}">
                                                        </div>
                                                        <div class="edit-pmu-action">
                                                            <a class="edit-question-first" data-id="{{ $quiz->id }}"
                                                                data-param="{{ $v }}">Update
                                                                Question</a>
                                                            <a href="{{ url('admin/delete-question/' . $quiz->id) }}"
                                                                onclick="return confirm('Are you sure you want to delete this question?');">Delete
                                                                Question</a>
                                                        </div>
                                                    </div>


                                                    <div class="pmu-answer-option-list">
                                                        <?php
                                                        $options = \App\Models\ChapterQuizOption::where('quiz_id', $quiz->id)->get();
                                                        ?>
                                                        <?php $s_no = 'A'; ?>
                                                        @foreach ($options as $item)
                                                            <div class="pmu-answer-box">
                                                                <div class="pmu-edit-questionnaire-ans">
                                                                    <div class="pmu-edit-ans-label">
                                                                        <div class="a-badge">{{ $s_no }}</div>
                                                                    </div>
                                                                    <div class="pmu-edit-questionnaire-text">
                                                                        <input type="text"
                                                                            class="form-control {{ $s_no }}"
                                                                            placeholder="Type Here..." name="answer"
                                                                            value="{{ $item->answer_option_key }}">
                                                                        <div class="update-remove-action">
                                                                            <a class="update-text edit-option"
                                                                                id="edit-option"
                                                                                data-id="{{ $item->id }}"
                                                                                data-param="{{ $s_no }}">Update</a>
                                                                            <a class="remove-text"
                                                                                href="{{ url('delete_option2/' . $item->id) }}"
                                                                                onclick="return confirm('Are you sure you want to delete this option?');">Remove</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php $s_no++; ?>
                                                        @endforeach
                                                        <div id="newinputquizListing"></div>
                                                        <div class="pmu-add-answer-info">
                                                            <a class="add-answer SaveOption">Save</a>
                                                            <a class="add-answer" id="addListingOption">Add Answer</a>
                                                        </div>
                                                    </div>

                                                    <div class="pmu-answer-option-action">
                                                        <div class="pmu-answer-form-select">
                                                            <form method="POST" action="{{ route('SaveAnswer') }}"
                                                                id="SaveAnswer">
                                                                <input type="hidden" name="_token"
                                                                    value="{{ csrf_token() }}" />
                                                                <input type="hidden" name="questionID"
                                                                    value="{{ $quiz->id }}" />
                                                                <div class="row">
                                                                    <div class="col-md-7">
                                                                        <div class="form-group">
                                                                            <select class="form-control" name="answerID">
                                                                                <option>Choose Correct Answer</option>
                                                                                @foreach ($options as $item)
                                                                                    <option value="{{ $item->id }}"
                                                                                        @if ($item->id == $quiz->correct_answer) selected='selected'
                                                                            @else @endif>
                                                                                        {{ $item->answer_option_key }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <button class="add-answer">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $v = 'AA'; ?>
                                            @endforeach
                                        </div>
                                    @endif

                                    @foreach ($datas as $data)
                                    <?php  $v = 1; ?>
                                        @if ($data->type == 'video')
                                            @php
                                                $randomNum = rand(0000, 9999);
                                            @endphp
                                            <div class="edit-pmu-form-item">
                                                <div class="edit-pmu-heading">
                                                    <div class="edit-pmu-text">
                                                        <h3 data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'CPDIV' . $randomNum }}">Video<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                                    </div>
                                                    <div class="edit-pmu-action">
                                                        <a href="{{ url('admin/delete-question/' . $data->id) }}"
                                                            onclick="return confirm('Are you sure you want to delete this question?');">
                                                            Delete Section</a>
                                                    </div>
                                                </div>
                                                <div class="edit-pmu-section collapse" id="{{ 'CPDIV' . $randomNum }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <h4>Uploaded Video</h4>
                                                                <div class="upload-signature">
                                                                    @if ($data->file)
                                                                        <div class="upload-file-item">
                                                                            <div class="upload-file-icon">
                                                                                <img src="{!! assets('assets/website-images/video-icon.svg') !!}">
                                                                            </div>
                                                                            <div class="upload-file-text">
                                                                                <h3>video</h3>
                                                                                {{-- <h5>10 kb</h5> --}}
                                                                            </div>
                                                                            <div class="upload-file-action">
                                                                                <a class="delete-btn"
                                                                                    href="{{ url('admin/delete-video/' . $data->id) }}"
                                                                                    onclick="return confirm('Are you sure you want to delete this video?');"><img
                                                                                        src="{!! assets('assets/website-images/close-circle.svg') !!}"></a>
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

                                                                    {{-- <video src="{!! assets('assets/upload/course/' . $data->file) !!}" controls>
                                                                    </video> --}}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <h4>Video Description</h4>
                                                                <textarea type="text" class="form-control" name="video_description" placeholder="Video Description" disabled>{{ $data->desc ?: '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($data->type == 'pdf')
                                            @php
                                                $randomNum = rand(0000, 9999);
                                            @endphp
                                            <div class="edit-pmu-form-item">
                                                <div class="edit-pmu-heading">
                                                    <div class="edit-pmu-text">
                                                        <h3 data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'CPDIV' . $randomNum }}">PDF<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                                    </div>
                                                    <div class="edit-pmu-action">
                                                        <a href="{{ url('admin/delete-question/' . $data->id) }}"
                                                            onclick="return confirm('Are you sure you want to delete this question?');">
                                                            Delete Section</a>
                                                    </div>
                                                </div>
                                                <div class="edit-pmu-section collapse" id="{{ 'CPDIV' . $randomNum }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <h4>Uploaded PDF</h4>
                                                                @if ($data->file)
                                                                    <div class="upload-file-item">
                                                                        <div class="upload-file-icon">
                                                                            <img src="{!! assets('assets/website-images/document-text.svg') !!}">
                                                                        </div>
                                                                        <div class="upload-file-text">
                                                                            <h3>Document</h3>
                                                                            {{-- <h5>2 KB</h5> --}}
                                                                        </div>
                                                                        <div class="upload-file-action">
                                                                            <a class="delete-btn"
                                                                                href="{{ url('admin/delete-pdf/' . $data->id) }}"
                                                                                onclick="return confirm('Are you sure you want to delete this pdf?');"><img
                                                                                    src="{!! assets('assets/website-images/close-circle.svg') !!}"></a>
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

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <h4>PDF Description</h4>
                                                                <textarea type="text" class="form-control" name="PDF_description" placeholder="PDF Description" disabled>{{ $data->desc ?: '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($data->type == 'assignment')
                                            @php
                                                $randomNum = rand(0000, 9999);
                                            @endphp
                                            <div class="edit-pmu-form-item">
                                                <form method="POST" action="{{ route('Home.SaveQuestion') }}"
                                                    class="pt-4" id="Form_assignment" enctype="multipart/form-data">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <input type="hidden" name="type" id="assignment"
                                                        value="assignment" />
                                                    <input type="hidden" name="chapter_id" id="chapter_id"
                                                        value="1" />
                                                    <div class="edit-pmu-heading">
                                                        <div class="edit-pmu-text">
                                                            <h3 data-bs-toggle="collapse"
                                                                data-bs-target="#{{ 'CPDIV' . $randomNum }}">Assignment<i class="las la-angle-down" style="margin-left: 15px;"></i>
                                                            </h3>
                                                        </div>
                                                        <div class="edit-pmu-action">
                                                            <a href="{{ url('admin/delete-question/' . $data->id) }}"
                                                                onclick="return confirm('Are you sure you want to delete this question?');">
                                                                Delete Section</a>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="pmu-answer-option-list">
                                                        <div class="pmu-answer-box">
                                                            <div class="pmu-edit-questionnaire-ans">
                                                                <div class="pmu-edit-questionnaire-text">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Paste Google Drive Link To Receive Assignment From Student "
                                                                        name="">
                                                                    <span class="pmu-drive-logo"><img src="{!! assets('assets/website-images/drive.svg') !!}"></span>
                                                                </div>
                                                            </div>
                                                        </div>
            
                                                        <div class="pmu-answer-box">
                                                            <div class="pmu-edit-questionnaire-ans">
                                                                <div class="pmu-edit-questionnaire-text">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Paste Dropbox Link To Receive Assignment From Student "
                                                                        name="">
                                                                    <span class="pmu-drive-logo"><img src="{!! assets('assets/website-images/dropbox.svg') !!}"></span>
                                                                </div>
                                                            </div>
                                                        </div>
            
            
            
                                                        <div class="pmu-answer-box">
                                                            <div class="pmu-edit-questionnaire-ans">
                                                                <div class="pmu-edit-questionnaire-text">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Paste OneDrive Link To Receive Assignment From Student "
                                                                        name="">
                                                                    <span class="pmu-drive-logo"><img src="{!! assets('assets/website-images/onedrive.svg') !!}"></span>
                                                                </div>
                                                            </div>
                                                        </div>
            
            
                                                        <div class="pmu-answer-box">
                                                            <div class="pmu-edit-questionnaire-ans">
                                                                <div class="pmu-edit-questionnaire-text">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Paste Drive Link To Receive Assignment From Student "
                                                                        name="">
                                                                    <span class="pmu-drive-logo"><img src="{!! assets('assets/website-images/link-icon.svg') !!}"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </form>
                                            </div>
                                        @elseif($data->type == 'survey')
                                            @php
                                                $randomNum = rand(0000, 9999);
                                                
                                            @endphp
                                            <div class="edit-pmu-form-item">
                                                <div class="edit-pmu-heading">
                                                    <div class="edit-pmu-text">
                                                        <h3 data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'CPDIV' . $randomNum }}">Survey<i class="las la-angle-down" style="margin-left: 15px;"></i></h3>
                                                        <div class="edit-pmu-checkbox-list">
                                                            <ul>
                                                                <li>
                                                                    <div class="pmucheckbox">
                                                                        <input type="checkbox" id="Optional"
                                                                            value="off" name="required_fied">
                                                                        <label for="Optional">
                                                                            Optional
                                                                        </label>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <div class="pmucheckbox">
                                                                        <input type="checkbox" id="Mandatory"
                                                                            value="on" name="required_fied">
                                                                        <label for="Mandatory">
                                                                            Mandatory
                                                                        </label>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="edit-pmu-action">
                                                        <a href="{{ url('admin/delete-question/' . $data->id) }}"
                                                            onclick="return confirm('Are you sure you want to delete this question?');">
                                                            Delete Section</a>
                                                    </div>
                                                </div>
                                                <div class="collapse" id="{{ 'CPDIV' . $randomNum }}">
                                                    <div class="pmu-edit-questionnaire-box">
                                                        <div class="pmu-edit-label">
                                                            <div class="pmu-q-badge">Q</div>
                                                        </div>
                                                        <div class="pmu-edit-questionnaire-content">
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter Question Title" name="survey_question"
                                                                value="{{ $data->title }}">
                                                        </div>
                                                    </div>

                                                    <div class="pmu-answer-option-list">
                                                        <?php
                                                        $options = \App\Models\ChapterQuizOption::where('quiz_id', $data->id)->get();
                                                        ?>
                                                        <?php $sno = 'A'; ?>
                                                        @foreach ($options as $item)
                                                            <div class="pmu-answer-box">
                                                                <div class="pmu-edit-questionnaire-ans">
                                                                    <div class="pmu-edit-ans-label">
                                                                        <div class="a-badge">{{ $sno }}</div>
                                                                    </div>

                                                                    <div class="pmu-edit-questionnaire-text">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Type Here..." name="option[5]"
                                                                            value="{{ $item->answer_option_key }}"
                                                                            required>
                                                                        <span class="remove-text">Remove</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php $sno++; ?>
                                                        @endforeach
                                                        <div class="pmu-add-answer-info">
                                                            <a class="add-answer" href="">Add more Question</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                        @endif

                                        <?php $v++; ?>
                                    @endforeach
                                @endif
                                <div class="edit-pmu-form-item" id="video_div">
                                    <div class="edit-pmu-heading">
                                        <div class="edit-pmu-text">
                                            <h3>Video</h3>
                                        </div>
                                        <div class="edit-pmu-action">
                                            <a href=""> Delete Section</a>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('Home.SaveQuestion') }}" class="pt-4"
                                        id="Form_Video" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="courseID" value="{{ $courseID }}" />
                                        <input type="hidden" name="type" id="type" value="video" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="{{$chapterID}}" />
                                        <div class="edit-pmu-section">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h4>Upload Video</h4>
                                                        <div class="upload-signature">
                                                            <input type="file" name="video" id="video"
                                                                class="uploadsignature addsignature">
                                                            <label for="video">
                                                                <div class="signature-text">
                                                                    <span id="video_file_name"><img
                                                                            src="{!! assets('assets/website-images/upload.svg') !!}"> Click here
                                                                        to Upload</span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <h4>Video Description</h4>
                                                        <textarea type="text" class="form-control" name="video_description" placeholder="Video Description"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="edit-pmu-form-item" id="pdf_div">
                                    <div class="edit-pmu-heading">
                                        <div class="edit-pmu-text">
                                            <h3>PDF</h3>
                                        </div>
                                        <div class="edit-pmu-action">
                                            <a href=""> Delete Section</a>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('Home.SaveQuestion') }}" class="pt-4"
                                        id="Form_PDF" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="courseID" value="{{ $courseID }}" />
                                        <input type="hidden" name="type" id="pdf" value="pdf" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="{{$chapterID}}" />
                                        <div class="edit-pmu-section">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h4>Upload PDF</h4>
                                                        <div class="upload-signature">
                                                            <input type="file" name="pdf" id="pdf_file"
                                                                class="uploadsignature addsignature">
                                                            <label for="pdf_file">
                                                                <div class="signature-text">
                                                                    <span id="pdf_file_name"><img
                                                                            src="{!! assets('assets/website-images/upload.svg') !!}"> Click here
                                                                        to Upload</span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <h4>PDF Description</h4>
                                                        <textarea type="text" class="form-control" name="PDF_description" placeholder="PDF Description"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="edit-pmu-form-item" id="quiz_div">
                                    <form method="POST" action="{{ route('Home.SaveQuestion') }}" class="pt-4"
                                        id="Form_Quiz" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="courseID" value="{{ $courseID }}" />
                                        <input type="hidden" name="type" id="quiz" value="quiz" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="{{$chapterID}}" />
                                        <div class="edit-pmu-heading">
                                            <div class="edit-pmu-text">
                                                <h3>Quiz</h3>
                                                <div class="edit-pmu-checkbox-list">
                                                    <ul>
                                                        <li>
                                                            <div class="pmucheckbox">
                                                                <input type="checkbox" id="Prerequisite"
                                                                    name="prerequisite">
                                                                <label for="Prerequisite">
                                                                    Prerequisite
                                                                </label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="edit-pmu-action">
                                                {{-- <a id="addQuestion"> Add Question</a> --}}
                                                <a href=""> Delete Section</a>
                                            </div>
                                        </div>

                                        <div class="questions">
                                            <div class="question">
                                                <div class="pmu-edit-questionnaire-box">
                                                    <div class="pmu-edit-label">
                                                        <div class="pmu-q-badge">Q</div>
                                                    </div>
                                                    <div class="pmu-edit-questionnaire-content">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Question Title" name="questions[0][text]" required>
                                                    </div>
                                                </div>
                                                <div class="options">
                                                    <div class="pmu-answer-option-list">
                                                        <div class="pmu-answer-box">
                                                            <div class="pmu-edit-questionnaire-ans">
                                                                <div class="pmu-edit-questionnaire-text">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Type Here..."
                                                                        name="questions[0][options][]" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="button" class="add-option">Add Option</button>
                                                <button type="button" class="remove-question" data-id="lok">Remove
                                                    Question</button>
                                            </div>
                                        </div>

                                        <div class="pmu-add-answer-info">
                                            <a class="add-answer" id="addQuestion">Add Question</a>
                                        </div>
                                    </form>
                                </div>

                                <div class="edit-pmu-form-item" id="assignment_div">
                                    <form method="POST" action="{{ route('Home.SaveQuestion') }}" class="pt-4"
                                        id="Form_assignment" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="courseID" value="{{ $courseID }}" />
                                        <input type="hidden" name="type" id="assignment" value="assignment" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="{{$chapterID}}" />
                                        <div class="edit-pmu-heading">
                                            <div class="edit-pmu-text">
                                                <h3>Assignment</h3>
                                            </div>
                                            <div class="edit-pmu-action">
                                                <a href=""> Delete Section</a>
                                            </div>
                                        </div>

                                        {{-- <div class="pmu-answer-option-list">
                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Paste Google Drive Link To Receive Assignment From Student "
                                                            name="">
                                                        <span class="pmu-drive-logo"><img src="{!! assets('assets/website-images/drive.svg') !!}"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Paste Dropbox Link To Receive Assignment From Student "
                                                            name="">
                                                        <span class="pmu-drive-logo"><img src="{!! assets('assets/website-images/dropbox.svg') !!}"></span>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Paste OneDrive Link To Receive Assignment From Student "
                                                            name="">
                                                        <span class="pmu-drive-logo"><img src="{!! assets('assets/website-images/onedrive.svg') !!}"></span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Paste Drive Link To Receive Assignment From Student "
                                                            name="">
                                                        <span class="pmu-drive-logo"><img src="{!! assets('assets/website-images/link-icon.svg') !!}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </form>
                                </div>

                                <div class="edit-pmu-form-item" id="survey_div">
                                    <form method="POST" action="{{ route('Home.SaveQuestion') }}" class="pt-4"
                                        id="Form_Survey" enctype="multipart/form-data">
                                        <input type="hidden" name="courseID" value="{{ $courseID }}" />
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="type" id="survey" value="survey" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="{{$chapterID}}" />
                                        <div class="edit-pmu-heading">
                                            <div class="edit-pmu-text">
                                                <h3>Survey</h3>
                                                <div class="edit-pmu-checkbox-list">
                                                    <ul>
                                                        <li>
                                                            <div class="pmucheckbox">
                                                                <input type="checkbox" id="Optional" value="off"
                                                                    name="required_fied">
                                                                <label for="Optional">
                                                                    Optional
                                                                </label>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <div class="pmucheckbox">
                                                                <input type="checkbox" id="Mandatory" value="on"
                                                                    name="required_fied">
                                                                <label for="Mandatory">
                                                                    Mandatory
                                                                </label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="edit-pmu-action">
                                                <a href=""> Delete Section</a>
                                            </div>
                                        </div>


                                        <div class="pmu-edit-questionnaire-box">
                                            <div class="pmu-edit-label">
                                                <div class="pmu-q-badge">Q</div>
                                            </div>
                                            <div class="pmu-edit-questionnaire-content">
                                                <input type="text" class="form-control"
                                                    placeholder="Enter Question Title" name="survey_question"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="pmu-answer-option-list">
                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-ans-label">
                                                        <div class="a-badge">A</div>
                                                    </div>
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Type Here..." name="option[5]" value=""
                                                            required>
                                                        <span class="remove-text">Remove</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-ans-label">
                                                        <div class="a-badge">B</div>
                                                    </div>
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Type Here..." name="option[4]" value=""
                                                            required>
                                                        <span class="remove-text">Remove</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pmu-add-answer-info">
                                                <a class="add-answer" href="">Add more Question</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="edit-questionnairetype-item">
                                    <h2>Questionnaire Type:</h2>
                                    <div class="edit-questionnairetype-list">
                                        <ul>
                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="Video" name="questionnairetype"
                                                        value="Video">
                                                    <label for="Video">
                                                        Video
                                                    </label>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="PDF" name="questionnairetype"
                                                        value="PDF">
                                                    <label for="PDF">
                                                        PDF
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="Quiz" name="questionnairetype"
                                                        value="Quiz">
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
                                                    <input type="radio" id="Assignment" name="questionnairetype"
                                                        value="Assignment">
                                                    <label for="Assignment">
                                                        Assignment
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="questionnairetype-radio">
                                                    <input type="radio" id="Survey" name="questionnairetype"
                                                        value="Survey">
                                                    <label for="Survey">
                                                        Survey
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                        <button class="add-answer" id="radio">Add Section</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add card -->
    <div class="modal ro-modal fade" id="SaveContinue" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

    <!-- Edit Question ajax -->
    <script>
        $('.edit-question-first').on('click', function() {
            var question_id = $(this).attr("data-id");
            var question_param = $(this).attr("data-param");
            let selector = '.' + question_param;
            var question = $(selector).val();
            $.ajax({
                url: 'https://nileprojects.in/arkansas/public/admin/update_question_list',
                method: 'GET',
                data: {
                    question_id: question_id,
                    question: question
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data == 1) {
                        location.reload();
                    }
                }
            });
        });
    </script>

    <!-- Style of Remove button -->
    <style>
        /* Style for the Remove Option button */
        .remove-option {
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
            color:var(--white);
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        /* .add-option:hover {
            background-color: #45a049;
        } */

        /* Style for the remove question button */
        .remove-question {
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

    <!-- Append new queston and option field using jquery -->
    <script>
        $(document).ready(function() {
            var questionCounter = 1;

            // Show/hide the Remove Question button based on the number of questions
            function updateRemoveButtonVisibility() {
                var questionCount = $('.question').length;
                $('.remove-question').prop('disabled', questionCount === 0);
            }

            // Initial update
            updateRemoveButtonVisibility();

            // Add question field
            $('#addQuestion').click(function() {
                var newQuestion = $('.question').first().clone();
                newQuestion.find(':input').val('');

                // Remove any existing remove-option buttons from the cloned question
                newQuestion.find('.remove-option').remove();


                // Update input names with the new questionCounter value
                newQuestion.find('[name]').each(function() {
                    var oldName = $(this).attr('name');
                    var newName = oldName.replace(/\[(\d+)\]/, '[' + questionCounter + ']');
                    $(this).attr('name', newName);
                });


                newQuestion.appendTo('.questions');
                questionCounter++;
            });


            // Add option field
            $(document).on('click', '.add-option', function() {
                var newOption = $(this).siblings('.options').find('input').first().clone();
                newOption.val('');
                newOption.css('margin-top', '10px');
                $(this).siblings('.options').append(newOption);

                // Append a remove button to the new option
                var removeOptionButton = $(
                    '<button type="button" class="remove-option">Remove Option</button>');

                $(this).siblings('.options').append(removeOptionButton);
            });

            // Remove question field
            $(document).on('click', '.remove-question', function() {
                $(this).closest('.question').remove();
                updateRemoveButtonVisibility();
            });

            // Remove option field
            $(document).on('click', '.remove-option', function() {
                var optionsContainer = $(this).closest('.options');
                optionsContainer.find('input[type="text"]').last().remove(); // Remove the last option input
                $(this).remove(); // Remove the "Remove Option" button
            });
        });
    </script>

    <!-- EditOption ajax -->
    <script>
        let _token = $("input[name='_token']").val();
        $('.edit-option').on('click', function() {
            var option_id = $(this).attr("data-id");
            var option_param = $(this).attr("data-param");
            let selector = '.' + option_param;
            var option = $(selector).val();
            $.ajax({
                url: 'https://nileprojects.in/arkansas/public/admin/update_option_list',
                method: 'GET',
                data: {
                    option_id: option_id,
                    option: option
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data == 1) {
                        location.reload();
                    }
                }
            });
        });
    </script>

    <!-- Append File name -->
    <script>
        $(document).ready(function() {
            $('input[name="video"]').change(function(e) {
                var geekss = e.target.files[0].name;
                $("#video_file_name").text(geekss);
            });
            $('input[name="pdf"]').change(function(e) {
                var geekss = e.target.files[0].name;
                $("#pdf_file_name").text(geekss);
            });
        });
    </script>

    <!-- Submit form And Mange all Hide and Show field(Append) -->
    <script>
        $(document).ready(function() {
            $("#video_div").hide();
            $("#pdf_div").hide();
            $("#quiz_div").hide();
            $("#assignment_div").hide();
            $("#survey_div").hide();
            $(document).on('click', '.hhh', function() {
                var let_id = '#' + $(this).attr('id');
                $(let_id).remove();
            });
            $("#radio").click(function() {
                let div_type = $('input[name="questionnairetype"]:checked').val();
                $('#type_mode').val(div_type);
                if (div_type == 'Video') {
                    $("#video_div").show();
                    $("#pdf_div").hide();
                    $("#quiz_div").hide();
                    $("#assignment_div").hide();
                    $("#survey_div").hide();
                } else if (div_type == 'PDF') {
                    $("#video_div").hide();
                    $("#pdf_div").show();
                    $("#quiz_div").hide();
                    $("#assignment_div").hide();
                    $("#survey_div").hide();
                } else if (div_type == 'Quiz') {
                    $("#video_div").hide();
                    $("#pdf_div").hide();
                    $("#quiz_div").show();
                    $("#assignment_div").hide();
                    $("#survey_div").hide();
                } else if (div_type == 'Assignment') {
                    $("#assignment_div").show();
                    $("#video_div").hide();
                    $("#pdf_div").hide();
                    $("#quiz_div").hide();
                    $("#survey_div").hide();
                } else if (div_type == 'Survey') {
                    $("#survey_div").show();
                    $("#video_div").hide();
                    $("#pdf_div").hide();
                    $("#quiz_div").hide();
                    $("#assignment_div").hide();

                } else {

                }
            });
            $("#form").click(function() {
                let get_type = $('input[name="type_mode"]').val();
                if (get_type == 'Video') {
                    //let div_questionS = $('input[name="questionS"]').val();
                    // if (div_questionS == '') {
                    //     alert('Question field is required');
                    // }
                    // let div_option1 = $('input[name="option[1]').val();
                    // let div_option2 = $('input[name="option[2]').val();
                    // if (div_option1 == '') {
                    //     alert('Option field is required');
                    // } else if(div_option2 == ''){
                    //     alert('Option field is required');
                    // }else{
                    //     $('#Form_S').submit();
                    // }
                    let div_video = $('input[name="video"]').val();
                    if (div_video == '') {
                        alert('Video file is required');
                    } else {
                        $('#Form_Video').submit();
                    }
                } else if (get_type == 'PDF') {
                    let div_video = $('input[name="pdf"]').val();
                    if (div_video == '') {
                        alert('PDF file is required');
                    } else {
                        $('#Form_PDF').submit();
                    }
                } else if (get_type == 'Quiz_2') {
                    var div_questionS = $('input[name="questions[0][options][]"]').val();
                    //let div_questionS = $('input[name="quiz_question"]').val();
                    // if (div_questionS == '') {
                    //     alert('Question field is required');
                    // }
                    var div_option1 = $('input[name="questions[0][options][]"]').val();
                    // let div_option1 = $('input[name="option[1]').val();
                    // let div_option2 = $('input[name="option[2]').val();
                    if (div_option1 == '') {
                        alert('Option field is required');
                    } else {
                        $('#Form_Quiz').submit();
                    }
                } else if (get_type == 'Survey') {
                    let survey_question = $('input[name="survey_question"]').val();
                    if (survey_question == '') {
                        alert('Question field is required');
                    }
                    let div_option3 = $('input[name="option[3]').val();
                    let div_option4 = $('input[name="option[4]').val();
                    if (div_option3 == '') {
                        alert('Option field is required');
                    } else if (div_option4 == '') {
                        alert('Option field is required');
                    } else {
                        $('#Form_Survey').submit();
                    }
                } else if (get_type == 'Assignment') {
                    $('#Form_assignment').submit();
                } else {
                    //var div_questionS = $('input[name="questions[0][options][]"]').val();
                    //let div_questionS = $('input[name="quiz_question"]').val();
                    // if (div_questionS == '') {
                    //     alert('Question field is required');
                    // }
                    //var div_option1 = $('input[name="questions[0][options][]"]').val();
                    // let div_option1 = $('input[name="option[1]').val();
                    // let div_option2 = $('input[name="option[2]').val();
                    // if (div_option1 == '') {
                    //     alert('Option field is required');
                    // } else {
                    $('#Form_Quiz').submit();
                    //}
                }
            });
        });
    </script>

    <!-- Add New input Form field(Append) -->
    <script>
        $(document).ready(function() {
            $(".SaveOption").hide();

            $("#addQuizOption").click(function() {

                var possible = 'AB' + Math.floor(Math.random() * (100 - 1) + 1);
                newRowAdd =
                    '<div class="pmu-answer-box" id="' + possible +
                    '" > <div class="pmu-edit-questionnaire-ans">' +
                    '<div class="pmu-edit-questionnaire-text">' +
                    '<input type="text" class="form-control" placeholder="Type Here..." name="option[' +
                    possible + ']">' +
                    '<span class="remove-text remove_newoption" id="' + possible + '">Remove</span>' +
                    '</div>' +
                    '</div>' + '</div>';
                $('#newinputquiz').append(newRowAdd);
            });
            $("#addListingOption").click(function() {
                $("#SaveOption").show();
                var possible = 'AB' + Math.floor(Math.random() * (100 - 1) + 1);
                newRowAdd =
                    '<div class="pmu-answer-box" id="' + possible +
                    '" > <div class="pmu-edit-questionnaire-ans">' +
                    '<div class="pmu-edit-questionnaire-text">' +
                    '<input type="text" class="form-control" placeholder="Type Here..." name="option[' +
                    possible + ']">' +
                    '<span class="remove-text remove_newoption" id="' + possible + '">Remove</span>' +
                    '</div>' +
                    '</div>' + '</div>';
                $('#newinputquizListing').append(newRowAdd);
            });
            $(document).on('click', '.remove_newoption', function() {
                var let_id = '#' + $(this).attr('id');
                $(let_id).remove();
            });
        });
    </script>

@endsection
