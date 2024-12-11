<?php
session_start();
require_once '../connection.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit();
}

// Verify correct role for this page
$current_role = basename(dirname($_SERVER['PHP_SELF'])); // Gets 'mahasiswa', 'admin', or 'dosen'
if ($_SESSION['role'] !== $current_role) {
    header("Location: ../index.php");
    exit();
}
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
  <link rel="stylesheet" href="../css/dashboard.css">
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

document.addEventListener('DOMContentLoaded', function () {
    fetch('../func/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'fetchProfile' })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch profile data");
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error(data.error);
            } else {
                // Populate profile fields
                document.querySelector('#profile-nim').textContent = data.nim || 'N/A';
                document.querySelector('#profile-nama-lengkap').textContent = data.nama_lengkap;
                document.querySelector('#profile-jenis-kelamin').textContent = data.jenis_kelamin;
                document.querySelector('#profile-no-hp').textContent = data.no_hp;
                document.querySelector('#profile-jurusan').textContent = data.jurusan;
                document.querySelector('#profile-prodi').textContent = data.prodi;
                document.querySelector('#profile-profesi').textContent = data.profesi || 'N/A';
                document.querySelector('#profile-email').textContent = data.email;
            }
        })
        .catch(error => console.error("Error fetching profile:", error));
});
document.addEventListener('DOMContentLoaded', function () {
    fetch('../func/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: 'fetchProfile'
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Failed to fetch profile data");
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            console.error(data.error);
        } else {
            document.getElementById('edit-nama-lengkap').value = data.nama_lengkap;
            document.getElementById('edit-jenis-kelamin').value = data.jenis_kelamin;
            document.getElementById('edit-no-hp').value = data.no_hp;
            document.getElementById('edit-jurusan').value = data.jurusan;
            document.getElementById('edit-prodi').value = data.prodi;
            document.getElementById('edit-profesi').value = data.profesi;
            document.getElementById('edit-email').value = data.email;
        }
    })
    .catch(error => console.error("Error fetching profile:", error));
});
function showAllNotifications() {
    document.querySelectorAll('.notification-list .card').forEach(card => {
        card.style.display = 'block';
    });
}

function showUnreadNotifications() {
    // You can add logic here to filter unread notifications
    // For now, it just shows all notifications
    showAllNotifications();
}

function switchToProfile() {
    document.querySelector('#v-pills-profile-tab').click();
}

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
            <img src="../img/brand1.png" alt="Logo" style="height: 30px;">
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
        <?php
        try {
            $user_id = $_SESSION['user_id'];
            $query = "SELECT nama_lengkap FROM dosen WHERE id = :user_id";
            $stmt = $koneksi->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user_data) {
                echo '<span>' . $user_data['nama_lengkap'] . '</span>';
            }
        } catch (PDOException $e) {
            echo '<span class="text-danger">Error loading user data</span>';
        }
        ?>
        <li class="nav-link dropdown">
            <a href="#" role="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
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
                  <form action="../index.php" method="POST">
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


        <h2><span class="badge badge-rounded-top bg-info badge-lg">Dosen</span></h2>
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
        <button class="nav-link" id="v-pills-punishment-tab" data-bs-toggle="pill" data-bs-target="#v-pills-punishment" type="button" role="tab" aria-controls="v-pills-punishment" aria-selected="false">
            <i class="fa fa-exclamation-triangle"></i> History
        </button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content flex-grow-1">
    <div class="tab-pane fade show active p-3 border rounded bg-light" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
    <?php
    try {
        // Fetch user name
        $user_id = $_SESSION['user_id'];
        $nameQuery = "SELECT nama_lengkap FROM dosen WHERE id = :user_id";
        $stmt = $koneksi->prepare($nameQuery);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Fetch home details
        $query = "SELECT 
            total_laporan,
            total_laporan_teraprove,
            total_laporan_dicheck
        FROM home_det_dosen
        WHERE fk_dos = :user_id";
        
        $stmt = $koneksi->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    
    <h4 class="mb-3">Hi, <?php echo htmlspecialchars($userData['nama_lengkap']); ?></h4>
    <p>Welcome in Si Disiplin</p>

    <!-- Stats Cards Container -->
    <div class="d-flex justify-content-center gap-4 mb-4 p-4" style="background-color: #15295E; border-radius: 10px;">
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Laporan Hari Ini</h6>
                <h3 class="mb-0"><?php echo $data['total_laporan']; ?></h3>
            </div>
        </div>
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Laporan Teraprove</h6>
                <h3 class="mb-0"><?php echo $data['total_laporan_teraprove']; ?></h3>
            </div>
        </div>
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Laporan DiCheck</h6>
                <h3 class="mb-0"><?php echo $data['total_laporan_dicheck']; ?></h3>
            </div>
        </div>
    </div>
    <?php
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Error fetching data: ' . $e->getMessage() . '</div>';
    }
    ?>
