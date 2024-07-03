<?php
session_start();

if (isset($_POST['username'], $_POST['password'])) {
    include('../conn/conn.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $row['tbl_user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                // Check if last_login_datetime is null
                if ($row['last_login_datetime'] === null) {
                    $_SESSION['first_login'] = true;
                    // Redirect to reset password page
                    header("Location: reset-password.php");
                    exit();
                } else {
                    // Update user status to 'online' and update last_login_datetime
                    $updateStmt = $conn->prepare("UPDATE tbl_user SET online_status = 'online', last_login_datetime = NOW() WHERE tbl_user_id = :user_id");
                    $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
                    $updateStmt->execute();

                    if ($row['role'] == 'admin') {
                        header("Location: /Dashboard/admin.php");
                        exit();
                    } else if ($row['role'] == 'user') {
                        header("Location: /Dashboard/user.php");
                        exit();
                    }
                }
            } else {
                echo "<script>alert('Invalid username or password.'); window.location.href = 'http://Localhost/Dashboard/';</script>";
            }
        } else {
            echo "<script>alert('Invalid username or password.'); window.location.href = 'http://Localhost/Dashboard/';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
