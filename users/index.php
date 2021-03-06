<?php
// include header
include $_SERVER['DOCUMENT_ROOT'] . '/includes/template/header.php';

// only allow access to admins
if ($_SESSION['access_level'] !== '1') {
    header('Location: /access-denied.php');
    exit();
}
// load users
$users = $users->listUsers();
?>
    <!-- html -->

    <div class="row col-md-9 col-md-offset-1 custyle">
        <?php
        if (isset($_GET['addsuccess']) && empty($_GET['addsuccess'])) {
            echo '<br><div class="alert alert-success" role="alert">User successfully added, they can now login.</div>';
        }
        if (isset($_GET['editsuccess']) && empty($_GET['editsuccess'])) {
            echo '<br><div class="alert alert-success" role="alert">User successfully updated.</div>';
        }
        if (isset($_GET['deletesuccess']) && empty($_GET['deletesuccess'])) {
            echo '<br><div class="alert alert-success" role="alert">User successfully deleted.</div>';
        }
        ?>
        <table class="table table-striped custab">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <?php
            foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo htmlentities($user['first_name']); ?></td>
                    <td><?php echo htmlentities($user['last_name']); ?></td>
                    <td><?php echo htmlentities($user['email']); ?></td>
                    <td><?php if ($user['access_level'] == 1) { echo("Admin"); } else { echo("Customer"); } ?></td>
                    <td><a href="/users/profile.php?id=<?php echo htmlentities($user['id']); ?>">View</a> : <a class="delete_row" href="/users/delete.php?id=<?php echo htmlentities($user['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>


    <!-- /html -->
<?php
// include footer
include ABS_PATH . '/includes/template/footer.php';
?>
