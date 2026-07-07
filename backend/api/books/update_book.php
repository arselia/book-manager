<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? '';
$title = trim($data['title'] ?? '');
$author = trim($data['author'] ?? '');
$publication_date = $data['publication_date'] ?? '';
$publisher = trim($data['publisher'] ?? '');
$num_pages = (int)($data['num_pages'] ?? 0);
$category_id = $data['category_id'] ?? ''; 

if (empty($id) || empty($title) || empty($category_id)) {
    echo json_encode(['success' => false, 'message' => 'ID, Judul, dan Kategori wajib diisi.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE books SET title=:title, author=:author, publication_date=:pub_date, 
                           publisher=:publisher, num_pages=:num_pages, category_id=:cat_id WHERE id=:id");
    
    $stmt->execute([
        'title' => $title, 
        'author' => $author, 
        'pub_date' => $publication_date,
        'publisher' => $publisher, 
        'num_pages' => $num_pages, 
        'cat_id' => $category_id,
        'id' => $id
    ]);

    echo json_encode(['success' => true, 'message' => 'Buku berhasil diperbarui!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}