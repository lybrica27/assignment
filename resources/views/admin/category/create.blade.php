@extends('admin.layouts.app')

@section('content')

    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">

            <form action="" method="post" id="categoryForm" name="categoryForm">

                <div class="card">
                    <div class="card-body">								
                        <div class="row">
    
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                    <p></p>
                                </div>
                            </div>

                            <!-- dropzone -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <input type="hidden" name="image_id" id="image_id" value="">	
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick text-center">
                                            <br>Drop Files here or Click to upload.<br><br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>	
                            <!-- /dropzone -->	
                            
                            <div class="col-md-8">
			    				<div class="mb-3">
			    					<label for="publish">Publish</label>
			    					<select name="publish" id="publish" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>	
			    				</div>
			    			</div>	

                        </div>
                    </div>							
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('categories.index')  }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>

            </form>
        </div>
        <!-- /.card -->
    </section>


    

@endsection

@section('script')
    <script>
        $('#categoryForm').submit(function(event){
            event.preventDefault();
            var element = $(this);

            $.ajax({
                url : '{{ route('categories.store') }}',
                type : 'post',
                data : element.serializeArray(),
                dataType : 'json',
                success : function(response){
                    
                    if(response["status"] == true){
                        window.location.href = "{{ route('categories.index') }}"
                        $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html(['']);
                    }

                    else{
                        var errors = response['errors'];
                        if (errors['name']) {
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                        }else{
                            $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html(['']);
                        }
                    }
                },

                error : function(jqXHR){
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
    </script>
@endsection