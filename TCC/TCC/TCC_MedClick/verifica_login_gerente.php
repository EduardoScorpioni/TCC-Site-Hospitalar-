<?php
session_start();
require 'conexao.php'; // conexão PDO

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST['usuario']);
    $senha   = $_POST['senha'];

    // Decide se é CPF ou Email
    if (filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM gerentes WHERE email = ?";
    } else {
        $sql = "SELECT * FROM gerentes WHERE cpf = ?";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario]);
    $gerente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gerente && password_verify($senha, $gerente['senha'])) {
        // ✅ Login OK
        $_SESSION['id_gerente']   = $gerente['id'];
        $_SESSION['nome_gerente'] = $gerente['nome'];
        $_SESSION['cpf_gerente']  = $gerente['cpf'];
        $_SESSION['email_gerente']= $gerente['email'];
        $_SESSION['tipo']         = 'gerente';

        header("Location: painel_gerente.php");
        exit();
    } else {
        // ❌ Falhou
        header("Location: login_gerente.php?erro=usuario");
        exit();
    }
}
?>
