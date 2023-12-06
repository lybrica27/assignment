@extends('admin.layouts.app')

@section('content')
    <section class="content-header">					
		<div class="container-fluid my-2">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Categories</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{ route('categories.create') }}" class="btn btn-primary">New Category</a>
				</div>
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
    <section class="content">
		
		<div class="container-fluid">
			
			<div class="card">
                <div class="card-body table-responsive ">
                    <table id="myTable" class="display">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Publish</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($categories->isNotEmpty())
                                @foreach($categories as $category)

                                    <tr>
                                        <td class="text-center">
                                            <!-- for editing -->
                                            <a href="{{ route('categories.edit', $category->id) }}">
                                                <svg class="filament-link-icon w-4 h-4 mr-3 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                            <!-- for delete -->
                                            <a href="#" onclick="deleteCategory({{ $category->id }})" class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td class="text-center">
                                            @if($category->publish == 'Yes')
												<svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
													<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
												</svg>
											@else
												<svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
													<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
												</svg>
											@endif
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            @else
                                <tr>
                                    <td>Records not found.</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>  
            </div>  

        </div>

    </section>

@endsection

@section('script')
    @include('admin.message')
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });

        function deleteCategory(id) {
            var url = '{{ route('categories.delete', 'ID') }}'.replace('ID', id);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {},
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status) {
                                Swal.fire('Deleted!', 'Your file has been deleted.', 'success')
                                    .then(() => window.location.href = "{{ route('categories.index') }}");
                            }
                        }
                    });
                }
            });
        }
    </script>

    
@endsection
