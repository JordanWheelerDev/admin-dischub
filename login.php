<?php
include 'functions.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['loginBtn'])) {
    login();
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
    <title>Login | DiscHub ADmin</title>
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <link href="css/login.css" rel="stylesheet" />
    <!-- Bootstrap 5.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="login-cont">
            <form action="" method="post">
                <div class="mb-3 text-center">
                    <img src="https://dischub.org/images/site/logo-b.png" alt="" class="img-fluid">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control form-control-lg rounded-0" id="username" name="username"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-lg rounded-0" id="password" name="password"
                        required>
                </div>
                <div class="mb-3">
                    <button type="submit" class="dha-btn w-100" name="loginBtn">Login</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>