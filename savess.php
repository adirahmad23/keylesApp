<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $imageData = $_POST["imageData"];
  $folderName = $_POST["folderName"];

  if (!is_dir("label/$folderName")) {
    mkdir("label/$folderName");
  }

  $timestamp = time();
  $imageName = "snapshot_$timestamp.png";
  $imagePath = "label/$folderName/$imageName";

  file_put_contents($imagePath, base64_decode($imageData));
  echo $imagePath;
} else {
  echo "Invalid Request";
}
?>
