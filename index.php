<?php
require_once 'backend/config/database.php';
require_once 'frontend/includes/header.php';
?>

<div class="flex flex-col space-y-6">
    
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-2xl shadow-sm p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang di Dashboard!</h2>
            <p class="text-blue-50 opacity-90 max-w-xl">
                Sistem Manajemen Perpustakaan ini dirancang untuk memudahkan Anda dalam mengelola inventaris buku dan kategori secara efisien.
            </p>
        </div>
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10"></div>
        <div class="absolute bottom-0 right-32 -mb-16 w-32 h-32 rounded-full bg-white opacity-10"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-6 flex items-center transition-transform hover:-translate-y-1 duration-300">
            <div class="bg-blue-50 p-4 rounded-xl mr-5">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Buku</p>
                <h3 id="stat-books" class="text-3xl font-bold text-gray-800 mt-1">...</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-6 flex items-center transition-transform hover:-translate-y-1 duration-300">
            <div class="bg-blue-50 p-4 rounded-xl mr-5">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Kategori</p>
                <h3 id="stat-categories" class="text-3xl font-bold text-gray-800 mt-1">...</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-blue-50 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-800">Buku Terbaru</h3>
            <a href="frontend/books/index.php" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul Buku</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Penulis</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                    </tr>
                </thead>
                <tbody id="recent-books-list" class="divide-y divide-blue-50">
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">Memuat data terbaru...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    try {
        const response = await fetch('backend/api/get_dashboard.php');
        const data = await response.json();

        if (data.success) {
            animateValue("stat-books", 0, data.stats.total_books, 1000);
            animateValue("stat-categories", 0, data.stats.total_categories, 1000);

            const tbody = document.getElementById('recent-books-list');
            if (data.recent_books.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">Belum ada buku yang ditambahkan.</td></tr>`;
            } else {
                tbody.innerHTML = data.recent_books.map(book => `
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">${book.title}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${book.author}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs">${book.category_name || 'Uncategorized'}</span>
                        </td>
                    </tr>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Failed to load dashboard stats:', error);
        document.getElementById('stat-books').innerText = '-';
        document.getElementById('stat-categories').innerText = '-';
        document.getElementById('recent-books-list').innerHTML = `<tr><td colspan="3" class="px-6 py-8 text-center text-red-500 text-sm">Gagal memuat data.</td></tr>`;
    }
});

function animateValue(id, start, end, duration) {
    if (start === end) {
        document.getElementById(id).innerHTML = end;
        return;
    }
    let range = end - start;
    let current = start;
    let increment = end > start ? 1 : -1;
    let stepTime = Math.abs(Math.floor(duration / range));
    let obj = document.getElementById(id);
    let timer = setInterval(function() {
        current += increment;
        obj.innerHTML = current;
        if (current == end) {
            clearInterval(timer);
        }
    }, stepTime);
}
</script>

<?php require_once 'frontend/includes/footer.php'; ?>