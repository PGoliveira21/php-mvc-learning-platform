<?php $pageTitle = 'Estatísticas'; ob_start(); ?>
<div class="stats-box">
    <h2>📊 Dashboard de Estatísticas</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total de Perguntas</h3>
            <div class="value"><?= $total ?></div>
        </div>
        <div class="stat-card">
            <h3>Acertos Hoje</h3>
            <div class="value ok"><?= $stats['correct_today'] ?? 0 ?></div>
        </div>
        <div class="stat-card">
            <h3>Erros Hoje</h3>
            <div class="value bad"><?= $stats['wrong_today'] ?? 0 ?></div>
        </div>
        <div class="stat-card">
            <h3>Precisão Hoje</h3>
            <div class="value"><?= $stats['accuracy_today'] ?? 0 ?>%</div>
        </div>
    </div>

    <h3 style="margin-top: 30px;">Progresso de Hoje</h3>
    <div class="progress-bar">
        <div class="progress-fill correct" style="width: <?= $stats['accuracy_today'] ?? 0 ?>%"></div>
    </div>
    <p style="text-align: center; color: #666;">
        <?= $stats['correct_today'] ?? 0 ?> acertos de <?= (($stats['correct_today'] ?? 0) + ($stats['wrong_today'] ?? 0)) ?> tentativas
    </p>

    <h3 style="margin-top: 30px;">Progresso Geral</h3>
    <div class="progress-bar">
        <div class="progress-fill correct" style="width: <?= $stats['accuracy_all_time'] ?? 0 ?>%"></div>
    </div>
    <p style="text-align: center; color: #666;">
        Precisão geral: <?= $stats['accuracy_all_time'] ?? 0 ?>% (<?= $stats['correct_all_time'] ?? 0 ?> acertos de <?= (($stats['correct_all_time'] ?? 0) + ($stats['wrong_all_time'] ?? 0)) ?> tentativas)
    </p>

    <!-- NOVO: Histórico de Erros por Categoria -->
    <?php if (!empty($errorHistory)): ?>
    <h3 style="margin-top: 40px; color: #dc3545;">⚠️ Perguntas com Mais Erros</h3>
    <p style="color: #666; margin-bottom: 15px;">Estas perguntas precisam de mais atenção</p>
    
    <?php 
    $currentCategory = '';
    foreach ($errorHistory as $item): 
        if ($currentCategory !== $item['category_name']):
            $currentCategory = $item['category_name'];
    ?>
        <h4 style="margin-top: 20px; color: #4a3aff;"><?= htmlspecialchars($currentCategory ?: 'Sem Categoria') ?></h4>
    <?php endif; ?>
    
    <div class="question-item" style="border-left: 4px solid #dc3545;">
        <div style="flex: 1;">
            <strong><?= htmlspecialchars($item['question_text']) ?></strong>
            <div style="margin-top: 5px; font-size: 13px;">
                <span style="color: #dc3545;">❌ Erros: <?= $item['error_count'] ?></span>
                <span style="color: #28a745; margin-left: 15px;">✅ Acertos: <?= $item['correct_count'] ?></span>
            </div>
        </div>
        <button class="btn-secondary" onclick="resetQuestion(<?= $item['question_id'] ?>)">Revisar</button>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p style="margin-top: 30px; color: #666; text-align: center;">Nenhum erro registrado ainda. Continue estudando!</p>
    <?php endif; ?>

    <?php if (!empty($correctQuestions)): ?>
    <h3 style="margin-top: 30px;">✅ Perguntas Acertadas (Revisar)</h3>
    <p style="color: #666; margin-bottom: 15px;">Clique em "Revisar" para voltar a praticar esta pergunta</p>
    <div class="question-list">
        <?php foreach ($correctQuestions as $q): ?>
        <div class="question-item">
            <div>
                <strong><?= htmlspecialchars($q['question_text']) ?></strong>
                <br>
                <small style="color: #666;">Resposta correta: <?= $q['correct_option'] ?></small>
            </div>
            <button class="btn-secondary" onclick="resetQuestion(<?= $q['id'] ?>)">Revisar</button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function resetQuestion(questionId) {
    if (!confirm('Deseja realmente resetar esta pergunta para revisão?')) return;

    var formData = new FormData();
    formData.append('question_id', questionId);

    fetch('/?page=api/reset-question', { method: 'POST', body: formData })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erro: ' + (data.error || 'Desconhecido'));
            }
        })
        .catch(function(err) {
            alert('Erro ao resetar pergunta');
        });
}
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>