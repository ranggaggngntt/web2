<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "eshop";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }

    public function readData() {
        $query = "SELECT p.product_id, p.product_name, p.description, p.price, p.qty, p.img, c.category_name, b.brand_name 
        FROM products p 
        INNER JOIN categories c ON p.categories_id = c.category_id
        INNER JOIN brands b ON p.brand_id = b.brand_id";
        
        try {
            $result = $this->conn->query($query);
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                return array();
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function readCategory($category){
        $query = "SELECT * FROM `$category`";
        try {
            $result = $this->conn->query($query);
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                return array();
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    private function uploadImage($image) {
        $targetDir = "images/";
        $targetFileName = $targetDir . uniqid() . "_" . basename($image["name"]);
        if (move_uploaded_file($image["tmp_name"], $targetFileName)) {
            return $targetFileName;
        } else {
            return false;
        }
    }

    public function getProductById($productId) {
        $query = "SELECT p.product_id, p.product_name, p.description, p.price, p.qty, p.img, c.category_name, b.brand_name 
        FROM products p 
        INNER JOIN categories c ON p.categories_id = c.category_id
        INNER JOIN brands b ON p.brand_id = b.brand_id WHERE p.product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return array();
        }
    }

    public function addProduct($productName, $description, $price, $quantity, $image, $category, $brand) {
        $productName = $this->conn->real_escape_string($productName);
        $description = $this->conn->real_escape_string($description);
        $price = (int)$price;
        $quantity = (int)$quantity;
        $image = $this->uploadImage($image);
        if ($image === false) {
            return false;
        }
        $query = "INSERT INTO products (product_name, description, price, qty, img, categories_id, brand_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdisii", $productName, $description, $price, $quantity, $image, $category, $brand);
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error adding product: " . $stmt->error;
        }
    }

    public function deleteProduct($productId) {
        $query = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $productId);
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error deleting product: " . $stmt->error;
        }
    }

    public function searchProducts($query) {
        $query = "SELECT p.product_id, p.product_name, p.description, p.price, p.qty, p.img, c.category_name, b.brand_name 
        FROM products p 
        INNER JOIN categories c ON p.categories_id = c.category_id
        INNER JOIN brands b ON p.brand_id = b.brand_id WHERE product_name LIKE '%$query%'";
        
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return array();
        }
    }

    public function updateProduct($productId, $productName, $description, $price, $quantity, $image, $category, $brand, $updateImage = false) {
        $productName = $this->conn->real_escape_string($productName);
        $description = $this->conn->real_escape_string($description);
        $price = (double)$price;
        $quantity = (int)$quantity;
    
        if ($updateImage) {
            $image = $this->uploadImage($image);
            if ($image === false) {
                return false;
            }
        }
    
        if ($updateImage) {
            $query = "UPDATE products SET product_name = ?, description = ?, price = ?, qty = ?, img = ?, categories_id = ?, brand_id = ? WHERE product_id = ?";
        } else {
            $query = "UPDATE products SET product_name = ?, description = ?, price = ?, qty = ?, categories_id = ?, brand_id = ? WHERE product_id = ?";
        }
    
        $stmt = $this->conn->prepare($query);
        if ($updateImage) {
            $stmt->bind_param("ssdissii", $productName, $description, $price, $quantity, $image, $category, $brand, $productId);
        } else {
            $stmt->bind_param("ssdiisi", $productName, $description, $price, $quantity, $category, $brand, $productId);
        }
    
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error updating product: " . $stmt->error;
        }
    }
}

?>
