@extends('layouts.app-master')

@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Courses</h2>
            </div>
            <div class="pmu-filter">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ URL::previous() }}" class="add-more">Back</a>
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
                                            <h5 style="text-align: center">No Chapter</h5>
                                        </td>
                                    </tr>
                                @else
                                    <?php $v = 1; ?>
                                    @foreach ($chapters as $chapter)
                                        <div class="chapter-list">
                                            <div class="chapter-item">
                                                <span>Chapter {{$v}}</span> <a href="{{ url('delete-chapter/' . $chapter->id) }}"
                                                    onclick="return confirm('Are you sure you want to delete this question?');"><img src="{!! url('assets/website-images/close-circle.svg') !!}"></a>
                                            </div>
                                        </div>
                                        <?php $v++; ?>
                                    @endforeach
                                @endif
                            <div class="chapter-action">
                                <a class="add-chapter-btn" href="{{ route('admin.SubmitChapter') }}">Add Chapter</a>
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
                            <h2>Chapter 1</h2>
                            {{-- <p>Chapter 1 Video: What Is Permanent Cosmetics?</p> --}}
                            <div class="pmu-courses-form">
                                @if ($datas->isEmpty())
                                    <tr>
                                        <td colspan="10">
                                            <h5 style="text-align: center">No Record Found</h5>
                                        </td>
                                    </tr>
                                @else
                                    <?php $v = 'AA'; ?>
                                    @foreach ($datas as $data)
                                        @if ($data->type == 'video')
                                            @php
                                                $randomNum = rand(0000, 9999);
                                            @endphp
                                            <div class="edit-pmu-form-item">
                                                <div class="edit-pmu-heading">
                                                    <div class="edit-pmu-text">
                                                        <h3 data-bs-toggle="collapse"
                                                        data-bs-target="#{{ 'CPDIV' . $randomNum }}">Video</h3>
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
                                                                                <img src="{!! url('assets/website-images/video-icon.svg') !!}">
                                                                            </div>
                                                                            <div class="upload-file-text">
                                                                                <h3>video</h3>
                                                                                {{-- <h5>10 kb</h5> --}}
                                                                            </div>
                                                                            <div class="upload-file-action">
                                                                                <a class="delete-btn" href="{{ url('admin/delete-video/' . $data->id) }}"
                                                                                    onclick="return confirm('Are you sure you want to delete this video?');"><img src="{!! url('assets/website-images/close-circle.svg') !!}"></a>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="10">
                                                                                <h5 style="text-align: center">No Video Found</h5>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    {{-- <video src="{!! url('assets/upload/course/' . $data->file) !!}" controls>
                                                                    </video> --}}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <h4>Video Decription</h4>
                                                                <textarea type="text" class="form-control" name="video_decription" placeholder="Video Decription">{{ $data->desc ?: '' }}</textarea>
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
                                                <div class="edit-pmu-heading" >
                                                    <div class="edit-pmu-text">
                                                        <h3 data-bs-toggle="collapse"
                                                        data-bs-target="#{{ 'CPDIV' . $randomNum }}">PDF</h3>
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
                                                                            <img src="{!! url('assets/website-images/document-text.svg') !!}">
                                                                        </div>
                                                                        <div class="upload-file-text">
                                                                            <h3>Document</h3>
                                                                            {{-- <h5>2 KB</h5> --}}
                                                                        </div>
                                                                        <div class="upload-file-action">
                                                                            <a class="delete-btn" href="{{ url('admin/delete-pdf/' . $data->id) }}"
                                                                                onclick="return confirm('Are you sure you want to delete this pdf?');"><img src="{!! url('assets/website-images/close-circle.svg') !!}"></a>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <tr>
                                                                        <td colspan="10">
                                                                            <h5 style="text-align: center">No PDF Found</h5>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <h4>PDF Decription</h4>
                                                                <textarea type="text" class="form-control" name="PDF_decription" placeholder="PDF Decription">{{ $data->desc ?: '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($data->type == 'quiz')
                                            @php
                                                $randomNum = rand(0000, 9999);
                                            @endphp
                                            <div class="edit-pmu-form-item" >
                                                <div class="edit-pmu-heading">
                                                    <div class="edit-pmu-text">
                                                        <h3 data-bs-toggle="collapse"
                                                        data-bs-target="#{{ 'CPDIV' . $randomNum }}">Quiz</h3>
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
                                                    <div class="edit-pmu-action">
                                                        {{-- <a id="addQuestionNew"> Add Question</a> --}}
                                                        <a href="{{ url('admin/delete-question/' . $data->id) }}" onclick="return confirm('Are you sure you want to delete this question?');"> Delete
                                                            Section</a>
                                                    </div>
                                                </div>

                                                <div class="collapse" id="{{ 'CPDIV' . $randomNum }}">
                                                    <div class="pmu-edit-questionnaire-box">
                                                        <div class="pmu-edit-label">
                                                            <div class="pmu-q-badge">Q</div>
                                                        </div>
                                                        <div class="pmu-edit-questionnaire-content">
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter Question Title" name="quiz_question"
                                                                value="{{ $data->title }}">
                                                        </div>
                                                    </div>

                                                    <div class="pmu-answer-option-list">
                                                        <?php
                                                        $options = \App\Models\ChapterQuizOption::where('quiz_id', $data->id)->get();
                                                        ?>
                                                        <?php $s_no = 'A'; ?>
                                                        @foreach ($options as $item)
                                                            <div class="pmu-answer-box">
                                                                <div class="pmu-edit-questionnaire-ans">
                                                                    <div class="pmu-edit-ans-label">
                                                                        <div class="a-badge">{{ $s_no }}</div>
                                                                    </div>
                                                                    <div class="pmu-edit-questionnaire-text">
                                                                        <input type="text" class="form-control {{ $s_no }}"
                                                                            placeholder="Type Here..." name="answer"
                                                                            value="{{ $item->answer_option_key }}">
                                                                            <div class="update-remove-action">
                                                                                <a class="update-text edit-option" id="edit-option" data-id="{{ $item->id }}"
                                                                                    data-param="{{ $s_no }}">Update</a>
                                                                                <a class="remove-text" href="{{ url('delete_option2/'. $item->id) }}" onclick="return confirm('Are you sure you want to delete this question?');">Remove</a>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php $s_no++; ?>
                                                        @endforeach
                                                        <div id="newinputquizListing"></div>
                                                        <div class="pmu-add-answer-info">
                                                            <a class="add-answer" id="SaveOption">Save</a>
                                                            <a class="add-answer" id="addListingOption">Add Answer</a>
                                                        </div>
                                                    </div>

                                                    <div class="pmu-answer-option-action">
                                                        <div class="pmu-answer-form-select">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <div class="form-group">
                                                                        <select class="form-control">
                                                                            <option>Choose Correct Answer</option>
                                                                            @foreach ($options as $item)
                                                                                <option name="{{ $item->id }}"
                                                                                    value="{{ $item->answer_option_key }}">
                                                                                    {{ $item->answer_option_key }}</option>
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
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="edit-pmu-form-item" id="DIV2">
                                                    <form method="POST" action="{{ route('Home.SaveQuestion') }}" class="pt-4"
                                                        id="Form_Quiz_2" enctype="multipart/form-data">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                        <input type="hidden" name="type" id="quiz" value="quiz" />
                                                        <input type="hidden" name="chapter_id" id="chapter_id" value="1" />
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
                                                                <a href=""> Add Question</a>
                                                                <a href=""> Delete Section</a>
                                                            </div>
                                                        </div>
                
                                                        <div class="pmu-edit-questionnaire-box">
                                                            <div class="pmu-edit-label">
                                                                <div class="pmu-q-badge">Q</div>
                                                            </div>
                                                            <div class="pmu-edit-questionnaire-content">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter Question Title" name="questions[0][text]"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="pmu-answer-option-list">
                                                            <div class="pmu-answer-box">
                                                                <div class="pmu-edit-questionnaire-ans">
                                                                    <div class="pmu-edit-questionnaire-text">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Type Here..." name="questions[0][options][]" value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="newinputquiz"></div>
                
                                                            <div class="pmu-add-answer-info">
                                                                <a class="add-answer add_option" id="add-option">Add Answer</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div> --}}
                                            </div>
                                        @elseif($data->type == 'assignment')
                                            @php
                                                $randomNum = rand(0000, 9999);
                                            @endphp
                                            <div class="edit-pmu-form-item" >
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
                                                            data-bs-target="#{{ 'CPDIV' . $randomNum }}">Assignment</h3>
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
                                                                    <span class="pmu-drive-logo"><img src="{!! url('assets/website-images/drive.svg') !!}"></span>
                                                                </div>
                                                            </div>
                                                        </div>
            
                                                        <div class="pmu-answer-box">
                                                            <div class="pmu-edit-questionnaire-ans">
                                                                <div class="pmu-edit-questionnaire-text">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Paste Dropbox Link To Receive Assignment From Student "
                                                                        name="">
                                                                    <span class="pmu-drive-logo"><img src="{!! url('assets/website-images/dropbox.svg') !!}"></span>
                                                                </div>
                                                            </div>
                                                        </div>
            
            
            
                                                        <div class="pmu-answer-box">
                                                            <div class="pmu-edit-questionnaire-ans">
                                                                <div class="pmu-edit-questionnaire-text">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Paste OneDrive Link To Receive Assignment From Student "
                                                                        name="">
                                                                    <span class="pmu-drive-logo"><img src="{!! url('assets/website-images/onedrive.svg') !!}"></span>
                                                                </div>
                                                            </div>
                                                        </div>
            
            
                                                        <div class="pmu-answer-box">
                                                            <div class="pmu-edit-questionnaire-ans">
                                                                <div class="pmu-edit-questionnaire-text">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Paste Drive Link To Receive Assignment From Student "
                                                                        name="">
                                                                    <span class="pmu-drive-logo"><img src="{!! url('assets/website-images/link-icon.svg') !!}"></span>
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
                                            <div class="edit-pmu-form-item" >
                                                <div class="edit-pmu-heading">
                                                    <div class="edit-pmu-text">
                                                        <h3 data-bs-toggle="collapse"
                                                        data-bs-target="#{{ 'CPDIV' . $randomNum }}">Survey</h3>
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
                                        <input type="hidden" name="type" id="type" value="video" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="1" />
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
                                                                            src="{!! url('assets/website-images/upload.svg') !!}"> Click here
                                                                        to Upload</span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <h4>Video Decription</h4>
                                                        <textarea type="text" class="form-control" name="video_decription" placeholder="Video Decription"></textarea>
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
                                        <input type="hidden" name="type" id="pdf" value="pdf" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="1" />
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
                                                                            src="{!! url('assets/website-images/upload.svg') !!}"> Click here
                                                                        to Upload</span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <h4>PDF Decription</h4>
                                                        <textarea type="text" class="form-control" name="PDF_decription" placeholder="PDF Decription"></textarea>
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
                                        <input type="hidden" name="type" id="quiz" value="quiz" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="1" />
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

                                        {{-- <div class="pmu-edit-questionnaire-box">
                                            <div class="pmu-edit-label">
                                                <div class="pmu-q-badge">Q</div> 
                                            </div>
                                            <div class="pmu-edit-questionnaire-content">
                                                <label for="questions[0][text]"></label>
                                                <input type="text" class="form-control" placeholder="Enter Question Title" name="questions[0][text]" value="What is the primary purpose of a tattoo consultation?">
                                            </div>
                                        </div> --}}

                                        <div class="questions">
                                            <div class="question">
                                                <div class="pmu-edit-questionnaire-box">
                                                    <div class="pmu-edit-label">
                                                        <div class="pmu-q-badge">Q</div> 
                                                    </div>
                                                    <div class="pmu-edit-questionnaire-content">
                                                        {{-- <label for="questions[0][text]"></label> --}}
                                                        <input type="text" class="form-control" placeholder="Enter Question Title" name="questions[0][text]">
                                                    </div>
                                                </div>
                                                {{-- <label for="questions[0][text]">Question:</label>
                                                <input type="text" name="questions[0][text]" class="form-control" required> --}}
                                    
                                                <div class="pmu-answer-option-list">
                                                    <div class="pmu-answer-box">
                                                        <div class="pmu-edit-questionnaire-ans">
                                                            <div class="pmu-edit-ans-label">
                                                                <div class="a-badge">A</div>
                                                            </div>
                                                            <div class="pmu-edit-questionnaire-text">
                                                                <input type="text" class="form-control" placeholder="Type Here..." name="questions[0][options][]">
                                                                {{-- <span class="remove-text">Remove</span> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="pmu-answer-box">
                                                        <div class="pmu-edit-questionnaire-ans">
                                                            <div class="pmu-edit-ans-label">
                                                                <div class="a-badge">B</div>
                                                            </div>
                                                            <div class="pmu-edit-questionnaire-text">
                                                                <input type="text" class="form-control" placeholder="Type Here..." name="questions[0][options][]">
                                                                {{-- <span class="remove-text">Remove</span> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="pmu-answer-box">
                                                        <div class="pmu-edit-questionnaire-ans">
                                                            <div class="pmu-edit-ans-label">
                                                                <div class="a-badge">C</div>
                                                            </div>
                                                            <div class="pmu-edit-questionnaire-text">
                                                                <input type="text" class="form-control" placeholder="Type Here..." name="questions[0][options][]">
                                                                {{-- <span class="remove-text">Remove</span> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="pmu-answer-box">
                                                        <div class="pmu-edit-questionnaire-ans">
                                                            <div class="pmu-edit-ans-label">
                                                                <div class="a-badge">D</div>
                                                            </div>
                                                            <div class="pmu-edit-questionnaire-text">
                                                                <input type="text" class="form-control" placeholder="Type Here..." name="questions[0][options][]">
                                                                {{-- <span class="remove-text">Remove</span> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="button" class="remove-question" >Remove Question</button>
                                                </div>
                                                {{-- <label for="questions[0][options][]">Options (comma-separated):</label>
                                                <input type="text" name="questions[0][options][]" class="form-control" required>

                                                <label for="questions[0][options][]">Options (comma-separated):</label>
                                                <input type="text" name="questions[0][options][]" class="form-control" required>

                                                <label for="questions[0][options][]">Options (comma-separated):</label>
                                                <input type="text" name="questions[0][options][]" class="form-control" required>

                                                <label for="questions[0][options][]">Options (comma-separated):</label>
                                                <input type="text" name="questions[0][options][]" class="form-control" required>
                                    
                                                <button type="button" class="remove-question" >Remove Question</button>  --}}
                                            </div>
                                        </div>
                                    
                                        {{-- <button type="button" id="addQuestion">Add Question</button> --}}
                                        <div class="pmu-add-answer-info">
                                            <a class="add-answer" id="addQuestion">Add Question</a>
                                        </div>
                                    </form>
                                </div>

                                <div class="edit-pmu-form-item" id="assignment_div">
                                    <form method="POST" action="{{ route('Home.SaveQuestion') }}" class="pt-4"
                                        id="Form_assignment" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="type" id="assignment" value="assignment" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="1" />
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
                                                        <span class="pmu-drive-logo"><img src="{!! url('assets/website-images/drive.svg') !!}"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Paste Dropbox Link To Receive Assignment From Student "
                                                            name="">
                                                        <span class="pmu-drive-logo"><img src="{!! url('assets/website-images/dropbox.svg') !!}"></span>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Paste OneDrive Link To Receive Assignment From Student "
                                                            name="">
                                                        <span class="pmu-drive-logo"><img src="{!! url('assets/website-images/onedrive.svg') !!}"></span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control"
                                                            placeholder="Paste Drive Link To Receive Assignment From Student "
                                                            name="">
                                                        <span class="pmu-drive-logo"><img src="{!! url('assets/website-images/link-icon.svg') !!}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </form>
                                </div>

                                <div class="edit-pmu-form-item" id="survey_div">
                                    <form method="POST" action="{{ route('Home.SaveQuestion') }}" class="pt-4"
                                        id="Form_Survey" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="type" id="survey" value="survey" />
                                        <input type="hidden" name="chapter_id" id="chapter_id" value="1" />
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
                                        <button class="add-answer" id="radio">Add Question</button>
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

    <!-- Style of Remove button -->
    <style>
        /* Style for the Add Option button */
        .add-option {
            margin-top: 5px; /* Add some top margin to separate from options */
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .add-option:hover {
            background-color: #45a049;
        }
        .remove-question {
            margin-top: 10px; /* Adjust the value as needed */
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    
        .remove-question:hover {
            background-color: #d32f2f;
        }
    </style>

<script>
    $(document).ready(function() {
        var questionCounter = 1;

        // Show/hide the Remove Question button based on the number of questions
        function updateRemoveButtonVisibility() {
            var questionCount = $('.question').length;
            $('.remove-question').prop('disabled', questionCount === 1);
        }

        // Initial update
        updateRemoveButtonVisibility();

        // Add question field
        $('#addQuestion').click(function() {

            var newQuestion = $('.question').first().clone();
                    newQuestion.find(':input').val('');
                    $('.questions .question:last-child .lop').empty();
    
                    // Adjust the input names using questionCounter
                    newQuestion.find('input[type="text"]').each(function() {
                        var oldName = $(this).attr('name');
                        var newName = oldName.replace(/\[(\d+)\]/, '[' + questionCounter + ']');
                        $(this).attr('name', newName);
                        //newQuestion.find('.lop').empty(); // Clear existing options
                    });


           /* var newQuestion = $('.question').first().clone();
            newQuestion.find(':input').val('');
            newQuestion.find('.lop').empty(); // Clear existing options*/

            newQuestion.appendTo('.questions');
            questionCounter++;

            // Update the visibility of the Remove Question button
            updateRemoveButtonVisibility();
        });

        // Remove question field
        $(document).on('click', '.remove-question', function() {
            $(this).closest('.question').remove();
            updateRemoveButtonVisibility();
        });

        // Remove option field
        $(document).on('click', '.remove-option', function() {
            $(this).closest('.options').remove();
        });

        // ... other code ...
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
                url: 'http://127.0.0.1:8000/admin/update_option_list',
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

    {{-- <script>
        $(document).ready(function() {
            var questionCounter = 1;

            $('#addQuestion').click(function() {
                var newQuestion = $('.question').first().clone();
                newQuestion.find(':input').val('');

                // Update input names with the new questionCounter value
                newQuestion.find('[name]').each(function() {
                    var oldName = $(this).attr('name');
                    var newName = oldName.replace(/\[(\d+)\]/, '[' + questionCounter + ']');
                    $(this).attr('name', newName);
                });

                newQuestion.appendTo('.questions');
                questionCounter++;
            });

            // Remove question field
            $(document).on('click', '.remove-question', function() {
                $(this).closest('.question').remove();
            });
        });
    </script> --}}
    

    {{-- <script>
        $(document).ready(function() {
            var questionCounter = 0; // Initialize with existing question count
    
            $('#addQuestion').click(function() {
                var newQuestion = $('.question').first().clone();
                newQuestion.find(':input').val('');
    
                // Update input names with the new questionCounter value
                newQuestion.find('[name]').each(function() {
                    var oldName = $(this).attr('name');
                    var newName = oldName.replace(/\[(\d+)\]/, '[' + questionCounter + ']');
                    $(this).attr('name', newName);
                });
    
                newQuestion.appendTo('.questions');
                questionCounter++;
            });
        });
    </script> --}}

{{-- <script>
    $(document).ready(function() {
        var questionCounter = 1;

        // Add question field
        $('#addQuestion2').click(function() {
            var newQuestion = $('.question').first().clone();
            newQuestion.find(':input').val('');
            newQuestion.find('.option').val('');
            newQuestion.find('.lop').empty(); // Clear existing options

            // Append a remove button to the new question
            var removeQuestionButton = $('<button class="remove-question">Remove Question</button>');
            newQuestion.append(removeQuestionButton);

            // Append a remove button to each option in the new question
            newQuestion.find('.remove-option').remove(); // Remove any existing remove-option buttons
            newQuestion.find('.options').each(function() {
                var removeOptionButton = $('<button class="remove-option">Remove Option</button>');
                $(this).append(removeOptionButton);
            });

            newQuestion.appendTo('.questions');
            questionCounter++;
        });

        // Remove question field
        $(document).on('click', '.remove-question', function() {
            $(this).closest('.question').remove();
        });

        // Remove option field
        $(document).on('click', '.remove-option', function() {
            $(this).closest('.options').remove();
        });

        // Add option field
        $(document).on('click', '.add-option', function() {
            var optionField = $('<input type="text" name="questions[' + (questionCounter - 1) + '][options][]" class="form-control option" required>');
            $(this).closest('.question').find('.lop').append(optionField);
        });

        // Save questions
        $('#saveQuestions').click(function() {
            var formData = $('#questionForm').serializeArray();

            $.ajax({
                url: '/questions', // Change this URL to your Laravel route
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Handle success response
                    console.log(response);
                },
                error: function(error) {
                    // Handle error response
                    console.error('Error:', error);
                }
            });
        });
    });
</script> --}}


    {{-- <script>
        $(document).ready(function() {
            var questionCounter = 1;
            var clickCounter = 0;
    
            $('#addQuestion2').on('click', function() {
                if (clickCounter < 5) {
                    var newQuestion = $('.question').first().clone();
                    newQuestion.find(':input').val('');
                    $('.questions .question:last-child .lop').empty();

                    var removeQuestionButton = $('<button class="remove-question">Remove Question</button>');
                    newQuestion.append(removeQuestionButton);
    
                    newQuestion.find('.remove-option').remove(); // Remove any existing remove-option buttons
                    // Adjust the input names using questionCounter
                    newQuestion.find('input[type="text"]').each(function() {
                        newQuestion.find('.remove-option').remove(); // Remove any existing remove-option buttons
                        var oldName = $(this).attr('name');
                        var newName = oldName.replace(/\[(\d+)\]/, '[' + questionCounter + ']');
                        $(this).attr('name', newName);
                        //newQuestion.find('.lop').empty(); // Clear existing options
                    });

                    newQuestion.appendTo('.questions');
                    questionCounter++;
                    clickCounter++;
    
                    if (clickCounter === 5) {
                        $('#addQuestion2').prop('disabled', true);
                    }
                }
            });
            var possible = 'AB' + Math.floor(Math.random() * (100 - 1) + 1);
            $(document).on('click', '.add-option', function() {
                
                // var optionField = '<div class="pmu-answer-box" id="' + possible +
                //     '" > <div class="pmu-edit-questionnaire-ans">' +
                //     '<div class="pmu-edit-questionnaire-text">' +
                //     '<input type="text" class="form-control" placeholder="Type Here..." name="questions[' + (questionCounter - 1) + '][options][]">' +
                //     '<span class="remove-text remove_newoption" id="' + possible + '">Remove</span>' +
                //     '</div>' +
                //     '</div>' + '</div>';
                var optionField = $('<input type="text" name="questions[' + (questionCounter - 1) + '][options][]" class="form-control lop" required>');
                $(this).before(optionField);
            });
        });
    </script> --}}

    <script>
       
    </script>

    <!-- Append Data -->
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

    <!-- Add input New Form field(Append) -->
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

    <!-- Add input New Form field(Append) -->
    <script>
        $(document).ready(function() {
            $("#SaveOption").hide();
            
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
