//  Edit Question ajax 
$('.edit-question-first').on('click', function () {
    var question_id = $(this).attr("data-id");
    var question_param = $(this).attr("data-param");
    let selector = '.' + question_param + question_id;
    var question = $(selector).val();
    $.ajax({
        url: arkansasUrl + '/admin/update_question_list',
        method: 'GET',
        data: {
            question_id: question_id,
            question: question
        },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (data) {
            if (data == 1) {
                location.reload();
            }
        }
    });
});


// Append new queston and option field using jquery
var questionCounter = 0;

// Show/hide the Remove Question button based on the number of questions
function updateRemoveButtonVisibility() {
    // var questionCount = $('.question').length;
    // $('.remove-question').prop('disabled', questionCount === 0);
}

// Initial update
updateRemoveButtonVisibility();

// Add question field
$(document).on('click', '.add-question-create', function () {
    let id = ($(this).attr('id').split('-'))[1];
    questionCounter++;
    let oplength = $('.options .pmu-answer-option-list .hidden'+id+questionCounter).length;
    var html = `<div class="question">
            <div class="pmu-edit-questionnaire-box">
                <div class="pmu-edit-label">
                    <div class="pmu-q-badge">Q</div>
                </div>
                <div class="pmu-edit-questionnaire-content">
                    <input type="text" class="form-control"
                        placeholder="Enter Question Title" name="questions[${id}][${questionCounter}][text]" required>
                </div>
            </div>
            <div class="pmu-edit-questionnaire-box">
                <div class="pmu-edit-label">
                    <div class="pmu-q-badge">M</div>
                </div>
                <div class="pmu-edit-questionnaire-content">
                    <input type="number" class="form-control" placeholder="Enter marks" name="questions[${id}][${questionCounter}][marks]" required>
                </div>
            </div>
            <div class="options">
                <div class="pmu-answer-option-list">
                    <input type="hidden" class="hidden${id}${questionCounter}" value="0">
                    <div class="pmu-answer-box">
                        <div class="pmu-edit-questionnaire-ans">
                            <div class="pmu-edit-questionnaire-text d-flex">
                                <input type="text" class="form-control" placeholder="Type Here..." name="questions[${id}][${questionCounter}][options][]" required>
                                <div class="pmucheckbox">
                                    <input type="checkbox" id="answer-option-${oplength}-${questionCounter}-${id}" class="" name="questions[${id}][${questionCounter}][correct][${oplength}]" value="1">
                                    <label for="answer-option-${oplength}-${questionCounter}-${id}"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="add-option" id="addOption-${id}-${questionCounter}">Add Option</button>
            <button type="button" class="remove-question" data-id="lok">Remove Question</button>
        </div>`;

    $('.questions-'+id).append(html);
    
});

// Add option field

let optionsCount = 0;

$(document).on('click', '.add-option', function () {
    let id = ($(this).attr('id').split('-'));
    let oplength = $('.options .pmu-answer-option-list .hidden'+id[1]+questionCounter).length;
    var op_html = `<div class="options">
                        <div class="pmu-answer-option-list">
                        <input type="hidden" class="hidden${id[1]}${questionCounter}" value="0">
                            <div class="pmu-answer-box">
                                <div class="pmu-edit-questionnaire-ans">
                                    <div class="pmu-edit-questionnaire-text d-flex">
                                        <input type="text" class="form-control" placeholder="Type Here..." name="questions[${id[1]}][${id[2] ?? questionCounter}][options][]" required>
                                        <div class="pmucheckbox">
                                            <input type="checkbox" class="" name="questions[${id[1]}][${id[2] ?? questionCounter}][correct][${oplength}]" id="answer-option-${oplength}-${id[2] ?? questionCounter}-${id[1]}" value="1">
                                            <label for="answer-option-${oplength}-${id[2] ?? questionCounter}-${id[1]}"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="remove-option" style="margin-bottom: 5px;">Remove Option</button>
                    </div>`;
    $(this).siblings('.options').append(op_html);
});

