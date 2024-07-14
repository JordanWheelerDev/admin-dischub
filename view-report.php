<?php
include 'functions.php';

if (!isLoggedIn()) {
    header('Location: login');
    exit;
}

if (!isset($_GET['sid'])) {
    header('Location: reports');
    exit;
}

$serverId = $_GET['sid'];
$stmt = $conn->prepare("SELECT * FROM reports WHERE server_id =?");
$stmt->bind_param("i", $serverId);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $row['name']; ?> Report | DiscHub Admin</title>
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
                            <h1 class="mt-4 mb-3"><?php echo $row['name']; ?> (Report)</h1>
                            <div class="card mb-4 rounded-0">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="fw-bold">Server Name</div>
                                        <div><?php echo $row['name']; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Report Reason</div>
                                        <div><?php echo $row['report_reason']; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Report Message</div>
                                        <div><?php echo $row['report_message']; ?></div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        let approveServerDiv = document.getElementById('approveServerDiv');
        let approvedServerBtn = document.getElementById('approveServerBtn');
        let declineServerDiv = document.getElementById('declineServerDiv');
        let declineServerBtn = document.getElementById('declineServerBtn');
        let drugsTos = document.getElementById('drugsTos');
        let declineReasoning = document.getElementById('declineReasoning');

        approvedServerBtn.addEventListener('click', () => {
            approveServerDiv.style.display = 'block';
            declineServerDiv.style.display = 'none';
        });

        declineServerBtn.addEventListener('click', () => {
            declineServerDiv.style.display = 'block';
            approveServerDiv.style.display = 'none';
        });

        drugsTos.addEventListener('click', () => {
            declineReasoning.value = drugsTos.innerText;
        });

    </script>
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