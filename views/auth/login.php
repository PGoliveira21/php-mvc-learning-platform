<?php $pageTitle = 'Login'; ob_start(); ?>
<div class="auth-box">
    <h2>Entrar</h2>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'cadastrado'): ?>
        <p class="success">Cadastro realizado! Faça login.</p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="/?page=login">
        <input type="text" name="username" placeholder="Usuário" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <p>Não tem conta? <a href="/?page=register">Cadastre-se</a></p>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>