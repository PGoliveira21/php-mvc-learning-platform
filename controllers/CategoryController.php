<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
    }

    public function manage() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        $categories = $this->categoryModel->getAll($userId);
        require __DIR__ . '/../views/categories/manage.php';
    }

    public function add() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        $name = trim($_POST['name'] ?? '');

        if ($name) {
            $this->categoryModel->create($userId, $name);
        }
        
        header("Location: /?page=categories");
        exit;
    }

    public function delete() {
        $this->requireAuthJson();
        $userId = $_SESSION['user_id'];
        $id = (int)($_POST['id'] ?? 0);

        $this->categoryModel->delete($userId, $id);
        echo json_encode(['success' => true]);
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