<?php
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/Progress.php';

class StatsController {
    private $questionModel;
    private $progressModel;

    public function __construct() {
        $this->questionModel = new Question();
        $this->progressModel = new Progress();
    }

    public function dashboard() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        $stats = $this->progressModel->getStats($userId);
        $total = $this->questionModel->countByUser($userId);
        $correctQuestions = $this->progressModel->getCorrectQuestions($userId);
        $errorHistory = $this->progressModel->getErrorHistory($userId);
        require __DIR__ . '/../views/stats/dashboard.php';
    }

    public function resetQuestion() {
        $this->requireAuthJson();
        $userId = $_SESSION['user_id'];
        $questionId = (int)($_POST['question_id'] ?? 0);
        
        $this->progressModel->resetQuestion($userId, $questionId);
        echo json_encode(['success' => true, 'message' => 'Pergunta resetada para revisão']);
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