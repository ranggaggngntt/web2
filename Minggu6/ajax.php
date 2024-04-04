<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "functions.php";

    $db = new Database();

    if ($_POST["action"] == "load") {
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
                echo "<td><button class='btn btn-danger delete-product' id='deleteProductBtn' data-product-id='" . $product['product_id'] . "'>Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo '<tr><td colspan="7">No products found.</td></tr>';
        }
    } elseif ($_POST["action"] == "add") {
        $productName = $_POST["productName"];
        $description = $_POST["description"];
        $price = $_POST["price"];
        $quantity = $_POST["quantity"];
        $category = $_POST["category"];
        $brand = $_POST["brand"];
    
        $image = $_FILES["image"];
    
        $result = $db->addProduct($productName, $description, $price, $quantity, $image, $category, $brand);
    
        if ($result) {
            echo "success";
        } else {
            error_log("Failed to add product: " . $db->getErrorMessage());
    
            echo "error: " . $db->getErrorMessage();
        }
    } elseif ($_POST["action"] == "delete") {
        $productId = $_POST["product_id"];
        $result = $db->deleteProduct($productId);
        if ($result) {
            echo "success";
        } else {
            error_log("Failed to add product: " . $db->getErrorMessage());
    
            echo "error: " . $db->getErrorMessage();
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>
