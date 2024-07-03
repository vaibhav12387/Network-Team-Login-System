<?php
session_start();
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];

    // Generate a new random password
    $newPassword = generateRandomPassword(); // Implement this function to generate a secure random password

    // Hash the new password
    $hashedPassword = $row['password'];

    // Update the user's password in the database
    $stmt = $conn->prepare("UPDATE `tbl_user` SET `password` = :password WHERE `tbl_user_id` = :userID");
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':userID', $userID);

    if ($stmt->execute()) {
        // Password reset successful
        // You might want to notify the user of their new password via email or other means
        $_SESSION['success_message'] = "Password reset successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to reset password.";
    }

    header("Location: ../admin.php"); // Redirect back to the admin page
    exit();
}

function generateRandomPassword() {
    // Implement a function to generate a secure random password (e.g., using random_bytes() and base64_encode())
    // Return the generated password
}
?>
