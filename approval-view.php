<?php
include 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['sid'])) {
    header('Location: awaiting-approval');
    exit;
}

$serverId = $_GET['sid'];
$stmt = $conn->prepare("SELECT * FROM servers WHERE server_id =?");
$stmt->bind_param("i", $serverId);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();

if (isset($_POST['approveServer'])) {
    $approved_type = 1;
    $is_public = 1;
    $stmt = $conn->prepare("UPDATE servers SET is_approved =?, is_public = ? WHERE server_id =?");
    $stmt->bind_param("iii", $approved_type, $is_public, $serverId);

    if ($stmt->execute()) {

        $reasoning = $_POST['reasoning'];
        $stmt2 = $conn->prepare("UPDATE server_flags SET approved =?, reasoning =?, reviewed_by =? WHERE server_id =?");
        $stmt2->bind_param("issi", $approved_type, $reasoning, $user_name, $serverId);

        if ($stmt2->execute()) {
            header('Location: awaiting-approval?success=approved');
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $stmt2->close();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}
if (isset($_POST['declineServer'])) {
    $approved_type = 0;
    $stmt = $conn->prepare("UPDATE servers SET is_approved =? WHERE server_id =?");
    $stmt->bind_param("ii", $approved_type, $serverId);

    if ($stmt->execute()) {

        $reasoning = $_POST['reasoning'];
        $stmt2 = $conn->prepare("UPDATE server_flags SET approved =?, reasoning =?, reviewed_by =? WHERE server_id =?");
        $stmt2->bind_param("issi", $approved_type, $reasoning, $user_name, $serverId);

        if ($stmt2->execute()) {
            header('Location: awaiting-approval?success=declined');
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $stmt2->close();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
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
    <title><?php echo $row['name']; ?> | DiscHub Admin</title>
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
                            <h1 class="mt-4 mb-3"><?php echo $row['name']; ?> (Awaiting Approval)</h1>
                            <div class="card mb-4 rounded-0">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="fw-bold">Server Name</div>
                                        <div><?php echo $row['name']; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Server Description</div>
                                        <div><?php echo $row['description']; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Server Category</div>
                                        <div><?php echo $row['category']; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Server Tags</div>
                                        <div>
                                            <?php foreach (explode(',', $row['tags']) as $tag) { ?>
                                                <span class="badge bg-primary mr-1"><?php echo $tag; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">User Count</div>
                                        <div><?php echo $row['user_count']; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">NSFW</div>
                                        <div>
                                            <?php if ($row['is_nsfw'] == 1) { ?>
                                                <span class="badge bg-danger">Yes</span>
                                            <?php } else { ?>
                                                <span class="badge bg-success">No</span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <button id="approveServerBtn" class="btn btn-primary mb-3">Approve</button>
                                    <button id="declineServerBtn" class="btn btn-danger mb-3">Decline</button>
                                    <div class="mb-3" id="approveServerDiv" style="display: none;">
                                        <form action="" method="post">
                                            <div class="mb-3">
                                                <label for="reasoning" class="form-label">Enter your reason for
                                                    approving this server</label>

                                                <textarea class="form-control" id="reasoning" name="reasoning"
                                                    rows="4"></textarea>
                                            </div>
                                            <button class="btn btn-primary" name="approveServer">Approve</button>
                                        </form>
                                    </div>
                                    <div class="mb-3" id="declineServerDiv" style="display: none;">
                                        <div class="mb-3">
                                            <button class="btn btn-info" id="drugsTos">Servers discussing drugs are
                                                against DiscHub's
                                                Terms of Service.</button>
                                        </div>
                                        <form action="" method="post">
                                            <div class="mb-3">
                                                <label for="reasoning" class="form-label">Enter your reason for
                                                    decling this server</label>

                                                <textarea class="form-control" id="declineReasoning" name="reasoning"
                                                    rows="4"></textarea>
                                            </div>
                                            <button class="btn btn-danger" name="declineServer">Decline</button>
                                        </form>
                                    </div>
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