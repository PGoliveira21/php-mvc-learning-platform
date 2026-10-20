function loadQuestion() {
    document.getElementById('feedback').classList.add('hidden');
    document.getElementById('next-btn').classList.add('hidden');
    document.getElementById('options').innerHTML = '<p>Carregando...</p>';

    fetch('/?page=api/next-question')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data.success) {
                document.getElementById('q-text').innerText = data.message;
                document.getElementById('options').innerHTML = '';
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

    fetch('/?page=api/submit-answer', { method: 'POST', body: formData })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            var feedback = document.getElementById('feedback');
            feedback.classList.remove('hidden', 'correct', 'wrong');

            if (data.is_correct) {
                feedback.innerText = data.message;
                feedback.classList.add('correct');
            } else {
                feedback.innerText = data.message;
                feedback.classList.add('wrong');
            }

            var btns = document.querySelectorAll('.option-btn');
            for (var i = 0; i < btns.length; i++) {
                btns[i].disabled = true;
            }

            document.getElementById('next-btn').classList.remove('hidden');
        });
}

if (document.getElementById('question-container')) {
    loadQuestion();
}