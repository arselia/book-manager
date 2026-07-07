<?php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../includes/header.php';

$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$all_categories = $cat_stmt->fetchAll();
?>

<div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 flex flex-col h-full">
    
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-bold text-gray-800">Daftar Buku</h3>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Buku
        </a>
    </div>

    <form id="filter-form" class="bg-slate-50 p-4 rounded-xl border border-blue-100 mb-6 flex flex-wrap gap-4 items-end" onsubmit="event.preventDefault();">
        <input type="hidden" name="page" id="page-input" value="1">

        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-600 mb-1">Cari (Judul/Penulis/Penerbit)</label>
            <input type="text" name="search" placeholder="Kata kunci..." 
                   class="w-full px-3 py-2 h-[42px] border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none text-sm bg-white">
        </div>
        
        <div class="w-48">
            <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
            <select name="category_id" class="w-full px-3 py-2 h-[42px] border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none text-sm bg-white cursor-pointer">
                <option value="">Semua Kategori</option>
                <?php foreach ($all_categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="w-48">
            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Terbit</label>
            <input type="date" name="pub_date" 
                   class="w-full px-3 py-2 h-[42px] border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none text-sm bg-white cursor-pointer">
        </div>

        <div class="w-48">
            <label class="block text-xs font-medium text-gray-600 mb-1">Urutkan</label>
            <select name="sort" class="w-full px-3 py-2 h-[42px] border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none text-sm bg-white cursor-pointer">
                <option value="id_desc">Terbaru (ID)</option>
                <option value="title_asc">Judul (A-Z)</option>
                <option value="title_desc">Judul (Z-A)</option>
                <option value="date_desc">Terbit Teratas</option>
                <option value="date_asc">Terbit Terlama</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="button" onclick="resetFilters()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 h-[42px] rounded-lg text-sm font-medium transition-colors flex items-center">Reset</button>
        </div>
    </form>

    <div class="overflow-y-auto border-t border-l border-r border-blue-100 rounded-t-lg flex-1 relative">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm border-b border-blue-100">
                <tr>
                    <th class="p-4 text-sm font-semibold text-gray-600">ID</th>
                    <th class="p-4 text-sm font-semibold text-gray-600 text-center">Action</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Judul</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Penulis</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Tgl Terbit</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Penerbit</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Halaman</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Kategori</th>
                </tr>
            </thead>
            <tbody id="books-table-body" class="divide-y divide-blue-50 transition-opacity duration-200">
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500 text-sm">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div id="pagination-container" class="px-4 py-3 border border-blue-100 border-t-0 bg-slate-50 rounded-b-lg flex items-center justify-between">
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    const tableBody = document.getElementById('books-table-body');
    const paginationContainer = document.getElementById('pagination-container');
    const pageInput = document.getElementById('page-input');
    let typingTimer;

    const fetchBooks = async () => {
        tableBody.style.opacity = '0.5';

        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        try {
            const response = await fetch('/backend/api/books/get_books.php?' + params.toString());
            const data = await response.json();
            
            renderTable(data.books);
            renderPagination(data.pagination);
            
            tableBody.style.opacity = '1';
        } catch (error) {
            console.error('Error fetching data:', error);
            tableBody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-red-500 text-sm">Gagal memuat data.</td></tr>`;
            tableBody.style.opacity = '1';
        }
    };

    const renderTable = (books) => {
        if (books.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-gray-500 text-sm">Tidak ada buku yang ditemukan.</td></tr>`;
            return;
        }

        tableBody.innerHTML = books.map(book => {
            const shortId = book.id.substring(0, 8);
            
            return `
            <tr class="hover:bg-blue-50/50 transition-colors">
                <td class="p-4 text-xs text-slate-400 font-mono tracking-wider" title="${book.id}">${shortId}</td>
                
                <td class="p-4 text-sm text-center space-x-2">
                    <a href="edit.php?id=${book.id}" class="text-blue-500 hover:text-blue-700 font-medium text-xs">Edit</a>
                    
                    <button onclick="deleteBook('${book.id}')" class="text-red-500 hover:text-red-700 font-medium text-xs cursor-pointer">Delete</button>
                </td>
                
                <td class="p-4 text-sm text-gray-800 font-medium">
                    ${book.title.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")}
                </td>
                <td class="p-4 text-sm text-gray-700">${book.author}</td>
                <td class="p-4 text-sm text-gray-700">${book.publication_date}</td>
                <td class="p-4 text-sm text-gray-700">${book.publisher}</td>
                <td class="p-4 text-sm text-gray-700 text-center">${book.num_pages}</td>
                <td class="p-4 text-sm text-gray-700">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">${book.category_name || 'Uncategorized'}</span>
                </td>
            </tr>
            `;
        }).join('');
    };

    const renderPagination = (pag) => {
        if (pag.total_pages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let html = `
            <span class="text-sm text-gray-600">
                Menampilkan ${(pag.current_page - 1) * pag.limit + 1} - ${Math.min(pag.current_page * pag.limit, pag.total_records)} dari ${pag.total_records} buku
            </span>
            <div class="flex items-center space-x-1">
        `;

        if (pag.current_page > 1) {
            html += `<button type="button" onclick="changePage(${pag.current_page - 1})" class="px-3 py-1 border border-blue-200 text-blue-600 bg-white rounded-md hover:bg-blue-50 transition-colors text-sm font-medium">&laquo; Prev</button>`;
        }

        for (let i = 1; i <= pag.total_pages; i++) {
            const activeClass = i === pag.current_page ? 'bg-blue-600 text-white border-blue-600' : 'border-blue-200 text-blue-600 bg-white hover:bg-blue-50';
            html += `<button type="button" onclick="changePage(${i})" class="px-3 py-1 border ${activeClass} rounded-md transition-colors text-sm font-medium">${i}</button>`;
        }

        if (pag.current_page < pag.total_pages) {
            html += `<button type="button" onclick="changePage(${pag.current_page + 1})" class="px-3 py-1 border border-blue-200 text-blue-600 bg-white rounded-md hover:bg-blue-50 transition-colors text-sm font-medium">Next &raquo;</button>`;
        }

        html += `</div>`;
        paginationContainer.innerHTML = html;
    };

    window.changePage = (newPage) => {
        pageInput.value = newPage;
        fetchBooks();
    };

    window.resetFilters = () => {
        form.reset();
        pageInput.value = 1;
        fetchBooks();
    };

    window.deleteBook = async (id) => {
        if (!confirm('Yakin ingin menghapus buku ini?')) return;
        
        try {
            const response = await fetch('/backend/api/books/delete_book.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            
            const result = await response.json();
            if (result.success) {
                fetchBooks();
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan saat menghapus buku.');
        }
    };

    form.addEventListener('input', function(e) {
        if (e.target.name === 'search') {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                pageInput.value = 1;
                fetchBooks();
            }, 300);
        }
    });

    form.addEventListener('change', function(e) {
        if (e.target.tagName === 'SELECT' || e.target.type === 'date') {
            pageInput.value = 1;
            fetchBooks();
        }
    });

    fetchBooks();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>