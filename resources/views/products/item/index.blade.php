@extends('layouts.dashboard-layout')
@push('stylesheets')
    <link href="{{ asset('./dist/libs/dropzone/dist/dropzone.css') }}" rel="stylesheet" />
@endpush
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

    {{-- ITEM ADD EDIT MODAL --}}
    <div class="modal" id="addItemModal" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="categoryId" name="category_id">
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
                            <div id="itemNameInvalid" class="invalid-feedback"></div>
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
                            <input type="number" class="form-control" id="itemStock" placeholder="Stock" />
                            <div id="stockInvalid" class="invalid-feedback"></div>
                        </div>
                        <input type="hidden" id="c" name="uploadedFileName">
                        <button type="submit" class="d-none"></button>
                    </form>
                    <form class="dropzone" id="dropzone-custom" action="#" autocomplete="off" novalidate>
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <div class="dz-message">
                            <h3 class="dropzone-msg-title">Media</h3>
                            <span class="dropzone-msg-desc">Upload Your Item Photo Here</span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="button" id="submitAddBtn" class="btn btn-primary ms-auto" data-bs-dismiss="modafl">
                        Save
                    </button>
                </div>
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
    <script src="{{ asset('./dist/libs/dropzone/dist/dropzone-min.js') }}" defer></script>
    <script type="text/javascript">
        $(function() {

            function fetchCategoriesAndPopulateSelect(selector, currentCategoryId = null) {
                $.ajax({
                    url: '{{ route('product.category.get-all-category') }}',
                    type: 'GET',
                    success: function(response) {
                        var categories = response.data;

                        $(selector).empty();
                        $(selector).append('<option value="">Select Category</option>');

                        $.each(categories, function(index, category) {
                            var option = $('<option></option>').attr('value', category.id).text(
                                category.category_name);

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
                var carouselIndicators = '';
                var carouselItems = '';

                // Loop through the item files to generate carousel indicators and items
                d.item_files.forEach(function(itemFile, index) {
                    var imageUrl = '{{ asset('') }}' + itemFile.file_path;
                    carouselIndicators +=
                        '<button type="button" data-bs-target="#carousel-indicators-thumb" data-bs-slide-to="' +
                        index + '" class="ratio ratio-4x3' + (index === 0 ? ' active' : '') +
                        '" style="background-image: url(' + imageUrl + ')"></button>';
                    carouselItems += '<div class="carousel-item' + (index === 0 ? ' active' : '') + '">' +
                        '<img class="d-block w-100" alt="" src="' + imageUrl +
                        '" style="max-width: 100%; max-height: 300px;" />' +
                        '</div>';
                });

                return '<div class="row-details">' +
                    '<p><strong>Item ID:</strong> ' + d.id + '</p>' +
                    '<p><strong>Category:</strong> ' + d.category_name + '</p>' +
                    '<p><strong>Stock:</strong> ' + d.stock + '</p>' +
                    '<div id="carousel-indicators-thumb" class="carousel slide carousel-fade" data-bs-ride="carousel">' +
                    '<div class="carousel-indicators carousel-indicators-thumb">' +
                    carouselIndicators +
                    '</div>' +
                    '<div class="carousel-inner">' +
                    carouselItems +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }

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

            var selectedFiles = [];

            var myDropzone = new Dropzone("#dropzone-custom", {
                uploadMultiple: true,
                autoProcessQueue: false, // Disable auto processing
                paramName: "file", // Name of the file parameter
                maxFilesize: 10, // Maximum file size in MB
                acceptedFiles: ".jpg, .jpeg, .png, .gif, .webp", // Allowed file types
                addRemoveLinks: true, // Add remove button
                dictRemoveFile: "Remove", // Text for remove button
                maxFiles: 10,
                parallelUploads: 10,
            });

            // Custom event handler saat file di tambahkan 
            myDropzone.on("addedfile", function(file) {
                selectedFiles.push(file);
            });

            // Function to remove files from the array when removed from Dropzone
            myDropzone.on("removedfile", function(file) {
                var index = selectedFiles.indexOf(file);
                if (index !== -1) {
                    selectedFiles.splice(index, 1);
                }
            });

            $("#submitAddBtn").on("click", function() {
                $("#addItemForm").find("[type='submit']").trigger("click");
            });

            // saat click tombol save
            // pada form add item
            $('#addItemForm').on('submit', function(event) {
                event.preventDefault();

                var itemName = $('#itemName').val();
                var itemCategory = $('#categorySelector').val();
                var itemStock = $('#itemStock').val();

                if (selectedFiles.length === 0) {
                    toastr.warning('Please upload a file.');
                    return;
                }

                myDropzone.processQueue();

                // Menunggu proses upload file selesai
                myDropzone.on('queuecomplete', function() {
                    var formData = new FormData();
                    formData.append('_method', 'POST');
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('name', itemName);
                    formData.append('category', itemCategory);
                    formData.append('stock', itemStock);
                    selectedFiles.forEach(function(file, index) {
                        formData.append('files[' + index + ']', file);
                    });

                    $.ajax({
                        url: '{{ route('product.item.store') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            myDropzone.removeAllFiles(true);
                            $('.data-table').DataTable().ajax.reload(null, false);
                            selectedFiles = [];
                            var notification = response.notification;
                            if (notification) {
                                toastr[notification.type](notification.message);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                var errors = xhr.responseJSON.errors;
                                $('#itemName').addClass('is-invalid');
                                $('#categorySelector').addClass('is-invalid');
                                $('#itemStock').addClass('is-invalid');
                                $('#itemNameInvalid').html(errors.name);
                                $('#categoryInvalid').html(errors.category);
                                $('#stockInvalid').html(errors.stock);
                            } else {
                                console.log(xhr);
                            }
                        }
                    });
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
