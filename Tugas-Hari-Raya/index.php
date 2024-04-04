<?php
include 'functions.php';
$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-fluid">
        <h1 class="mb-4">Product List</h1>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button class="btn btn-primary" id="addProductBtn" data-toggle="modal" data-target="#addProductModal">Add Product</button>
            </div>
            <div class="col-md-6 mb-3 d-flex justify-content-end">
                <div class="mr-2">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
                </div>
                <div>
                    <button class="btn btn-primary" id="searchBtn">Search</button>
                </div>
            </div>
        </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Image</th> 
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
            </tbody>
        </table>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="productForm" enctype="multipart/form-data">
                        <input type="hidden" id="product_id" name="product_id">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <?php
                                $categories = $db->readCategory('categories');
                                foreach ($categories as $category) {
                                    echo "<option value='" . $category['category_id'] . "'>" . $category['category_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <select class="form-control" id="brand" name="brand" required>
                                <?php
                                $brands = $db->readCategory('brands');
                                foreach ($brands as $brand) {
                                    echo "<option value='" . $brand['brand_id'] . "'>" . $brand['brand_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveProductBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
$(document).ready(function () {
    function loadProducts() {
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: { action: 'load' },
            success: function (response) {
                $('tbody').html(response);
            }
        });
    }

    $('#addProductBtn').click(function () {
        $('#addProductModal').modal('show');
        $('#productForm')[0].reset();
        $('#productForm').attr('action', 'add');
        $('#addProductModalLabel').text('Add Product');
    });

    // Add or Update function
    $('#saveProductBtn').click(function () {
        var formData = new FormData($('#productForm')[0]);
        var action = $('#productForm').attr('action');
        formData.append('action', action);

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response === 'success') {
                    var successMessage = (action === "add") ? 'Product added successfully!' : 'Product updated successfully!';
                    Swal.fire({
                        icon: 'success',
                        title: successMessage,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#addProductModal').modal('hide');
                    loadProducts();
                } else {
                    var errorMessage = (action === "add") ? 'Failed to add product' : 'Failed to update product';
                    Swal.fire({
                        icon: 'error',
                        title: errorMessage,
                        text: 'Please try again later.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        });
    });

    // Delete function
    $(document).on('click', '.delete-product', function () {
        var productId = $(this).data('product-id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: { action: 'delete', product_id: productId },
                success: function (response) {
                    if (response === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Product deleted successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        loadProducts();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to delete product',
                            text: 'Please try again later.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        }
    });

    // Get data for modal
    $(document).on('click', '.update-product', function () {
        var productId = $(this).data('product-id');
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: { action: 'get_product', product_id: productId },
            success: function (response) {
                var productDetails = JSON.parse(response)[0];
                $('#product_id').val(productDetails.product_id);
                $('#productName').val(productDetails.product_name);
                $('#description').val(productDetails.description);
                $('#price').val(productDetails.price);
                $('#quantity').val(productDetails.qty);
                $('#category option[value="' + productDetails.category_id + '"]').prop('selected', true);
                $('#brand option[value="' + productDetails.brand_id + '"]').prop('selected', true);
                
                $('#productForm').attr('action', 'update');
                $('#addProductModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error occurred while get product',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    // Search function
    $(document).on('click', '#searchBtn', function () {
        var query = $('#searchInput').val();

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: {
                action: 'search',
                query: query
            },
            success: function (response) {
                $('tbody').html(response);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error occurred while searching',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    // initiate for product loaded
    loadProducts();
});
</script>
</body>
</html>
