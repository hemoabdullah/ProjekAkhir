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
                document.querySelector('#profile-no-hp-ortu').textContent = data.no_hp_ortu || 'N/A';
                document.querySelector('#profile-jurusan').textContent = data.jurusan;
                document.querySelector('#profile-prodi').textContent = data.prodi;
                document.querySelector('#profile-kelas').textContent = data.kelas || 'N/A';
                document.querySelector('#profile-email').textContent = data.email;
            }
        })
        .catch(error => console.error("Error fetching profile:", error));
});

function populateEditForm(data) {
    document.getElementById('edit-nim').value = data.nim || '';
    document.getElementById('edit-nama-lengkap').value = data.nama_lengkap || '';
    document.getElementById('edit-jenis-kelamin').value = data.jenis_kelamin || 'Laki-laki';
    document.getElementById('edit-no-hp').value = data.no_hp || '';
    document.getElementById('edit-no-hp-ortu').value = data.no_hp_ortu || '';
    document.getElementById('edit-email').value = data.email || '';
}
function switchToEditProfile() {
    fetch('../func/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: 'fetchProfile'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.error) {
            populateEditForm(data);
            document.querySelector('#v-pills-edit-profile-tab').click();
        }
    })
    .catch(error => console.error("Error:", error));
}

// Add this to your existing script section
document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.querySelector('form[action="../func/report.php"]');
    
    reportForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('../func/report.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Report submitted successfully');
                reportForm.reset();
                // Switch to history tab
                document.querySelector('#v-pills-history-tab').click();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the report');
        });
    });
});

document.getElementById('profile-photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please upload an image file');
            this.value = '';
            return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-photo').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

function refreshHistory() {
    fetch('../func/get_history.php')
    .then(response => response.json())
    .then(data => {
        const historyTable = document.querySelector('#history-diterima tbody');
        historyTable.innerHTML = '';
        
        data.forEach(item => {
            const row = `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.nama_pelanggaran}</td>
                    <td>${item.bobot}</td>
                    <td>${item.status}</td>
                    <td><button class="btn btn-warning btn-sm" onclick="viewDetail(${item.id})">Check</button></td>
                </tr>
            `;
            historyTable.insertAdjacentHTML('beforeend', row);
        });
    })
    .catch(error => console.error('Error:', error));
}
document.querySelector('form[action="../func/report.php"]').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const waktuInput = formData.get('waktu');
    
    // Format datetime to SQL Server compatible format
    const date = new Date(waktuInput);
    const formattedDate = date.toISOString().slice(0, 19).replace('T', ' ');
    formData.set('waktu', formattedDate);
    
    fetch('../func/report.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Report submitted successfully');
            this.reset();
            document.querySelector('#v-pills-history-tab').click();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the report');
    });
});

