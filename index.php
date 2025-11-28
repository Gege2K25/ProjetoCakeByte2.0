<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
require 'db.php';

// Busca todos os bolos
$stmt = $pdo->query("SELECT * FROM bolos ORDER BY id DESC");
$bolos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Bolos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🍰 Estoque de Bolos</h1>
        <div>
            <a href="adicionar.php" class="btn btn-success">Adicionar Novo</a>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome do Bolo</th>
                <th>Sabor</th>
                <th>Preço (R$)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bolos as $bolo): ?>
            <tr>
                <td><?= $bolo['id'] ?></td>
                <td><?= htmlspecialchars($bolo['nome']) ?></td>
                <td><?= htmlspecialchars($bolo['sabor']) ?></td>
                <td>R$ <?= number_format($bolo['preco'], 2, ',', '.') ?></td>
                <td>
                    <a href="editar.php?id=<?= $bolo['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="deletar.php?id=<?= $bolo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>