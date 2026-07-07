<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

$search = $_GET['search'] ?? '';
$category_id = $_GET['category_id'] ?? '';
$pub_date = $_GET['pub_date'] ?? '';
$sort = $_GET['sort'] ?? 'id_desc';

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = "";
$params = [];

if ($search !== '') {
    $whereClause .= " AND (b.title LIKE :search OR b.author LIKE :search OR b.publisher LIKE :search)";
    $params['search'] = "%$search%";
}

if ($category_id !== '') {
    $whereClause .= " AND b.category_id = :category_id";
    $params['category_id'] = $category_id;
}

if ($pub_date !== '') {
    $whereClause .= " AND b.publication_date = :pub_date";
    $params['pub_date'] = $pub_date;
}

try {
    $countQuery = "SELECT COUNT(*) FROM books b WHERE 1=1 " . $whereClause;
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $total_records = $countStmt->fetchColumn();
    $total_pages = ceil($total_records / $limit);

    $query = "SELECT b.*, c.name as category_name 
              FROM books b 
              LEFT JOIN categories c ON b.category_id = c.id 
              WHERE 1=1 " . $whereClause;

    switch ($sort) {
        case 'title_asc': $query .= " ORDER BY b.title ASC"; break;
        case 'title_desc': $query .= " ORDER BY b.title DESC"; break;
        case 'date_desc': $query .= " ORDER BY b.publication_date DESC"; break;
        case 'date_asc': $query .= " ORDER BY b.publication_date ASC"; break;
        default: $query .= " ORDER BY b.created_at DESC"; break;
    }

    $query .= " LIMIT $limit OFFSET $offset";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'books' => $books,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'limit' => $limit
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}