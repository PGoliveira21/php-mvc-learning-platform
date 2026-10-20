<?php
session_start();

// 1. Configuração e conexão com o banco de dados
require_once __DIR__ . '/config/database.php';

// 2. Inclusão de todos os Controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/QuestionController.php';
require_once __DIR__ . '/controllers/StudyController.php';
require_once __DIR__ . '/controllers/StatsController.php';
require_once __DIR__ . '/controllers/CategoryController.php';

// 3. Roteamento simples baseado na query string (?page=...)
$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';

// 4. Roteador (Switch)
switch ($page) {
    
    // --- Autenticação ---
    case 'login':
        $ctrl = new AuthController();
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? $ctrl->login() : $ctrl->showLogin();
        break;

    case 'register':
        $ctrl = new AuthController();
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? $ctrl->register() : $ctrl->showRegister();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    // --- Gerenciamento de Perguntas ---
    case 'add-question':
        $ctrl = new QuestionController();
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? $ctrl->store() : $ctrl->showAdd();
        break;

    case 'manage-questions':
        (new QuestionController())->manage();
        break;

    case 'edit-question':
        (new QuestionController())->edit();
        break;

    case 'update-question':
        (new QuestionController())->update();
        break;

    case 'api/delete-question':
        (new QuestionController())->delete();
        break;

    // --- Gerenciamento de Categorias ---
    case 'categories':
        (new CategoryController())->manage();
        break;

    case 'api/add-category':
        (new CategoryController())->add();
        break;

    case 'api/delete-category':
        (new CategoryController())->delete();
        break;

    // --- Sessão de Estudo (Dashboard Principal) ---
    case 'dashboard':
    case '':
        (new StudyController())->dashboard();
        break;

    // --- Estatísticas ---
    case 'stats':
        (new StatsController())->dashboard();
        break;

    // --- APIs (Retornam JSON) ---
    case 'api/next-question':
        (new StudyController())->getNextQuestion();
        break;

    case 'api/submit-answer':
        (new StudyController())->submitAnswer();
        break;

    case 'api/reset-question':
        (new StatsController())->resetQuestion();
        break;

    // --- Erro 404 (Página não encontrada) ---
    default:
        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
        echo "<p>A página '<strong>" . htmlspecialchars($page) . "</strong>' não foi encontrada no sistema.</p>";
        echo "<br><a href='/?page=dashboard' style='color: blue; text-decoration: underline;'>Voltar ao Dashboard</a>";
        break;
}