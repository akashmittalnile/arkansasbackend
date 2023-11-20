@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Help Support')
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
                                                <input type="text" class="form-control rounded border me-2" placeholder="Search" aria-label="Search" aria-describedby="search-addon" id="myinput"/>
                                                <span class="input-group-text border-0" id="search-addon" style="background: #E0B220;">
                                                    <i class="las la-search"></i>
                                                </span>
                                            </div>

                                            <div data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; overflow-y: scroll;">
                                                <ul class="list-unstyled mb-0">
                                                    @forelse($user as $val)
                                                    <li class="p-2 border-bottom user-info" data-id="{{$val->id}}" data-fname="{{$val->first_name ?? 'NA'}}" data-lname="{{$val->last_name ?? 'NA'}}" data-img="{{( ($val->profile_image=='' || $val->profile_image==null) ? null : asset('upload/profile-image/'.$val->profile_image) )}}">
                                                        <a href="javascript:void(0)" class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    @if($val->profile_image!="" && $val->profile_image!=null)
                                                                    <img style="border-radius: 50%; object-fit: cover; object-position: center;" src="{{ asset('upload/profile-image/'.$val->profile_image) }}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                                                                    @else
                                                                    <img style="border-radius: 50%; object-fit: cover; object-position: center;" src="{{ asset('assets/website-images/user.jpg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                                                                    @endif
                                                                    <span class="badge bg-success badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="chat-name fw-bold mb-0" style="color: #E0B220; font-size: 0.8rem;">{{ $val->first_name ?? "NA" }} {{ $val->last_name }}</p>
                                                                    <p class="small text-muted"></p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-1">
                                                                <!-- <p class="small text-muted mb-1">Just now</p> -->
                                                                <!-- <span class="badge bg-danger rounded-pill float-end">3</span> -->
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @empty
                                                    @endforelse

                                                    <div class="d-flex flex-column align-items-center justify-content-center mt-5 d-none" id="no_record_found">
                                                        <div>
                                                            <img src="{{ url('/assets/website-images/nodata.svg') }}" alt="">
                                                        </div>
                                                        <div class="font-weight-bold">
                                                            <p class="font-weight-bold" style="font-size: 1.2rem;">No users found </p> 
                                                        </div>
                                                    </div>

                                                </ul>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6 col-lg-7 col-xl-8 body-chat-message-user d-none">

                                        <div class="pt-3 pe-3 messages-card" data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; overflow-y: scroll;">

                                            

                                        </div>

                                        <div class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                                            <img style="border-radius: 50%;" src="{{ asset('assets/website-images/user.jpg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60" id="userAvatar">
                                            <input type="text" class="form-control form-control-lg border ms-3" id="message-input" placeholder="Type message">
                                            <a class="fs-24 ms-3 text-muted" href="#!"><i class="las la-paperclip"></i></a>
                                            <a class="fs-24 ms-3 text-muted" href="#!"><i class="las la-smile"></i></a>
                                            <a class="fs-24 ms-3" href="#!"><i class="las la-paper-plane btnSend"></i></a>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="ajax-chat-url" value="">
                        <input type="hidden" id="ajax-chat-url-first" value="">
                        <input type="hidden" id="ajax-chat-url-last" value="">
                        <input type="hidden" id="ajax-chat-url-img" value="">
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $(document).ready(function(){
        const userCount = "{{ count($user) }}";
        $("#search-addon").on("click", function() {
            var value = $('#myinput').val().toLowerCase();
            $(".list-unstyled .user-info").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });

            var count = $('.list-unstyled .user-info:hidden').length;
            if(count == userCount){
                $("#no_record_found").removeClass('d-none');
            }
            else{
                $("#no_record_found").addClass('d-none');
            }
        });
    });

    $(document).on('click', '.user-info', function(){
        $("#ajax-chat-url").val($(this).attr('data-id'));
        $("#ajax-chat-url-first").val($(this).attr('data-fname'));
        $("#ajax-chat-url-last").val($(this).attr('data-lname'));
        $("#ajax-chat-url-img").val($(this).attr('data-img'));
        $(".body-chat-message-user").removeClass('d-none');
        let userAvaImg = ($("#ajax-chat-url-img").val()=="") ? "{{ asset('assets/website-images/user.jpg') }}" : $("#ajax-chat-url-img").val();
        // console.log(userAvaImg);
        $("#userAvatar").attr('src', userAvaImg);
    })
