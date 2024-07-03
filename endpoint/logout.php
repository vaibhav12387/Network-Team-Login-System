<?php
session_start();
include('../conn/conn.php'); // Adjust path as necessary

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    try {
        // Update user status to Offline
        $stmt = $conn->prepare("UPDATE `tbl_user` SET `online_status` = 'Offline' WHERE `tbl_user_id` = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Destroy the session
        session_destroy();

        // Redirect to login page after logout
        echo "
        <script>
            alert('Logged Out Successfully!');
            window.location.href = 'http://Localhost/Dashboard/';
        </script>
        ";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If session user_id is not set, redirect to login page
    header("Location: http://Localhost/Dashboard/");
    exit();
}
?>
