<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login1.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <title>Bem-vindo</title>
</head>
<body>
  <h1>Olá, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>
  <p>Você está logado com sucesso.</p>
  <a href="logout.php">Sair</a>
</body>
</html>
