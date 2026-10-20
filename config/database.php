<?php
/**
 * Configuração do banco de dados com auto-instalação das tabelas.
 */

define('DB_HOST', 'sql207.infinityfree.com');
define('DB_PORT', '3306');
define('DB_NAME', 'if0_43217423_perguntas');
define('DB_USER', 'if0_43217423');
define('DB_PASS', 'wfDl0F2VlV');


class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            // Conecta DIRETAMENTE ao banco (sem SHOW DATABASES)
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Cria as tabelas automaticamente se não existirem
            $this->installTables();

        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo() {
        return $this->pdo;
    }

    /**
     * Cria as tabelas automaticamente se não existirem.
     * Usa CREATE TABLE IF NOT EXISTS - seguro e idempotente.
     */
    private function installTables() {
        // Tabela de categorias
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_user_category (user_id, name),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // Tabela de usuários
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // Tabela de perguntas
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS questions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                category_id INT DEFAULT NULL,
                question_text TEXT NOT NULL,
                option_a VARCHAR(255) NOT NULL,
                option_b VARCHAR(255) NOT NULL,
                option_c VARCHAR(255) NOT NULL,
                option_d VARCHAR(255) NOT NULL,
                correct_option CHAR(1) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // Adiciona a coluna category_id se ela não existir (para quem já tem o sistema rodando)
        try {
            $this->pdo->exec("ALTER TABLE questions ADD COLUMN category_id INT DEFAULT NULL AFTER user_id");
        } catch (PDOException $e) {
            // Coluna já existe, ignora o erro
        }

        // Tabela de progresso (status atual de cada pergunta)
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS progress (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                question_id INT NOT NULL,
                status ENUM('new', 'correct', 'wrong') DEFAULT 'new',
                last_attempt_date DATE NOT NULL,
                UNIQUE KEY unique_user_question (user_id, question_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // NOVA TABELA: registra TODAS as tentativas individuais
        // Permite contar erros mesmo que a pergunta tenha sido acertada depois
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                question_id INT NOT NULL,
                status ENUM('correct', 'wrong') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}

/**
 * Helper para obter o PDO rapidamente de qualquer lugar do sistema.
 */
function db() {
    return Database::getInstance()->getPdo();
}