function viewDetail(reportId) {
    fetch(`../func/get_report_detail.php?id=${reportId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Switch to diajukan tab and populate data
            document.querySelector('[data-bs-target="#history-diajukan"]').click();
            
            // Populate the detail view with the returned data
            document.querySelector('#detail-bukti').src = '../uploads/evidence/' + data.bukti;
            document.querySelector('#detail-nama').textContent = data.name;
            document.querySelector('#detail-pelanggaran').textContent = data.nama_pelanggaran;
            document.querySelector('#detail-waktu').textContent = new Date(data.waktu).toLocaleString();
            document.querySelector('#detail-lokasi').textContent = data.lokasi;
        } else {
            alert('Error loading report details');
        }
    })
    .catch(error => console.error('Error:', error));
}

function viewSubmittedDetail(reportId) {
    fetch(`../func/get_submitted_report.php?id=${reportId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('#detail-bukti').src = '../uploads/evidence/' + data.bukti;
            document.querySelector('#detail-pelanggaran').textContent = data.nama_pelanggaran;
            document.querySelector('#detail-waktu').textContent = new Date(data.waktu).toLocaleString('id-ID');
            document.querySelector('#detail-lokasi').textContent = data.lokasi;
            
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        } else {
            alert('Error loading report details');
        }
    })
    .catch(error => console.error('Error:', error));
}
function viewReportDetail(reportId) {
    fetch(`../func/get_report_details.php?id=${reportId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('#modal-bukti').src = '../uploads/evidence/' + data.data.bukti;
            document.querySelector('#modal-pelanggaran').textContent = data.data.nama_pelanggaran;
            document.querySelector('#modal-waktu').textContent = new Date(data.data.waktu).toLocaleString('id-ID');
            document.querySelector('#modal-lokasi').textContent = data.data.lokasi;
            
            new bootstrap.Modal(document.getElementById('reportDetailModal')).show();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to load report details');
    });
}
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('../func/update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profile updated successfully');
            document.querySelector('#v-pills-profile-tab').click();
        } else {
            alert('Error updating profile: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating profile');
    });
});


document.querySelector('input[name="bukti"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    const maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!allowedTypes.includes(file.type)) {
        alert('Please upload an image file (JPEG, PNG, or GIF)');
        this.value = '';
        return;
    }
    
    if (file.size > maxSize) {
        alert('File size must be less than 5MB');
        this.value = '';
        return;
    }
});

// Add this to your existing DOMContentLoaded event listener
fetch('../func/login.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
        action: 'fetchProfile'
    })
})
.then(response => response.json())
.then(data => {
    if (!data.error) {
        populateEditForm(data);
    }
})
.catch(error => console.error("Error fetching profile:", error));

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
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Report submitted successfully!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

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
            $query = "SELECT nama_lengkap, nim FROM mahasiswa WHERE id = :user_id";
            $stmt = $koneksi->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user_data) {
                echo '<span class="me-2">' . $user_data['nim'] . '</span>';
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
            <div class="modal-body">Are you sure you want to log out?</div>
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


        <h2><span class="badge badge-rounded-top bg-info badge-lg">Student</span></h2>
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
    <?php
    try {
        // Fetch user name
        $user_id = $_SESSION['user_id'];
        $nameQuery = "SELECT nama_lengkap FROM mahasiswa WHERE id = :user_id";
        $stmt = $koneksi->prepare($nameQuery);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Fetch attendance and home details
        $query = "SELECT 
            a.total_keterlambatan, a.total_alpha, a.total_ijin, 
            a.total_sakit, a.total_dispensasi,
            h.total_laporan, h.total_laporan_ditolak
        FROM attendance a
        INNER JOIN home_det h ON h.att_link = a.id
        WHERE h.mhsw_id = :user_id";
        
        $stmt = $koneksi->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    
    <h4 class="mb-3">Hi, <?php echo htmlspecialchars($userData['nama_lengkap']); ?>!</h4>
    <p>Welcome in Si Disiplin</p>

    <!-- Top Stats Cards -->
    <div class="d-flex gap-3 mb-4">
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h3 class="mb-0"><?php echo $data['total_ijin']; ?></h3>
                <small>Total Permissions</small>
            </div>
        </div>
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h3 class="mb-0"><?php echo $data['total_laporan']; ?></h3>
                <small>Total Reports</small>
            </div>
        </div>
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h3 class="mb-0"><?php echo $data['total_laporan_ditolak']; ?></h3>
                <small>Total Rejected Reports</small>
            </div>
        </div>
        <div class="card flex-fill text-center">
            <div class="card-body">
                <h3 class="mb-0"><?php echo $data['total_sakit']; ?></h3>
                <small>Total Illness</small>
            </div>
        </div>
    </div>

    <!-- Bottom Stats Card -->
    <div class="card bg-danger text-white">
        <div class="card-body">
            <h5 class="card-title mb-4">Alpha, Permission, and Student Illness</h5>
            <div class="d-flex justify-content-between text-center">
                <div>
                    <h4 class="mb-0"><?php echo $data['total_keterlambatan']; ?></h4>
                    <small>Total Delay</small>
                </div>
                <div>
                    <h4 class="mb-0"><?php echo $data['total_alpha']; ?></h4>
                    <small>Alpha</small>
                </div>
                <div>
                    <h4 class="mb-0"><?php echo $data['total_ijin']; ?></h4>
                    <small>Permission</small>
                </div>
                <div>
                    <h4 class="mb-0"><?php echo $data['total_sakit']; ?></h4>
                    <small>sick</small>
                </div>
                <div>
                    <h4 class="mb-0"><?php echo $data['total_dispensasi']; ?></h4>
                    <small>Total Dispensation</small>
                </div>
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
    <img src="<?php 
        $user_id = $_SESSION['user_id'];
        $stmt = $koneksi->prepare('SELECT pfp FROM mahasiswa WHERE id = ?');
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        echo $result['pfp'] ? '../uploads/profile/' . $result['pfp'] : '../img/profile-photo.jpg';
    ?>" class="rounded-3 mb-3" style="width: 150px; height: 200px; object-fit: cover;" id="profile-image">
</div>

            
<div class="row mb-3">
    <div class="col-4">NIM</div>
    <div class="col-8" id="profile-nim"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Full Name</div>
    <div class="col-8" id="profile-nama-lengkap"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Gender</div>  
    <div class="col-8" id="profile-jenis-kelamin"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Handphone Number</div>
    <div class="col-8" id="profile-no-hp"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Parents Handphone</div>
    <div class="col-8" id="profile-no-hp-ortu"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Major</div>
    <div class="col-8" id="profile-jurusan"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Study Program</div>
    <div class="col-8" id="profile-prodi"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Class</div>
    <div class="col-8" id="profile-kelas"></div>
</div>
<div class="row mb-3">
    <div class="col-4">Email</div>
    <div class="col-8" id="profile-email"></div>
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
<form id="editProfileForm" method="POST" action="../func/update_profile.php" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Profile Picture</label>
        <input type="file" class="form-control" name="profile_photo" id="profile-photo" accept="image/*">
    </div>
    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input type="text" class="form-control" name="nim" id="edit-nim">
    </div>
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" name="nama_lengkap" id="edit-nama-lengkap" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Gender</label>
        <select class="form-select" name="jenis_kelamin" id="edit-jenis-kelamin" readonly>
            <option value="Laki-laki">Male</option>
            <option value="Perempuan">Female</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Handphone Number</label>
        <input type="tel" class="form-control" name="no_hp" id="edit-no-hp" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Parents Handphone Number</label>
        <input type="tel" class="form-control" name="no_hp_ortu" id="edit-no-hp-ortu">
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
        <button class="btn btn-primary" onclick="showAllNotifications()">All Notifications</button>
        <button class="btn btn-warning" onclick="showUnreadNotifications()">Not Read yet</button>
    </div>
    
    <div class="notification-list">
        <?php
        try {
            $user_id = $_SESSION['user_id'];
            $query = "SELECT title, content, id FROM mail_notif_mahasiswa 
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
                echo '<div class="alert alert-info">There is No notification</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error fetching notifications: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</div>

<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-report" role="tabpanel" aria-labelledby="v-pills-report-tab">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Report Violations!</h5>
            <form method="POST" action="../func/report.php" enctype="multipart/form-data" id="reportForm">
            <div class="mb-3">
    <label class="form-label">Students involved</label>
    <select name="name" class="form-select bg-light" required>
        <option value="" selected disabled>Select students</option>
        <?php 
        try {
            $stmt = $koneksi->prepare("SELECT id, nim, nama_lengkap FROM mahasiswa ORDER BY nama_lengkap ASC");
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($row['nama_lengkap']) . "'>" . 
                     htmlspecialchars($row['nim'] . " - " . $row['nama_lengkap']) . "</option>";
            }
        } catch(PDOException $e) {
            echo "<option disabled>Error loading students</option>";
        }
        ?>
    </select>
</div>

    <div class="mb-3">
        <label class="form-label">Prove</label>
        <input type="file" name="bukti" class="form-control bg-light" accept="image/*" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Types of Violations</label>
        <select name="nama_pelanggaran" class="form-select bg-light" required>
            <option value="" selected disabled>Select violation</option>
            <?php
            try {
                $stmt = $koneksi->prepare("SELECT violation_description, level FROM violation ORDER BY level ASC");
                $stmt->execute();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . htmlspecialchars($row['violation_description']) . "'>" . 
                         htmlspecialchars("Level " . $row['level'] . " - " . $row['violation_description']) . "</option>";
                }
            } catch(PDOException $e) {
                echo "<option disabled>Error loading violations</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Time</label>
        <input type="datetime-local" name="waktu" class="form-control bg-light" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Location</label>
        <input type="text" name="lokasi" class="form-control bg-light" required>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Send!</button>
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
        <h5>Reports Received</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Violation Number</th>
                    <th>Violation Name</th>
                    <th>Weight</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $user_id = $_SESSION['user_id'];
                    $query = "SELECT r.id, r.nama_pelanggaran, h.bobot, h.status 
                             FROM report r 
                             INNER JOIN history h ON h.fk_report = r.id 
                             WHERE r.name = (SELECT nama_lengkap FROM mahasiswa WHERE id = :user_id)
                             ORDER BY r.waktu DESC";
                    
                    $stmt = $koneksi->prepare($query);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();
                    
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>VIO" . str_pad($row['id'], 3, '0', STR_PAD_LEFT) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_pelanggaran']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['bobot']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td><button class='btn btn-warning btn-sm' onclick='viewDetail(" . $row['id'] . ")'>Check</button></td>";
                        echo "</tr>";
                    }
                    
                    if ($stmt->rowCount() == 0) {
                        echo "<tr><td colspan='5' class='text-center'>Tidak ada laporan yang diterima</td></tr>";
                    }
                } catch(PDOException $e) {
                    echo "<tr><td colspan='5' class='text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="tab-pane fade" id="history-diajukan" role="tabpanel">
    <div class="table-responsive">
        <h5>Submitted Reports</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Violation Number</th>
                    <th>Violation Name</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $user_id = $_SESSION['user_id'];
                    $query = "SELECT r.id, r.nama_pelanggaran, r.waktu, h.status 
                             FROM report r 
                             INNER JOIN history h ON h.fk_report = r.id 
                             ORDER BY r.waktu DESC";
                    
                    $stmt = $koneksi->prepare($query);
                    $stmt->execute();
                    
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>VIO" . str_pad($row['id'], 3, '0', STR_PAD_LEFT) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_pelanggaran']) . "</td>";
                        echo "<td>" . date('d/m/Y H:i', strtotime($row['waktu'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td><button class='btn btn-warning btn-sm' onclick='viewReportDetail(" . $row['id'] . ")'>Check</button></td>";
                        echo "</tr>";
                    }
                } catch(PDOException $e) {
                    echo "<tr><td colspan='5' class='text-danger'>Error loading reports: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="reportDetailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Prove</h6>
                        <img id="modal-bukti" class="img-fluid rounded" alt="Bukti">
                    </div>
                    <div class="mb-3">
                        <h6>Voilation Name</h6>
                        <p id="modal-pelanggaran" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <h6>Time</h6>
                        <p id="modal-waktu" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <h6>Location</h6>
                        <p id="modal-lokasi" class="text-muted"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>
</div>

<div class="tab-pane fade p-3 border rounded bg-light" id="v-pills-punishment" role="tabpanel" aria-labelledby="v-pills-punishment-tab">
    <div class="table-responsive">
        <h5>List of Penalties</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Violation Number</th>
                    <th>Violation Name</th>
                    <th>Punishment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $user_id = $_SESSION['user_id'];
                    
                    // Get student's name first
                    $nameQuery = "SELECT nama_lengkap FROM mahasiswa WHERE id = :user_id";
                    $stmt = $koneksi->prepare($nameQuery);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();
                    $studentData = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Then get their punishment records
                    $query = "SELECT 
                        r.id,
                        r.nama_pelanggaran,
                        h.hukuman,
                        h.status
                    FROM report r
                    INNER JOIN history h ON h.fk_report = r.id 
                    WHERE r.name = :student_name
                    ORDER BY r.waktu DESC";
                    
                    $stmt = $koneksi->prepare($query);
                    $stmt->bindParam(':student_name', $studentData['nama_lengkap']);
                    $stmt->execute();
                    
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>VIO" . str_pad($row['id'], 3, '0', STR_PAD_LEFT) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_pelanggaran']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['hukuman']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "</tr>";
                    }
                    
                    if ($stmt->rowCount() == 0) {
                        echo "<tr><td colspan='4' class='text-center'>Tidak ada hukuman yang tercatat</td></tr>";
                    }
                    
                } catch(PDOException $e) {
                    echo "<tr><td colspan='4' class='text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
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