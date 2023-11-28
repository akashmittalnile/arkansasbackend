@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Completion Status')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>Completion Status</h2>
        </div>
        <div class="pmu-search-filter wd10">
            <div class="row g-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <a href="{{ route('SA.StudentDetail', encrypt_decrypt('encrypt', $id)) }}" class="newcourse-btn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pmu-content-list">
        <div class="pmu-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="pmu-course-details-item">
                        <div class="pmu-course-details-media w-25">
                            <a data-fancybox data-type="iframe" data-src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fapciedu%2Fvideos%2F203104562693996%2F&show_text=false&width=560&t=0" href="javascript:;">
                                <video width="300" height="210" controls controlslist="nodownload noplaybackrate" disablepictureinpicture volume>
                                    <source src="{{ uploadAssets('upload/disclaimers-introduction/' . $course->introduction_image) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <!-- <div class="pmu-video-icon"><img src="{!! assets('assets/superadmin-images/video.svg')!!}"></div> -->
                            </a>
                        </div>
                        <div class="pmu-course-details-content w-75">
                            <div class="coursestatus"><img src="{!! assets('assets/superadmin-images/tick.svg')!!}">
                                @if ($course->status == 0)
                                    Unpublished
                                @else
                                    Published 
                                @endif
                            </div>

                            <h2>{{ ($course->title) ? : ''}}</h2>
                            <div class="pmu-course-details-price">${{ number_format($course->course_fee,2) ? : 0}}</div>
                            <p>{{ ($course->description) ? : ''}}</p>
                            <div class="course-tag">
                                <h3>Tags:</h3>
                                <div class="tags-list">
                                    @foreach($combined as $val)
                                        @if($val['selected'])
                                            <div class="Tags-text">{{ $val['name'] }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pmu-course-details-Chapter">
                        <div class="pmu-course-details-heading">
                            <h2>Chapters</h2>
                        </div>
                        <div class="pmu-course-details-accordion-list">
                            
                            @forelse($chapters as $key => $value)
                            <div class="pmu-course-accordion-item">
                                <div class="pmu-course-accordion-head accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#Chapter{{$key+1}}">
                                    <div class="pmu-course-accordion-title">
                                        <h2 class="text-capitalize">Chapter {{$key+1}}: {{ $value->chapter ?? "NA" }}</h2>
                                        <a class="edit-icon-btn" style="background: #fff; border:1px gray solid;" href="#"><img src="{{ assets('assets/website-images/arrow-down.svg') }}"></a>
                                    </div>
                                </div>
                                <div class="pmu-course-accordion-body accordion-collapse collapse" id="Chapter{{$key+1}}">
                                    <div class="pmu-course-point-list">
                                        @if(count($value->chapterSection)>0)
                                            @foreach($value->chapterSection as $val)
                                            
                                                @if($val->type =='video')
                                                <div class="pmu-course-point-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <img src="{{ assets('assets/website-images/video-icon.svg') }}">  {{$val->title}}
                                                    </div>
                                                    <div>
                                                        @if(!is_null($val->chapterStep($id)) && $val->chapterStep($id)->status == 1)<img src="{{ assets('assets/website-images/tick-circle.svg') }}">  @else <img width="41" height="41" src="{{ assets('assets/website-images/close-circle.svg') }}"> @endif
                                                    </div>
                                                </div>
                                                @elseif($val->type=='pdf' || $val->type=='assignment')
                                                <div class="pmu-course-point-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <img src="{{ assets('assets/website-images/document-text.svg') }}"> {{$val->title}}
                                                    </div>
                                                    <div>
                                                        @if(!is_null($val->chapterStep($id)) && $val->chapterStep($id)->status == 1 && $val->type=='assignment')<a target="_black" href="{{ uploadAssets('upload/course/'.$val->chapterStep($id)->file) }}"><img src="{{ assets('assets/website-images/pdf.svg') }}" class="mx-3" alt="No pdf found"></a>@endif
                                                        @if(!is_null($val->chapterStep($id)) && $val->chapterStep($id)->status == 1)<img src="{{ assets('assets/website-images/tick-circle.svg') }}"> @else <img width="41" height="41" src="{{ assets('assets/website-images/close-circle.svg') }}"> @endif
                                                    </div>
                                                </div>
                                                @else
                                                <div class="pmu-course-point-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <img src="{{ assets('assets/website-images/book2.svg') }}"> {{$val->title}}
                                                    </div>
                                                    <div>

                                                        @if(!is_null($val->chapterStep($id)) && $val->chapterStep($id)->status == 1 && $val->type=='quiz')
                                                        <button type="button" class="cancel-btn" data-quiz="{{ encrypt_decrypt('encrypt', $val->id) }}" style="padding: 9px 10px;" id="resultOpenModal">
                                                            Result
                                                        </button>
                                                        @endif

                                                        @if(!is_null($val->chapterStep($id)) && $val->chapterStep($id)->status == 1)<img src="{{ assets('assets/website-images/tick-circle.svg') }}">  @else <img width="41" height="41" src="{{ assets('assets/website-images/close-circle.svg') }}"> @endif
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        @else
                                        No Record Found
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            @endforelse


                        </div>
                    </div>



                    <div class="pmu-comment-section">
                        <div class="pmu-comment-list">
                            <div class="pmu-comment-box-head">
                                <div class="pmu-comment-1">
                                    <h1>Rating & Review</h1>
                                    @if(count($review) != 0)
                                    <div class="pmu-comment-rating"><img src="{!! assets('assets/superadmin-images/star.svg')!!}"> {{ number_format($reviewAvg, 1) }}</div>
                                    @endif
                                </div>
                                <div class="pmu-comment-head-action">
                                    <!-- <a class="addcomment-btn"><i class="las la-plus"></i> See More Reviews</a> -->
                                </div>
                            </div>

                            @forelse($review as $value)
                            <div class="pmu-comment-item">
                                <div class="pmu-comment-profile">
                                    <img src="{!! assets('assets/superadmin-images/user.png')!!}">
                                </div>
                                <div class="pmu-comment-content">
                                    <div class="pmu-comment-head">
                                        <h2>{{ $value->first_name ?? "NA" }} {{ $value->last_name ?? "" }}</h2>
                                        <div class="pmu-date"><i class="las la-calendar"></i>{{ date('d M, Y, g:iA', strtotime($value->created_date ?? "")) }}</div>
                                    </div>
                                    <div class="pmu-comment-rating"><img src="{!! assets('assets/superadmin-images/star.svg')!!}"> {{ number_format($value->rating,1) ?? "0.0" }}</div>
                                    <div class="pmu-comment-descr">{{ $value->review ?? "NA" }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center">
                                No rating & review found
                            </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal ro-modal fade" id="resultModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="quiz-results-section">
                
                    <div class="quiz-results-content">

                        <h3 id="msg1">Hurry!</h3>
                        <p id="msg2">You passed this quiz with a score of</p>

                        <div id="outerContainer">
                            <div class="quiz-results-chart">
                                <div class="quiz-results-text">
                                    <h2 id="perObtained"></h2>
                                    <h5>Your Score</h5>
                                </div>
                                <div class="quizcircle" style="animation-delay: -3s"></div>
                                <div class="quizcircle" style="animation-delay: -2s"></div>
                                <div class="quizcircle" style="animation-delay: -1s"></div>
                                <div class="quizcircle" style="animation-delay: 0s"></div>
                            </div>
                        </div>

                        <p id="needPer">You need % to pass</p>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', "#resultOpenModal", function() {
        quizId = $(this).attr('data-quiz');
        $.ajax({
            url: "{{ route('Home.student.result', ['id'=> encrypt_decrypt('encrypt', $id)]) }}",
            method: 'GET',
            data: {
                quizId : quizId,
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (data) {
                if (data.status) {
                    console.log(data);
                    $("#perObtained").html(((data.obtained * 100) / data.total).toFixed(2)+"%");
                    $("#needPer").html("You need "+data.percen+"% to pass");
                    if(((data.obtained * 100) / data.total) >= data.percen){
                        $("#msg1").html("Hurry!");
                        $("#msg2").html("You passed this quiz with a score of");
                    } else {
                        $("#msg1").html("Whoops!");
                        $("#msg2").html("You failed this quiz with a score of"); 
                    }
                    $("#resultModal").modal('show');
                } else if (data.status) {
                    setInterval(function () {location.reload();}, 3000);
                }
            }
        });
    });
</script>

<style type="text/css">

    .quiz-results-section {
        position: relative;
        background: #261313;
        padding: 2rem;
    }

    .quiz-results-chart {
        width: 100%;
        height: 275px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .quiz-results-content h3 {
        color: #FFF;
        text-align: center;
        font-family: League Spartan;
        font-size: 30px;
        font-style: normal;
        font-weight: 600;
        line-height: 100%;
        letter-spacing: -0.3px;
        margin: 0;
        padding: 0;
    }

    .quiz-results-content p {
        color: #FFF;
        text-align: center;
        font-family: League Spartan;
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: 100%;
        letter-spacing: -0.2px;
    }

    .quizcircle {
        border-radius: 50%;
        background-color: #653C3C;
        width: 150px;
        height: 150px;
        position: absolute;
        opacity: 0;
        animation: scaleIn 4s infinite cubic-bezier(.36, .11, .89, .32);
    }

    .quiz-results-text {
        z-index: 100;
        background-color: #E0B220;
        border-radius: 100%;
        width: 120px;
        height: 120px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .quiz-results-text h2 {
        color: #FFF;
        text-align: center;
        font-family: League Spartan;
        font-size: 29px;
        font-style: normal;
        font-weight: 700;
        line-height: 100%;
        /* 29px */
        letter-spacing: -0.29px;
        margin: 0;
        padding: 0
    }

    .quiz-results-text h5 {
        color: #000;
        text-align: center;
        font-family: League Spartan;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 100%;
        /* 14px */
        letter-spacing: -0.14px;
        margin: 0;
        padding: 0
    }


    @keyframes scaleIn {
        from {
            transform: scale(.5, .5);
            opacity: .5;
        }

        to {
            transform: scale(2, 2);
            opacity: 0;
        }
    }

    .quiz-results-card {
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        border: 1px solid var(--gray, #ECECEC);
        background: var(--white, #FFF);
        position: relative;margin-bottom: 10px;
    }

    .quiz-results-card h3 {
        color: #281809;
        font-family: League Spartan;
        font-size: 22px;
        font-style: normal;
        font-weight: 600;
        line-height: 16px;
        /* 72.727% */
        letter-spacing: 0.4px;
        margin: 0;
        padding: 0
    }


    .quiz-results-card p {
        color: var(--gray-gray-600, #505667);
        font-family: League Spartan;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 20px;
        /* 142.857% */
        letter-spacing: 0.25px;
        margin: 0
    }

    .attempt h3 {
        color: #E0B220;
    }

    .Correct h3 {
        color: #34A853;
    }

    .Wrong h3 {
        color: #EB001B;
    }

    .quiz-results-action {
        text-align: center;
    }

    a.Retakebtn {
        border-radius: 5px;
        background: var(--white, #FFF);
        box-shadow: 0px 4px 12px 0px rgba(182, 0, 248, 0.06);
        color: var(--Brown, #261313);
        text-align: center;
        font-family: League Spartan;
        font-size: 14px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
        text-transform: uppercase;
        padding: 15px 30px;
        display: inline-block;
    }





    .becomeacreator-form-info {
        position: relative;
        padding: 2rem;
    }

    .becomeacreator-form-info h2 {
        font-size: 24px;
        text-align: center;
        margin: 0;
        padding: 0;
        color: #281809;
    }

    .becomeacreator-form-info p {
        font-size: 14px;
        text-align: center;
        margin: 0 0 1rem 0;
        color: #281809;
    }

    .becomeacreator-btn-action {
        text-align: center;
    }

    .becomeacreator-btn-action .close-btn {
        background: #fff;
        color: #281809;
        text-transform: uppercase;
        padding: 10px 30px;
        border: none;
        display: inline-block;
        font-size: 14px;
        border-radius: 5px;
    }

    .becomeacreator-btn-action .Login-btn {
        background: #281809;
        color: #fff;
        text-transform: uppercase;
        padding: 10px 30px;
        border: none;
        display: inline-block;
        font-size: 14px;
        border-radius: 5px;
        box-shadow: 0 4px 28px rgb(168 91 91 / 21%);
    }

    .becomeacreator-form-media {
        text-align: center;
    }

</style>

@endsection