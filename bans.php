<?php
include 'functions.php';

if (!isLoggedIn()) {
    header('Location: login');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="https://dischub.org/images/logo-w.png">
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
                                    <?php if (isset($_GET['success']) && $_GET['success'] == 'unban') { ?>
                                        <div class="res-message-success">User was unbanned successfully.</div>
                                    <?php } elseif (isset($_GET['success']) && $_GET['success'] == 'ban') { ?>
                                        <div class="res-message-success">User was banned successfully.</div>
                                    <?php } ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Reason</th>
                                                <th>Lift Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $conn->prepare("SELECT * FROM bans");
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($ban = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $ban['user_id']; ?></td>
                                                    <td><?php echo $ban['reason']; ?></td>
                                                    <td><?php echo $ban['lift_date']; ?></td>
                                                    <td><a href="unban?uid=<?php echo $ban['user_id']; ?>"
                                                            class="btn btn-info">Unban</a></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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