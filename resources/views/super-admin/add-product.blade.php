@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Add Product')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Products</h2>
            </div>
            <div class="pmu-filter">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('super-admin/products') }}" class="add-more">Back</a>
                        <a href="#" id="SaveProduct" class="add-more">Save & Continue</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pmu-courses-form-section">
                            <h2>Product Details</h2>
                            <div class="pmu-courses-form">
                                <form method="post" action="{{ route('SA.SubmitProduct') }}" id="AddProduct" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="status" value="1" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Title</h4>
                                                <input type="text" class="form-control" name="title" placeholder="Title" id="title" required>
                                                @if ($errors->has('title'))
                                                    <span class="text-danger text-left">{{ $errors->first('title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Description</h4>
                                                <textarea type="text" class="form-control" name="description" placeholder="Description" required></textarea>
                                                @if ($errors->has('description'))
                                                    <span class="text-danger text-left">{{ $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Price</h4>
                                                <input type="number" class="form-control" name="price"
                                                    placeholder="Enter Price" min="0" required>
                                                @if ($errors->has('price'))
                                                    <span class="text-danger text-left">{{ $errors->first('price') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Quantity</h4>
                                                <input type="number" class="form-control" name="qnt" min="0" placeholder="Product Quantity" required>
                                                @if ($errors->has('qnt'))
                                                    <span class="text-danger text-left">{{ $errors->first('qnt') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Upload Product Image(jpg,jpeg,png only|Size:2048)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="image[]" id="PDF/JPEG Or PNG"
                                                        class="uploadsignature addsignature" multiple required accept="image/png, image/jpg, image/jpeg" onchange="loadImageFile(event)">
                                                    <label for="PDF/JPEG Or PNG">
                                                        <div class="signature-text-img" >
                                                            <span ><img src="{!! url('assets/website-images/upload.svg') !!}"> Click here to Upload</span>
                                                        </div>
                                                    </label>
                                                    <div id="image_names"></div>
                                                    @if ($errors->has('image'))
                                                        <span class="text-danger text-left">{{ $errors->first('image') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        

                                        {{-- <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Tags With Comma</h4>
                                                <select class="form-control livesearch form-control p-3" name="livesearch[]" multiple="multiple" required></select>
                                            </div>
                                        </div>  --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JQuery Search Tags -->
    <script type="text/javascript">
        $('.livesearch').select2({
            placeholder: 'Select tags',
            ajax: {
                url: 'http://127.0.0.1:8000/load-sectors',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.tag_name,
                                id: item.tag_name
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
    
    <!-- Include jQuery Validation Plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <!-- Style of h2 tag and error message  jQuery Validation -->
    <style>
        .error {
            color: red;
        }
        h2 {
            color: white;
        },
    </style>

     <!-- Include jQuery Validation -->
    <script>
        $(document).ready(function() {
            $('#AddProduct').validate({
                rules: {
                    title: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    price: {
                        required: true,
                    },
                    qnt: {
                        required:true,
                    },
                    // livesearch: {
                    //     required: true,
                    // },
                },
                messages: {
                    title: {
                        required: 'Please enter title',
                    },
                    description: {
                        required: 'Please enter description',
                    },
                    price: {
                        required: 'Please enter price fee',
                    },
                    qnt: {
                        required: 'Please enter quantity',
                    },
                    // livesearch: {
                    //     required: 'Please enter tags',
                    // },
                },

                submitHandler: function(form) {
                    // This function will be called when the form is valid and ready to be submitted
                    form.submit();
                }
            });
        });

        const loadImageFile = (event) => {
            // src: URL.createObjectURL(event.target.files[0])
            let html = ``;
            for(i=0; i<event.target.files.length; i++){
                html += `<img class="m-2" width="160" height="80" style="object-fit: cover; object-position: center; border-radius: 8px; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;" src="${URL.createObjectURL(event.target.files[i])}">`
            }
            $("#image_names").html(html);
        };
    </script>

    <!-- Submit form using Jquery -->
    <script>
        $(document).ready(function() {
            $('#SaveProduct').click(function() {
                $('#AddProduct').submit();
            });
        });
    </script>

    <!-- Append File name -->
    {{-- <script>
        $(document).ready(function() {
            $('input[name="image"]').change(function(e) {
                var geekss = e.target.files[0].name;
                $("#image_name").text(geekss);
            });
        });
    </script> --}}
     <script>
        $(document).ready(function() {
            $('input[name="image"]').change(function(e) {
                var imageNames = ""; // Initialize an empty string to store the names of selected files

                // Loop through all selected files
                for (var i = 0; i < e.target.files.length; i++) {
                    var fileName = e.target.files[i].name;

                    // Add the file name to the string, separated by a comma or newline
                    imageNames += fileName + ", ";
                }

                // Remove the trailing comma and whitespace
                imageNames = imageNames.replace(/,\s*$/, "");

                // Display the names in the "image_names" div
                $("#image_names").text(imageNames);
            });
        });
    </script>

@endsection
