<?php
session_start();
include('../conn/conn.php'); // Adjust the path if needed

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['edit_profile'])) {
    $user_id = $_SESSION['user_id'];
    $name = htmlspecialchars($_POST['editName'], ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($_POST['editUsername'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['editEmail'], ENT_QUOTES, 'UTF-8');

    try {
        $stmt = $conn->prepare("UPDATE `tbl_user` SET `name` = :name, `username` = :username, `email` = :email WHERE `tbl_user_id` = :user_id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Profile updated successfully.";
        } else {
            $_SESSION['message'] = "Failed to update profile.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
    }

    header("Location: ../index.php");
    exit;
}
?>
