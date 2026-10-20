<?php $pageTitle = 'Nova Pergunta'; ob_start(); ?>
<div class="form-box">
    <h2>Adicionar Nova Pergunta</h2>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso'): ?>
        <p class="success">Pergunta salva com sucesso!</p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (empty($categories)): ?>
        <p class="error">Você precisa <a href="/?page=categories">criar uma categoria</a> antes de adicionar perguntas.</p>
    <?php else: ?>
    <form method="POST" action="/?page=add-question">
        <label>Categoria:</label>
        <select name="category_id" required>
            <option value="">Selecione uma categoria...</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Pergunta:</label>
        <textarea name="question_text" required></textarea>

        <label>Opção A:</label>
        <input type="text" name="option_a" required>

        <label>Opção B:</label>
        <input type="text" name="option_b" required>

        <label>Opção C:</label>
        <input type="text" name="option_c" required>

        <label>Opção D:</label>
        <input type="text" name="option_d" required>

        <label>Resposta Correta:</label>
        <select name="correct_option" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>

        <button type="submit">Salvar Pergunta</button>
    </form>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>