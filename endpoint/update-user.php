<?php
session_start();
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['tbl_user_id']; // Ensure to match the form field name
    $name = $_POST['name'];
    $username = $_POST['username'];
	$email = $_POST['email'];
    $role = $_POST['role'];

    // Update the user's data in the database
    $stmt = $conn->prepare("UPDATE `tbl_user` SET `name` = :name, `username` = :username, `email` = :email, `role` = :role WHERE `tbl_user_id` = :userID");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
	$stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':userID', $userID);

    if ($stmt->execute()) {
        // Data update successful
        $_SESSION['success_message'] = "User data updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update user data.";
    }

    header("Location: ../admin.php"); // Redirect back to the admin page
    exit();
}
?>
