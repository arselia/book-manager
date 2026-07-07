<?php
$current_uri = $_SERVER['REQUEST_URI'];
$is_books = strpos($current_uri, '/frontend/books/') !== false;
$is_categories = strpos($current_uri, '/frontend/categories/') !== false;
$is_home = (!$is_books && !$is_categories);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Book Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-gray-800 flex h-screen overflow-hidden">

    <aside class="w-64 bg-white border-r border-blue-100 flex flex-col shadow-sm">
        <div class="p-6 border-b border-blue-50">
            <h1 class="text-2xl font-bold text-blue-600 tracking-wide">Book<span class="text-gray-700">Management</span></h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            
            <a href="/index.php" class="flex items-center px-4 py-3 rounded-lg font-medium transition-colors duration-200 <?= $is_home ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50/50 hover:text-blue-600' ?>">
                <svg class="w-5 h-5 mr-3 <?= $is_home ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Beranda
            </a>
            
            <a href="/frontend/books/index.php" class="flex items-center px-4 py-3 rounded-lg font-medium transition-colors duration-200 <?= $is_books ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50/50 hover:text-blue-600' ?>">
                <svg class="w-5 h-5 mr-3 <?= $is_books ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Buku
            </a>
            
            <a href="/frontend/categories/index.php" class="flex items-center px-4 py-3 rounded-lg font-medium transition-colors duration-200 <?= $is_categories ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50/50 hover:text-blue-600' ?>">
                <svg class="w-5 h-5 mr-3 <?= $is_categories ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Kategori
            </a>
            
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="h-16 bg-white border-b border-blue-100 flex items-center px-8 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-700">Dashboard Manajemen</h2>
        </header>

        <div class="flex-1 overflow-y-auto p-8 bg-slate-50">