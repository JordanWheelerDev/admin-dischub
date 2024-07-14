<?php
include '../config/db.php';

header('Content-Type: application/json');

// Get the raw POST data
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

if (!$data || !isset($data['serverId'], $data['reasoning'], $data['approved'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

$serverId = $data['serverId'];
$reasoning = $data['reasoning'];
$approved = (int) $data['approved']; // Convert to integer for safety

if (trim($reasoning) === '') {
    echo json_encode(['success' => false, 'message' => 'Reasoning is required.']);
    exit;
}

// Update the server approval status in the database
$stmt = $conn->prepare("UPDATE servers SET is_approved = ? WHERE server_id = ?");
$stmt->bind_param('iss', $approved, $serverId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Server approval status updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update server approval status.']);
}
