<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login_medico.php");
    exit();
}

$medico_id = $_SESSION['medico_id'];

$atende_24h = isset($_POST['atende_24h']) ? 1 : 0;
$hora_inicio = $_POST['hora_inicio'];
$hora_almoco_inicio = $_POST['hora_almoco_inicio'];
$hora_almoco_fim = $_POST['hora_almoco_fim'];
$hora_fim = $_POST['hora_fim'];

// Verifica se já existe registro
$stmt = $pdo->prepare("SELECT id FROM horario_funcionamento WHERE medico_id = ?");
$stmt->execute([$medico_id]);

if ($stmt->rowCount() > 0) {
    $update = $pdo->prepare("UPDATE horario_funcionamento 
        SET atende_24h=?, hora_inicio=?, hora_almoco_inicio=?, hora_almoco_fim=?, hora_fim=? 
        WHERE medico_id=?");
    $update->execute([$atende_24h, $hora_inicio, $hora_almoco_inicio, $hora_almoco_fim, $hora_fim, $medico_id]);
} else {
    $insert = $pdo->prepare("INSERT INTO horario_funcionamento 
        (medico_id, atende_24h, hora_inicio, hora_almoco_inicio, hora_almoco_fim, hora_fim) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $insert->execute([$medico_id, $atende_24h, $hora_inicio, $hora_almoco_inicio, $hora_almoco_fim, $hora_fim]);
}

header("Location: liberar_horarios.php?expediente=ok");
exit();
    