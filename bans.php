<?php
include 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$maxBans = 5;
$bansIssued = 0;
$remainingBans = $maxBans;
$bans = [];

if ($user_role == "t-mod") {
    // Prepare the SQL statement to count the number of bans
    $stmt = $conn->prepare("SELECT COUNT(*) as ban_count FROM bans WHERE by_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $bansIssued = $row['ban_count'];
        $remainingBans = $maxBans - $bansIssued;
    }
    $stmt->close();

    // Fetch the bans if the number of bans is less than 5
    if ($bansIssued < $maxBans) {
        $stmt = $conn->prepare("SELECT * FROM bans WHERE by_id = ?");
        $stmt->bind_param("i", $tmod_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $bans[] = $row;
        }
        $stmt->close();
    }
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
    <title>Bans | DiscHub Admin</title>
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
                            <h1 class="mt-4">Bans</h1>
                            <div class="card mb-4 rounded-0">
                                <div class="card-body">
                                    <?php if ($user_role == "t-mod"): ?>
                                        <?php if ($bansIssued < $maxBans): ?>
                                            <div class="res-message">
                                                You have <?php echo $remainingBans; ?> out of <?php echo $maxBans; ?> bans
                                                remaining.
                                            </div>

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>User ID</th>
                                                        <th>Reason</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($bans as $ban): ?>
                                                        <tr>
                                                            <td><?php echo $ban['user_id']; ?></td>
                                                            <td><?php echo $ban['reason']; ?></td>
                                                            <td><?php echo $ban['date']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-warning rounded-0">
                                                You have reached the maximum number of bans. An admin will review your bans.
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
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