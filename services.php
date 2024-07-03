<?php
session_start();
include('./conn/conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT `name`, `username`, `profile_picture`, `email`, `online_status`, `role` FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_name = htmlspecialchars($row['name']);
    $email = htmlspecialchars($row['email']);
    $username = htmlspecialchars($row['username']);
    $online_status = htmlspecialchars($row['online_status']);
    $profile_picture = htmlspecialchars($row['profile_picture']);
    $user_role = htmlspecialchars($row['role']);
} else {
    echo "User not found.";
    exit;
}

$home_url = ($user_role === 'admin') ? 'admin.php' : 'user.php';

$stmt_roles = $conn->query("SELECT DISTINCT `role` FROM `tbl_user` ORDER BY `role` ASC");
$roles = $stmt_roles->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-image: url("https://img.freepik.com/free-vector/abstract-secure-technology-background_23-2148357087.jpg?t=st=1704160688~exp=1704161288~hmac=b37f6a9bb894ee4655cd411712309f6507644a1de23f709e5dfc0c33ed33bb15");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.8) !important;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f0f0f0 !important;
        }

        .navbar-nav > li > a {
            color: #f0f0f0 !important;
            transition: color 0.3s ease-in-out;
        }

        .navbar-nav > li > a:hover {
            color: #00aaff !important;
        }

        .profile-img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .dropdown-menu {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .dropdown-item {
            color: #f0f0f0;
            transition: background-color 0.3s ease-in-out;
        }

        .dropdown-item:hover {
            background-color: #007bff;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden; /* Ensure no scrollbars for the main content */
        }

        .iframe-container {
            position: relative;
            width: 100%;
            height: calc(100vh - 56px); /* Adjust based on your navbar height */
            overflow: hidden;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">My Application</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $home_url ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reportv3/CDC/">CDC F5</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reportv3/IDC/">IDC F5</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="uploads/profile_pictures/<?= $profile_picture ?>" alt="Profile Picture" class="profile-img">
                        <?= $user_name ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editProfileModal">Edit Profile</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#resetPasswordModal">Reset Password</a>
                        <a class="dropdown-item" href="endpoint/logout.php">Log out</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <input type="hidden" name="edit_profile">
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="editName" value="<?= $user_name ?>">
                        </div>
                        <div class="form-group">
                            <label for="editUsername">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="editUsername" value="<?= $username ?>">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="editEmail" value="<?= $email ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <input type="hidden" name="reset_password">
                        <p>Are you sure you want to reset your password?</p>
                        <button type="submit" class="btn btn-warning">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="iframe-container">
            <iframe src="http://Localhost/Dashboard/reportv3/CDC/#m=v" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>

    <!-- Bootstrap 4 JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
