<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arkanasas</title>
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/iconsax/iconsax.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/auth.css') !!}">
    <script src="{!! url('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/website-js/function.js') !!}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/becomeacreator.css') !!}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

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
            position: relative;
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
</head>

<body>

    <div class="quiz-results-section">
        <div class="continer">
            <div class="quiz-results-content">
                @if( number_format((float)(($obtained * 100) / $total), 1) >= 40 )
                <h3>Hurry!</h3>
                <p>You passed this quiz with a score of</p>
                @else
                <h3>Whoops!</h3>
                <p>You failed this quiz with a score of</p>
                @endif

                <div id="outerContainer">
                    <div class="quiz-results-chart">
                        <div class="quiz-results-text">
                            <h2>{{ number_format((float)(($obtained * 100) / $total), 1) }}%</h2>
                            <h5>Your Score</h5>
                        </div>
                        <div class="quizcircle" style="animation-delay: -3s"></div>
                        <div class="quizcircle" style="animation-delay: -2s"></div>
                        <div class="quizcircle" style="animation-delay: -1s"></div>
                        <div class="quizcircle" style="animation-delay: 0s"></div>
                    </div>
                </div>

                <p>You need 40% to pass</p>


            </div>
            <div class="quiz-results-section">
                <div class="row">
                    <div class="col-md-3">
                        <div class="quiz-results-card">
                            <h3>{{ $totalQuestion ?? 0 }}</h3>
                            <p>Total Question</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="quiz-results-card attempt">
                            <h3>{{ $totalQuestion ?? 0 }}</h3>
                            <p>Total attempt Questions</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="quiz-results-card Correct">
                            <h3>{{ $totalCorrect ?? 0 }}</h3>
                            <p>Correct Question</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="quiz-results-card Wrong">
                            <h3>{{ $totalWrong ?? 0 }}</h3>
                            <p>Wrong Question</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="quiz-results-action">
                <a class="Retakebtn" href="#">Retake Quiz</a>
                <a class="Retakebtn" data-bs-toggle="modal" data-bs-target="#Prerequisite">poup</a>
            </div>
        </div>
    </div>

</body>

</html>



<!-- Add card -->
<div class="modal ro-modal fade" id="Prerequisite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="becomeacreator-form-info">
                    <div class="becomeacreator-form-media">
                        <svg xmlns="http://www.w3.org/2000/svg" width="101" height="100" viewBox="0 0 101 100" fill="none">
                            <path opacity="0.4" d="M50.4999 91.6666C73.5118 91.6666 92.1666 73.0118 92.1666 49.9999C92.1666 26.9881 73.5118 8.33325 50.4999 8.33325C27.4881 8.33325 8.83325 26.9881 8.83325 49.9999C8.83325 73.0118 27.4881 91.6666 50.4999 91.6666Z" fill="#E0B220" />
                            <path d="M49.5 58.5V57.4502C49.5 54.0502 51.6508 52.2501 53.8014 50.8001C55.9008 49.4001 58 47.6002 58 44.3002C58 39.7002 54.2109 36 49.5 36C44.7891 36 41 39.7002 41 44.3002M49.4766 71H49.5227" stroke="#E0B220" stroke-width="5.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h2>Prerequisite(s) have not yet been completed!</h2>
                    <p>To move forward, please complete all prerequisites in Chapter 2: Frequently Asked Questions</p>
                    <div class="becomeacreator-btn-action">
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">Close</a>
                        <a href="#" class="Login-btn">OK, got it</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>