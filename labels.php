<?php
include 'proses/koneksi.php';
$kon = new Koneksi();

$abc = $kon->kueri("SELECT * FROM label");
$data = $kon->hasil_array($abc);

$namaOnly = array_column($data, 'nama');

// Menghasilkan array JavaScript dari PHP
$namaOnlyJson = json_encode($namaOnly);

echo $namaOnlyJson;
?>