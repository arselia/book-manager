<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? '';
$name = trim($data['name'] ?? '');

if ($id === 0 || empty($name)) {
    echo json_encode(['success' => false, 'message' => 'ID dan Nama kategori wajib diisi.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
    $stmt->execute(['name' => $name, 'id' => $id]);
    echo json_encode(['success' => true, 'message' => 'Kategori berhasil diperbarui!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}