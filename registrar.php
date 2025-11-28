<?php
// registrar.php
require 'db.php'; // Inclui a conexão com o banco

$erros = [];
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // --- 1. VALIDAÇÃO BÁSICA ---
    if (empty($usuario) || empty($senha) || empty($confirma_senha)) {
        $erros[] = "Todos os campos são obrigatórios.";
    }
    if ($senha !== $confirma_senha) {
        $erros[] = "As senhas digitadas não coincidem.";
    }
    if (strlen($senha) < 6) {
        $erros[] = "A senha deve ter no mínimo 6 caracteres.";
    }

    if (empty($erros)) {
        // --- 2. VERIFICA SE O USUÁRIO JÁ EXISTE ---
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :u");
        $stmt->execute([':u' => $usuario]);
        
        if ($stmt->fetchColumn() > 0) {
            $erros[] = "Este nome de usuário já está em uso. Escolha outro.";
        } else {
            // --- 3. INSERÇÃO DO NOVO USUÁRIO ---
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (:u, :s)");
            
            if ($stmt->execute([':u' => $usuario, ':s' => $senhaHash])) {
                $sucesso = "Usuário **$usuario** cadastrado com sucesso! Você já pode fazer o login.";
            } else {
                $erros[] = "Ocorreu um erro ao tentar registrar o usuário no banco de dados.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro de Novo Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 shadow" style="width: 400px;">
        <h3 class="text-center mb-4">Novo Cadastro</h3>

        <?php if (!empty($erros)): ?>
            <div class="alert alert-danger">
                <?php foreach ($erros as $erro): ?>
                    * <?= $erro ?> <br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?= $sucesso ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nome de Usuário</label>
                <input type="text" name="usuario" class="form-control" value="<?= htmlspecialchars($usuario ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Confirme a Senha</label>
                <input type="password" name="confirma_senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Criar Conta</button>
        </form>
        <div class="mt-3 text-center">
            <a href="login.php" class="text-primary">Já tem uma conta? Faça login</a>
        </div>
    </div>
</body>
</html>