</div>

<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <img src="../img/profile-photo.jpg" class="rounded-3 mb-3" style="width: 150px; height: 200px; object-fit: cover;">
            </div>
            
<div class="row mb-3">
    <div class="col-4">NIM</div>
    <div class="col-8" id="profile-nim"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Nama Lengkap</div>
    <div class="col-8" id="profile-nama-lengkap"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Jenis Kelamin</div>
    <div class="col-8" id="profile-jenis-kelamin"></div>
</div>
<div class="row mb-3">
    <div class="col-4">No. Handphone</div>
    <div class="col-8" id="profile-no-hp"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Jurusan</div>
    <div class="col-8" id="profile-jurusan"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Prodi</div>
    <div class="col-8" id="profile-prodi"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Profesi</div>
    <div class="col-8" id="profile-profesi"></div>
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
            <form id="editProfileForm" method="POST" action="../func/update_profile.php">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" id="edit-nama-lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-select" name="jenis_kelamin" id="edit-jenis-kelamin" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Handphone</label>
                    <input type="tel" class="form-control" name="no_hp" id="edit-no-hp" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <select class="form-select" name="jurusan" id="edit-jurusan" required>
                        <option value="Teknologi Informasi">Teknologi Informasi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prodi</label>
                    <select class="form-select" name="prodi" id="edit-prodi" required>
                        <option value="D-IV Teknik Informatika">D-IV Teknik Informatika</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profesi</label>
                    <input type="text" class="form-control" name="profesi" id="edit-profesi" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="edit-email" required>
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
        <button class="btn btn-primary" onclick="showAllNotifications()">Semua</button>
        <button class="btn btn-warning" onclick="showUnreadNotifications()">Belum Dibaca</button>
    </div>
    
    <div class="notification-list">
        <?php
        try {
            $user_id = $_SESSION['user_id'];
            $query = "SELECT title, content, id FROM mail_notif_dosen 
                     WHERE mail_type = :user_id 
                     ORDER BY id DESC";
            
            $stmt = $koneksi->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            while ($notification = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">' . htmlspecialchars($notification['title']) . '</h6>
                            <p class="card-text text-muted">' . htmlspecialchars($notification['content']) . '</p>
                        </div>
                    </div>';
            }
            
            if ($stmt->rowCount() == 0) {
                echo '<div class="alert alert-info">Tidak ada notifikasi</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error fetching notifications: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</div>

        <?php
        require_once '../connection.php'; // Include database connection

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $bukti = $_POST['bukti'];
            $nama_pelanggaran = $_POST['nama_pelanggaran'];
            $waktu = date('Y-m-d H:i:s', strtotime($_POST['waktu'])); // Ensure proper datetime format
            $lokasi = $_POST['lokasi'];

            try {
                $query = "INSERT INTO report (name, bukti, nama_pelanggaran, waktu, lokasi) VALUES (:name, :bukti, :nama_pelanggaran, :waktu, :lokasi)";
                $stmt = $koneksi->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':bukti', $bukti);
                $stmt->bindParam(':nama_pelanggaran', $nama_pelanggaran);
                $stmt->bindParam(':waktu', $waktu);
                $stmt->bindParam(':lokasi', $lokasi);
                $stmt->execute();
                echo "Report successfully submitted.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>

        <div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-report" role="tabpanel" aria-labelledby="v-pills-report-tab">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Laporkan Pelanggaran!</h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Mahasiswa Terlibat</label>
                            <input type="text" class="form-control bg-light" name="name" placeholder="Masukkan nama mahasiswa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bukti</label>
                            <textarea class="form-control bg-light" name="bukti" rows="3" placeholder="Masukkan bukti yang menguatkan laporan" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Pelanggaran</label>
                            <input type="text" class="form-control bg-light" name="nama_pelanggaran" placeholder="Masukkan nama pelanggaran" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="datetime-local" class="form-control bg-light" name="waktu" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control bg-light" name="lokasi" placeholder="Masukkan lokasi kejadian" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
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