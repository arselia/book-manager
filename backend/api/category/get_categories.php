<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = "";
$params = [];

if ($search !== '') {
    $whereClause .= " WHERE c.name LIKE :search";
    $params['search'] = "%$search%";
}

$countQuery = "SELECT COUNT(*) FROM categories c" . $whereClause;
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($params);
$total_records = $countStmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

$query = "SELECT c.id, c.name, DATE_FORMAT(c.created_at, '%d %b %Y, %H:%i') as formatted_date, COUNT(b.id) as book_count 
          FROM categories c 
          LEFT JOIN books b ON c.id = b.category_id 
          $whereClause 
          GROUP BY c.id 
          ORDER BY c.id DESC 
          LIMIT $limit OFFSET $offset";
          
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'categories' => $categories,
    'pagination' => [
        'current_page' => $page,
        'total_pages' => $total_pages,
        'total_records' => $total_records,
        'limit' => $limit
    ]
]);