$(document).on('click', '.add-survey-option', function () {
    let id = ($(this).attr('id').split('-'));
    var op_html = `<div class="pmu-answer-box">
            <div class="pmu-edit-questionnaire-ans">
                <div class="pmu-edit-questionnaire-text">
                    <input type="text" class="form-control"
                        placeholder="Type Here..." name="survey_question[${id[1]}][${questionSurveyCounter}][options][]" value=""
                        required>
                </div>
            </div>
            <button type="button" class="remove-survey-option" style="margin-bottom: 5px;">Remove Option</button>
        </div>`;
        $(this).siblings(".survey-op-"+id[1] + '-' + id[2]).append(op_html);
});

// Remove question field
$(document).on('click', '.remove-question', function () {
    $(this).closest('.question').remove();
    updateRemoveButtonVisibility();
});

// Remove option field
$(document).on('click', '.remove-option', function () {
    var optionsContainer = $(this).closest('.options').remove();
    // optionsContainer.find('input[type="text"]').last().remove(); // Remove the last option input
    // $(this).remove(); // Remove the "Remove Option" button
});

// Remove survey option field
$(document).on('click', '.remove-survey-option', function () {
    $(this).closest('.pmu-answer-box').remove();
});

// EditOption ajax
let _token = $("input[name='_token']").val();
$('.edit-option').on('click', function () {
    var option_id = $(this).attr("data-id");
    var option_param = $(this).attr("data-param");
    let selector = '.' + option_param + option_id;
    var option = $(selector).val();
    $.ajax({
        url: arkansasUrl + '/admin/update_option_list',
        method: 'GET',
        data: {
            option_id: option_id,
            option: option
        },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (data) {
            if (data == 1) {
                location.reload();
            }
        }
    });
});

$(document).on('click', '.SaveOption', function () {
    var quiz_id = $(this).attr("data-quiz-id");
    // $(".newop"+quiz_id).val();
    var option_val = $(".newop"+quiz_id).map(function() {
        return this.value;
    }).get();
    var answer_val = $(`input[class="answerAddCheckbox${quiz_id}"]`).map(function() {
        if($(this).is(":checked")) return 1;
        else return 0;
    }).get();
    $.ajax({
        url: arkansasUrl + '/admin/add-option',
        method: 'GET',
        data: {
            quiz_id,
            option_val,
            answer_val
        },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (data) {
            if (data == 1) {
                location.reload();
                toastr.success('New answer added successfully.');
            }
        }
    });
});

$(document).on('change', '.ordering-select-function', function () {
    var id = $(this).attr("data-id");
    var chapterid = $(this).attr("data-chapter-id");
    var val = $(this).val();
    $.ajax({
        url: arkansasUrl + '/admin/change-ordering/' + chapterid + '/' + id + '/' + val,
        method: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (data) {
            if (data == 1) {
                location.reload();
                toastr.success("Ordering changed.");
            }
        }
    });
});

$(document).on('change', '.answerEditCheckbox', function () {
    var id = $(this).attr("data-answer-id");
    var val = $(this).is(":checked");
    if(val) val = 1;
    else val = 0;
    $.ajax({
        url: arkansasUrl + '/admin/change-answer-option/' + id + '/' + val,
        method: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (data) {
            if (data == 1) {
                toastr.success("Answer changed.");
            }
        }
    });
});

// Append File name
$(document).on('change', 'input[type="file"]',function (e) {
    var geekss = e.target.files[0].name;
    let id = ($(this).attr('id').split('-'))[1];
    if(($(this).attr('id').split('-'))[0] == 'video')
        $('#video_file_name-'+id).text(geekss);
});

$(document).on('change', 'input[type="file"]',function (e) {
    var geekss = e.target.files[0].name;
    let id = ($(this).attr('id').split('-'))[1];
    if(($(this).attr('id').split('-'))[0] == 'pdf_file')
        $('#pdf_file_name-'+id).text(geekss);
});


