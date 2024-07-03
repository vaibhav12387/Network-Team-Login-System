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
    $user_name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
    $online_status = htmlspecialchars($row['online_status'], ENT_QUOTES, 'UTF-8');
    $profile_picture = htmlspecialchars($row['profile_picture'], ENT_QUOTES, 'UTF-8');
    $user_role = htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8');
} else {
    echo "User not found.";
    exit;
}

$home_url = ($user_role === 'admin') ? 'admin.php' : 'user.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard-theme.css"> <!-- Include the Dashboard theme CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="header-container">
    <nav class="navbar navbar-expand-lg navbar-dark">
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
                    <a class="nav-link" href="f5.php">F5 Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="INSIDE.php">INSIDE F5</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="DMZ.php">DMZ F5</a>
                </li>
                <!-- AWS PROD -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="awsProdDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        AWS PROD
                    </a>
                    <div class="dropdown-menu" aria-labelledby="awsProdDropdown">
                        <a class="dropdown-item" href="#">us-east-1 - Application A</a>
                        <a class="dropdown-item" href="#">us-west-2 - Application B</a>
                        <a class="dropdown-item" href="#">eu-west-1 - Application C</a>
                    </div>
                </li>
                <!-- AWS Shared Services -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="awsSharedDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        AWS Shared Services
                    </a>
                    <div class="dropdown-menu" aria-labelledby="awsSharedDropdown">
                        <a class="dropdown-item" href="#">us-west-2 - Application D</a>
                        <a class="dropdown-item" href="#">us-east-1 - Application E</a>
                        <a class="dropdown-item" href="#">ap-southeast-2 - Application F</a>
                    </div>
                </li>
                <!-- AWS Non Prod -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="awsNonProdDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        AWS Non Prod
                    </a>
                    <div class="dropdown-menu" aria-labelledby="awsNonProdDropdown">
                        <a class="dropdown-item" href="#">us-east-1 - Application G</a>
                        <a class="dropdown-item" href="#">us-west-2 - Application H</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Bookmark.php">Bookmark</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="uploads/profile_pictures/<?= $profile_picture ?>" alt="Profile Picture" class="profile-img">
                        <?= $user_name ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editProfileModal">My Profile</a>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#resetPasswordModal">Reset Password</button>
                        <a class="dropdown-item" href="endpoint/logout.php">Log out</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">My Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="endpoint/edit_profile.php" method="POST">
                    <input type="hidden" name="edit_profile">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="editName" value="<?= $user_name ?>" required readonly style="background-color: #f0f0f0; cursor: not-allowed; color: #555;">
                    </div>
                    <div class="form-group">
                        <label for="editUsername">Username</label>
                        <input type="text" class="form-control" id="editUsername" name="editUsername" value="<?= $username ?>" required readonly style="background-color: #f0f0f0; cursor: not-allowed; color: #555;">
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="editEmail" value="<?= $email ?>" required readonly style="background-color: #f0f0f0; cursor: not-allowed; color: #555;">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="resetPasswordForm" method="post" action="endpoint/reset_password.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        <div id="newPasswordError" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirmNewPassword">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
                        <div id="confirmNewPasswordError" class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="path/to/dashboard-theme.js"></script> <!-- Include the Dashboard theme JavaScript -->
<script>
// Custom JavaScript for password validation
$('#resetPasswordForm').on('submit', function(event) {
    var newPassword = $('#newPassword').val();
    var confirmNewPassword = $('#confirmNewPassword').val();
    var newPasswordError = $('#newPasswordError');
    var confirmNewPasswordError = $('#confirmNewPasswordError');

    if (newPassword !== confirmNewPassword) {
        event.preventDefault();
        confirmNewPasswordError.text('Passwords do not match.');
        confirmNewPassword.addClass('is-invalid');
    } else {
        confirmNewPasswordError.text('');
        confirmNewPassword.removeClass('is-invalid');
    }

    if (newPassword.length < 8) {
        event.preventDefault();
        newPasswordError.text('Password must be at least 8 characters long.');
        newPassword.addClass('is-invalid');
    } else {
        newPasswordError.text('');
        newPassword.removeClass('is-invalid');
    }
});
</script>
</body>
</html>
