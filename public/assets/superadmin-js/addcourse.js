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
            <div class="options">
                <div class="pmu-answer-option-list">
                    <div class="pmu-answer-box">
                        <div class="pmu-edit-questionnaire-ans">
                            <div class="pmu-edit-questionnaire-text">
                                <input type="text" class="form-control" placeholder="Type Here..." name="questions[${id}][${questionCounter}][options][]" required>
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
    var op_html = `<div class="options">
                        <div class="pmu-answer-option-list">
                            <div class="pmu-answer-box">
                                <div class="pmu-edit-questionnaire-ans">
                                    <div class="pmu-edit-questionnaire-text">
                                        <input type="text" class="form-control" placeholder="Type Here..." name="questions[${id[1]}][${id[2] ?? questionCounter}][options][]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="remove-option" style="margin-bottom: 5px;">Remove Option</button>
                    </div>`;

    $(this).siblings('.options').append(op_html);
    
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
    $.ajax({
        url: arkansasUrl + '/admin/add-option',
        method: 'GET',
        data: {
            quiz_id,
            option_val
        },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (data) {
            console.log(data);
            if (data == 1) {
                location.reload();
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
                                    </div>
                                    <div class="edit-pmu-text">
                                        <div class="pmu-edit-questionnaire-ans">
                                            <div class="pmu-edit-questionnaire-text">
                                                <input type="number" class="form-control" min="1" step="0" placeholder="Queue no." name="queue[${countForm}]" required>
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
                                </div>
                                <div class="edit-pmu-text">
                                    <div class="pmu-edit-questionnaire-ans">
                                        <div class="pmu-edit-questionnaire-text">
                                            <input type="number" class="form-control" min="1" step="0" placeholder="Queue no." name="queue[${countForm}]" required>
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
            let len = $('#quiz_div').length;
            htmlForm = `<div class="edit-pmu-form-item" id="quiz_div">
                            <input type="hidden" name="type[${countForm}]" id="quiz" value="quiz" />
                            <div class="edit-pmu-heading">
                                <div class="edit-pmu-text">
                                    <h3>Quiz</h3>
                                    <div class="edit-pmu-checkbox-list">
                                        <ul>
                                            <li>
                                                <div class="pmucheckbox">
                                                    <input type="checkbox" id="Prerequisite-${countForm}" name="prerequisite[${countForm}]">
                                                    <label for="Prerequisite-${countForm}">Prerequisite</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="edit-pmu-text">
                                    <div class="pmu-edit-questionnaire-ans">
                                        <div class="pmu-edit-questionnaire-text">
                                            <input type="number" class="form-control" min="1" step="0" placeholder="Queue no." name="queue[${countForm}]" required>
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
                                    <div class="options">
                                        <div class="pmu-answer-option-list">
                                            <div class="pmu-answer-box">
                                                <div class="pmu-edit-questionnaire-ans">
                                                    <div class="pmu-edit-questionnaire-text">
                                                        <input type="text" class="form-control" placeholder="Type Here..." name="questions[${countForm}][${questionCounter}][options][]" required>
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
                                        <input type="hidden" name="type[]" id="assignment" value="assignment" />
                                        <div class="edit-pmu-heading">
                                            <div class="edit-pmu-text">
                                                <h3>Assignment</h3>
                                            </div>
                                            <div class="edit-pmu-action">
                                                <a href="javascript:void(0)" class="dlt-div" data-id="assignment_div" data-type="Assignment"> Delete Section</a>
                                            </div>
                                        </div>
                                    </div>`;
            countForm += 1;
        } else if (div_type == 'Survey') {
            htmlForm = `<div class="edit-pmu-form-item" id="survey_div">
                                <input type="hidden" name="type[]" id="survey" value="survey" />
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
                                        <a href="javascript:void(0)" class="dlt-div" data-id="survey_div" data-type="Survey"> Delete Section</a>
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
            '<div class="pmu-edit-questionnaire-text">' +
            '<input type="text" class="form-control newop'+id+'" placeholder="Type Here..." name="option[' +
            possible + ']">' +
            '<span class="remove-text remove_newoption" data-remove-id="' + id + '" id="' + possible + '">Remove</span>' +
            '</div>' +
            '</div>' + '</div>';
        $('#newinputquizListing'+id).append(newRowAdd);
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
});