// Submit form And Mange all Hide and Show field(Append)
$(document).ready(function () {

    $("#chapterName").html(($(".chapter-item.active").attr('data-index')) ? "Chapter" + ' ' + $(".chapter-item.active").attr('data-index') : "Chapter");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "disableTimeOut": true,
    };

    // $("#video_div").hide();
    // $("#pdf_div").hide();
    // $("#quiz_div").hide();
    // $("#assignment_div").hide();
    // $("#survey_div").hide();

    let type_arr = [];

    $(document).on('click', '.hhh', function () {
        var let_id = '#' + $(this).attr('id');
        $(let_id).remove();
    });

    let htmlForm = ``;
    let countForm = 0;
    $("#radio").click(function () {
        let div_type = $('input[name="questionnairetype"]:checked').val();
        type_arr.push(div_type);
        $('#type_mode').val(div_type);
        if(div_type == "" || div_type == null){
            return;
        }
        $('.survey-btn').removeClass('d-none');

        if (div_type == 'Video') {
            htmlForm = `<div class="edit-pmu-form-item" id="video_div">
                                <div class="edit-pmu-heading">
                                    <div class="edit-pmu-text">
                                        <h3>Video</h3>
                                        <div class="edit-pmu-checkbox-list">
                                            <ul>
                                                <li>
                                                    <div class="pmucheckbox">
                                                        <input type="checkbox" id="Prerequisite-${countForm}" value="1" name="prerequisite[${countForm}]">
                                                        <label for="Prerequisite-${countForm}">Prerequisite</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="edit-pmu-text">
                                        <div class="pmu-edit-questionnaire-ans">
                                            <div class="pmu-edit-questionnaire-text">
                                                <input type="number" class="form-control" min="1" step="0" placeholder="Assign serial order" name="queue[${countForm}]" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="edit-pmu-action">
                                        <a href="javascript:void(0)" class="dlt-div" data-id="video_div" data-type="Video"> Delete Section</a>
                                    </div>
                                </div>
                                <input type="hidden" name="type[${countForm}]" id="type" value="video" />
                                <div class="edit-pmu-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Upload Video</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="video[${countForm}]" id="video-${countForm}"
                                                        class="uploadsignature addsignature" required>
                                                    <label for="video-${countForm}">
                                                        <div class="signature-text">
                                                            <span id="video_file_name-${countForm}">
                                                                <img src="${arkansasUrl}/assets/website-images/upload.svg"> Click here to Upload</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Video Description</h4>
                                                <textarea type="text" class="form-control" name="video_description[${countForm}]" placeholder="Video Description" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
            countForm += 1;
        } else if (div_type == 'PDF') {
            htmlForm = `<div class="edit-pmu-form-item" id="pdf_div">
                            <div class="edit-pmu-heading">
                                <div class="edit-pmu-text">
                                    <h3>PDF</h3>
                                    <div class="edit-pmu-checkbox-list">
                                        <ul>
                                            <li>
                                                <div class="pmucheckbox">
                                                    <input type="checkbox" id="Prerequisite-${countForm}" value="1" name="prerequisite[${countForm}]">
                                                    <label for="Prerequisite-${countForm}">Prerequisite</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="edit-pmu-text">
                                    <div class="pmu-edit-questionnaire-ans">
                                        <div class="pmu-edit-questionnaire-text">
                                            <input type="number" class="form-control" min="1" step="0" placeholder="Assign serial order" name="queue[${countForm}]" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-pmu-action">
                                    <a href="javascript:void(0)" class="dlt-div" data-id="pdf_div" data-type="PDF"> Delete Section</a>
                                </div>
                            </div>
                            <input type="hidden" name="type[${countForm}]" id="pdf" value="pdf" />
                            <div class="edit-pmu-section">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h4>Upload PDF</h4>
                                            <div class="upload-signature">
                                                <input type="file" name="pdf[${countForm}]" id="pdf_file-${countForm}"
                                                    class="uploadsignature addsignature" required>
                                                <label for="pdf_file-${countForm}">
                                                    <div class="signature-text">
                                                        <span id="pdf_file_name-${countForm}"><img
                                                                src="${arkansasUrl}/assets/website-images/upload.svg"> Click here
                                                            to Upload</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h4>PDF Description</h4>
                                            <textarea type="text" class="form-control" name="PDF_description[${countForm}]" placeholder="PDF Description" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
            countForm += 1;
        } else if (div_type == 'Quiz') {
            let oplength = $('.options .pmu-answer-option-list .hidden'+countForm+questionCounter).length;
            htmlForm = `<div class="edit-pmu-form-item" id="quiz_div">
                            <input type="hidden" name="type[${countForm}]" id="quiz" value="quiz" />
                            <div class="edit-pmu-heading">
                                <div class="edit-pmu-text">
                                    <h3>Quiz</h3>
                                    <div class="edit-pmu-checkbox-list">
                                        <ul>
                                            <li>
                                                <div class="pmucheckbox">
                                                    <input type="checkbox" id="Prerequisite-${countForm}" value="1" name="prerequisite[${countForm}]">
                                                    <label for="Prerequisite-${countForm}">Prerequisite</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="edit-pmu-text">
                                    <div class="pmu-edit-questionnaire-ans">
                                        <div class="pmu-edit-questionnaire-text">
                                            <input type="number" class="form-control" min="1" step="0" placeholder="Assign serial order" name="queue[${countForm}]" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-pmu-action">
                                    <a href="javascript:void(0)" class="dlt-div" data-id="quiz_div" data-type="Quiz"> Delete Section</a>
                                </div>
                            </div>
                            <div class="questions-${countForm}">
                                <div class="question">

                                    <div class="pmu-edit-questionnaire-box">
                                        <div class="pmu-edit-label">
                                            <div class="pmu-q-badge">Q</div>
                                        </div>
                                        <div class="pmu-edit-questionnaire-content">
                                            <input type="text" class="form-control"
                                                placeholder="Enter Question Title" name="questions[${countForm}][${questionCounter}][text]" required>
                                        </div>
                                    </div>

                                    <div class="pmu-edit-questionnaire-box">
                                        <div class="pmu-edit-label">
                                            <div class="pmu-q-badge">M</div>
                                        </div>
                                        <div class="pmu-edit-questionnaire-content">
                                            <input type="number" class="form-control" placeholder="Enter marks" name="questions[${countForm}][${questionCounter}][marks]" required>
                                        </div>
                                    </div>

                                    <div class="options">
                                        <div class="pmu-answer-option-list">
                                        <input type="hidden" class="hidden${countForm}${questionCounter}" value="0">
                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text d-flex">
                                                        <input type="text" class="form-control" placeholder="Type Here..." name="questions[${countForm}][${questionCounter}][options][]" required>
                                                        <div class="pmucheckbox">
                                                            <input type="checkbox" class="" name="questions[${countForm}][${questionCounter}][correct][${oplength}]" id="answer-option-${oplength}-${questionCounter}-${countForm}" value="1">
                                                            <label for="answer-option-${oplength}-${questionCounter}-${countForm}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="add-option" id="addOption-${countForm}-${questionCounter}">Add Option</button>
                                    <button type="button" class="remove-question" data-id="lok">Remove Question</button>
                                </div>
                            </div>
                            <div class="pmu-add-answer-info">
                                <a class="add-answer add-question-create" id="addQuestion-${countForm}">Add Question</a>
                            </div>
                        </div>`;
            countForm += 1;
        } else if (div_type == 'Assignment') {
            htmlForm = `<div class="edit-pmu-form-item" id="assignment_div">
                            <input type="hidden" name="type[${countForm}]" id="assignment" value="assignment" />
                            <div class="edit-pmu-heading">
                                <div class="edit-pmu-text">
                                    <h3>Assignment</h3>
                                    <div class="edit-pmu-checkbox-list">
                                        <ul>
                                            <li>
                                                <div class="pmucheckbox">
                                                    <input type="checkbox" id="Prerequisite-${countForm}" value="1" name="prerequisite[${countForm}]">
                                                    <label for="Prerequisite-${countForm}">Prerequisite</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="edit-pmu-text">
                                    <div class="pmu-edit-questionnaire-ans">
                                        <div class="pmu-edit-questionnaire-text">
                                            <input type="number" class="form-control" min="1" step="0" placeholder="Assign serial order" name="queue[${countForm}]" required>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="assignment[${countForm}]" id="assignment-${countForm}">

                                <div class="edit-pmu-action">
                                    <a href="javascript:void(0)" class="dlt-div" data-id="assignment_div" data-type="Assignment"> Delete Section</a>
                                </div>
                            </div>
                        </div>`;
            countForm += 1;
        } else if (div_type == 'Survey') {
            htmlForm = `<div class="edit-pmu-form-item" id="survey_div">
                                <input type="hidden" name="type[${countForm}]" id="survey" value="survey" />
                                <div class="edit-pmu-heading">
                                    <div class="edit-pmu-text">
                                        <h3>Survey</h3>
                                        <div class="edit-pmu-checkbox-list">
                                            <ul>
                                                <li>
                                                    <div class="pmucheckbox">
                                                        <input type="checkbox" id="Prerequisite-${countForm}" value="1" name="prerequisite[${countForm}]">
                                                        <label for="Prerequisite-${countForm}">Prerequisite</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="edit-pmu-checkbox-list">
                                            <ul>
                                                <li>
                                                    <div class="pmucheckbox-radio">
                                                        <input type="radio" id="Optional-${countForm}" value="0"
                                                            name="required_field[${countForm}]">
                                                        <label for="Optional-${countForm}">
                                                            Optional
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="pmucheckbox-radio">
                                                        <input type="radio" id="Mandatory-${countForm}" value="1"
                                                            name="required_field[${countForm}]">
                                                        <label for="Mandatory-${countForm}">
                                                            Mandatory
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="edit-pmu-text">
                                        <div class="pmu-edit-questionnaire-ans">
                                            <div class="pmu-edit-questionnaire-text">
                                                <input type="number" class="form-control" min="1" step="0" placeholder="Assign serial order" name="queue[${countForm}]" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="edit-pmu-action">
                                        <a href="javascript:void(0)" class="dlt-div" data-id="survey_div" data-type="Survey"> Delete Section</a>
                                    </div>
                                </div>
                                <div class="surveyQuestion-${countForm}">
                                    <div class="pmu-edit-questionnaire-box">
                                        <div class="pmu-edit-label">
                                            <div class="pmu-q-badge">Q</div>
                                        </div>
                                        <div class="pmu-edit-questionnaire-content">
                                            <input type="text" class="form-control"
                                                placeholder="Enter Question Title" name="survey_question[${countForm}][${questionSurveyCounter}][text]"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="pmu-answer-option-list survey-op-${countForm}-${questionSurveyCounter}">
                                        <div class="pmu-answer-box">
                                            <div class="pmu-edit-questionnaire-ans">
                                                <div class="pmu-edit-questionnaire-text">
                                                    <input type="text" class="form-control"
                                                        placeholder="Type Here..." name="survey_question[${countForm}][${questionSurveyCounter}][options][]" value=""
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pmu-answer-box">
                                            <div class="pmu-edit-questionnaire-ans">
                                                <div class="pmu-edit-questionnaire-text">
                                                    <input type="text" class="form-control"
                                                        placeholder="Type Here..." name="survey_question[${countForm}][${questionSurveyCounter}][options][]" value=""
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="add-survey-option" id="addOption-${countForm}-${questionSurveyCounter}">Add Option</button>
                                </div>
                                <div class="pmu-add-answer-info">
                                    <a class="add-answer addSurveyQuestion" id="addSurvey-${countForm}">Add more Question</a>
                                </div>
                            </div>`;
            countForm += 1;
        }

        $("#add-course-form").append(htmlForm);
    });

    $(document).on('click', '.dlt-div', function () {
        // alert('hello');
        let div_type = $(this).attr('data-id');
        let type = $(this).attr('data-type');

        $(this).closest('.edit-pmu-form-item').remove();

        let countVideo = $('#video_div').length;
        let countPdf = $('#pdf_div').length;
        let countQuiz = $('#quiz_div').length;
        let countAssignment = $('#assignment_div').length;
        let countSurvey = $('#survey_div').length;

        if (!countVideo && !countPdf && !countQuiz && !countAssignment && !countSurvey)
            $('.survey-btn').addClass('d-none');

        type_arr = type_arr.filter(function (item) {
            return item != type
        });
    });

    $("#formAddCourse").on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        let formData = new FormData(this);

        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: function () {
                toastr.info('Form submit.');
            },
            success: function (response) {
                if (response.status == 201) {
                    location.reload();
                    toastr.success(response.message);
                    // return false;
                }

                if (response.status == 200) {
                    toastr.error(response.message);
                    return false;
                }
            }
        });
    });
});

