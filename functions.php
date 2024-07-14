<?php
session_start();

include 'config/db.php';

function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function login()
{
    global $conn;
    session_start(); // Start the session at the beginning of the function

    // Ensure sanitize function is defined elsewhere in your codebase
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM staff WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['username'] = $row['username'];
            // Redirect to dashboard
            header("Location: index.php");
            exit();
        } else {
            // Password does not match
            echo "Invalid username or password!";
            exit();
        }
    } else {
        // No user found with that username
        echo "Invalid username or password!";
        exit();
    }
    $stmt->close();
}

function register()
{
    global $conn;

    // Sanitize the input data
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO staff (username, password) VALUES (?,?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

function isLoggedIn()
{
    if (isset($_SESSION['username'])) {
        return true;
    } else {
        return false;
    }
}

if (isset($_SESSION['username'])) {
    $uname = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT * FROM staff WHERE username = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $user_id = $row['id'];
    $user_name = $row['username'];
    $user_role = $row['role'];
}

function getNumberOfReports()
{
    global $conn;
    $resolved = 0;
    $stmt = $conn->prepare("SELECT COUNT(*) as total_reports FROM reports WHERE resolved =?");
    $stmt->bind_param("i", $resolved);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['total_reports'];
}

function getNumberOfAwaitingApprovalServers()
{
    global $conn;
    $approved = 0;
    $stmt = $conn->prepare("SELECT COUNT(*) as total_servers FROM servers WHERE is_approved =?");
    $stmt->bind_param("i", $approved);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['total_servers'];
}