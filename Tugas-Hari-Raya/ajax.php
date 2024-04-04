<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    require_once "functions.php";

    $db = new Database();
    $action = $_POST["action"];
    if ($action == "load") {
        $products = $db->readData();
        if (!empty($products)) {
            foreach ($products as $product) {
                echo "<tr id='product_" . $product['product_id'] . "'>";
                echo "<td><img src='" . $product['img'] . "' alt='" . $product['product_name'] . "' style='max-width: 50px;'></td>";
                echo "<td>" . $product['product_name'] . "</td>";
                echo "<td>" . $product['description'] . "</td>";
                echo "<td>Rp " . number_format($product['price']) . "</td>";
                echo "<td>" . $product['qty'] . "</td>";
                echo "<td>" . $product['category_name'] . "</td>";
                echo "<td>" . $product['brand_name'] . "</td>";
                echo "<td><div class='d-flex'>";
                echo "<button class='btn btn-info update-product mr-2' data-product-id='" . $product['product_id'] . "'>Update</button>";
                echo "<button class='btn btn-danger delete-product' data-product-id='" . $product['product_id'] . "'>Delete</button>";
                echo "</div></td>";
                echo "</tr>";
            }
        } else {
            echo '<tr><td colspan="7">No products found.</td></tr>';
        }
    } elseif ($action == "add" || $action === "update") {
        $productName = $_POST["productName"];
        $description = $_POST["description"];
        $price = $_POST["price"];
        $quantity = $_POST["quantity"];
        $category = $_POST["category"];
        $brand = $_POST["brand"];
    
        $image = $_FILES["image"];
    
        if ($action === 'add') {
            $result = $db->addProduct($productName, $description, $price, $quantity, $image, $category, $brand);
        } elseif ($action === 'update' && isset($_POST['product_id'])) {
            $productId = $_POST['product_id'];
            if (!empty($_FILES["image"]["name"])) {
                $updateImage = true;
            } else {
                $updateImage = false;
            }
            $result = $db->updateProduct($productId, $productName, $description, $price, $quantity, $image, $category, $brand);
        }
        
        if ($result) {
            echo "success";
        } else {
            error_log("Failed to add product: " . $result);
    
            echo "error: " . $result;
        }
    } elseif ($action == "delete") {
        $productId = $_POST["product_id"];
        $result = $db->deleteProduct($productId);
        if ($result) {
            echo "success";
        } else {
            error_log("Failed to add product: " . $db->getErrorMessage());
    
            echo "error: " . $db->getErrorMessage();
        }
    } elseif ($action == "search") {
        $query = $_POST["query"];
        $filteredProducts = $db->searchProducts($query);
        if (!empty($filteredProducts)) {
            foreach ($filteredProducts as $product) {
                echo "<tr id='product_" . $product['product_id'] . "'>";
                echo "<td><img src='" . $product['img'] . "' alt='" . $product['product_name'] . "' style='max-width: 50px;'></td>";
                echo "<td>" . $product['product_name'] . "</td>";
                echo "<td>" . $product['description'] . "</td>";
                echo "<td>Rp " . number_format($product['price']) . "</td>";
                echo "<td>" . $product['qty'] . "</td>";
                echo "<td>" . $product['category_name'] . "</td>";
                echo "<td>" . $product['brand_name'] . "</td>";
                echo "<td><div class='d-flex'>";
                echo "<button class='btn btn-info update-product mr-2' data-product-id='" . $product['product_id'] . "'>Update</button>";
                echo "<button class='btn btn-danger delete-product' data-product-id='" . $product['product_id'] . "'>Delete</button>";
                echo "</div></td>";
                echo "</tr>";
            }
        } else {
            echo '<tr><td colspan="7">No products found.</td></tr>';
        }
    } elseif ($action === 'get_product' && isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];
        $product = $db->getProductById($productId);
        if ($product) {
            echo json_encode($product);
        } else {
            echo "error: Product not found";
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>
