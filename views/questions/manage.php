<?php $pageTitle = 'Gerenciar Perguntas'; ob_start(); ?>
<div class="form-box">
    <h2>📋 Gerenciar Perguntas</h2>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <p class="success">✅ Pergunta atualizada com sucesso!</p>
    <?php endif; ?>

    <?php if (empty($questions)): ?>
        <p style="color: var(--gray-500); text-align: center; margin-top: 30px; font-size: 16px;">
            📭 Nenhuma pergunta cadastrada ainda.
        </p>
    <?php else: ?>
        <?php 
        // Agrupa as perguntas por categoria
        $grouped = [];
        foreach ($questions as $q) {
            $catName = $q['category_name'] ?: 'Sem Categoria';
            if (!isset($grouped[$catName])) {
                $grouped[$catName] = [];
            }
            if ($q['question_id']) {
                $grouped[$catName][] = $q;
            }
        }

        $categoryIndex = 0;
        foreach ($grouped as $catName => $items): 
        ?>
            <div class="category-accordion" style="margin-bottom: 16px;">
                <div class="category-header" onclick="toggleCategory('cat-<?= $categoryIndex ?>')">
                    <div class="category-info">
                        <span class="category-icon"></span>
                        <span class="category-name"><?= htmlspecialchars($catName) ?></span>
                        <span class="category-count"><?= count($items) ?> pergunta(s)</span>
                    </div>
                    <span class="category-arrow" id="arrow-cat-<?= $categoryIndex ?>">▼</span>
                </div>
                
                <div class="category-content" id="cat-<?= $categoryIndex ?>" style="display: none;">
                    <?php foreach ($items as $q): ?>
                    <div class="question-card">
                        <div class="question-main">
                            <div class="question-text">
                                <strong><?= htmlspecialchars($q['question_text']) ?></strong>
                                <div class="question-meta">
                                    <span class="meta-correct">✅ Resposta: <strong><?= $q['correct_option'] ?></strong></span>
                                    <?php if ($q['total_attempts'] > 0): ?>
                                        <span class="meta-stats">
                                             <?= $q['total_attempts'] ?>x 
                                            <span style="color: var(--success);">✓<?= $q['correct_count'] ?></span>
                                            <span style="color: var(--danger);">✗<?= $q['wrong_count'] ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span class="meta-new">🆕 Nunca respondida</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="question-actions">
                                <a href="/?page=edit-question&id=<?= $q['question_id'] ?>" class="btn-edit">✏️ Editar</a>
                                <button type="button" onclick="deleteQuestion(<?= $q['question_id'] ?>)" class="btn-delete">🗑️ Excluir</button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php $categoryIndex++; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    /* Accordion Styles */
    .category-accordion {
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--white);
        transition: all 0.2s ease;
    }
    
    .category-accordion:hover {
        border-color: var(--gray-300);
        box-shadow: var(--shadow-md);
    }
    
    .category-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
        user-select: none;
    }
    
    .category-header:hover {
        background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-50) 100%);
    }
    
    .category-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .category-icon {
        font-size: 20px;
    }
    
    .category-name {
        font-weight: 700;
        font-size: 16px;
        color: var(--gray-900);
    }
    
    .category-count {
        background: var(--primary);
        color: white;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .category-arrow {
        font-size: 14px;
        color: var(--gray-500);
        transition: transform 0.3s ease;
    }
    
    .category-arrow.rotated {
        transform: rotate(180deg);
    }
    
    .category-content {
        padding: 16px 24px 24px 24px;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
    }
    
    .question-card {
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }
    
    .question-card:last-child {
        margin-bottom: 0;
    }
    
    .question-card:hover {
        border-color: var(--primary-light);
        box-shadow: var(--shadow-sm);
    }
    
    .question-main {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }
    
    .question-text {
        flex: 1;
    }
    
    .question-text strong {
        font-size: 15px;
        color: var(--gray-900);
        display: block;
        margin-bottom: 8px;
    }
    
    .question-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 13px;
        color: var(--gray-600);
    }
    
    .meta-correct {
        color: var(--gray-700);
    }
    
    .meta-stats {
        font-weight: 500;
    }
    
    .meta-new {
        color: var(--gray-400);
        font-style: italic;
    }
    
    .question-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }
    
    .btn-edit, .btn-delete {
        padding: 8px 14px;
        border-radius: var(--radius);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: inherit;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
        color: white;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .btn-delete {
        background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        color: white;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    @media (max-width: 768px) {
        .question-main {
            flex-direction: column;
        }
        
        .question-actions {
            width: 100%;
            margin-top: 12px;
        }
        
        .btn-edit, .btn-delete {
            flex: 1;
            text-align: center;
        }
        
        .category-header {
            padding: 14px 16px;
        }
        
        .category-content {
            padding: 12px 16px 20px 16px;
        }
    }
</style>

<script>
function toggleCategory(id) {
    var content = document.getElementById(id);
    var arrow = document.getElementById('arrow-' + id);
    
    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'block';
        arrow.classList.add('rotated');
        // Animação suave de entrada
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px)';
        setTimeout(function() {
            content.style.transition = 'all 0.3s ease';
            content.style.opacity = '1';
            content.style.transform = 'translateY(0)';
        }, 10);
    } else {
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px)';
        setTimeout(function() {
            content.style.display = 'none';
            arrow.classList.remove('rotated');
        }, 300);
    }
}

function deleteQuestion(id) {
    if (!confirm('⚠️ Deseja realmente excluir esta pergunta?\n\nEsta ação não pode ser desfeita.')) return;

    var formData = new FormData();
    formData.append('id', id);

    fetch('/?page=api/delete-question', { method: 'POST', body: formData })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                // Remove o card visualmente com animação
                var cards = document.querySelectorAll('.question-card');
                cards.forEach(function(card) {
                    var btn = card.querySelector('.btn-delete');
                    if (btn && btn.getAttribute('onclick').includes(id)) {
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(-20px)';
                        setTimeout(function() {
                            location.reload();
                        }, 300);
                    }
                });
            } else {
                alert('❌ Erro: ' + (data.error || 'Desconhecido'));
            }
        })
        .catch(function(err) {
            alert('❌ Erro ao excluir pergunta');
        });
}

// Abre a primeira categoria automaticamente ao carregar
window.addEventListener('DOMContentLoaded', function() {
    var firstCategory = document.querySelector('.category-content');
    if (firstCategory) {
        toggleCategory(firstCategory.id);
    }
});
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>