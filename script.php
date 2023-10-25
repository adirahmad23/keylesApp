<!DOCTYPE html>
<html>
<head>
  <title>Snapshot from Webcam</title>
</head>
<body>
  <h1>Webcam Snapshot</h1>
  <video id="video" width="640" height="480" autoplay></video>
  <button id="snapshotButton">Take Snapshot</button>
  <canvas id="canvas" width="640" height="480"></canvas>
  <img id="snapshot" src="" alt="Snapshot">

  <script>
    const video = document.getElementById("video");
    const canvas = document.getElementById("canvas");
    const snapshotButton = document.getElementById("snapshotButton");
    const snapshot = document.getElementById("snapshot");

    navigator.mediaDevices.getUserMedia({ video: true })
      .then(function (stream) {
        video.srcObject = stream;
      })
      .catch(function (error) {
        console.log("Something went wrong with the webcam: " + error);
      });

    snapshotButton.addEventListener("click", function () {
      canvas.getContext("2d").drawImage(video, 0, 0, canvas.width, canvas.height);
      const imageURI = canvas.toDataURL("image/png");

      const folderName = prompt("Enter folder name (e.g., 'Adi', 'Ainul', 'Thoriq'):");
      saveSnapshot(imageURI, folderName);
    });

    function saveSnapshot(imageData, folderName) {
      fetch("savess.php", {
        method: "POST",
        body: JSON.stringify({
          imageData: imageData.split(",")[1],
          folderName: folderName,
        }),
      })
        .then(response => response.text())
        .then(data => {
          console.log("Image saved:", data);
          snapshot.src = "labels/" + data; // Menampilkan snapshot di halaman
        })
        .catch(error => {
          console.error("An error occurred:", error);
        });
    }
  </script>
</body>
</html>