var questionSurveyCounter = 0;

$(document).on('click', '.addSurveyQuestion', function () {
    let id = ($(this).attr('id').split('-'))[1];
    questionSurveyCounter++;
    let oplength = $('.options .pmu-answer-option-list .hidden'+id+questionSurveyCounter).length;
    var html = `<div class="pmu-edit-questionnaire-box">
                    <div class="pmu-edit-label">
                        <div class="pmu-q-badge">Q</div>
                    </div>
                    <div class="pmu-edit-questionnaire-content">
                        <input type="text" class="form-control"
                            placeholder="Enter Question Title" name="survey_question[${id}][${questionSurveyCounter}][text]"
                            value="">
                    </div>
                </div>
                <div class="pmu-answer-option-list survey-op-${id}-${questionSurveyCounter}">
                    <div class="pmu-answer-box">
                        <div class="pmu-edit-questionnaire-ans">
                            <div class="pmu-edit-questionnaire-text">
                                <input type="text" class="form-control"
                                    placeholder="Type Here..." name="survey_question[${id}][${questionSurveyCounter}][options][]" value=""
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="pmu-answer-box">
                        <div class="pmu-edit-questionnaire-ans">
                            <div class="pmu-edit-questionnaire-text">
                                <input type="text" class="form-control"
                                    placeholder="Type Here..." name="survey_question[${id}][${questionSurveyCounter}][options][]" value=""
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="add-survey-option" id="addOption-${id}-${questionSurveyCounter}">Add Option</button>`;

    $('.surveyQuestion-'+id).append(html);
    
});

