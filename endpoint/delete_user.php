<?php
include('../conn/conn.php'); // Adjust path as per your file structure

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tbl_user_id'])) {
    $userID = $_POST['tbl_user_id'];

    $stmt = $conn->prepare("DELETE FROM `tbl_user` WHERE `tbl_user_id` = ?");
    $stmt->execute([$userID]);

    // Redirect to admin page after deletion
    header("Location: ../admin.php");
    exit();
} else {
    // Handle invalid request (if any)
    echo "Invalid request.";
}
?>
