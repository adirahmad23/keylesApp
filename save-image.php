<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $count = $_POST['count']; // Nomor gambar yang sedang diambil
    $imageData = $_POST['image'];

    $folder = 'labels/' . $name;
    if (!file_exists($folder)) {
        mkdir($folder, 0755, true);
    }

    $filename = $folder . '/' . $count . '.png'; // Menggunakan nomor gambar sebagai nama file
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $decodedImage = base64_decode($imageData);

    if (file_put_contents($filename, $decodedImage)) {
        http_response_code(200);
    } else {
        http_response_code(500);
    }
} else {
    http_response_code(405); // Metode Tidak Diizinkan
}
?>
