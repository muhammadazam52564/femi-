@extends('layouts.admin.app')
@section('title')
Create Product
@endsection
@section('content')
<div class="container">
    <a id="success_added" href="{{ route('admin.products') }}"></a>
    <div class="row p-3 m-md-4 bg-white shadow ">
        <div class="col-md-12 py-4 pb-3 d-flex justify-content-between">
            <h3>Add New Product</h3>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="form-group bg-white p-3 col-md-6">
                    <label for="pname">Product Type</label>
                    <select class="form-control" id="type-selector" onchange="image_reset(event)">
                        <option value="sp"> Simple Product </option>
                        <option value="vp"> Variable Product </option>
                        <!-- <option value="gp"> Grouped Product </option> -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class=" row sp add-product">
                        <div class="bg-white p-3 col-md-6">
                            <form id="sp_form">
                                <meta name="_token" content="{{ csrf_token() }}" />
                                <div class="form-group">
                                    <label for="pname">Product Name</label>
                                    <input type="text" name="product_name" id="pname" class="form-control"
                                        placeholder="Product Name">
                                </div>
                                <div class="d-flex">
                                    <div class="form-group w-50 px-1">
                                        <label for="qty">Qty</label>
                                        <input type="text" name="product_qty" id="qty" class="form-control"
                                            placeholder="Qty 1">
                                    </div>
                                    <div class="form-group w-50 px-1">
                                        <label for="price">Price</label>
                                        <input name="product_price" type="text" id="price" class="form-control"
                                            placeholder="$ 100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea rows="7" name="product_description" id="description" class="form-control" placeholder="product description"></textarea>
                                </div>
                                <div class=" form-group">
                                    <div class="bg-white d-flex justify-content-between">
                                        <div class="container mt-3 w-100">
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between bg-white">
                                                    <h4>Product Images</h4>
                                                    <input type="file" id="sp_image" class="d-none"
                                                        onchange="image_select(event)" multiple>
                                                    <button class="btn btn-sm btn-primary" type="button"
                                                        onclick="document.getElementById('sp_image').click()">Choose
                                                        Images</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 p-3">
                            <div class="bg-white d-flex flex-wrap justify-content-start" id="container"></div>
                        </div>
                    </div>

                    <div class="vp add-product d-none row">
                        <div class="bg-white p-3 col-md-6">
                            <form id="vp_form">
                                <meta name="_token" content="{{ csrf_token() }}" />
                                <div class="form-group">
                                    <label for="pname">Product Name</label>
                                    <input type="text" name="product_name" id="pname" class="form-control"
                                        placeholder="Product Name">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="product_description" id="description" class="form-control"
                                        placeholder="product description"></textarea>
                                </div>
                                <div class=" form-group">
                                    <div class="bg-white d-flex justify-content-between">
                                        <div class="container mt-3 w-100">
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between bg-white">
                                                    <h4>Product Images</h4>
                                                    <input type="file" id="vp_image" class="d-none"
                                                        onchange="image_select(event)" multiple>
                                                    <button class="btn btn-sm btn-primary" type="button"
                                                        onclick="document.getElementById('vp_image').click()">Choose
                                                        Images</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-3">
                                    <div class="row">
                                        <div id="varient_headings" class="col-md-12 d-none">
                                            <div class="row mb-4">
                                                <div class="col-md-4 col-lg-4">
                                                    <label for="varient">Varient</label>
                                                </div>
                                                <div class="col-md-3 col-lg-3">
                                                    <label for="varient">Quantity</label>
                                                </div>
                                                <div class="col-md-3 col-lg-3">
                                                    <label for="varient">Price</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="cover__selected__varient">
                                            <!-- here display selected__ varients -->
                                        </div>
                                    </div>
                                    <div class="row p-0 m-0">
                                        <div class="col-md-4 col-lg-4 p-0 px-1">
                                            <label for="varient">Varient</label>
                                            <input type="text" id="varient" class="form-control" placeholder="Varient">
                                        </div>
                                        <div class="col-md-4 col-lg-3 p-0 px-1">
                                            <label for="qty">Quantity</label>
                                            <input type="number" id="v_qty" class="form-control" placeholder="10">
                                        </div>
                                        <div class="col-md-4 col-lg-3 p-0 px-1">
                                            <label for="price">Price</label>
                                            <input type="number" id="v_price" class="form-control" placeholder="Price">
                                        </div>
                                        <div class="col-md-3 col-lg-2 p-0 px-1">
                                            <label for="add_varient"></label><br />
                                            <button id="add_varients" type="button" class="mt-2 btn btn-success"
                                                onclick="add_varient()">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 p-3">
                            <div class="bg-white d-flex flex-wrap justify-content-start" id="vp_container"></div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-md-12" id="sp_answers">
                            @foreach($questions as $key =>  $question)
                                <h5> {{$question->question}} </h5>
                                <select class="form-control mb-3" id="q{{ $key + 1 }}" name="q{{ $key + 1 }}" multiple>
                                    <option value="">--select--</option>
                                    @foreach($question->answers as $answer)
                                        <option value="{{ $answer->id }}">{{ strtoupper($answer->option)}} : {{ $answer->statement }}</option>
                                    @endforeach
                                </select>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <div class="bg-white p-3 col-md-12 product_new_save" id="sp_save">
                            <div class="pt-5 w-100 d-flex justify-content-end ">
                                <button type="button" id="sp_submit" class="btn bgc-primary text-white">Save Product</button>
                            </div>
                        </div>

                        <div class="bg-white p-3 col-md-12 product_new_save d-none" id="vp_save">
                            <div class="pt-5 w-100 d-flex justify-content-end ">
                                <button type="button" id="vp_submit" class="btn bgc-primary text-white">Save Product</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gp add-product d-none row">
                        <div class="bg-white p-3 col-md-6">
                            <form id="gp_form">
                                <meta name="_token" content="{{ csrf_token() }}" />
                                <div class="form-group">
                                    <label for="pname">Product Name</label>
                                    <input type="text" name="product_name" id="pname" class="form-control"
                                        placeholder="Product Name">
                                </div>
                                <div class="form-group">
                                    <label for="gp_price">Price</label>
                                    <input name="price" type="number" id="gp_price" class="form-control"
                                        placeholder="product price">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="product_description" id="description" class="form-control"
                                        placeholder="product description"></textarea>
                                </div>
                                <div class=" form-group">
                                    <div class="bg-white d-flex justify-content-between">
                                        <div class="container mt-3 w-100">
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between bg-white">
                                                    <h4>Product Images</h4>
                                                    <input type="file" id="vp_image" class="d-none"
                                                        onchange="image_select(event)" multiple>
                                                    <button class="btn btn-sm btn-primary" type="button"
                                                        onclick="document.getElementById('vp_image').click()">Choose
                                                        Images</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-3">
                                    <div class="row">
                                        <div id="products_headings" class="col-md-12 d-none">
                                            <div class="row mb-4">
                                                <div class="col-md-5 col-lg-5">
                                                    <label for="">Product</label>
                                                </div>
                                                <div class="col-md-5 col-lg-5">
                                                    <label>Quantity</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="cover__selected__products">
                                            <!-- here display selected__ products -->
                                        </div>
                                    </div>
                                    <div class="row p-0 m-0">
                                        <div class="col-md-5 col-lg-5 p-0 px-1">
                                            <label for="product__name">Product</label>
                                            <input type="text" id="product__name" class="form-control"
                                                placeholder="product name">
                                        </div>
                                        <div class="col-md-5 col-lg-5 p-0 px-1">
                                            <label for="product__qty"> Quantity </label>
                                            <input type="number" id="product__qty" class="form-control"
                                                placeholder="456">
                                        </div>
                                        <div class="col-md-2 col-lg-2 p-0 px-1">
                                            <label for="add_products_btn"></label><br />
                                            <button id="add_products_btn" type="button" class=" mt-2 btn btn-success "
                                                onclick="add_new_product()">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 p-3">
                            <div class="bg-white d-flex flex-wrap justify-content-start" id="gp_container">

                            </div>
                        </div>

                        <div class="bg-white p-3 col-md-12">
                            <div class="pt-5 w-100 d-flex justify-content-end ">
                                <button type="button" id="gp_form_submit" class="btn btn-primary"> Save Product</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        let images      = [];
        let varients    = [];
        let products    = [];
        let answers     = [];
        let formData;
        $(document).ready(() => 
        {
            $('#type-selector').on('change', () => 
            {
                $('.add-product').addClass('d-none');
                $('.product_new_save').addClass('d-none');
                let curr     = '.' + $('#type-selector').val();
                let curr_btn = '#' + $('#type-selector').val() + "_save";
                $(curr).removeClass('d-none');
                $(curr_btn).removeClass('d-none');
                console.log('curr', curr_btn);
            })
        });

        function add_varient() {
            let varient = $('#varient').val();
            let qty = $('#v_qty').val();
            let price = $('#v_price').val();

            $('#varient').val('');
            $('#v_qty').val('');
            $('#v_price').val('');

            varients.push({
                varient,
                qty,
                price
            })
            console.log(varients);
            dispaly_varient();
        }

        function dispaly_varient() {
            if (varients.length > 0) {
                $('#cover__selected__varient').html('')
                $('#varient_headings').removeClass('d-none')
            }
            varients.forEach((ele, index) => {
                $('#cover__selected__varient').append(
                    '<div class="row mb-4">' +
                    '<div class="col-md-4 col-lg-4">' +
                    '<label for="varient">' + ele.varient + '</label>' +
                    '</div>' +
                    '<div class="col-md-3 col-lg-3">' +
                    '<label for="varient">' + ele.qty + '</label>' +
                    '</div>' +
                    '<div class="col-md-3 col-lg-3">' +
                    '<label for="varient">' + ele.price + '</label>' +
                    '</div>' +
                    '<div class="col-md-3 col-lg-2">' +
                    '<label class="fa fa-times" style="cursor:pointer" onclick="varient_remove(' + index +
                    ')"  data-index=' + index + '></label>' +
                    '</div>' +
                    '</div>'
                )
            });
        }

        function varient_remove(index) {
            if (index > -1) {
                varients.splice(index, 1);
            }
            dispaly_varient();
        }

        function add_new_product() {
            let product = $('#product__name').val();
            let qty = $('#product__qty').val();
            $('#product__name').val('');
            $('#product__qty').val('');
            products.push({
                product,
                qty
            })
            console.log('products', products);
            dispaly_product();
        }

        function dispaly_product() {

            if (products.length > 0) {
                $('#cover__selected__products').html('')
                $('#products_headings').removeClass('d-none')
            } else {
                $('#cover__selected__products').html('')
                $('#products_headings').addClass('d-none')
            }
            products.forEach((ele, index) => {
                $('#cover__selected__products').append(
                    '<div class="row mb-4">' +
                    '<div class="col-md-4 col-lg-4">' +
                    '<label for="varient">' + ele.product + '</label>' +
                    '</div>' +
                    '<div class="col-md-3 col-lg-3">' +
                    '<label for="varient">' + ele.qty + '</label>' +
                    '</div>' +
                    '<div class="col-md-3 col-lg-2">' +
                    '<label class="fa fa-times" style="cursor:pointer" onclick="product_remove(' + index +
                    ')"  data-index=' + index + '></label>' +
                    '</div>' +
                    '</div>'
                )
            });
        }

        function product_remove(index) {
            console.log('removing ', index);
            if (index > -1) {
                products.splice(index, 1);
            }
            dispaly_product();
        }

        // Images Preview

        function image_select(e) {
            var image = e.target.files;
            // var image = document.querySelector('.image').files;
            for (i = 0; i < image.length; i++) {
                if (check_duplicate(image[i].name)) {
                    images.push({
                        "name": image[i].name,
                        "url": URL.createObjectURL(image[i]),
                        "file": image[i],
                    })
                } else {
                    alert(image[i].name + " is already added to the list");
                }
            }

            // document.getElementById('form').reset();
            document.getElementById('container').innerHTML = image_show();
            document.getElementById('vp_container').innerHTML = image_show();
            document.getElementById('gp_container').innerHTML = image_show();
        }

        // this function will show images in the DOM

        function image_show() {
            var image = "";
            images.forEach((i) => {
                image += `<div class="image_container d-flex justify-content-center position-relative">
                                    <img src="` + i.url + `" alt="Image">
                                    <span class="position-absolute" onclick="delete_image(` + images.indexOf(i) + `)">&times;</span>
                                </div>`;
            })
            return image;
        }

        // this function will delete a specific Image from the container

        function delete_image(e) {
            images.splice(e, 1);
            document.getElementById('container').innerHTML = image_show();
            document.getElementById('vp_container').innerHTML = image_show();
        }

        // this function will check duplicate images

        function check_duplicate(name) {
            var image = true;
            if (images.length > 0) {
                for (e = 0; e < images.length; e++) {
                    if (images[e].name == name) {
                        image = false;
                        break;
                    }
                }
            }
            return image;
        }

        // Image Reset

        function image_reset(event) {
            images = [];
            document.getElementById('container').innerHTML = image_show();
            document.getElementById('vp_container').innerHTML = image_show();
        }

        // this function will get all images from the array

        function get_image_data() {
            var form = new FormData()
            for (let index = 0; index < images.length; index++) {
                form.append("file[" + index + "]", images[index]['file']);
            }
            return form;
        }

        $('#sp_submit').click(function() {
            let myForm = document.getElementById('sp_form');
            formData = new FormData(myForm);
            formData.append("type", 'sp');
            for (let index = 0; index < images.length; index++) {
                formData.append("file[" + index + "]", images[index]['file']);
            }
            let id, val, question;

            for (let i = 1; i < 6; i++) 
            {
                id          ="#q"+ i;
                val         = $(id).val();
                if (val !== "") {
                    answers.push(val);
                    // answers = {...answers, [question] : val }
                    // answers.push([question] : val);
                }
            }
            console.log('answers', answers);
            formData.append("answers", JSON.stringify(answers));
            answers = [];
            submit_form_data(formData)
        });

        $('#vp_submit').click(function() {

            // alert('vp_form')
            let myForm = document.getElementById('vp_form');
            formData = new FormData(myForm);
            formData.append("type", 'vp');
            for (let index = 0; index < images.length; index++) {
                formData.append("file[" + index + "]", images[index]['file']);
            }
            formData.append("varients", JSON.stringify(varients));
            submit_form_data(formData)
        });

        $('#gp_form_submit').click(function() {
            // alert('gp_form')
            let myForm = document.getElementById('gp_form');
            formData = new FormData(myForm);
            formData.append("type", 'gp');
            for (let index = 0; index < images.length; index++) {
                formData.append("file[" + index + "]", images[index]['file']);
            }
            formData.append("products", JSON.stringify(products));
            submit_form_data(formData)
        });

        function submit_form_data(formData) {
            console.log('formData', formData);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.adds-product') }}",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                success: function(res) {
                    if (res.status === 200) {
                        toastr.success("Product Addred Successfully"); 
                        setTimeout(() => {
                            document.getElementById("success_added").click();
                        }, 400);
                    }
                }
            })
        }
    </script>
@endpush