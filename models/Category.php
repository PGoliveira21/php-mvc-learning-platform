<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $pdo;

    public function __construct() {
        $this->pdo = db();
    }

    public function create($userId, $name) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
        return $stmt->execute([$userId, $name]);
    }

    public function getAll($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE user_id = ? ORDER BY name ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function delete($userId, $id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
}