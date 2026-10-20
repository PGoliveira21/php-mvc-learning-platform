<?php
require_once __DIR__ . '/../config/database.php';

class Question {
    private $pdo;

    public function __construct() {
        $this->pdo = db();
    }

    public function create($userId, $categoryId, $text, $optA, $optB, $optC, $optD, $correct) {
        $stmt = $this->pdo->prepare("
            INSERT INTO questions (user_id, category_id, question_text, option_a, option_b, option_c, option_d, correct_option)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $categoryId, $text, $optA, $optB, $optC, $optD, strtoupper($correct)]);
    }

    public function getNext($userId, $categoryId = null) {
        $sql = "
            SELECT q.id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d,
                   COALESCE(p.status, 'new') AS status
            FROM questions q
            LEFT JOIN progress p ON q.id = p.question_id AND p.user_id = ?
            WHERE q.user_id = ?
            AND (
                p.id IS NULL 
                OR p.status != 'correct' 
                OR p.last_attempt_date != CURDATE()
            )
        ";
        
        $params = [$userId, $userId];

        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND q.category_id = ?";
            $params[] = $categoryId;
        }

        $sql .= "
            ORDER BY
                CASE
                    WHEN p.status = 'new' OR p.status IS NULL THEN 1
                    WHEN p.status = 'correct' AND p.last_attempt_date < CURDATE() THEN 2
                    WHEN p.status = 'wrong' AND p.last_attempt_date = CURDATE() THEN 3
                    ELSE 4
                END,
                RAND()
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function findByIdAndUser($id, $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM questions WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public function countByUser($userId, $categoryId = null) {
        $sql = "SELECT COUNT(*) AS total FROM questions WHERE user_id = ?";
        $params = [$userId];
        
        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }

    public function getAllByCategory($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                c.id AS category_id,
                c.name AS category_name,
                q.id AS question_id,
                q.question_text,
                q.option_a,
                q.option_b,
                q.option_c,
                q.option_d,
                q.correct_option,
                q.created_at,
                COALESCE(stats.total_attempts, 0) AS total_attempts,
                COALESCE(stats.correct_count, 0) AS correct_count,
                COALESCE(stats.wrong_count, 0) AS wrong_count
            FROM categories c
            LEFT JOIN questions q ON c.id = q.category_id AND q.user_id = ?
            LEFT JOIN (
                SELECT 
                    question_id,
                    COUNT(*) AS total_attempts,
                    SUM(CASE WHEN status = 'correct' THEN 1 ELSE 0 END) AS correct_count,
                    SUM(CASE WHEN status = 'wrong' THEN 1 ELSE 0 END) AS wrong_count
                FROM attempts
                WHERE user_id = ?
                GROUP BY question_id
            ) stats ON q.id = stats.question_id
            WHERE c.user_id = ?
            ORDER BY c.name ASC, q.created_at DESC
        ");
        $stmt->execute([$userId, $userId, $userId]);
        return $stmt->fetchAll();
    }

    public function update($id, $userId, $categoryId, $text, $optA, $optB, $optC, $optD, $correct) {
        $stmt = $this->pdo->prepare("
            UPDATE questions 
            SET category_id = ?, question_text = ?, option_a = ?, option_b = ?, 
                option_c = ?, option_d = ?, correct_option = ?
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$categoryId, $text, $optA, $optB, $optC, $optD, strtoupper($correct), $id, $userId]);
    }

    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM questions WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
}