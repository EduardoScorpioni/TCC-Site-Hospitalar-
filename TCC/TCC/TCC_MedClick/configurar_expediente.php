<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login_medico.php");
    exit();
}

$medico_id = $_SESSION['medico_id'];

// Carrega dados existentes
$stmt = $pdo->prepare("SELECT * FROM horario_funcionamento WHERE medico_id = ?");
$stmt->execute([$medico_id]);
$horario = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
<meta charset="UTF-8">
<title>Configurar Horário de Funcionamento</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.container { max-width: 500px; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; }
h1 { text-align: center; color: #003366; }
label { display: block; margin-top: 15px; font-weight: bold; }
input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
button { margin-top: 20px; background-color: #003366; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background-color: #1e4a80; }
</style>
</head>
<body>
<div class="container">
    <h1>Horário de Funcionamento</h1>
    <form method="post" action="processa_expediente.php">
        <label>Atendimento 24h:</label>
        <input type="checkbox" name="atende_24h" value="1" <?= $horario && $horario['atende_24h'] ? 'checked' : '' ?>>

        <label>Hora de Início:</label>
        <input type="time" name="hora_inicio" value="<?= $horario['hora_inicio'] ?>">

        <label>Início do Almoço:</label>
        <input type="time" name="hora_almoco_inicio" value="<?= $horario['hora_almoco_inicio'] ?>">

        <label>Fim do Almoço:</label>
        <input type="time" name="hora_almoco_fim" value="<?= $horario['hora_almoco_fim'] ?>">

        <label>Hora de Fim:</label>
        <input type="time" name="hora_fim" value="<?= $horario['hora_fim'] ?>">

        <button type="submit">Salvar</button>
    </form>
</div>
</body>
</html>
