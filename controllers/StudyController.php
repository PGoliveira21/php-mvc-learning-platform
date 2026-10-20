<?php
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/Progress.php';
require_once __DIR__ . '/../models/Category.php';

class StudyController {
    private $questionModel;
    private $progressModel;
    private $categoryModel;

    public function __construct() {
        $this->questionModel = new Question();
        $this->progressModel = new Progress();
        $this->categoryModel = new Category();
    }

    public function dashboard() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        
        // Pega a categoria selecionada da URL (se houver)
        $categoryId = isset($_GET['cat_id']) && $_GET['cat_id'] !== '' ? (int)$_GET['cat_id'] : null;

        $stats = $this->progressModel->getStats($userId);
        $total = $this->questionModel->countByUser($userId, $categoryId);
        
        // Busca todas as categorias para montar o filtro
        $categories = $this->categoryModel->getAll($userId);

        require __DIR__ . '/../views/study/dashboard.php';
    }

    /**
     * API: Retorna a próxima pergunta (JSON).
     */
    public function getNextQuestion() {
        $this->requireAuthJson();
        $userId = $_SESSION['user_id'];
        
        // Pega a categoria do POST
        $categoryId = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;
        
        $q = $this->questionModel->getNext($userId, $categoryId);

        if ($q) {
            echo json_encode(['success' => true, 'question' => $q]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhuma pergunta encontrada para esta categoria ou você já revisou todas hoje.']);
        }
    }

    /**
     * API: Submete uma resposta (JSON).
     */
    public function submitAnswer() {
        $this->requireAuthJson();
        $userId = $_SESSION['user_id'];
        $questionId = (int)($_POST['question_id'] ?? 0);
        $selected = strtoupper($_POST['selected_option'] ?? '');

        if (!in_array($selected, ['A','B','C','D'])) {
            echo json_encode(['error' => 'Opção inválida']);
            return;
        }

        $question = $this->questionModel->findByIdAndUser($questionId, $userId);
        if (!$question) {
            echo json_encode(['error' => 'Pergunta não encontrada']);
            return;
        }

        $isCorrect = ($selected === $question['correct_option']);
        $status = $isCorrect ? 'correct' : 'wrong';

        $this->progressModel->update($userId, $questionId, $status);

        echo json_encode([
            'is_correct' => $isCorrect,
            'message' => $isCorrect ? '✅ Correto!' : '❌ Errado. Essa pergunta voltará mais tarde.'
        ]);
    }

    private function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /?page=login");
            exit;
        }
    }

    private function requireAuthJson() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Não autorizado']);
            exit;
        }
    }
}