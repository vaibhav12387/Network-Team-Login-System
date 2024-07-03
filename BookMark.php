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
    <title>Bookmarks</title>


<link rel="stylesheet" href="css/dashboard-theme.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


</head>
<body>

<div class="main">
    <h2>IMP URL</h2>

    <select id="category_select" class="mb-3 form-control">
        <option value="tac_case">TAC CASE</option>
        <option value="portal">Portal</option>
        <option value="intranet">Intranet</option>
        <option value="monitoring">Monitoring Tools</option>
        <option value="certificate">Certificate Management</option>
    </select>

    <div id="bookmark_tables">
        <!-- Initially display only the TAC CASE category -->
        <?php include('bookmarks/tac_case_bookmarks.php'); ?>
    </div>

    <!-- Bootstrap 4 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</div>

<!-- JavaScript to handle category selection and update table content -->
<script>
    const categorySelect = document.getElementById('category_select');
    const bookmarkTables = document.getElementById('bookmark_tables');

    categorySelect.addEventListener('change', function () {
        const selectedCategory = this.value;

        // Clear previous content
        bookmarkTables.innerHTML = '';

        // Load corresponding category content dynamically
        switch (selectedCategory) {
            case 'tac_case':
                loadBookmarkTable('bookmarks/tac_case_bookmarks.php');
                break;
            case 'portal':
                loadBookmarkTable('bookmarks/portal_bookmarks.php');
                break;
            case 'intranet':
                loadBookmarkTable('bookmarks/intranet_bookmarks.php');
                break;
            case 'monitoring':
                loadBookmarkTable('bookmarks/monitoring_bookmarks.php');
                break;
            case 'certificate':
                loadBookmarkTable('bookmarks/certificate_bookmarks.php');
                break;
            default:
                // Handle default case or error
                break;
        }
    });

    // Function to load bookmark table content from server-side PHP scripts
    function loadBookmarkTable(url) {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                bookmarkTables.innerHTML = data;
            })
            .catch(error => console.error('Error fetching bookmark data:', error));
    }

    // Initial load for the default category
    loadBookmarkTable('bookmarks/tac_case_bookmarks.php');
</script>

</body>
</html>
