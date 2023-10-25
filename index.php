<!DOCTYPE html>
<html lang="en">
<head>
  <title>Keyless App</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="face-api.min.js"></script>
  <script defer src="script.js"></script>

    <style>
/* CSS untuk membuat footer sticky */
#sticky-footer {
  position: fixed;
  bottom: 0;
  width: 100%;
  background-color: #343a40; /* Warna latar belakang footer */
  color: #fff; /* Warna teks footer */
  padding-top: 20px; /* Padding di bagian atas footer */
  padding-bottom: 20px; /* Padding di bagian bawah footer */
  text-align: center;
}

  </style>
</head>
<body>

<div class="container-fluid p-2 px-3 bg-primary text-white text-center">
  <h4>Keyless</h4>
</div>
  
<div class="container mt-3 ">
  <div class="row">
    <div class="col-sm-4" id="kotak">
      <video id="video" width="400" height="350" autoplay></video>
    </div>
  </div>
</div>

<footer id="sticky-footer" class="py-2 bg-dark text-white text-center">
  <div class="container">
    <small>Copyright &copy; Your Website</small>
  </div>
</footer>

</body>
</html>
