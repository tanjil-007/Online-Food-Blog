<?php
// controllers/deleteItem.php
// AJAX endpoint — checks if an email is already registered
// Returns JSON: { "available": true/false }
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../model/userModel.php';

$email     = trim($_POST['email']      ?? '');
$excludeId = (int)($_POST['exclude_id'] ?? 0);

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['available' => false, 'message' => 'Invalid email format.']);
    exit;
}

$taken = emailExists($email, $excludeId > 0 ? $excludeId : null);

echo json_encode([
    'available' => !$taken,
    'message'   => $taken ? 'That email is already registered.' : 'Email is available.'
]);
