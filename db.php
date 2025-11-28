<?php
// db.php
try {
    // Cria/Conecta ao banco de dados SQLite (arquivo: banco_bolos.sqlite)
    $pdo = new PDO('sqlite:banco_bolos.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Cria a tabela de Usuários se não existir
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario TEXT NOT NULL,
        senha TEXT NOT NULL
    )");

    // 2. Cria a tabela de Bolos se não existir
    $pdo->exec("CREATE TABLE IF NOT EXISTS bolos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        sabor TEXT NOT NULL,
        preco REAL NOT NULL
    )");

    // 3. Verifica se existe usuário, se não, cria o Admin padrão
    // Usuário: admin | Senha: 123
    $checkUser = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    if ($checkUser == 0) {
        $senhaHash = password_hash('123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (:u, :s)");
        $stmt->execute([':u' => 'admin', ':s' => $senhaHash]);
    }

} catch (PDOException $e) {
    die("Erro no banco de dados: " . $e->getMessage());
}
?>