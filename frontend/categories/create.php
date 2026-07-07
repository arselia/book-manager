<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="max-w-xl mx-auto w-full py-4">
    <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-8">
        <div class="flex items-center mb-8 pb-6 border-b border-gray-100">
            <div class="bg-blue-50 p-3 rounded-lg mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Tambah Kategori Baru</h3>
                <p class="text-sm text-gray-500 mt-1">Masukkan nama kategori untuk pengelompokan buku.</p>
            </div>
        </div>
        
        <div id="error-message" class="hidden mb-6 bg-red-50 text-red-600 border border-red-200 p-4 rounded-lg text-sm font-medium"></div>

        <form id="create-category-form" class="space-y-6">
            <div>
                <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Kategori</label>
                <input type="text" name="name" id="name" required placeholder="Contoh: Fiksi Sains..." 
                       class="w-full px-4 py-2.5 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition-all text-sm bg-slate-50 focus:bg-white">
            </div>
            
            <div class="flex items-center space-x-3 pt-6 mt-2 border-t border-gray-100">
                <button type="submit" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">
                    Simpan Kategori
                </button>
                <a href="index.php" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('create-category-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const errorBox = document.getElementById('error-message');
    
    submitBtn.innerHTML = 'Menyimpan...';
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-70');
    errorBox.classList.add('hidden');

    const data = { name: document.getElementById('name').value };

    try {
        const response = await fetch('/backend/api/category/create_category.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = 'index.php';
        } else {
            errorBox.textContent = result.message;
            errorBox.classList.remove('hidden');
        }
    } catch (error) {
        errorBox.textContent = 'Terjadi kesalahan pada server.';
        errorBox.classList.remove('hidden');
    } finally {
        submitBtn.innerHTML = 'Simpan Kategori';
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-70');
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>