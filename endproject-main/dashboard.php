<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Get user information from session
$email = $_SESSION['email'];
$userLevel = $_SESSION['level'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css">
  <script>
            document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.nav-pills .nav-link');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
function switchToProfile() {
    const profileTab = document.querySelector('#v-pills-profile-tab');
    profileTab.click();
}

// Add event listener for profile photo preview
document.getElementById('profile-photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-photo').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
        </script>
</head>
<body>

<div
    class="container text-break"
>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid border-bottom border-2">
        <a class="navbar-brand" href="#">
            <img src="img/brand1.png" alt="Logo" style="height: 30px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#"></a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <span class="me-2">Placeholder</span>
                <span>Placeholder</span>
                <li class="nav-link dropdown">
                <a href="#" role="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" class="">
                    <i class="fas fa-user-circle fa-2x"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" type="btn" class="btn btn-danger mx-auto d-flex" data-bs-toggle="modal" data-bs-target="#modalLogoutId">Log out</a></li>
                </ul>
                </li>
            </div>
        </div>
    </div>
</nav>


<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div
    class="modal fade"
    id="modalLogoutId"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    
    role="dialog"
    aria-labelledby="modalTitleId"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
        role="document"
    >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Log out
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">Are you soure you want to log out ?</div>
            <div class="modal-footer">
                  <form action="index.php" method="POST">
                     <button type="submit" class="btn btn-danger">Yes</button>
                     <button type="button" class="btn btn-success" data-bs-dismiss="modal">No</button>
                  </form>
            </div>
        </div>
    </div>
</div>

<!-- Optional: Place to the bottom of scripts -->
<script>
    const myModal = new bootstrap.Modal(
        document.getElementById("modalId"),
        options,
    );
</script>


        <h2><span class="badge badge-rounded-top bg-info badge-lg">Mahasiswa</span></h2>
    <div class="row"> 
        <div class="col">
        <div class="d-flex">
    <!-- Vertical Nav Tabs -->
    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
            <i class="fas fa-home"></i> Home
        </button>
        <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
            <i class="fas fa-user"></i> Profile
        </button>
        <button class="nav-link d-none" id="v-pills-edit-profile-tab" data-bs-toggle="pill"  data-bs-target="#v-pills-edit-profile" type="button" role="tab" aria-controls="v-pills-edit-profile" aria-selected="false">
    Edit Profile
        </button>
        <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
            <i class="fas fa-bell"></i> Notifications
        </button>
        <button class="nav-link" id="v-pills-report-tab" data-bs-toggle="pill" data-bs-target="#v-pills-report" type="button" role="tab" aria-controls="v-pills-report" aria-selected="false" >
            <i class="fa fa-flag"></i> Report
        </button>
        <button class="nav-link" id="v-pills-history-tab" data-bs-toggle="pill" data-bs-target="#v-pills-history" type="button" role="tab" aria-controls="v-pills-history" aria-selected="false">
            <i class="fa fa-history"></i> History
        </button>
        <button class="nav-link" id="v-pills-punishment-tab" data-bs-toggle="pill" data-bs-target="#v-pills-punishment" type="button" role="tab" aria-controls="v-pills-punishment" aria-selected="false">
            <i class="fa fa-exclamation-triangle"></i> Punishment
        </button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content flex-grow-1">
    <div class="tab-pane fade show active p-3 border rounded bg-light" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
    <h4 class="mb-3">Hai, Agus Kopling!</h4>
    
    <!-- Top Stats Cards -->
    <div class="d-flex gap-3 mb-4">
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h3 class="mb-0">0</h3>
                <small>Total Izin</small>
            </div>
        </div>
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h3 class="mb-0">2</h3>
                <small>Total Laporan</small>
            </div>
        </div>
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h3 class="mb-0">4</h3>
                <small>Total Laporan Ditolak</small>
            </div>
        </div>
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h3 class="mb-0">3</h3>
                <small>Total Sakit</small>
            </div>
        </div>
    </div>

    <!-- Bottom Stats Card -->
    <div class="card bg-danger text-white">
        <div class="card-body">
            <h5 class="card-title mb-4">Data Absensi Alpha, Ijin, dan Sakit Mahasiswa</h5>
            <div class="d-flex justify-content-between text-center">
                <div>
                    <h4 class="mb-0">16</h4>
                    <small>Total Keterlambatan</small>
                </div>
                <div>
                    <h4 class="mb-0">2</h4>
                    <small>Alpha</small>
                </div>
                <div>
                    <h4 class="mb-0">8</h4>
                    <small>Ijin</small>
                </div>
                <div>
                    <h4 class="mb-0">6</h4>
                    <small>Sakit</small>
                </div>
                <div>
                    <h4 class="mb-0">4</h4>
                    <small>Total Dispensasi</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <img src="profile-photo.jpg" class="rounded-3 mb-3" style="width: 150px; height: 200px; object-fit: cover;">
            </div>
            
            <div class="row mb-3">
                <div class="col-4">NIM</div>
                <div class="col-8">234777203412</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-4">Nama Lengkap</div>
                <div class="col-8">Agus Kopling</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-4">Jenis Kelamin</div>
                <div class="col-8">Laki-laki</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-4">No. Handphone</div>
                <div class="col-8">082145678900</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-4">No. Handphone Orang Tua / Wali</div>
                <div class="col-8">082145678900</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-4">Jurusan</div>
                <div class="col-8">Teknologi Informasi</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-4">Prodi</div>
                <div class="col-8">D-IV Teknik Informatika</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-4">Kelas</div>
                <div class="col-8">2</div>
            </div>
            
            <div class="text-end">
                <button class="btn btn-primary" onclick="document.querySelector('#v-pills-edit-profile-tab').click()">Edit</button>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-edit-profile" role="tabpanel" aria-labelledby="v-pills-edit-profile-tab">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Edit Profile</h5>
            <form>
                <div class="text-center mb-4">
                    <img id="preview-photo" src="profile-photo.jpg" class="rounded-3 mb-3" style="width: 150px; height: 200px; object-fit: cover;">
                    <div>
                        <input type="file" class="form-control" id="profile-photo" accept="image/*">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control" value="234777203412">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="Agus Kopling">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-select">
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Handphone</label>
                    <input type="tel" class="form-control" value="082145678900">
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Handphone Orang Tua / Wali</label>
                    <input type="tel" class="form-control" value="082145678900">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <select class="form-select">
                        <option value="Teknologi Informasi">Teknologi Informasi</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prodi</label>
                    <select class="form-select">
                        <option value="D-IV Teknik Informatika">D-IV Teknik Informatika</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <input type="text" class="form-control" value="2">
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="switchToProfile()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
    <div class="d-flex gap-2 mb-4">
        <button class="btn btn-primary">Semua</button>
        <button class="btn btn-warning">Belum Dibaca</button>
    </div>

    <div class="notification-list">
        <!-- Pengumuman Kedisiplinan -->
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">Pengumuman Kedisiplinan</h6>
                <p class="card-text text-muted">Anda menerima laporan pengumuman berupa masukdi di Gedung Utama (Gedung G) pada hari Minggu 5 November 2024.</p>
            </div>
        </div>

        <!-- Pengajuan Banding Diterima -->
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">Pengajuan Banding Diterima</h6>
                <p class="card-text text-muted">Pengajuan anda telah diterima pengumuman anda telah diterima. Silakan cek Report History anda melalui menu pelanggaran.</p>
            </div>
        </div>

        <!-- Pengajuan Laporan Dikonfirmasi -->
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">Pengajuan Laporan Dikonfirmasi</h6>
                <p class="card-text text-muted">Laporan anda mengenai pengumuman tata tertib mahasiswa kini juga merta telah kami terima terhadi silahkan lihat. Terima kasih telah partisipasi di kita dalam menjaga tata tertib kampus.</p>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-report" role="tabpanel" aria-labelledby="v-pills-report-tab">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Laporkan Pelanggaran!</h5>
            
            <form>
                <div class="mb-3">
                    <label class="form-label">Mahasiswa terlibat</label>
                    <input type="text" class="form-control bg-light" placeholder="Masukkan nama mahasiswa">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bukti</label>
                    <textarea class="form-control bg-light" rows="3" placeholder="Masukkan bukti yang menguatkan laporan"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama pelanggaran</label>
                    <select class="form-select bg-light">
                        <option selected disabled>Pilih Pelanggaran</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Waktu</label>
                    <input type="text" class="form-control bg-light" placeholder="Masukkan waktu kejadian">
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" class="form-control bg-light" placeholder="Masukkan lokasi kejadian">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-history" role="tabpanel" aria-labelledby="v-pills-history-tab">
    <div class="nav nav-tabs mb-4" role="tablist">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#history-diterima" type="button" role="tab">Diterima</button>
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#history-diajukan" type="button" role="tab">Diajukan</button>
    </div>

    <div class="tab-content">
        <!-- Diterima tab -->
        <div class="tab-pane fade show active" id="history-diterima" role="tabpanel">
            <div class="table-responsive">
                <h5>Laporan yang Diterima</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No. Pelanggaran</th>
                            <th>Nama Pelanggaran</th>
                            <th>Bobot</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ABC01</td>
                            <td>Merokok</td>
                            <td>IV</td>
                            <td>Accepted</td>
                            <td><button class="btn btn-warning btn-sm">Check</button></td>
                        </tr>
                <tr>
                    <td>ABC02</td>
                    <td>Merusak sarana prasarana</td>
                    <td>II</td>
                    <td>Accepted</td>
                    <td><button class="btn btn-warning btn-sm">Check</button></td>
                </tr>
                <tr>
                    <td>ABC03</td>
                    <td>Berkelahi</td>
                    <td></td>
                    <td>Pending</td>
                    <td><button class="btn btn-warning btn-sm">Check</button></td>
                </tr>
                <tr>
                    <td>ABC04</td>
                    <td>Berkata Tidak Sopan</td>
                    <td></td>
                    <td>Pending</td>
                    <td><button class="btn btn-warning btn-sm">Check</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="tab-pane fade" id="history-diajukan" role="tabpanel">
<div class="card">
           <div class="card-body">
             <div class="d-flex mb-3">
                 <div class="flex-grow-1">
                     <h6>Bukti</h6>
                      <img src="path_to_image" class="img-fluid rounded" style="max-width: 200px;" alt="Bukti">
                  </div>
              </div>

      <div class="mb-3">
        <h6>Nama Mahasiswa terlapor</h6>
        <p class="text-muted">Agus Kopling</p>
      </div>

      <div class="mb-3">
        <h6>NIM Mahasiswa terlapor</h6>
        <p class="text-muted">234171230412</p>
      </div>

      <div class="mb-3">
        <h6>Tingkat dan Jenis pelanggaran</h6>
        <p class="text-muted">IV - Merusak Sarana Prasarana</p>
      </div>

      <div class="mb-3">
        <h6>Waktu</h6>
        <p class="text-muted">Kamis, 14 November 2024 pukul 14:59</p>
      </div>

      <div class="mb-3">
        <h6>Lokasi</h6>
        <p class="text-muted">Di Ruang Kelas</p>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-secondary">Aju Banding</button>
        <button class="btn btn-primary">Terima</button>
      </div>
    </div>
  </div>
</div>
    </div>
</div>

<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-punishment" role="tabpanel" aria-labelledby="v-pills-punishment-tab">
    <div class="table-responsive">
        <h5>Laporan yang Diajukan</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No. Pelanggaran</th>
                    <th>Nama Pelanggaran</th>
                    <th>Hukuman</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ABC01</td>
                    <td>Merokok</td>
                    <td>Membersihkan taman</td>
                    <td>Accepted</td>
                </tr>
                <tr>
                    <td>ABC02</td>
                    <td>Merusak sarana prasarana</td>
                    <td>Mengganti barang yang sama</td>
                    <td>Not Done</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</div>

        </div>
    </div>
</div>




    
</body>




</html>