<?php

require_once '../config/database.php';

require_once '../app/models/User.php';
require_once '../app/repositories/UserRepository.php';
require_once '../app/services/AuthService.php';
require_once '../app/controllers/AuthController.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userRepository = new UserRepository($pdo);
    $authService = new AuthService($userRepository);
    $authController = new AuthController($authService);

    $success = $authController->register(
        $username,
        $password
    );

    if ($success) {
        $message = 'Conta criada com sucesso!';
    } else {
        $message = 'Não foi possível criar a conta.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
</head>

<body>

<h1>Criar conta</h1>

<form method="POST">

    <label>Usuário:</label>
    <input type="text" name="username" required>

    <br><br>

    <label>Senha:</label>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit">
        Criar conta
    </button>

</form>

<p>
    <?= htmlspecialchars($message) ?>
</p>

</body>

</html>