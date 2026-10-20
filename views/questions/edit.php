<?php $pageTitle = 'Editar Pergunta'; ob_start(); ?>
<div class="form-box">
    <h2>✏️ Editar Pergunta</h2>
    
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="/?page=update-question">
        <input type="hidden" name="id" value="<?= $question['id'] ?>">
        
        <label>Categoria:</label>
        <select name="category_id" required>
            <option value="">Selecione uma categoria...</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $question['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Pergunta:</label>
        <textarea name="question_text" required><?= htmlspecialchars($question['question_text']) ?></textarea>

        <label>Opção A:</label>
        <input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>" required>

        <label>Opção B:</label>
        <input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>" required>

        <label>Opção C:</label>
        <input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>" required>

        <label>Opção D:</label>
        <input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>" required>

        <label>Resposta Correta:</label>
        <select name="correct_option" required>
            <option value="A" <?= $question['correct_option'] === 'A' ? 'selected' : '' ?>>A</option>
            <option value="B" <?= $question['correct_option'] === 'B' ? 'selected' : '' ?>>B</option>
            <option value="C" <?= $question['correct_option'] === 'C' ? 'selected' : '' ?>>C</option>
            <option value="D" <?= $question['correct_option'] === 'D' ? 'selected' : '' ?>>D</option>
        </select>

        <button type="submit">Salvar Alterações</button>
        <a href="/?page=manage-questions" style="display: block; text-align: center; margin-top: 10px; color: #666;">Cancelar</a>
    </form>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>