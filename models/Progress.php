<?php
require_once __DIR__ . '/../config/database.php';

class Progress {
    private $pdo;

    public function __construct() {
        $this->pdo = db();
    }

    public function update($userId, $questionId, $status) {
        $today = date('Y-m-d');
        
        // Atualiza o status atual na tabela progress
        $stmt = $this->pdo->prepare("
            INSERT INTO progress (user_id, question_id, status, last_attempt_date)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                status = VALUES(status),
                last_attempt_date = VALUES(last_attempt_date)
        ");
        $stmt->execute([$userId, $questionId, $status, $today]);

        // NOVO: Registra a tentativa na tabela attempts
        $stmt = $this->pdo->prepare("
            INSERT INTO attempts (user_id, question_id, status)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$userId, $questionId, $status]);

        return true;
    }

    public function getStats($userId) {
        // Conta TODAS as tentativas do dia (não apenas o último status)
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(*) AS total_attempts_today,
                SUM(CASE WHEN status = 'correct' AND DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS correct_today,
                SUM(CASE WHEN status = 'wrong' AND DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS wrong_today,
                SUM(CASE WHEN status = 'correct' THEN 1 ELSE 0 END) AS correct_all_time,
                SUM(CASE WHEN status = 'wrong' THEN 1 ELSE 0 END) AS wrong_all_time,
                COUNT(*) AS total_attempts_all_time
            FROM attempts
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $stats = $stmt->fetch();
        
        $total = $stats['correct_today'] + $stats['wrong_today'];
        $stats['accuracy_today'] = $total > 0 ? round(($stats['correct_today'] / $total) * 100, 1) : 0;
        
        $total_all = $stats['correct_all_time'] + $stats['wrong_all_time'];
        $stats['accuracy_all_time'] = $total_all > 0 ? round(($stats['correct_all_time'] / $total_all) * 100, 1) : 0;
        
        return $stats;
    }

    public function getCorrectQuestions($userId) {
        $stmt = $this->pdo->prepare("
            SELECT q.id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option
            FROM questions q
            INNER JOIN progress p ON q.id = p.question_id
            WHERE q.user_id = ? AND p.status = 'correct'
            ORDER BY p.last_attempt_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function resetQuestion($userId, $questionId) {
        $stmt = $this->pdo->prepare("
            UPDATE progress 
            SET status = 'new', last_attempt_date = '2000-01-01'
            WHERE user_id = ? AND question_id = ?
        ");
        return $stmt->execute([$userId, $questionId]);
    }

    public function getErrorHistory($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                COALESCE(c.name, 'Sem Categoria') AS category_name,
                q.id AS question_id,
                q.question_text,
                COUNT(CASE WHEN a.status = 'wrong' THEN 1 END) AS error_count,
                COUNT(CASE WHEN a.status = 'correct' THEN 1 END) AS correct_count
            FROM questions q
            INNER JOIN attempts a ON q.id = a.question_id
            LEFT JOIN categories c ON q.category_id = c.id
            WHERE q.user_id = ?
            GROUP BY q.id, c.name, q.question_text
            HAVING error_count > 0
            ORDER BY c.name ASC, error_count DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}