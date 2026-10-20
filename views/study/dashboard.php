<?php $pageTitle = 'Sessão de Estudo'; ob_start(); ?>
<div class="study-box">
    <h2>Sessão de Estudo</h2>
    
    <!-- Filtro de Categoria -->
    <form method="GET" action="/?page=dashboard" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="hidden" name="page" value="dashboard">
        <select name="cat_id" onchange="this.form.submit()" style="flex: 1;">
            <option value="">Todas as categorias</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['cat_id']) && $_GET['cat_id'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <div class="stats" id="stats-container">
        <span>Total de perguntas (filtro): <strong><?= $total ?></strong></span>
        <span>Acertos hoje: <strong class="ok" id="stat-correct"><?= $stats['correct_today'] ?? 0 ?></strong></span>
        <span>Erros hoje: <strong class="bad" id="stat-wrong"><?= $stats['wrong_today'] ?? 0 ?></strong></span>
    </div>

    <div id="question-container">
        <h3 id="q-text">Carregando...</h3>
        <div id="options"></div>
        <div id="feedback" class="feedback hidden"></div>
        <button id="next-btn" class="hidden" onclick="loadQuestion()">Próxima Pergunta →</button>
    </div>
</div>

<script>
// Pega a categoria selecionada no filtro
var currentCategoryId = <?= isset($_GET['cat_id']) && $_GET['cat_id'] !== '' ? (int)$_GET['cat_id'] : 'null' ?>;
var correctCount = <?= $stats['correct_today'] ?? 0 ?>;
var wrongCount = <?= $stats['wrong_today'] ?? 0 ?>;

function loadQuestion() {
    document.getElementById('feedback').classList.add('hidden');
    document.getElementById('next-btn').classList.add('hidden');
    document.getElementById('options').innerHTML = '<p>Carregando...</p>';

    var formData = new FormData();
    if (currentCategoryId) {
        formData.append('category_id', currentCategoryId);
    }

    fetch('/?page=api/next-question', { method: 'POST', body: formData })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data.success) {
                document.getElementById('q-text').innerText = data.message;
                document.getElementById('options').innerHTML = '';
                document.getElementById('next-btn').classList.add('hidden');
                return;
            }

            var q = data.question;
            document.getElementById('q-text').innerText = q.question_text;

            var optionsDiv = document.getElementById('options');
            optionsDiv.innerHTML = '';

            ['A', 'B', 'C', 'D'].forEach(function(opt) {
                var btn = document.createElement('button');
                btn.className = 'option-btn';
                btn.innerText = opt + ') ' + q['option_' + opt.toLowerCase()];
                btn.onclick = (function(selectedOpt) {
                    return function() { submitAnswer(q.id, selectedOpt); };
                })(opt);
                optionsDiv.appendChild(btn);
            });
        })
        .catch(function(err) {
            document.getElementById('options').innerHTML = '<p class="error">Erro ao carregar pergunta.</p>';
        });
}

function submitAnswer(questionId, selectedOption) {
    var formData = new FormData();
    formData.append('question_id', questionId);
    formData.append('selected_option', selectedOption);
    if (currentCategoryId) {
        formData.append('category_id', currentCategoryId);
    }

    fetch('/?page=api/submit-answer', { method: 'POST', body: formData })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            var feedback = document.getElementById('feedback');
            feedback.classList.remove('hidden', 'correct', 'wrong');

            if (data.is_correct) {
                feedback.innerText = data.message;
                feedback.classList.add('correct');
                correctCount++;
                document.getElementById('stat-correct').innerText = correctCount;
            } else {
                feedback.innerText = data.message;
                feedback.classList.add('wrong');
                wrongCount++;
                document.getElementById('stat-wrong').innerText = wrongCount;
            }

            var btns = document.querySelectorAll('.option-btn');
            for (var i = 0; i < btns.length; i++) {
                btns[i].disabled = true;
            }

            document.getElementById('next-btn').classList.remove('hidden');
        });
}

loadQuestion();
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>