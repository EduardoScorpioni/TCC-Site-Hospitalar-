<?php
require 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: consultas_medico.php");
    exit;
}

$id_consulta = $_GET['id'];

// Buscar dados da consulta atual
$stmt = $pdo->prepare("SELECT * FROM consultas WHERE id_consulta = ?");
$stmt->execute([$id_consulta]);
$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consulta || $consulta['status'] !== 'Agendada') {
    die("Consulta não encontrada ou não pode ser adiada.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adiar Consulta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Adiar Consulta</h3>
    <form action="processar_adiamento.php" method="POST" class="mt-4">
        <input type="hidden" name="id_consulta" value="<?= $consulta['id_consulta'] ?>">

        <div class="mb-3">
            <label for="data" class="form-label">Nova Data</label>
            <input type="date" name="data" id="data" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hora" class="form-label">Novo Horário</label>
            <input type="time" name="hora" id="hora" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Confirmar Adiamento</button>
        <a href="consultas_medico.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
