<?php
session_start();
require 'conexao.php';

$usuario = $_POST['usuario'];
$senha   = $_POST['senha'];

/* === LOGIN COMO GERENTE === */
$stmt = $pdo->prepare("SELECT * FROM gerentes WHERE cpf = ? OR email = ?");
$stmt->execute([$usuario, $usuario]);
$gerente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($gerente && password_verify($senha, $gerente['senha'])) {
    $_SESSION['tipo']     = 'gerente';
    $_SESSION['id']       = $gerente['id'];
    $_SESSION['usuario']  = $gerente['nome'];
    $_SESSION['cpf']      = $gerente['cpf'];
    $_SESSION['email']    = $gerente['email'];
    $_SESSION['telefone'] = $gerente['telefone'];
    $_SESSION['imagem']   = $gerente['imagem'];

    header("Location: painel_gerente.php?login=ok");
    exit;
}

/* === LOGIN COMO PACIENTE === */
$stmt = $pdo->prepare("SELECT * FROM pacientes WHERE cpf = ? OR email = ?");
$stmt->execute([$usuario, $usuario]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($paciente && password_verify($senha, $paciente['senha'])) {
    $_SESSION['tipo']      = 'paciente';
    $_SESSION['id']        = $paciente['id'];
    $_SESSION['usuario']   = $paciente['nome'];
    $_SESSION['cpf']       = $paciente['cpf'];
    $_SESSION['email']     = $paciente['email'];
    $_SESSION['telefone']  = $paciente['telefone'];
    $_SESSION['imagem']    = $paciente['imagem'];

    header("Location: index.php?login=ok");
    exit;
}

/* === SE NÃO ENCONTRAR NENHUM === */
header("Location: login1.php?erro=usuario");
exit;
