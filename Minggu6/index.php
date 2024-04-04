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
        <button class="btn btn-primary" id="addProductBtn" data-toggle="modal" data-target="#addProductModal">Add Product</button><br><br>

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
                        <form id="addProductForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" products="3" required></textarea>
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
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
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
                        <button type="button" class="btn btn-primary" id="submitProductBtn">Submit</button>
                    </div>
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
            <tbody>
        </tbody>
    </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
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

            // Add product
            $(document).on('click', '#submitProductBtn', function (event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('addProductForm'));
                formData.append('action', 'add');
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response === 'success') {
                            $('#addProductModal').modal('hide');
                            loadProducts(); // Reload product list
                        } else {
                            alert('Failed to add product. Please try again later.');
                        }
                    }
                });
            });

            // Delete product
            $(document).on('click', '#deleteProductBtn', function () {
            var productId = $(this).data('product-id');
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: { action: 'delete', product_id: productId },
                    success: function (response) {
                        if (response === 'success') {
                            loadProducts();
                        } else {
                            alert('Failed to delete product. Please try again later.');
                        }
                    }
                });
            }
        });

            // Load product
            loadProducts();
        });
    </script>
</body>
</html>
