<?php
require_once __DIR__ . '/../../backend/config/database.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

if (!$book) { header('Location: index.php'); exit; }

$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-3xl mx-auto w-full py-4">
    <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-8">
        
        <div class="flex items-center mb-8 pb-6 border-b border-gray-100">
            <div class="bg-blue-50 p-3 rounded-lg mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Edit Buku</h3>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi buku di bawah ini.</p>
            </div>
        </div>
        
        <div id="error-message" class="hidden mb-6 bg-red-50 text-red-600 border border-red-200 p-4 rounded-lg text-sm font-medium"></div>

        <form id="edit-book-form" class="grid grid-cols-2 gap-6">
            <input type="hidden" name="id" value="<?= htmlspecialchars($book['id']) ?>">

            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Judul Buku</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($book['title']) ?>" 
                       class="w-full px-4 py-2.5 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition-all text-sm bg-slate-50 focus:bg-white">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Penulis</label>
                <input type="text" name="author" required value="<?= htmlspecialchars($book['author']) ?>" 
                       class="w-full px-4 py-2.5 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition-all text-sm bg-slate-50 focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Penerbit</label>
                <input type="text" name="publisher" required value="<?= htmlspecialchars($book['publisher']) ?>" 
                       class="w-full px-4 py-2.5 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition-all text-sm bg-slate-50 focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tanggal Terbit</label>
                <input type="date" name="publication_date" required value="<?= htmlspecialchars($book['publication_date']) ?>" 
                       class="w-full px-4 py-2.5 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition-all text-sm bg-slate-50 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jumlah Halaman</label>
                <input type="number" name="num_pages" required min="1" value="<?= htmlspecialchars($book['num_pages']) ?>" 
                       class="w-full px-4 py-2.5 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition-all text-sm bg-slate-50 focus:bg-white">
            </div>

            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kategori</label>
                <select name="category_id" required 
                        class="w-full px-4 py-2.5 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition-all text-sm bg-slate-50 cursor-pointer">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $book['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-span-2 flex items-center space-x-3 pt-6 mt-2 border-t border-gray-100">
                <button type="submit" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">
                    Update Buku
                </button>
                <a href="index.php" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('edit-book-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const errorBox = document.getElementById('error-message');
    
    submitBtn.innerHTML = 'Memperbarui...';
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-70');
    errorBox.classList.add('hidden');

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch('/backend/api/books/update_book.php', {
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
        submitBtn.innerHTML = 'Update Buku';
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-70');
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>