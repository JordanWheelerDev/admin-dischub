<?php
include 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (!$user_id == 1) {
    header('Location: index.php');
    exit;
}

$message = '';

if (isset($_POST['addUserBtn'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $stmt = $conn->prepare("INSERT INTO staff (username, password, `role`) VALUES (?,?,?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = 'User added successfully!';
    } else {
        $message = 'Failed to add user.';
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
    <title>Add User | DiscHub Admin</title>
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
                            <h1 class="mt-4">Add User</h1>
                            <div class="card mb-4 rounded-0">
                                <div class="card-body">
                                    <?php if (!empty($message)) { ?>
                                        <div class="mb-3 res-message"><?php echo $message; ?></div>
                                    <?php } ?>
                                    <form action="" method="post">
                                        <div class="mb-3">
                                            <label for="username">Username</label>
                                            <input class="form-control rounded-0" id="username" name="username"
                                                type="text" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="password">Password</label>
                                            <input class="form-control rounded-0" id="password" name="password"
                                                type="password" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="role">Role</label>
                                            <select class="form-select rounded-0" id="role" name="role" required>
                                                <option value="">Select Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="mod">Mod</option>
                                                <option value="t-mod">Trial Mod</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-info rounded-0" name="addUserBtn">Add User</button>
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