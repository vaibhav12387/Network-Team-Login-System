<?php
session_start();
include('../conn/conn.php');  // Adjust the path as necessary

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

if ($newPassword !== $confirmPassword) {
	    // Notify user on screen
                echo "<script>alert('New passwords do not match.'); window.location.href = 'http://Localhost/Dashboard/index.php';</script>";
                exit();
}

$newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);

try {
    $stmt = $conn->prepare("SELECT password FROM tbl_user WHERE tbl_user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = $row['password'];
        
        if (password_verify($currentPassword, $hashedPassword)) {
            $updateStmt = $conn->prepare("UPDATE tbl_user SET password = :new_password WHERE tbl_user_id = :user_id");
            $updateStmt->bindParam(':new_password', $newPasswordHashed, PDO::PARAM_STR);
            $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                // Notify user on screen
                echo "<script>alert('Password updated successfully.'); window.location.href = 'http://Localhost/Dashboard/index.php';</script>";
                exit();
            } else {
               // Notify user on screen
                echo "<script>alert('Error updating password..'); window.location.href = 'http://Localhost/Dashboard/index.php';</script>";
                exit();
            }
        } else {
			    // Notify user on screen
                echo "<script>alert('Current password is incorrect.'); window.location.href = 'http://Localhost/Dashboard/index.php';</script>";
                exit();
        }
    } else {
        echo "User not found.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
