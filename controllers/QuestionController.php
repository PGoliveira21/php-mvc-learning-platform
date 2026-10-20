<?php
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/Category.php';

class QuestionController {
    private $questionModel;
    private $categoryModel;

    public function __construct() {
        $this->questionModel = new Question();
        $this->categoryModel = new Category();
    }

    public function showAdd() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        $categories = $this->categoryModel->getAll($userId);
        require __DIR__ . '/../views/questions/add.php';
    }

    public function store() {
        $this->requireAuth();

        $userId = $_SESSION['user_id'];
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $text   = trim($_POST['question_text'] ?? '');
        $optA   = trim($_POST['option_a'] ?? '');
        $optB   = trim($_POST['option_b'] ?? '');
        $optC   = trim($_POST['option_c'] ?? '');
        $optD   = trim($_POST['option_d'] ?? '');
        $correct = strtoupper($_POST['correct_option'] ?? '');

        if (!$categoryId || !$text || !$optA || !$optB || !$optC || !$optD || !in_array($correct, ['A','B','C','D'])) {
            $error = "Preencha todos os campos e selecione uma categoria.";
            $categories = $this->categoryModel->getAll($userId);
            require __DIR__ . '/../views/questions/add.php';
            return;
        }

        $this->questionModel->create($userId, $categoryId, $text, $optA, $optB, $optC, $optD, $correct);
        header("Location: /?page=add-question&msg=sucesso");
        exit;
    }

    // NOVO: Listar perguntas para gerenciamento
    public function manage() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        $questions = $this->questionModel->getAllByCategory($userId);
        require __DIR__ . '/../views/questions/manage.php';
    }

    // NOVO: Mostrar formulário de edição
    public function edit() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        $id = (int)($_GET['id'] ?? 0);
        
        $question = $this->questionModel->findByIdAndUser($id, $userId);
        if (!$question) {
            header("Location: /?page=manage-questions&error=not_found");
            exit;
        }
        
        $categories = $this->categoryModel->getAll($userId);
        require __DIR__ . '/../views/questions/edit.php';
    }

    // NOVO: Atualizar pergunta
    public function update() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        
        $id = (int)($_POST['id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $text   = trim($_POST['question_text'] ?? '');
        $optA   = trim($_POST['option_a'] ?? '');
        $optB   = trim($_POST['option_b'] ?? '');
        $optC   = trim($_POST['option_c'] ?? '');
        $optD   = trim($_POST['option_d'] ?? '');
        $correct = strtoupper($_POST['correct_option'] ?? '');

        if (!$id || !$categoryId || !$text || !$optA || !$optB || !$optC || !$optD || !in_array($correct, ['A','B','C','D'])) {
            $error = "Preencha todos os campos corretamente.";
            $question = $this->questionModel->findByIdAndUser($id, $userId);
            $categories = $this->categoryModel->getAll($userId);
            require __DIR__ . '/../views/questions/edit.php';
            return;
        }

        $this->questionModel->update($id, $userId, $categoryId, $text, $optA, $optB, $optC, $optD, $correct);
        header("Location: /?page=manage-questions&msg=updated");
        exit;
    }

    // NOVO: Deletar pergunta (API)
    public function delete() {
        $this->requireAuthJson();
        $userId = $_SESSION['user_id'];
        $id = (int)($_POST['id'] ?? 0);

        $this->questionModel->delete($id, $userId);
        echo json_encode(['success' => true, 'message' => 'Pergunta excluída com sucesso']);
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