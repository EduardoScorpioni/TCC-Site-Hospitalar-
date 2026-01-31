<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login_medico.php");
    exit();
}

$medico_id = $_SESSION['medico_id'];
$atende_24h = $_POST['atende_24h'];

if ($atende_24h == 1) {
    $hora_abertura = "00:00:00";
    $hora_almoco_inicio = null;
    $hora_almoco_fim = null;
    $hora_fechamento = "23:59:59";
} else {
    $hora_abertura = $_POST['hora_abertura'];
    $hora_almoco_inicio = $_POST['hora_almoco_inicio'] ?: null;
    $hora_almoco_fim = $_POST['hora_almoco_fim'] ?: null;
    $hora_fechamento = $_POST['hora_fechamento'];
}

// Se já existe configuração, atualiza, senão insere
$check = $pdo->prepare("SELECT id FROM horarios_funcionamento WHERE medico_id = ?");
$check->execute([$medico_id]);

if ($check->rowCount() > 0) {
    $stmt = $pdo->prepare("UPDATE horarios_funcionamento 
        SET hora_abertura=?, hora_almoco_inicio=?, hora_almoco_fim=?, hora_fechamento=?, atende_24h=? 
        WHERE medico_id=?");
    $stmt->execute([$hora_abertura, $hora_almoco_inicio, $hora_almoco_fim, $hora_fechamento, $atende_24h, $medico_id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO horarios_funcionamento 
        (medico_id, hora_abertura, hora_almoco_inicio, hora_almoco_fim, hora_fechamento, atende_24h) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$medico_id, $hora_abertura, $hora_almoco_inicio, $hora_almoco_fim, $hora_fechamento, $atende_24h]);
}

header("Location: liberar_horarios.php?config=ok");
exit();
?>
