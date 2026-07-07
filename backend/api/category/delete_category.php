<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute(['id' => $id]);
    echo json_encode(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
} catch (PDOException $e) {

    if ($e->getCode() == '23000') {
        echo json_encode(['success' => false, 'message' => 'Gagal: Kategori ini sedang digunakan oleh satu atau lebih buku.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}