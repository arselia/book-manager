<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$name = trim($data['name'] ?? '');

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Nama kategori wajib diisi.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute(['name' => $name]);
    echo json_encode(['success' => true, 'message' => 'Kategori berhasil ditambahkan!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}