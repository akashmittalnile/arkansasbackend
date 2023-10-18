@extends('layouts.app-master')
@section('title', 'Makeup University - Help Support')
@section('content')
<link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/help.css') !!}">
<div class="body-main-content">
    <div class="message-section">
        <section style="background-color: #e6e6e6; border-radius: 30px;">
            <div class="container p-4">

                <div class="row">
                    <div class="col-md-12">

                        <div class="card" id="chat3" style="border-radius: 15px;">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">

                                        <div class="p-3">

                                            <div class="input-group rounded mb-3">
                                                <input type="search" class="form-control rounded border me-2" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                                                <span class="input-group-text border-0" id="search-addon" style="background: #E0B220;">
                                                    <i class="las la-search"></i>
                                                </span>
                                            </div>

                                            <div data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; overflow-y: scroll;">
                                                <ul class="list-unstyled mb-0">
                                                    <li class="p-2 border-bottom">
                                                        <a href="#!" class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    <img style="border-radius: 50%;" src="{{ asset('assets/website-images/user.jpg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                                                                    <span class="badge bg-success badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="chat-name fw-bold mb-0" style="color: #E0B220;">Arkansas</p>
                                                                    <p class="small text-muted">Hello, Are you there?</p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-1">
                                                                <p class="small text-muted mb-1">Just now</p>
                                                                <span class="badge bg-danger rounded-pill float-end">3</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6 col-lg-7 col-xl-8">

                                        <div class="pt-3 pe-3" data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; overflow-y: scroll;">

                                            <div class="d-flex flex-row justify-content-start">
                                                <img style="border-radius: 50%;" src="{{ asset('assets/website-images/user.jpg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                                                <div>
                                                    <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">Lorem ipsum
                                                        dolor
                                                        sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                                                        dolore
                                                        magna aliqua.</p>
                                                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row justify-content-end">
                                                <div>
                                                    <p style="background: #261313;" class="small p-2 me-3 mb-1 text-white rounded-3">Ut enim ad minim veniam,
                                                        quis
                                                        nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13</p>
                                                </div>
                                                <img src="{{ asset('assets/website-images/user.png') }}" alt="avatar 1" style="width: 45px; height: 100%; border-radius: 50%">
                                            </div>

                                            <div class="d-flex flex-row justify-content-start">
                                                <img style="border-radius: 50%;" src="{{ asset('assets/website-images/user.jpg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                                                <div>
                                                    <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">Duis aute
                                                        irure
                                                        dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                                    </p>
                                                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row justify-content-end">
                                                <div>
                                                    <p style="background: #261313;" class="small p-2 me-3 mb-1 text-white rounded-3">Excepteur sint occaecat
                                                        cupidatat
                                                        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13</p>
                                                </div>
                                                <img src="{{ asset('assets/website-images/user.png') }}" alt="avatar 1" style="width: 45px; height: 100%; border-radius: 50%">
                                            </div>

                                            <div class="d-flex flex-row justify-content-start">
                                                <img style="border-radius: 50%;" src="{{ asset('assets/website-images/user.jpg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                                                <div>
                                                    <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">Sed ut
                                                        perspiciatis
                                                        unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam
                                                        rem
                                                        aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
                                                        dicta
                                                        sunt explicabo.</p>
                                                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row justify-content-end">
                                                <div>
                                                    <p style="background: #261313;" class="small p-2 me-3 mb-1 text-white rounded-3">Nemo enim ipsam
                                                        voluptatem quia
                                                        voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos
                                                        qui
                                                        ratione voluptatem sequi nesciunt.</p>
                                                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13</p>
                                                </div>
                                                <img src="{{ asset('assets/website-images/user.png') }}" alt="avatar 1" style="width: 45px; height: 100%; border-radius: 50%">
                                            </div>

                                            <div class="d-flex flex-row justify-content-start">
                                                <img style="border-radius: 50%;" src="{{ asset('assets/website-images/user.jpg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                                                <div>
                                                    <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">Neque porro
                                                        quisquam
                                                        est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non
                                                        numquam
                                                        eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
                                                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row justify-content-end">
                                                <div>
                                                    <p style="background: #261313;" class="small p-2 me-3 mb-1 text-white rounded-3">Ut enim ad minima veniam,
                                                        quis
                                                        nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea
                                                        commodi
                                                        consequatur?</p>
                                                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13</p>
                                                </div>
                                                <img src="{{ asset('assets/website-images/user.png') }}" alt="avatar 1" style="width: 45px; height: 100%; border-radius: 50%">
                                            </div>

                                        </div>

                                        <div class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                                            <img style="border-radius: 50%;" src="{{ asset('assets/website-images/user.jpg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                                            <input type="text" class="form-control form-control-lg border ms-3" id="exampleFormControlInput2" placeholder="Type message" style="">
                                            <a class="fs-24 ms-3 text-muted" href="#!"><i class="las la-paperclip"></i></a>
                                            <a class="fs-24 ms-3 text-muted" href="#!"><i class="las la-smile"></i></a>
                                            <a class="fs-24 ms-3" href="#!"><i class="las la-paper-plane"></i></a>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
</div>
@endsection