</script>
<script type="module">
    import { getAuth, signInAnonymously } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-auth.js"
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-app.js";
    import { getFirestore, collection, getDocs, addDoc, orderBy, query} from "https://www.gstatic.com/firebasejs/9.1.3/firebase-firestore.js";

    const firebaseConfig = {
        apiKey: "AIzaSyCRUook3bb04i2tonzu-25R03iUcOVk5Hg",
        authDomain: "arkansas-2309b.firebaseapp.com",
        databaseURL: "https://arkansas-2309b-default-rtdb.firebaseio.com",
        projectId: "arkansas-2309b",
        storageBucket: "arkansas-2309b.appspot.com",
        messagingSenderId: "741749289086",
        appId: "1:741749289086:web:8dd855ebb34e133e47e82f",
        measurementId: "G-YYEB9LE852"
    };

    const receiver_id = $("#ajax-chat-url").val();
    const group_id = "1-" + receiver_id;
    const app = initializeApp(firebaseConfig);
    let defaultFirestore = getFirestore(app);
    console.log("Firestore => ", defaultFirestore);
    const auth = getAuth(app);
    signInAnonymously(auth)
        .then((result) => {
        console.log(result);
    })
    .catch((error) => {
      	console.log('error',error);
        const errorCode = error.code;
        const errorMessage = error.message;
        // ...
    });

    length = 36;
    const characters = '0123456789abcdefghijklmnopqrstuvwxyz'; // characters used in string
    let result = ''; // initialize the result variable passed out of the function
    for (let i = length; i > 0; i--) {
        result += characters[Math.floor(Math.random() * characters.length)];
    }
    let random = result;

    window.sendNewMessage = async function(group_id_new2, message, receiver_id, userName) {
        //alert(6);
        const chatCol = collection(defaultFirestore, 'arkansas_support/' + group_id_new2 + '/messages');
        let data = {
            text: message ?? "HHH",
            sendBy: '1',
            sendto: receiver_id,
            adminName: 'Arkansas',
            userName: userName,
            user: {
                _id: 1
            },
            _id: random,
            createdAt: new Date()
        };

        console.log("Data => ",data);

        const add = await addDoc(chatCol, data);
        const chatCols = query(collection(defaultFirestore, 'arkansas_support/' + group_id_new2 + '/messages'), orderBy('createdAt', 'asc'));
        const chatSnapshot = await getDocs(chatCols);
        const chatList = chatSnapshot.docs.map(doc => doc.data());
        showAllMessages(chatList);
        //location.reload();
    }


    window.getClientChat = async function(group_id, ajax_call = false) {
        console.log("Group ID => ",group_id);
        const chatCols = query(collection(defaultFirestore, 'arkansas_support/' + group_id + '/messages'), orderBy('createdAt',
            'asc'));
        const chatSnapshot = await getDocs(chatCols);
        const chatList = chatSnapshot.docs.map(doc => doc.data());
        console.log("get client chat => ", chatList);

        showAllMessages(chatList);
    }
    $(document).on('click', '.user-info', function(){
        getClientChat(group_id);
    })
    
</script>

<script>
    $(document).ready(function() {

        const receiver_id = $("#ajax-chat-url").val();
        

        $(document).on('click', '.btnSend', function() {
            const user_firstName = $("#ajax-chat-url-first").val();
            const user_lastName = $("#ajax-chat-url-last").val();
            const userName = user_firstName + user_lastName;
            const receiver_id = $("#ajax-chat-url").val();
            const group_id = "1-" + receiver_id;
            let message = $('#message-input');
            let time = moment().format('MMM DD, YYYY HH:mm A');
            if (message.val() != '') {
                sendNewMessage(group_id, message.val(), receiver_id, userName);
                showMessage(message.val(), time, userName);
                message.val('').focus();
            }

        })
    });

    function showAllMessages(list, ajax_call = false) {
        $('.messages-card').html('');
        if (list.length == 0) return false;
        let html = `${list.map(row => admin(row,ajax_call)).join('')}`;
        $('.messages-card').html(html);
        if (ajax_call == false) {
            $(".body-chat-message-user").stop().animate({
                scrollTop: $(".body-chat-message-user")[0].scrollHeight
            }, 1000);
        }
    }

    function showMessage(message, time, userName) {
        let msg = `<div class="d-flex flex-row justify-content-end">
                <div>
                    <p style="background: #261313;" class="small p-2 me-3 mb-1 text-white rounded-3">${message}</p>
                    <p class="small me-3 mb-3 rounded-3 text-muted">${time}</p>
                </div>
                <img src="{{ (auth()->user()->profile_image=='' || auth()->user()->profile_image == null) ? asset('assets/website-images/user.png') : asset('upload/profile-image/'.auth()->user()->profile_image) }}" alt="avatar 1" style="width: 45px; height: 100%; border-radius: 50%">
            </div>`;
        $('.messages-card').append(msg);

        $(".body-chat-message-user").stop().animate({
            scrollTop: $(".body-chat-message-user")[0].scrollHeight
        }, 1000);
    }

    
    function admin(row) {
        let userProImg = ($("#ajax-chat-url-img").val()=="") ? "{{ asset('assets/website-images/user.jpg') }}" : $("#ajax-chat-url-img").val();
        let html = '';
        var formattedDate = moment.unix(row.createdAt.seconds).format('MMM DD, YYYY HH:mm A');
        if (row.sendto == 1) {

            html = `<div class="d-flex flex-row justify-content-start">
                    <img style="border-radius: 50%;" src="${userProImg}" alt="avatar" class="d-flex align-self-center me-3" width="60">
                    <div>
                        <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${row.text}</p>
                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end">${formattedDate}</p>
                    </div>
                </div>`;
        } else {
            html = `<div class="d-flex flex-row justify-content-end">
                <div>
                    <p style="background: #261313;" class="small p-2 me-3 mb-1 text-white rounded-3">${row.text}</p>
                    <p class="small me-3 mb-3 rounded-3 text-muted">${formattedDate}</p>
                </div>
                <img src="{{ (auth()->user()->profile_image=='' || auth()->user()->profile_image == null) ? asset('assets/website-images/user.png') : asset('upload/profile-image/'.auth()->user()->profile_image) }}" alt="avatar 1" style="width: 45px; height: 100%; border-radius: 50%">
            </div>`;
        }
        return html;
    }
</script>
<script>
    setInterval(function() {
        const receiver_id = $("#ajax-chat-url").val();
        const group_id = "1-" + receiver_id;
        getClientChat(group_id, true);
    }, 5000);
</script>

@endsection