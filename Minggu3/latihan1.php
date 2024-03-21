<?php

$mahasiswa = [
    [
        "nim" => "23501122",
        "nama" => "rangga",
        "jurusan" => "Sistem Informasi",
        "email" => "rangga@ranggahaxor.com",
        "img" =>  "img1.png"
    ],
    [
        "nim" => "23501120",
        "nama" => "siswanto",
        "jurusan" => "Teknologi Informasi",
        "email" => "siswanto@stimata.ac.id",
        "img" =>  "img2.jpg"
    ],
    [
        "nim" => "23501121",
        "nama" => "dwi",
        "jurusan" => "Teknologi Informasi",
        "email" => "dwi@stimata.ac.id",
        "img" =>  "img3.jpg"
    ],

];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Daftar Mahasiswa</h1>
    <?php foreach ($mahasiswa as $mhs) : ?>
        <ul>
            <li>
                <a href="latihan2.php?nama=<?php echo $mhs['nama']; ?>&nim=<?php echo $mhs['nim']; ?>&jurusan=<?php echo $mhs['jurusan']; ?>&email=<?php echo $mhs['email']; ?>&img=<?php echo $mhs['img']; ?>" > <?php echo $mhs['nama']; ?></a>
            </li>
        </ul>
    <?php endforeach; ?>
</body>
</html>