<?php
session_start();
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) exit(header("Location: index.php"));

// Busca dados atuais
$stmt = $pdo->prepare("SELECT * FROM bolos WHERE id = :id");
$stmt->execute([':id' => $id]);
$bolo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sabor = $_POST['sabor'];
    $preco = str_replace(',', '.', $_POST['preco']);

    $stmt = $pdo->prepare("UPDATE bolos SET nome = :n, sabor = :s, preco = :p WHERE id = :id");
    $stmt->execute([':n' => $nome, ':s' => $sabor, ':p' => $preco, ':id' => $id]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Bolo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3>Editar Bolo</h3>
    <form method="POST" class="mt-3" style="max-width: 500px;">
        <div class="mb-3">
            <label>Nome do Bolo</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($bolo['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Sabor Principal</label>
            <input type="text" name="sabor" class="form-control" value="<?= htmlspecialchars($bolo['sabor']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Preço</label>
            <input type="text" name="preco" class="form-control" value="<?= $bolo['preco'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>