// Add New input Form field(Append)
$(document).ready(function () {
    $(".SaveOption").hide();

    $("#addQuizOption").click(function () {

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

    $(document).on('click', "#addListingOption", function () {
        let id = $(this).attr('data-id');
        $("#SaveOption"+id).show();
        var possible = 'AB' + Math.floor(Math.random() * (100 - 1) + 1);
        newRowAdd =
            '<div class="pmu-answer-box" id="' + possible +
            '" > <div class="pmu-edit-questionnaire-ans">' +
            '<div class="pmu-edit-questionnaire-text d-flex">' +
            '<input type="text" class="form-control newop'+id+'" placeholder="Type Here..." name="option[' +
            possible + ']">' +
            '<span class="remove-text remove_newoption mx-5" data-remove-id="' + id + '" id="' + possible + '">Remove</span>' +
            '<div class="pmucheckbox"> <input type="checkbox" class="answerAddCheckbox'+id+'" name="answer[' + possible + ']" id="answer-option-'+possible+'" value="1"> <label for="answer-option-'+possible+'"></label> </div>' +
            '</div>' +
            '</div>' + '</div>';
        $('#newinputquizListing'+id).append(newRowAdd);
    });

    $(document).on('click', "#addListingSurveyOption", function () {
        let id = $(this).attr('data-id');
        $("#SaveOption"+id).show();
        var possible = 'AB' + Math.floor(Math.random() * (100 - 1) + 1);
        newRowAdd =
            '<div class="pmu-answer-box" id="' + possible +
            '" > <div class="pmu-edit-questionnaire-ans">' +
            '<div class="pmu-edit-questionnaire-text">' +
            '<input type="text" class="form-control newop'+id+'" placeholder="Type Here..." name="option[' +
            possible + ']">' +
            '<span class="remove-text remove_surveyoption" data-remove-id="' + id + '" id="' + possible + '">Remove</span>' +
            '</div>' +
            '</div>' + '</div>';
        $('#newinputSurveyListing'+id).append(newRowAdd);
    });

    $(document).on('click', '.remove_newoption', function () {
        var let_id = '#' + $(this).attr('id');
        let save_btn_remove_id = $(this).attr('data-remove-id');
        $(let_id).remove();
        let lengthAnswerInput = $(`#newinputquizListing${save_btn_remove_id} .pmu-answer-box`).length;
        if(lengthAnswerInput == 0){
            $('#SaveOption'+save_btn_remove_id).hide();
        }
    });

    $(document).on('click', '.remove_surveyoption', function () {
        var let_id = '#' + $(this).attr('id');
        let save_btn_remove_id = $(this).attr('data-remove-id');
        $(let_id).remove();
        let lengthAnswerSurInput = $(`#newinputSurveyListing${save_btn_remove_id} .pmu-answer-box`).length;
        if(lengthAnswerSurInput == 0){
            $('#SaveOption'+save_btn_remove_id).hide();
        }
    });
});