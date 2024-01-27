@extends('layouts.dashboard-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Products/Item')
@section('pageTitleInfo', isset($pageTitleInfo) ? $pageTitleInfo : 'Item Information')
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
                                        <button type="button" class="btn btn-md btn-outline-primary" id="addItemBtn">
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
                                                    <th>Item Name</th>
                                                    <th>Category</th>
                                                    <th>Stock</th>
                                                    <th width="105px">Action</th>
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

    {{-- ITEM ADD EDIT MODAL --}}
    <div class="modal" id="addItemModal" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addItemForm" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="categoryId" name="category_id">
                    <div class="modal-body">
                        <label class="form-label">Item Name</label>
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box-seam"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" />
                                    <path d="M12 12l8 -4.5" />
                                    <path d="M8.2 9.8l7.6 -4.6" />
                                    <path d="M12 12v9" />
                                    <path d="M12 12l-8 -4.5" />
                                </svg>
                            </span>
                            <input type="text" class="form-control" id="itemName" placeholder="Your Item Name" />
                            <div id="itemNameInvalid" class="invalid-feedback">asdf</div>
                        </div>
                        <label class="form-label">Category</label>
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
                            <select id="categorySelector" class="form-select">
                            </select>
                            <div id="categoryInvalid" class="invalid-feedback"></div>
                        </div>
                        <label class="form-label">Stock</label>
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-stack-2"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 4l-8 4l8 4l8 -4l-8 -4" />
                                    <path d="M4 12l8 4l8 -4" />
                                    <path d="M4 16l8 4l8 -4" />
                                </svg>
                            </span>
                            <input type="number" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                id="itemStock" placeholder="Stock" />
                            <div id="stockInvalid" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ITEM INFO EDIT MODAL --}}
    <div class="modal" id="editItemModal" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Item Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateItemForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="itemId" name="item_id">
                    <div class="modal-body">
                        <label class="form-label">Item Name</label>
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box-seam"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" />
                                    <path d="M12 12l8 -4.5" />
                                    <path d="M8.2 9.8l7.6 -4.6" />
                                    <path d="M12 12v9" />
                                    <path d="M12 12l-8 -4.5" />
                                </svg>
                            </span>
                            <input type="text" class="form-control" id="currentItemName"
                                placeholder="Your New Item Name" />
                        </div>
                        <label class="form-label">Category</label>
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 4h6v6h-6z" />
                                    <path d="M14 4h6v6h-6z" />
                                    <path d="M4 14h6v6h-6z" />
                                    <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                </svg>
                            </span>
                            <select id="editCategorySelector" class="form-select">
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <label class="form-label">Stock</label>
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-stack-2"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 4l-8 4l8 4l8 -4l-8 -4" />
                                    <path d="M4 12l8 4l8 -4" />
                                    <path d="M4 16l8 4l8 -4" />
                                </svg>
                            </span>
                            <input type="number" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                id="currentItemStock" placeholder="Stock" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
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
                    <div class="text-muted">Do you really want to remove this item? <strong>What you've done cannot be
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
                <input type="hidden" id="itemToDelete" value="">
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {

            // Fetch categories and populate the select input
            function fetchCategoriesAndPopulateSelect(selector, currentCategoryId = null) {
                $.ajax({
                    url: '{{ route('product.category.get-all-category') }}', // Adjust the route to fetch categories
                    type: 'GET',
                    success: function(response) {
                        var categories = response.data; // Assuming your response has a 'data' property

                        // Clear existing options
                        $(selector).empty();

                        // Add a default option
                        $(selector).append('<option value="">Select Category</option>');

                        // Add options for each category
                        $.each(categories, function(index, category) {
                            // $(selector).append('<option value="' + category.id +
                            //     '">' +
                            //     category.category_name + '</option>');
                            var option = $('<option></option>').attr('value', category.id).text(
                                category.category_name);

                            // Check if it's the current category and mark it as selected
                            if (category.id == currentCategoryId) {
                                option.attr('selected', 'selected');
                            }

                            $(selector).append(option);
                        });
                    }
                });
            }

            // inisialisasi data table
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('product.item.index') }}",
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // saat click tombol add
            $('#addItemBtn').click(function() {
                $('#itemName').val('');
                fetchCategoriesAndPopulateSelect('#categorySelector');
                $('#addItemModal').modal('show');
            });

            // saat click tombol edit
            $('.data-table').on('click', '.edit', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('product.item.edit', ['id' => '_id_']) }}'.replace('_id_',
                        id),
                    type: 'GET',
                    success: function(response) {
                        var categoryId = response.categories.length > 0 ? response.categories[0]
                            .id : null;
                        $('#itemId').val(response.id);
                        $('#currentItemName').val(response.item_name);
                        fetchCategoriesAndPopulateSelect('#editCategorySelector', categoryId);
                        $('#currentItemStock').val(response.stock);

                        $('#editItemModal').modal('show');
                    }
                });
            });

            // saat click tombol save
            // pada form add item
            $('#addItemForm').on('submit', function(event) {
                event.preventDefault();

                var itemName = $('#itemName').val();
                var itemCategory = $('#categorySelector').val();
                var itemStock = $('#itemStock').val();

                $.ajax({
                    url: '{{ route('product.item.store') }}',
                    method: 'POST',
                    data: {
                        _method: 'POST',
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: itemName,
                        category: itemCategory,
                        stock: itemStock,
                    },
                    success: function(response) {
                        $('.data-table').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        // Check if the response has JSON data (validation errors)
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Display validation errors below the input field
                            var errors = xhr.responseJSON.errors;
                            $('#itemName').addClass('is-invalid');
                            $('#categorySelector').addClass('is-invalid');
                            $('#itemStock').addClass('is-invalid');
                            $('#itemNameInvalid').html(errors.name);
                            $('#categoryInvalid').html(errors.category);
                            $('#stockInvalid').html(errors.stock);
                        } else {
                            // Handle other types of errors
                            console.log(xhr);
                        }
                    }
                });
            });

            // saat click tombol save
            // pada form update category
            $('#updateItemForm').on('submit', function(event) {
                event.preventDefault();

                var itemId = $('#itemId').val();
                var newItemName = $('#currentItemName').val();
                var newItemCategory = $('#editCategorySelector').val();
                var newItemStock = $('#currentItemStock').val();

                $.ajax({
                    url: '{{ route('product.item.update', ['id' => '_id_']) }}'.replace('_id_',
                        itemId),
                    method: 'POST',
                    data: {
                        _method: 'PATCH',
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: newItemName,
                        category_id: newItemCategory,
                        stock: newItemStock,
                    },
                    success: function(response) {
                        $('.data-table').DataTable().ajax.reload(null, false);
                    }
                });
            });

            // saat click tombol delete
            // pada modal delete confirmation
            $('.data-table').on('click', '.delete', function() {
                var id = $(this).data('id');
                $('#itemToDelete').val(id);
                $('#modal-danger').modal('show');
            });

            $('#confirmDeleteBtn').click(function() {
                const itemId = $('#itemToDelete').val();

                $.ajax({
                    url: '{{ route('product.item.destroy', ['id' => '_id_']) }}'.replace('_id_',
                        itemId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('.data-table').DataTable().ajax.reload(null, false);
                    }
                });
            });
        });
    </script>
@endpush
