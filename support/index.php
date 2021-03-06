<?php
// include header
include $_SERVER['DOCUMENT_ROOT'] . '/includes/template/header.php';
// instantiate projects class
$support = new \SimpleLance\Support($db);
// check if user is a customer and show only their tickets
if ($_SESSION['access_level'] != '1') {
    $ticket = $support->listUserTickets($_SESSION['id']);
?>
    <div class="row col-md-9 col-md-offset-1 custyle">
        <?php
        if (isset($_GET['addsuccess']) && empty($_GET['addsuccess'])) {
            echo '<br><div class="alert alert-success" role="alert">Support ticket successfully added.</div>';
        }
        if (isset($_GET['updatesuccess']) && empty($_GET['editsuccess'])) {
            echo '<br><div class="alert alert-success" role="alert">Support ticket successfully updated.</div>';
        }
        ?>
        <br><br>
        <a href="/support/new.php" class="btn btn-primary">Create New Ticket</a>
        <br><br>
        <table class="table table-striped custab">
            <thead>
            <tr>
                <th>Subject</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Last Reply By</th>
                <th>Last Reply On</th>
                <th>View</th>
            </tr>
            </thead>
            <?php
            foreach ($ticket as $t) { ?>
                <tr>
                    <td><?php echo htmlentities($t['subject']); ?></td>
                    <td><?php echo $support->getPriority($t['priority']); ?></td>
                    <td><?php echo $support->getStatus($t['status']); ?></td>
                    <td><?php echo $users->getUser($t['last_reply_user'])['first_name'].' '.$users->getUser($t['last_reply_user'])['last_name']; ?></td>
                    <td><?php echo date('d/m/Y H:m', strtotime($t['last_reply_date'])); ?></td>
                    <td><a href="/support/ticket.php?id=<?php echo htmlentities($t['id']); ?>">View</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
<?php } else {

    // load all tickets
    $ticket = $support->listAllTickets();
    ?>
    <!-- html -->

    <div class="row col-md-9 col-md-offset-1 custyle">
        <?php
        if (isset($_GET['addsuccess']) && empty($_GET['addsuccess'])) {
            echo '<br><div class="alert alert-success" role="alert">Support ticket successfully added.</div>';
        }
        if (isset($_GET['editsuccess']) && empty($_GET['editsuccess'])) {
            echo '<br><div class="alert alert-success" role="alert">Support ticket successfully updated.</div>';
        }
        ?>
        <table class="table table-striped custab">
            <thead>
            <tr>
                <th>Subject</th>
                <th>Owner</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Last Reply By</th>
                <th>Last Reply On</th>
                <th>View</th>
            </tr>
            </thead>
            <?php
            foreach ($ticket as $t) { ?>
                <tr>
                    <td><?php echo htmlentities($t['subject']); ?></td>
                    <td><?php echo $users->getUser($t['owner'])['first_name'].' '.$users->getUser($t['owner'])['last_name']; ?></td>
                    <td><?php echo $support->getPriority($t['priority']); ?></td>
                    <td><?php echo $support->getStatus($t['status']); ?></td>
                    <td><?php echo $users->getUser($t['last_reply_user'])['first_name'].' '.$users->getUser($t['last_reply_user'])['last_name']; ?></td>
                    <td><?php echo date('d/m/Y H:m', strtotime($t['last_reply_date'])); ?></td>
                    <td><a href="/support/ticket.php?id=<?php echo htmlentities($t['id']); ?>">View</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>


    <!-- /html -->
<?php
}
// include footer
include ABS_PATH . '/includes/template/footer.php';
?>
