<?php
include 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
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
    <title>Awaiting Approval | DiscHub Admin</title>
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
                            <h1 class="mt-4 mb-3">Servers Awaiting Approval</h1>
                            <div class="card mb-4 rounded-0">
                                <div class="card-body">
                                    <?php if (isset($_GET['success']) && $_GET['success'] == "declined") { ?>
                                        <div class="mb-3">
                                            <div class="res-message-danger">Server was declined for public view
                                                successfully.</div>
                                        </div>
                                    <?php } else if (isset($_GET['success']) && $_GET['success'] == "approved") { ?>
                                            <div class="mb-3">
                                                <div class="res-message-success">Server was approved for public view
                                                    successfully.</div>
                                            </div>
                                    <?php } ?>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Server Name</th>
                                                <th>Server ID</th>
                                                <th>Reason</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $status = 0;
                                            $stmt = $conn->prepare("SELECT * FROM server_flags WHERE approved = ?");
                                            $stmt->bind_param("i", $status);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($row = $result->fetch_assoc()) {
                                                if (empty($row['reviewed_by'])) {
                                                    ?>

                                                    <tr>
                                                        <td><?php echo $row['server_name']; ?></td>
                                                        <td><?php echo $row['server_id']; ?></td>
                                                        <td><?php echo $row['reason']; ?></td>
                                                        <td>
                                                            <a href="approval-view?sid=<?php echo $row['server_id']; ?>"
                                                                class="btn btn-info btn-sm">View</a>
                                                        </td>

                                                    </tr>

                                                    <?php
                                                }
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