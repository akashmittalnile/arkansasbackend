@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Edit Product')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Edit Products</h2>
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
                                <form method="post" action="{{ route('SA.Update.Products') }}" id="AddProduct" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="status" value="1" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Title</h4>
                                                <input type="text" class="form-control" name="title" placeholder="Title" id="title" value="{{ $product->name }}" required>
                                                @if ($errors->has('title'))
                                                    <span class="text-danger text-left">{{ $errors->first('title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Description</h4>
                                                <textarea type="text" class="form-control" name="description" placeholder="Description" required>{{ $product->product_desc }}</textarea>
                                                @if ($errors->has('description'))
                                                    <span class="text-danger text-left">{{ $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Price</h4>
                                                <input type="number" class="form-control" name="price"
                                                    placeholder="Enter Price" min="0" required value="{{ $product->price }}">
                                                @if ($errors->has('price'))
                                                    <span class="text-danger text-left">{{ $errors->first('price') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Quantity</h4>
                                                <input type="number" class="form-control" name="qnt" value="{{ $product->unit }}" min="0" placeholder="Product Quantity" required>
                                                @if ($errors->has('qnt'))
                                                    <span class="text-danger text-left">{{ $errors->first('qnt') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Category</h4>
                                                <select name="product_category" id="" class="form-control">
                                                    <option @if($product->category == "") selected @endif value="">Select Category</option>
                                                    @foreach(getCategory(2) as $val)
                                                        <option @if($product->category_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="id" value="{{ encrypt_decrypt('encrypt', $product->id) }}">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Tags With Comma</h4>
                                                <select class="form-control livesearch p-3" name="tags[]" multiple="multiple" required>
                                                    @foreach($combined as $val)
                                                        <option @if($val['selected']) selected @endif value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @foreach($attr as $valAttr)
                                        <div class="col-2 mx-2 my-4" style="width: 160px; height: 80px;">
                                            <img class="p-0" width="160" height="80" style="object-fit: cover; object-position: center; border-radius: 8px; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;" src="{!! url('upload/products/'.$valAttr->attribute_value) !!}" />
                                            <a href="{{ route('SA.Delete.Products.Image', encrypt_decrypt('encrypt', $valAttr->id)) }}" onclick="return confirm('Are you sure you want to delete this product image?');"><i style="border: 1px solid red; background: red; border-radius: 50%; padding: 5px; color: white; position: relative; top: -90px; right: -144px;" class="las la-trash"></i></a>
                                        </div>
                                        @endforeach

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Upload Product Image(jpg,jpeg,png only|Size:2048)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="image[]" id="PDFJPEGOrPNG"
                                                        class="uploadsignature addsignature" multiple accept="image/png, image/jpg, image/jpeg" onchange="loadImageFile(event)">
                                                    <label for="PDFJPEGOrPNG">
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
        });
    </script>
    
    <!-- Include jQuery Validation Plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

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

        $('.livesearch').select2({
            placeholder: 'Select tags',
            tags: true,
        });

        $(document).ready(function() {
            
            $(".select2-container .selection .select2-selection .select2-search__field").addClass('form-control');

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
                    product_category: {
                        required: true,
                    },
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
                    product_category: {
                        required: 'Please enter product category',
                    },
                },

                submitHandler: function(form) {
                    // This function will be called when the form is valid and ready to be submitted
                    form.submit();
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    element.closest(".form-group").append(error);

                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                },
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
