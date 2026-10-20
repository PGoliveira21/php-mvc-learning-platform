<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $pageTitle ?? 'Sistema de Estudo' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }
        
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #ec4899;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --gray-900: #0f172a;
            --gray-800: #1e293b;
            --gray-700: #334155;
            --gray-600: #475569;
            --gray-500: #64748b;
            --gray-400: #94a3b8;
            --gray-300: #cbd5e1;
            --gray-200: #e2e8f0;
            --gray-100: #f1f5f9;
            --gray-50: #f8fafc;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --radius: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.5rem;
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            color: var(--gray-800);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-tap-highlight-color: transparent;
        }

        /* ===== NAVBAR DESKTOP ===== */
        .desktop-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 0 24px;
            display: flex;
            gap: 8px;
            align-items: center;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .desktop-nav a { 
            color: var(--gray-700); 
            text-decoration: none; 
            font-weight: 500;
            font-size: 14px;
            padding: 16px 16px;
            transition: all 0.2s ease;
            border-bottom: 2px solid transparent;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .desktop-nav a:hover { 
            color: var(--primary);
            background: var(--gray-50);
            border-bottom-color: var(--primary);
        }
        
        .desktop-nav a.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        
        .user-info { 
            color: var(--gray-600); 
            margin-left: auto;
            font-weight: 500;
            font-size: 14px;
        }
        
        .btn-logout-desktop {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            padding: 8px 16px !important;
            border-radius: var(--radius);
            color: white !important;
            font-weight: 600;
            border: none;
            margin-left: 12px;
            box-shadow: var(--shadow-sm);
        }
        
        .btn-logout-desktop:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* ===== MOBILE TOP BAR ===== */
        .mobile-topbar {
            display: none;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 12px 20px;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--gray-200);
            justify-content: space-between;
            align-items: center;
        }
        
        .mobile-topbar-title {
            font-weight: 700;
            font-size: 16px;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .mobile-topbar-logout {
            background: var(--danger-light);
            color: var(--danger);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #fecaca;
        }

        /* ===== BOTTOM NAV MOBILE ===== */
        .mobile-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.08);
            border-top: 1px solid var(--gray-200);
            z-index: 1000;
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
        
        .mobile-nav-inner {
            display: flex;
            justify-content: space-around;
            align-items: stretch;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .mobile-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--gray-500);
            padding: 8px 4px 10px 4px;
            transition: all 0.2s ease;
            position: relative;
            min-height: 56px;
        }
        
        .mobile-nav-item .nav-icon {
            font-size: 22px;
            line-height: 1;
            margin-bottom: 4px;
            transition: transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mobile-nav-item .nav-icon svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            fill: none;
            transition: transform 0.2s ease;
        }
        
        .mobile-nav-item .nav-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.01em;
            line-height: 1.2;
        }
        
        .mobile-nav-item.active {
            color: var(--primary);
        }
        
        .mobile-nav-item.active .nav-icon {
            transform: scale(1.15);
        }
        
        .mobile-nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: var(--primary);
            border-radius: 0 0 4px 4px;
        }

        main { 
            max-width: 1000px; 
            margin: 40px auto; 
            padding: 0 24px; 
        }

        /* Cards */
        .auth-box, .form-box, .study-box, .stats-box {
            background: var(--white);
            padding: 40px;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--gray-200);
        }

        h2 { 
            margin-bottom: 24px; 
            color: var(--gray-900);
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        
        h3 { 
            margin-bottom: 16px; 
            color: var(--gray-800);
            font-size: 20px;
            font-weight: 600;
        }

        label { 
            font-weight: 600; 
            margin-top: 16px; 
            display: block;
            color: var(--gray-700);
            font-size: 14px;
        }
        
        input, textarea, select {
            width: 100%; 
            padding: 12px 16px; 
            margin: 8px 0 16px 0;
            border: 2px solid var(--gray-200); 
            border-radius: var(--radius); 
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: var(--white);
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        textarea { 
            min-height: 100px; 
            resize: vertical; 
        }

        button {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white; 
            border: none;
            padding: 14px 24px; 
            border-radius: var(--radius); 
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            margin-top: 12px; 
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-md);
            font-family: inherit;
        }
        
        button:hover { 
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .option-btn {
            background: var(--gray-50);
            color: var(--gray-800); 
            text-align: left;
            margin: 8px 0; 
            padding: 16px 20px;
            border: 2px solid var(--gray-200);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .option-btn:hover:not(:disabled) { 
            background: var(--gray-100);
            border-color: var(--primary-light);
            transform: translateX(4px);
        }
        
        .option-btn:disabled { 
            cursor: not-allowed; 
            opacity: 0.6; 
        }

        .feedback { 
            padding: 16px 20px; 
            border-radius: var(--radius); 
            margin-top: 20px; 
            font-weight: 600;
            font-size: 15px;
        }
        
        .feedback.correct { 
            background: var(--success-light); 
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .feedback.wrong { 
            background: var(--danger-light); 
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .hidden { display: none; }

        .success { 
            background: var(--success-light); 
            color: #065f46; 
            padding: 14px 18px; 
            border-radius: var(--radius); 
            margin-bottom: 20px;
            border-left: 4px solid var(--success);
            font-weight: 500;
        }
        
        .error { 
            background: var(--danger-light); 
            color: #991b1b; 
            padding: 14px 18px; 
            border-radius: var(--radius); 
            margin-bottom: 20px;
            border-left: 4px solid var(--danger);
            font-weight: 500;
        }

        .stats { 
            display: flex; 
            gap: 12px; 
            margin: 20px 0; 
            flex-wrap: wrap; 
        }
        
        .stats span { 
            background: var(--gray-50);
            padding: 12px 18px; 
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 500;
            border: 1px solid var(--gray-200);
        }
        
        .ok { color: var(--success); font-weight: 700; }
        .bad { color: var(--danger); font-weight: 700; }

        .auth-box p { margin-top: 20px; text-align: center; color: var(--gray-600); }
        .auth-box a { color: var(--primary); font-weight: 600; text-decoration: none; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 24px 0;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
            padding: 24px;
            border-radius: var(--radius-lg);
            text-align: center;
            border: 1px solid var(--gray-200);
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-card h3 {
            font-size: 13px;
            color: var(--gray-500);
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }
        
        .stat-card .value {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .progress-bar {
            width: 100%;
            height: 12px;
            background: var(--gray-200);
            border-radius: 999px;
            overflow: hidden;
            margin: 16px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success) 0%, #34d399 100%);
            transition: width 0.5s ease;
            border-radius: 999px;
        }
        
        .question-item {
            background: var(--gray-50);
            padding: 20px;
            margin: 12px 0;
            border-radius: var(--radius-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--gray-200);
        }
        
        .question-item button {
            width: auto;
            padding: 10px 18px;
            margin: 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--gray-600) 0%, var(--gray-700) 100%);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .auth-box, .form-box, .study-box, .stats-box {
            animation: fadeIn 0.4s ease-out;
        }

        /* ===== RESPONSIVO MOBILE ===== */
        @media (max-width: 768px) {
            .desktop-nav {
                display: none !important;
            }
            
            .mobile-topbar {
                display: flex !important;
            }
            
            .mobile-nav {
                display: block !important;
            }
            
            main {
                margin: 20px auto 90px auto;
                padding: 0 16px;
            }
            
            .auth-box, .form-box, .study-box, .stats-box {
                padding: 24px;
                border-radius: var(--radius-lg);
            }
            
            h2 {
                font-size: 22px;
            }
            
            h3 {
                font-size: 17px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            
            .stat-card {
                padding: 16px;
            }
            
            .stat-card .value {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <?php 
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    $isAuthPage = in_array($currentPage, ['login', 'register']);
    ?>
    
    <?php if (!$isAuthPage): ?>
    
    <!-- NAVBAR DESKTOP -->
    <nav class="desktop-nav">
        <a href="/?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">📚 Estudo</a>
        <a href="/?page=add-question" class="<?= $currentPage === 'add-question' ? 'active' : '' ?>">➕ Nova Pergunta</a>
        <a href="/?page=manage-questions" class="<?= $currentPage === 'manage-questions' ? 'active' : '' ?>">📋 Gerenciar</a>
        <a href="/?page=categories" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">📂 Categorias</a>
        <a href="/?page=stats" class="<?= $currentPage === 'stats' ? 'active' : '' ?>">📊 Estatísticas</a>
        <?php if (isset($_SESSION['username'])): ?>
            <span class="user-info">Olá, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="/?page=logout" class="btn-logout-desktop">Sair</a>
        <?php endif; ?>
    </nav>
    
    <!-- TOPBAR MOBILE -->
    <div class="mobile-topbar">
        <span class="mobile-topbar-title">📚 Onki</span>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="/?page=logout" class="mobile-topbar-logout">Sair</a>
        <?php endif; ?>
    </div>
    
    <!-- BOTTOM NAV MOBILE -->
    <nav class="mobile-nav">
        <div class="mobile-nav-inner">
            <a href="/?page=dashboard" class="mobile-nav-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <span class="nav-icon">📚</span>
                <span class="nav-label">Estudo</span>
            </a>
            <a href="/?page=add-question" class="mobile-nav-item <?= $currentPage === 'add-question' ? 'active' : '' ?>">
                <span class="nav-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </span>
                <span class="nav-label">Nova</span>
            </a>
            <a href="/?page=manage-questions" class="mobile-nav-item <?= $currentPage === 'manage-questions' ? 'active' : '' ?>">
                <span class="nav-icon">📋</span>
                <span class="nav-label">Gerenciar</span>
            </a>
            <a href="/?page=categories" class="mobile-nav-item <?= $currentPage === 'categories' ? 'active' : '' ?>">
                <span class="nav-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                </span>
                <span class="nav-label">Categorias</span>
            </a>
            <a href="/?page=stats" class="mobile-nav-item <?= $currentPage === 'stats' ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-label">Estatísticas</span>
            </a>
        </div>
    </nav>
    <?php endif; ?>
    
    <main>
        <?= $content ?? '' ?>
    </main>
</body>
</html>