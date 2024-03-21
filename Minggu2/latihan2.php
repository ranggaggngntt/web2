<?php

$nama = $_GET['nama'];
$nim = $_GET['nim'];
$jurusan = $_GET['jurusan'];
$email = $_GET['email'];
$img = $_GET['img'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <img src="<?= $img; ?>" alt="">
    <h1><?= $nama; ?></h1>
    <h1><?= $nim; ?></h1>
    <h1><?= $jurusan; ?></h1>
    <h1><?= $email; ?></h1>
</body>
</html>