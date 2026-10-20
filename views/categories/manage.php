<?php $pageTitle = 'Gerenciar Categorias'; ob_start(); ?>
<div class="form-box">
    <h2>Gerenciar Categorias</h2>
    
    <form method="POST" action="/?page=api/add-category" style="display: flex; gap: 10px; margin-bottom: 20px;">
        <input type="text" name="name" placeholder="Nome da categoria (ex: Matemática, História)" required style="flex: 1;">
        <button type="submit" style="width: auto; margin: 0;">Adicionar</button>
    </form>

    <h3>Suas Categorias</h3>
    <div id="category-list">
        <?php if (empty($categories)): ?>
            <p style="color: #666;">Nenhuma categoria cadastrada.</p>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
            <div class="question-item" style="margin-top: 10px;">
                <strong><?= htmlspecialchars($cat['name']) ?></strong>
                <button class="btn-secondary" onclick="deleteCategory(<?= $cat['id'] ?>)" style="width: auto; background: #dc3545;">Excluir</button>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteCategory(id) {
    if (!confirm('Deseja excluir esta categoria? As perguntas dela não serão excluídas, mas ficarão sem categoria.')) return;

    var formData = new FormData();
    formData.append('id', id);

    fetch('/?page=api/delete-category', { method: 'POST', body: formData })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) location.reload();
        });
}
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>