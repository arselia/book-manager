<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 flex flex-col h-full">
    
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Daftar Kategori</h3>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kategori
        </a>
    </div>

    <form id="filter-form" class="bg-slate-50 p-4 rounded-xl border border-blue-100 mb-6" onsubmit="event.preventDefault();">
        <input type="hidden" name="page" id="page-input" value="1">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" placeholder="Cari nama kategori..." 
                   class="w-full pl-10 pr-4 py-2 h-[42px] border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none text-sm bg-white shadow-sm transition-shadow">
        </div>
    </form>

    <div class="flex-1 flex flex-col min-h-0 relative">
        <div class="overflow-y-auto border-t border-l border-r border-blue-100 rounded-t-lg flex-1 bg-white">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm border-b border-blue-100">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-gray-600 w-24">ID</th>
                        <th class="p-4 text-sm font-semibold text-gray-600 text-center w-40">Action</th>
                        <th class="p-4 text-sm font-semibold text-gray-600">Kategori</th>
                        <th class="p-4 text-sm font-semibold text-gray-600 text-center">Jumlah Buku</th>
                        <th class="p-4 text-sm font-semibold text-gray-600">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody id="categories-table-body" class="divide-y divide-blue-50 transition-opacity duration-200">
                    <tr><td colspan="5" class="p-8 text-center text-gray-500">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="px-5 py-3 border border-blue-100 border-t-0 bg-slate-50 rounded-b-lg flex items-center justify-between">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    const tableBody = document.getElementById('categories-table-body');
    const paginationContainer = document.getElementById('pagination-container');
    const pageInput = document.getElementById('page-input');
    let typingTimer;

    const fetchCategories = async () => {
    renderTable(null); 
    
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);

    try {
        const response = await fetch('/backend/api/category/get_categories.php?' + params.toString());
        const data = await response.json();
        renderTable(data.categories); 
        renderPagination(data.pagination);
    } catch (error) {
        tableBody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-red-500">Gagal memuat data.</td></tr>`;
    }
};

const renderTable = (categories) => {
    if (!categories) {
        tableBody.innerHTML = `
            ${[...Array(5)].map(() => `
                <tr class="animate-pulse">
                    <td class="p-4"><div class="h-4 bg-gray-200 rounded w-20"></div></td>
                    <td class="p-4"><div class="h-4 bg-gray-200 rounded w-16"></div></td>
                    <td class="p-4"><div class="h-4 bg-gray-200 rounded w-48"></div></td>
                    <td class="p-4"><div class="h-4 bg-gray-200 rounded w-24"></div></td>
                    <td class="p-4"><div class="h-4 bg-gray-200 rounded w-32"></div></td>
                </tr>
            `).join('')}
        `;
        return;
    }

    if (categories.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-gray-500">Tidak ada kategori yang ditemukan.</td></tr>`;
        return;
    }

    tableBody.innerHTML = categories.map(cat => {
        const shortId = cat.id.substring(0, 8);
        return `
            <tr class="hover:bg-blue-50/50 transition-colors">
                <td class="p-4 text-xs text-slate-400 font-mono tracking-wider">${shortId}</td>
                <td class="p-4 text-sm text-center space-x-3">
                    <a href="edit.php?id=${cat.id}" class="text-blue-500 hover:text-blue-700 font-medium text-xs">Edit</a>
                    <button onclick="deleteCategory('${cat.id}')" class="text-red-500 hover:text-red-700 font-medium text-xs cursor-pointer">Delete</button>
                </td>
                <td class="p-4 text-sm text-gray-800 font-medium">${cat.name}</td>
                <td class="p-4 text-sm text-center">
                    <span class="bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-1 rounded-md text-xs font-medium">${cat.book_count} Buku</span>
                </td>
                <td class="p-4 text-sm text-gray-700">${cat.formatted_date}</td>
            </tr>
        `;
    }).join('');
};

    const renderPagination = (pag) => {
        if (pag.total_pages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let html = `<span class="text-sm text-gray-500 font-medium">Menampilkan ${(pag.current_page - 1) * pag.limit + 1} - ${Math.min(pag.current_page * pag.limit, pag.total_records)} dari ${pag.total_records} kategori</span><div class="flex items-center space-x-1">`;
        if (pag.current_page > 1) {
            html += `<button type="button" onclick="changePage(${pag.current_page - 1})" class="px-3 py-1.5 border border-blue-200 text-blue-600 bg-white rounded-md hover:bg-blue-50 text-sm font-medium">&laquo;</button>`;
        }
        for (let i = 1; i <= pag.total_pages; i++) {
            const activeClass = i === pag.current_page ? 'bg-blue-600 text-white border-blue-600' : 'border-blue-200 text-blue-600 bg-white hover:bg-blue-50';
            html += `<button type="button" onclick="changePage(${i})" class="px-3 py-1.5 border ${activeClass} rounded-md text-sm font-medium">${i}</button>`;
        }
        if (pag.current_page < pag.total_pages) {
            html += `<button type="button" onclick="changePage(${pag.current_page + 1})" class="px-3 py-1.5 border border-blue-200 text-blue-600 bg-white rounded-md hover:bg-blue-50 text-sm font-medium">&raquo;</button>`;
        }
        html += `</div>`;
        paginationContainer.innerHTML = html;
    };

    window.changePage = (newPage) => {
        pageInput.value = newPage;
        fetchCategories();
    };

    window.deleteCategory = async (id) => {
        if (!confirm('Yakin ingin menghapus kategori ini?')) return;
        try {
            const response = await fetch('/backend/api/category/delete_category.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            const result = await response.json();
            if (result.success) {
                fetchCategories();
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan saat menghapus kategori.');
        }
    };

    form.addEventListener('input', function(e) {
        if (e.target.name === 'search') {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                pageInput.value = 1;
                fetchCategories();
            }, 300);
        }
    });

    fetchCategories();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>