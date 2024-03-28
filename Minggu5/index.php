<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Product</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

<h2>Semua Product</h2>

<table>
    <tr>
        <th>Nama</th>
        <th>Deskripsi</th>
        <th>Harga</th>
        <th>Quantity</th>
        <th>Kategori</th>
        <th>Brand</th>
    </tr>

    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "eshop";

        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die("Koneksi Gagal: " . $conn->connect_error);
        }
        $sql = "SELECT p.product_name, p.description, p.price, p.qty, c.category_name, b.brand_name 
                FROM products p 
                INNER JOIN categories c ON p.categories_id = c.category_id
                INNER JOIN brands b ON p.brand_id = b.brand_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["product_name"]. "</td>";
                echo "<td>" . $row["description"]. "</td>";
                echo "<td>Rp. " . $row["price"]. "</td>";
                echo "<td>" . $row["qty"]. "</td>";
                echo "<td>" . $row["category_name"]. "</td>";
                echo "<td>" . $row["brand_name"]. "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada product.</td></tr>";
        }

        $conn->close();
    ?>
</table>
</body>
</html>
