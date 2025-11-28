<?php
session_start();
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));
require 'db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM bolos WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: index.php");
exit;