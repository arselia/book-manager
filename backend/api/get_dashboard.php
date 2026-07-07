<?php

require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

try {
    $bookCountStmt = $pdo->query("SELECT COUNT(*) FROM books");
    $totalBooks = $bookCountStmt->fetchColumn();

    $catCountStmt = $pdo->query("SELECT COUNT(*) FROM categories");
    $totalCategories = $catCountStmt->fetchColumn();

    $recentBooksStmt = $pdo->query("
        SELECT b.id, b.title, b.author, c.name as category_name 
        FROM books b 
        LEFT JOIN categories c ON b.category_id = c.id 
        ORDER BY b.created_at DESC 
        LIMIT 5
    ");
    $recentBooks = $recentBooksStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'stats' => [
            'total_books' => $totalBooks,
            'total_categories' => $totalCategories
        ],
        'recent_books' => $recentBooks
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}