<?php
include 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['uid'])) {
    $userId = $_GET['uid'];
    $stmt = $conn->prepare("DELETE FROM bans WHERE user_id =?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        $ispub = 1;
        $stmt2 = $conn->prepare("UPDATE servers SET is_public = ? WHERE owner_id = ?");
        $stmt2->bind_param("ii", $ispub, $userId);
        if ($stmt2->execute()) {
            header('Location: bans?success=unban');
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}
