<?php
include('./header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>


<link rel="stylesheet" href="css/dashboard-theme.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


</head>
<body>
<div class="main">
    <div class="title-container">
        <h2>Welcome <?= $user_name ?></h2>
    </div>
    <!-- Button to trigger the modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerModal">
        Register
    </button>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register Your Account!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Please enter your personal details.</p>
                    <form action="./endpoint/add-user.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" class="form-control custom-input" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select class="form-control custom-input" id="role" name="role" required>
                                <option value="" disabled selected>-select-</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="registerUsername">Username:</label>
							<input type="email" class="form-control custom-input" id="registerUsername" name="username" required>

                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control custom-input" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="registerPassword">Password:</label>
                            <input type="password" class="form-control custom-input" id="registerPassword" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password:</label>
                            <input type="password" class="form-control custom-input" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="profilePicture">Profile Picture:</label>
                            <input type="file" class="form-control custom-input" id="profilePicture" name="profilePicture">
                        </div>
                        <button type="submit" class="btn btn-primary form-control">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="users-container">
        <h2>List of Users</h2>
        <div class="table-responsive">
            <table class="table table-dark table-striped">
                <thead class="text-center">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Account Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Role</th>
                        <th scope="col">Profile Picture</th>
                        <th scope="col">User Status</th> <!-- New column -->
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM `tbl_user`");
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $row) {
                        $userID = htmlspecialchars($row['tbl_user_id']);
                        $name = htmlspecialchars($row['name']);
                        $username = htmlspecialchars($row['username']);
                        $role = htmlspecialchars($row['role']);
                        $online_status = htmlspecialchars($row['online_status']); // Fetch online status

                        // Determine status text
                        $statusText = ($online_status === 'online') ? 'Connected' : 'Disconnected';

                        $profile_picture = htmlspecialchars($row['profile_picture']);
                    ?>
                        <tr>
                            <td><?= $userID ?></td>
                            <td><?= $name ?></td>
                            <td><?= $username ?></td>
                            <td><?= $role ?></td>
                            <td><img src="uploads/profile_pictures/<?= $profile_picture ?>" alt="Profile Picture" class="profile-img"></td>
                            <td><?= $online_status ?></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $userID ?>">Edit</button>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#resetModal<?= $userID ?>">Reset Password</button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $userID ?>">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $userID ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $userID ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $userID ?>">Edit User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="./endpoint/update-user.php" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="tbl_user_id" value="<?= $userID ?>">
                                            <div class="form-group">
                                                <label for="editName<?= $userID ?>">Full Name:</label>
                                                <input type="text" class="form-control" id="editName<?= $userID ?>" name="name" value="<?= $name ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="editUsername<?= $userID ?>">Username:</label>
                                                <input type="email" class="form-control" id="editUsername<?= $userID ?>" name="username" value="<?= $username ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="editEmail<?= $userID ?>">Email:</label>
                                                <input type="email" class="form-control" id="editEmail<?= $userID ?>" name="email" value="<?= $row['email'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="editRole<?= $userID ?>">Role:</label>
                                                <select class="form-control" id="editRole<?= $userID ?>" name="role" required>
                                                    <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                    <option value="user" <?= $role == 'user' ? 'selected' : '' ?>>User</option>
                                                </select>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="editProfilePicture<?= $userID ?>">Profile Picture:</label>
                                                <input type="file" class="form-control" id="editProfilePicture<?= $userID ?>" name="profile_picture">
                                            </div> -->
                                            <button type="submit" class="btn btn-primary form-control">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reset Password Modal -->
                        <div class="modal fade" id="resetModal<?= $userID ?>" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel<?= $userID ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="resetModalLabel<?= $userID ?>">Reset Password</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="./endpoint/reset_password_admin.php" method="POST">
                                            <input type="hidden" name="tbl_user_id" value="<?= $userID ?>">
                                            <div class="form-group">
                                                <label for="newPassword<?= $userID ?>">New Password:</label>
                                                <input type="password" class="form-control" id="newPassword<?= $userID ?>" name="new_password" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary form-control">Reset Password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteModal<?= $userID ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $userID ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?= $userID ?>">Delete User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete user <?= $name ?>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="./endpoint/delete_user.php" method="POST">
                                            <input type="hidden" name="tbl_user_id" value="<?= $userID ?>">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery, Popper.js, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $('#registerModal').on('shown.bs.modal', function () {
            $('#name').trigger('focus');
        });
    });
</script>
</body>
</html>
