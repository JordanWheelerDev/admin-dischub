<?php
include 'functions.php';

if (!isLoggedIn()) {
    header('Location: login');
    exit;
}

if (!isset($_GET['uid'])) {
    header('Location: servers');
    exit;
}

$uid = $_GET['uid'];


if (isset($_POST['banUser'])) {
    // Check if the user is already banned
    $sqlCheck = "SELECT * FROM bans WHERE user_id = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $uid);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $sqlUpdateBan = "UPDATE bans SET ban_date = ?, reason = ?, lift_date =?, banned_by=? WHERE user_id = ?";
        $stmtUpdateBan = $conn->prepare($sqlUpdateBan);
        $reason = $_POST['reason'];
        $lift_date = $_POST['lift_date'];
        $today = date('Y-m-d H:i:s');
        $stmtUpdateBan->bind_param("ssis", $today, $reason, $lift_date, $user_name, $uid);
        $stmtUpdateBan->execute();
    } else {
        // User is not banned, insert a new ban record
        $sqlInsertBan = "INSERT INTO bans (user_id, ban_date, reason, lift_date, banned_by) VALUES (?,?,?,?,?)";
        $stmtInsertBan = $conn->prepare($sqlInsertBan);
        $reason = $_POST['reason'];
        $lift_date = $_POST['lift_date'];
        $today = date('Y-m-d H:i:s');
        $stmtInsertBan->bind_param("isssi", $uid, $today, $reason, $lift_date, $user_name);
        $stmtInsertBan->execute();
    }

    // Update the servers table to set is_public = 0 for all servers owned by the banned user
    $sqlUpdateServers = "UPDATE servers SET is_public = 0 WHERE owner_id = ?";
    $stmtUpdateServers = $conn->prepare($sqlUpdateServers);
    $stmtUpdateServers->bind_param("i", $uid);
    $stmtUpdateServers->execute();

    // Redirect to the bans page with a success message
    header('Location: servers?ban=success');
    exit();
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Ban <?php echo $row['uid']; ?> | DiscHub Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css?v=<?php echo time(); ?>" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <?php include 'includes/navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8">
                            <h1 class="mt-4 mb-3">Ban User ID <?php echo $uid; ?></h1>
                            <div class="card mb-4 rounded-0">
                                <div class="card-body">
                                    <form action="" method="post">
                                        <div class="mb-3">
                                            <label for="banReason" class="form-label">Reason for Ban</label>
                                            <textarea class="form-control" id="banReason" name="reason" rows="3"
                                                required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="liftDate" class="form-label">Ban Lift Date</label>
                                            <select name="lift_date" id="liftDate" class="form-select" required>
                                                <option value="">Select Date</option>
                                                <?php
                                                $currentDate = date('Y-m-d');
                                                $date = new DateTime($currentDate);

                                                // Array of intervals
                                                $intervals = [
                                                    '+1 day' => '1 Day',
                                                    '+1 month' => '1 Month',
                                                    '+3 months' => '3 Months',
                                                    '+6 months' => '6 Months',
                                                    '+1 year' => '1 Year',
                                                    '+50 years' => '50 Years'
                                                ];

                                                foreach ($intervals as $interval => $label) {
                                                    $date->modify($interval);
                                                    echo '<option value="' . $date->format('Y-m-d') . '">' . $label . ' (' . $date->format('F jS, Y') . ')</option>';
                                                    $date = new DateTime($currentDate); // Reset date after each modification
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-danger rounded-0" type="submit" name="banUser">Ban
                                                User</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
</body>

</html>