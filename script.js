const video = document.getElementById("video");

Promise.all([
  faceapi.nets.ssdMobilenetv1.loadFromUri("/models"),
  faceapi.nets.faceRecognitionNet.loadFromUri("/models"),
  faceapi.nets.faceLandmark68Net.loadFromUri("/models"),
]).then(startWebcam);

function startWebcam() {
  navigator.mediaDevices
    .getUserMedia({
      video: true,
      audio: false,
    })
    .then((stream) => {
      video.srcObject = stream;
    })
    .catch((error) => {
      console.error(error);
    });
}

let labels;

async function getLabeledFaceDescriptions() {
  try {
    const response = await fetch('labels.php'); // Pastikan URL-nya benar
    if (!response.ok) {
      throw new Error(`Gagal mengambil data dari server. Kode status: ${response.status}`);
    }
    
    labels = await response.json();
    console.log(labels); // Ini akan mencetak array label dari hasil PHP

    return Promise.all(
      labels.map(async (label) => {
        const descriptions = [];
        for (let i = 1; i <= 5; i++) {
          const img = await faceapi.fetchImage(`./labels/${label}/${i}.png`);
          const detections = await faceapi
            .detectSingleFace(img)
            .withFaceLandmarks()
            .withFaceDescriptor();
          
          if (detections) {
            descriptions.push(detections.descriptor);
          } else {
            console.log(`No face detected for ${label}/${i}.png`);
            descriptions.push(new Float32Array(128)); // Deskripsi "unknown"
          }
        }
        return new faceapi.LabeledFaceDescriptors(label, descriptions);
      })
    );
  } catch (error) {
    console.error('Terjadi kesalahan saat mengambil data dari PHP:', error);
    throw error;
  }
}


video.addEventListener("play", async () => {
  const labeledFaceDescriptors = await getLabeledFaceDescriptions();
  const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

  const kotak = document.getElementById("kotak"); // Mengambil elemen dengan ID "kotak"
  const video = document.getElementById("video"); // Mengambil elemen video
  const canvas = faceapi.createCanvasFromMedia(video);
  kotak.append(canvas);


  const displaySize = { width: 450, height: 380 };
  faceapi.matchDimensions(canvas, displaySize);

  setInterval(async () => {
    const detections = await faceapi
      .detectAllFaces(video)
      .withFaceLandmarks()
      .withFaceDescriptors();

    const resizedDetections = faceapi.resizeResults(detections, displaySize);

    canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
   
    const results = resizedDetections.map((d) => {
      return faceMatcher.findBestMatch(d.descriptor);
    });
    results.forEach((result, i) => {
      const box = resizedDetections[i].detection.box;
      const drawBox = new faceapi.draw.DrawBox(box, {
        label: result,
      });
    
      // Hitung posisi baru berdasarkan deteksi wajah
      const positionX = box.x;
      const positionY = box.y;
// Atur margin atas dan kiri elemen canvas berdasarkan posisi wajah
    canvas.style.marginTop = -900 + "px";
    canvas.style.marginLeft = -20 + "px";

      // Atur posisi kotak (canvas) berdasarkan hasil deteksi wajah
      drawBox.draw(canvas, { x: positionX, y: positionY });
    });
    
  }, 100);
});

