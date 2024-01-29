@extends('layouts.dashboard-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Products/Category')
@section('pageTitleInfo', isset($pageTitleInfo) ? $pageTitleInfo : 'Products Category')
@section('content')
    <div class="page">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-md btn-outline-primary" id="addCategoryBtn">
                                            <!-- SVG icon from http://tabler-icons.io -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-plus" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Add
                                        </button>
                                    </div>
                                    <div class="mb-3">
                                        <table class="table table-bordered table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Category</th>
                                                    <th width="160px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CATEGORY ADD EDIT MODAL --}}
    <div class="modal" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addCategoryForm" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="categoryId" name="category_id">
                    <div class="modal-body">
                        <label class="form-label">Category Name</label>
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category-plus"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
                                </svg>
                            </span>
                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                id="categoryName" placeholder="Your Category Name" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CATEGORY INFO EDIT MODAL --}}
    <div class="modal" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Category Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateCategoryForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="categoryId" name="category_id">
                    <div class="modal-body">
                        <label class="form-label">Category Name</label>
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 4h6v6h-6z" />
                                    <path d="M14 4h6v6h-6z" />
                                    <path d="M4 14h6v6h-6z" />
                                    <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                </svg>
                            </span>
                            <input type="text" class="form-control" id="currentCategoryName"
                                placeholder="Your New Category Name" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE SELECTED DATA MODAL --}}
    <div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to remove this category? <strong>What you've done cannot be
                            undone</strong>.
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                    Cancel
                                </a></div>
                            <div class="col"><a href="#" id="confirmDeleteBtn" class="btn btn-danger w-100"
                                    data-bs-dismiss="modal">
                                    Delete
                                </a></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="categoryToDelete" value="">
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {

            // inisialisasi data table
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('product.category.index') }}",
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Event listener untuk menampilkan row details
            $('.data-table tbody').on('click', '.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });

            // Function untuk format row details
            function format(d) {
                return '<div class="row-details">' +
                    '<p><strong>Item ID:</strong> ' + d.id + '</p>' +
                    '<p><strong>Category:</strong> ' + d.category_name + '</p>' +
                    '</div>';
            }

            // saat click tombol add
            $('#addCategoryBtn').click(function() {
                $('#categoryName').val('');
                $('#addCategoryModal').modal('show');
            });

            // saat click tombol edit
            $('.data-table').on('click', '.edit', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('product.category.edit', ['id' => '_id_']) }}'.replace('_id_',
                        id),
                    type: 'GET',
                    success: function(response) {
                        $('#categoryId').val(response.id);
                        $('#currentCategoryName').val(response.category_name);

                        $('#editCategoryModal').modal('show');
                    }
                });
            });

            // saat click tombol save
            // pada form add category
            $('#addCategoryForm').on('submit', function(event) {
                event.preventDefault();

                var categoryName = $('#categoryName').val();

                $.ajax({
                    url: '{{ route('product.category.store') }}',
                    method: 'POST',
                    data: {
                        _method: 'POST',
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: categoryName,
                    },
                    success: function(response) {
                        $('.data-table').DataTable().ajax.reload(null, false);
                        $('#addCategoryModal').modal('hide');
                        var notification = response.notification;
                        if (notification) {
                            toastr[notification.type](notification.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            $('#categoryName').addClass('is-invalid');
                            $('.invalid-feedback').html(errors.name);
                        } else {
                            console.log(xhr);
                        }
                    }
                });
            });

            // saat click tombol save
            // pada form update category
            $('#updateCategoryForm').on('submit', function(event) {
                event.preventDefault();

                var categoryId = $('#categoryId').val();
                var newCategoryName = $('#currentCategoryName').val();

                $.ajax({
                    url: '{{ route('product.category.update', ['id' => '_id_']) }}'.replace('_id_',
                        categoryId),
                    method: 'POST',
                    data: {
                        _method: 'PATCH',
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: newCategoryName,
                    },
                    success: function(response) {
                        $('.data-table').DataTable().ajax.reload(null, false);
                        $('#editCategoryModal').modal('hide');
                        var notification = response.notification;
                        if (notification) {
                            toastr[notification.type](notification.message);
                        }
                    }
                });
            });

            // saat click tombol delete
            // pada modal delete confirmation
            $('.data-table').on('click', '.delete', function() {
                var id = $(this).data('id');
                $('#categoryToDelete').val(id);
                $('#modal-danger').modal('show');
            });

            $('#confirmDeleteBtn').click(function() {
                const categoryId = $('#categoryToDelete').val();

                $.ajax({
                    url: '{{ route('product.category.destroy', ['id' => '_id_']) }}'.replace('_id_',
                        categoryId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('.data-table').DataTable().ajax.reload(null, false);
                        var notification = response.notification;
                        if (notification) {
                            toastr[notification.type](notification.message);
                        }
                    }
                });
            });
        });
    </script>
@endpush
