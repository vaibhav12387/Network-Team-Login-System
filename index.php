<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Team Login System</title>
    <link rel="stylesheet" href="css/Style.css"> <!-- Include the Dashboard theme CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        include('conn/conn.php');
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT `role`, `last_login_datetime` FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $role = $row['role'];
            $last_login_datetime = $row['last_login_datetime'];

            // Check if last_login_datetime is null (first time login)
            if ($last_login_datetime === NULL) {
                $_SESSION['first_login'] = true;
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        $('#resetPasswordModal').modal('show');
                    });
                </script>";
            }

            // Redirect based on user role
            if ($role == 'admin') {
                header("Location: /Dashboard/admin.php");
                exit();
            } else if ($role == 'user') {
                header("Location: /Dashboard/user.php");
                exit();
            }
        }
    }
    ?>

    <div class="announcement">
        <h1>Network Team Login System</h1>
    </div>
    <div class="main">
        <div class="login-container" id="loginForm">
            <h2 class="text-center">Welcome Back!</h2>
            <p class="text-center">Please enter your login details.</p>
            <form action="./endpoint/login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control custom-input" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control custom-input" id="password" name="password" required>
                </div>
                <div class="row m-auto">
                    <div class="form-group form-check col-6">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Remember Password</label>
                    </div>
                    <small class="show-form col-6 text-center" onclick="showForm()">No Account? Register Here.</small>
                </div>
                <button type="submit" class="btn btn-primary login-btn form-control">Login</button>
            </form>
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
                        <p>For security reasons, you are required to reset your password.</p>
                        <!-- Add your reset password form here -->
                        <form action="./endpoint/First_password_Form.php" method="POST">
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmNewPassword">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Reset Password Modal -->

        <div class="registration-container" id="registrationForm" style="display:none;">
            <h2 class="text-center">Register Your Account!</h2>
            <p class="text-center">Please enter your personal details.</p>
            <form action="./endpoint/add-user.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" class="form-control custom-input" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select class="form-control custom-input" id="role" name="role" required>
                        <option value="" disabled selected>-select-</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>                
                </div>
                <div class="form-group">
                    <label for="registerUsername">Username:</label>
                    <input type="text" class="form-control custom-input" id="registerUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control custom-input" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Password:</label>
                    <input type="password" class="form-control custom-input" id="registerPassword" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control custom-input" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="form-group">
                    <label for="profilePicture">Profile Picture:</label>
                    <input type="file" class="form-control custom-input" id="profilePicture" name="profilePicture">
                </div>
                <button type="submit" class="btn btn-primary login-register form-control">Register</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script>
        function showForm() {
            const loginForm = document.getElementById('loginForm');
            const registrationForm = document.getElementById('registrationForm');
            if (registrationForm.style.display === 'none') {
                loginForm.style.display = 'none';
                registrationForm.style.display = 'block';
            } else {
                loginForm.style.display = 'block';
                registrationForm.style.display = 'none';
            }
        }
    </script>
</body>
</html>
