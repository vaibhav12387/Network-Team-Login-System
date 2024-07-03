<?php
session_start();
include('../conn/conn.php'); // Adjust path as per your directory structure

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs (optional)
    
    $newPassword = $_POST['new_password'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    try {
        // Hash the new password
        $hashedPassword = $row['password'];

        // Update password and user status in the database
        $updateStmt = $conn->prepare("UPDATE tbl_user SET password = :password, online_status = 'online', last_login_datetime = NOW() WHERE tbl_user_id = :user_id");
        $updateStmt->bindParam(':password', $hashedPassword);
        $updateStmt->bindParam(':user_id', $user_id);
        
        if ($updateStmt->execute()) {
            $_SESSION['status_message'] = 'Password changed successfully!';
			
            header("Location: /Dashboard/admin.php"); // Redirect to admin dashboard
            exit();
        } else {
            $_SESSION['error_message'] = 'Failed to change password.';
            header("Location: admin_change_password.php"); // Redirect back to change password page on failure
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
        header("Location: admin_change_password.php"); // Redirect back to change password page on error
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Invalid request.';
    header("Location: admin_change_password.php"); // Redirect back to change password page on invalid request
    exit();
}
?>
