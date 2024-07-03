<?php
include('../conn/conn.php');

if (isset($_POST['name'], $_POST['role'], $_POST['username'], $_POST['password'], $_POST['email'])) {
    $name = htmlspecialchars($_POST['name']);
    $role = htmlspecialchars($_POST['role']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $conn->beginTransaction(); // Start transaction

    try {
        // Handle profile picture upload
        if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == UPLOAD_ERR_OK) {
            $profilePicture = $_FILES['profilePicture'];
            $uploadDir = 'uploads/profile_pictures/';
            $profilePictureName = basename($profilePicture['name']);
            $uploadFile = $uploadDir . $profilePictureName;

            // Check file size (limit: 1MB)
            if ($profilePicture['size'] > 1048576) {
                echo "File size exceeds the 1MB limit.";
                $profilePictureName = 'default_image.png'; // Default image path
            } elseif (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
                // File uploaded successfully
                echo "File uploaded successfully!";
            } else {
                // File upload error
                echo "Error uploading file.";
                $profilePictureName = 'default_image.png'; // Default image path
            }
        } else {
            $profilePictureName = 'default_image.png'; // Default image path
        }

        // Insert user data into the database
        $query = "INSERT INTO tbl_user (name, username, email, password, role, profile_picture) VALUES (:name, :username, :email, :password, :role, :profile_picture)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $profilePictureName, PDO::PARAM_STR);
        $stmt->execute();


        // Check if user data insertion was successful
        if ($stmt->rowCount() > 0) {
            $conn->commit(); // Commit transaction
            echo "<script>alert('Registered Successfully!'); window.location.href = 'http://Localhost/Dashboard/';</script>";
        } else {
            $conn->rollBack(); // Roll back transaction
            echo "<script>alert('Registration Failed!'); window.location.href = 'http://Localhost/Dashboard/';</script>";
        }
    } catch (PDOException $e) {
        $conn->rollBack(); // Roll back transaction
        echo "Error: " . $e->getMessage();
    }
}
?>
