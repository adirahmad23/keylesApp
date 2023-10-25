<!DOCTYPE html>
<html>
<head>
    <title>Webcam Snapshot</title>
</head>
<body>
    <h1>Pengambilan Gambar dari Webcam</h1>
    <video id="webcam" width="640" height="480" autoplay></video>
    <input type="text" id="nameInput" placeholder="Masukkan Nama">
    <button id="captureButton">Ambil Gambar</button>
    <canvas id="canvas" width="640" height="480" style="display: none;"></canvas>
    <img id="snapshot" src="" alt="Gambar" style="display: none;">

    <script>
        var captureCount = 0; // Untuk melacak jumlah gambar yang diambil
        var currentCapture = 1; // Nomor gambar yang sedang diambil
        var buttonClicked = false; // Untuk melacak apakah tombol sudah diklik

        // Mengakses webcam
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                var video = document.getElementById('webcam');
                video.srcObject = stream;
            })
            .catch(function(error) {
                console.log('Error mengakses webcam:', error);
            });

        // Mengambil gambar
        document.getElementById('captureButton').addEventListener('click', function() {
            if (buttonClicked || currentCapture > 5) {
                return; // Jika tombol sudah diklik atau sudah mengambil 5 gambar, keluar dari fungsi
            }
            buttonClicked = true;

            var video = document.getElementById('webcam');
            var canvas = document.getElementById('canvas');
            var snapshot = document.getElementById('snapshot');
            var nameInput = document.getElementById('nameInput');
            
            if (nameInput.value.trim() === '') {
                alert('Harap masukkan nama sebelum mengambil gambar.');
                buttonClicked = false; // Kembalikan status tombol
                return;
            }

            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            snapshot.src = canvas.toDataURL('image/png');
            snapshot.style.display = 'block';

            // Menyimpan gambar dengan nama yang dimasukkan dan nama berurutan (1-5)
            var name = nameInput.value;
            var imgData = snapshot.src.replace('data:image/png;base64,', '');

            var formData = new FormData();
            formData.append('name', name);
            formData.append('image', imgData);
            formData.append('count', currentCapture); // Untuk menunjukkan nomor gambar

            // Mengirim data ke script PHP untuk menyimpan gambar
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'save-image.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Gambar ' + currentCapture + ' berhasil disimpan!');
                    currentCapture++; // Menambahkan nomor gambar berikutnya
                    if (currentCapture > 5) {
                        document.getElementById('captureButton').disabled = false; // Aktifkan kembali tombol setelah 5 gambar diambil
                    }
                } else {
                    alert('Gagal menyimpan Gambar ' + currentCapture + '.');
                }
                buttonClicked = false; // Kembalikan status tombol setelah selesai
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>
