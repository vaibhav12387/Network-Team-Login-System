<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_login'])) {
    header("Location: /Dashboard/");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../conn/conn.php');

    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    if ($newPassword === $confirmNewPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
		
			// Update user status to 'online' and update last_login_datetime
      
        try {
            $updateStmt = $conn->prepare("UPDATE tbl_user SET password = :password, online_status = 'online', last_login_datetime = NOW() WHERE tbl_user_id = :user_id");
            $updateStmt->bindParam(':password', $hashedPassword);
            $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
            $updateStmt->execute();

            unset($_SESSION['first_login']);

            if ($_SESSION['role'] == 'admin') {
                header("Location: /Dashboard/admin.php");
                exit();
            } else if ($_SESSION['role'] == 'user') {
                header("Location: /Dashboard/user.php");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Passwords do not match.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .main {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .title-container h2 {
            z-index: 2;
            position: relative;
        }

        .background-messages {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .message {
            position: absolute;
            color: rgba(0, 0, 0, 0.1);
            font-size: 2rem;
            animation: drop 10s linear infinite;
        }

        @keyframes drop {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(100%);
            }
        }
    </style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="main">
        <div class="title-container">
            <h2>Welcome</h2>
        </div>
        <div class="background-messages"></div>
    </div>

    <script>
        const messages = ["Hello", "Welcome", "Enjoy", "Hello World", "Network Team", "Login System", "Security", "User"];
        const container = document.querySelector('.background-messages');

        function createMessage() {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message');
            messageElement.textContent = messages[Math.floor(Math.random() * messages.length)];
            messageElement.style.left = Math.random() * 100 + 'vw';
            messageElement.style.animationDuration = Math.random() * 5 + 5 + 's';
            container.appendChild(messageElement);

            setTimeout(() => {
                container.removeChild(messageElement);
            }, 10000);
        }

        setInterval(createMessage, 1000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#resetPasswordModal').modal('show');
        });
    </script>

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
                    <form action="" method="POST">
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
</body>
</html>
