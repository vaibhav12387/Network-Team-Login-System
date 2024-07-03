<?php
include('./header.php');

if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F5 Monitoring</title>

<link rel="stylesheet" href="css/dashboard-theme.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

	    <script>
        function refreshIframe() {
            document.getElementById('content-frame').src += '';
        }
        setInterval(refreshIframe, 60000); // Refresh every 60 seconds
    </script>
</head>
<body>

 
    <!-- Main Content -->
    <div class="main-content">
	<div class="iframe-container">
            <iframe id="content-frame" src="http://172.20.3.201:5000" frameborder="0" name="content"></iframe>
        </div>

    <!-- Bootstrap 4 JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
