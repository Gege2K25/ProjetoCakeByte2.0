<?php
session_start();
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sabor = $_POST['sabor'];
    $preco = str_replace(',', '.', $_POST['preco']); // Aceita vírgula ou ponto

    $stmt = $pdo->prepare("INSERT INTO bolos (nome, sabor, preco) VALUES (:n, :s, :p)");
    $stmt->execute([':n' => $nome, ':s' => $sabor, ':p' => $preco]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Bolo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3>Adicionar Novo Bolo</h3>
    <form method="POST" class="mt-3" style="max-width: 500px;">
        <div class="mb-3">
            <label>Nome do Bolo</label>
            <input type="text" name="nome" class="form-control" required placeholder="Ex: Vulcão de Chocolate">
        </div>
        <div class="mb-3">
            <label>Sabor Principal</label>
            <input type="text" name="sabor" class="form-control" required placeholder="Ex: Chocolate Belga">
        </div>
        <div class="mb-3">
            <label>Preço</label>
            <input type="text" name="preco" class="form-control" required placeholder="Ex: 45.00">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>