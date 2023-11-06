// ----------------note editor---------------
$(function() {
    $("#makeMeSummernote1").summernote();
    $("#makeMeSummernote2").summernote();
  
      $("button#btnToggleStyle").on("click", function(e) {
        e.preventDefault();
        var styleEle = $("style#fixed");
        if (styleEle.length == 0)
          $("<style id=\"fixed\">.note-editor .dropdown-toggle::after { all: unset; } .note-editor .note-dropdown-menu { box-sizing: content-box; } .note-editor .note-modal-footer { box-sizing: content-box; }</style>")
          .prependTo("body");
        else
          styleEle.remove();
      })
    })
  
  
  // -------------------image uploading--------------------
  $('.file-input').change(function(){
    var curElement = $('.image');
    console.log(curElement);
    var reader = new FileReader();
  
    reader.onload = function (e) {
        curElement.attr('src', e.target.result);
    };
  
    reader.readAsDataURL(this.files[0]);
  });
  
  
  // ----------------dropdown----------------
  $(document).ready(function(){
    $("#SelExample").select2({
        placeholder: "Select Category"
    }),
    $("#SelExample-1").select2({
        placeholder: "Select Tags",
        multiple: true
    }),
    $("#SelExample-2").select2();;
    $("#SelExample").select2("val", "4");
  $('#SelExample option:selected').text('Vizag');
   
  });
  
  // ---------------------select otopns-----------------------
  $('.tab-content').hide();
  $('.contents').hide();
  
  $('#tab-1').show(), $('#tab-2').show();
  
  $('#select-box').change(function () {
      var dropdown = $('#select-box').val();
      $('.tab-content').hide();
      $('.contents').hide();
  
      if (dropdown === '1') {
          $('#tab-1').show();
          $('#tab-2').show();
      } else if (dropdown === '3') {
          $('#tab-3').show();
      }
  });
  
  
  // -----------------button display---------------------
  let Buttons = document.querySelectorAll(".selectSection button");
  
  for (let button of Buttons) {
    button.addEventListener('click', (e) => {
      const et = e.target;
      const active = document.querySelector(".active");
      if (active) {
        active.classList.remove("active");
      }
      et.classList.add("active");
      
      let allContent = document.querySelectorAll('.content');
  
      for (let content of allContent) {
        if(content.getAttribute('data-number') === button.getAttribute('data-number')) {
          content.style.display = "block";
         }
        else {
          content.style.display = "none";
         }
       }
    });
  }
  
  
  // -----------------radio buttons-----------------
  const planA = document.getElementById('planA');
  const planB = document.getElementById('planB');
  const planC = document.getElementById('planC');
  const planAOptions = document.querySelectorAll('[name=planAOption]');
  const planBOptions = document.querySelectorAll('[name=planBOption]');
  const planCOptions = document.querySelectorAll('[name=planCOption]');
  
  planA.addEventListener('change', () => {
      planAOptions.forEach(option => (option.disabled = false));
      planBOptions.forEach(option => (option.disabled = planA.checked));
      planCOptions.forEach(option => (option.disabled = planA.checked));
  });
  
  planB.addEventListener('change', () => {
      planBOptions.forEach(option => (option.disabled = false));
      planAOptions.forEach(option => (option.disabled = planB.checked));
      planCOptions.forEach(option => (option.disabled = planB.checked));
  });
  
  planC.addEventListener('change', () => {
      planCOptions.forEach(option => (option.disabled = false));
      planAOptions.forEach(option => (option.disabled = planC.checked));
      planBOptions.forEach(option => (option.disabled = planC.checked));
  });
  
  
  // -----------------remove image-------------------
  Dropzone.autoDiscover = false;
  $(document).ready(function () {
    var zdrop = new Dropzone('#dropzone', {
      url: "http://localhost/arkansas/public/super-admin/addproduct",
      maxFiles: 1,
      maxFilesize: 30,
      addRemoveLinks: true,
      removeFilePromise: function () {
        return new Promise((resolve, reject) => {
          let rand = Math.floor(Math.random() * 3);
          console.log(rand);
          if (rand == 0) reject('didnt remove properly');
          if (rand > 0) resolve();
        });
      }
    });
  });
  
  
  