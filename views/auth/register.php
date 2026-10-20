<?php $pageTitle = 'Cadastro'; ob_start(); ?>
<div class="auth-box">
    <h2>Criar Conta</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="/?page=register">
        <input type="text" name="username" placeholder="Usuário (mín. 3)" required minlength="3">
        <input type="password" name="password" placeholder="Senha (mín. 4)" required minlength="4">
        <button type="submit">Cadastrar</button>
    </form>
    <p>Já tem conta? <a href="/?page=login">Faça login</a></p>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>