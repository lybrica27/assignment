@extends('admin.layouts.app')

@section('content')
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Item</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" method="post" name="productForm" id="productForm">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-7">
                        <div class="card mb-3">
                            <div class="card-body">								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="name">Item Name</label>
                                            <input type="text" name="name" id="name" value="{{ $products->name }}" class="form-control" placeholder="Input Name">	
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control">
                                                <option value="">Select Category</option>
                                                @if($categories->isNotEmpty())
                                                    @foreach($categories as $category)
                                                        <option {{ ($products->category_id == $category->id)? 'selected':'' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" value="{{ $products->price }}" id="price" class="form-control" placeholder="Price">
                                            <p class="error"></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"> {{ $products->description }} </textarea>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">								
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="condition">Select Item Condition</label>
                                            <select name="condition" id="condition" class="form-control">
                                                <option {{ ($products->condition == "new")? 'selected':'' }} value="new">New</option>
                                                <option {{ ($products->condition == "used")? 'selected':'' }} value="used">Used</option>
                                                <option {{ ($products->condition == "good-secondhand")? 'selected':'' }} value="good-secondhand">Good Second Hand</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type">Select Item Type</label>
                                            <select name="type" id="type" class="form-control">                                              
                                                <option {{ ($products->type == "sell")? 'selected':'' }} value="sell">Sell</option>
                                                <option {{ ($products->type == "buy")? 'selected':'' }} value="buy">Buy</option>
                                                <option {{ ($products->type == "exchange")? 'selected':'' }} value="exchange">Exchange</option>
                                            </select>
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="publish">Publish</label>
                                            <select name="publish" id="publish" class="form-control">
                                                <option {{ ($products->publish == 'yes')? 'selected':'' }} value="yes">Yes</option>
                                                <option {{ ($products->publish == 'no')? 'selected':'' }} value="no">No</option>
                                            </select>
                                        </div>
                                    </div>                                     
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <input type="hidden" name="item_image" id="image_id" value="">
                                <h2 class="h4 mb-3">Item Photo</h2>								
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br>Drop files here or click to upload.<br><br>                                            
                                    </div>
                                </div>
                            </div>	                                                                      
                        </div>
                        
                    </div>
                    <div class="col-md-5">

                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Owner Information</h2>
                                <div class="mb-3">
                                    <label for="name">Owner Name</label>
                                    <input type="text" name="owner_name" value="{{ $products->owner_name }}" id="name" class="form-control" placeholder="Input Owner Name">	
                                </div>
                                <div class="mb-3">
                                    <label for="contact">Contact Number</label>
                                    <input type="tel" name="owner_contact" value="{{ $products->owner_contact }}" id="contact" class="form-control" placeholder="Phone Number">	
                                </div>
                                <div class="mb-3">
                                    <label for="address">Address</label>
                                    <textarea name="owner_address" id="address" class="form-control" placeholder="Enter Address" rows="5"> {{ $products->owner_address }} </textarea>
                                </div>
                            </div>
                        </div> 

                        <div id="map"></div>
                                                       
                    </div>
                </div>
                
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
            <!-- /.card -->
        </form>

    </section>
@endsection

@section('script')
    <script>
        $(function () {
            // Summernote
            $('.summernote').summernote({
                height: '300px'
            });
        });   

        $('#productForm').submit(function(event){
            event.preventDefault();
            var formArray = $(this).serializeArray();

            $.ajax({
                url : '{{ route('products.update', $products->id) }}',
                type : 'put',
                data : formArray,
                dataType : 'json',
                success : function(response){
                    if( response['status'] == true ){           
                        window.location.href = "{{ route('products.index') }}";
                    }
                    
                    else{
                        var errors = response['errors'];
                        $.each(errors, function(key,value){
                            $(`#${key}`).on('input',function(){
                                $(this).removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                            })
                        });
                        $.each(errors, function(key,value){
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(value);
                        });
                    };

                },
                error : function(){
                    console.log("something went wrong");
                }
            })
        })

        //Dropzone
        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init:function(){
                this.on('addedfile',function(file){
                    if(this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",   
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg, image/png, image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                $("#image_id").val(response.image_id);
                //console.log(response);
            }
        }) 

        var map = L.map('map').setView([16.8409, 96.1735], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);
        var marker = L.marker([12.84, 91.17]).addTo(map);
        var popup = L.popup();
        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent("You clicked the map at " + e.latlng.toString())
                .openOn(map);
        }

map.on('click', onMapClick);

map.on('click', onMapClick);
        

    </script>
@endsection