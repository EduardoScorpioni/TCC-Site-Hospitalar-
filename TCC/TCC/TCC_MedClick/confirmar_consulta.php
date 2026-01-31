<?php
session_start();
require 'conexao.php';

// Verifica se o paciente está logado
if (!isset($_SESSION['email'])) {
    header("Location: login1.php");
    exit();
}

// Verifica se recebeu código
if (!isset($_GET['codigo']) || empty($_GET['codigo'])) {
    die("Código de confirmação inválido.");
}

$codigo = $_GET['codigo'];

// Busca todas as informações da consulta
$sql = "
    SELECT 
        c.codigo_confirmacao,
        c.data,
        c.hora,
        c.status,
        p.nome AS nome_paciente,
        m.nome AS nome_medico,
        m.local_consulta_id,
        e.nome AS nome_especialidade
    FROM consultas c
    INNER JOIN pacientes p ON c.paciente_id = p.id
    INNER JOIN medicos m ON c.medico_id = m.id
    INNER JOIN especialidades e ON c.especialidade_id = e.id
    WHERE c.codigo_confirmacao = ?
    LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$codigo]);
$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consulta) {
    die("Consulta não encontrada para este código.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <title>Confirmação de Consulta - MedClick</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #A2D65B;
        }
        .card {
            max-width: 600px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #2980b9;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .obs {
            font-size: 0.95rem;
            margin-top: 20px;
            color: #555;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="card">
    <div class="card-header text-center">
        Consulta Agendada com Sucesso!
    </div>
    <div class="card-body">
        <p><strong>Código de Confirmação:</strong> <?= htmlspecialchars($consulta['codigo_confirmacao']) ?></p>
        <p><strong>Paciente:</strong> <?= htmlspecialchars($consulta['nome_paciente']) ?></p>
        <p><strong>Médico:</strong> <?= htmlspecialchars($consulta['nome_medico']) ?></p>
        <p><strong>Especialidade:</strong> <?= htmlspecialchars($consulta['nome_especialidade']) ?></p>
        <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($consulta['data'])) ?></p>
        <p><strong>Horário:</strong> <?= htmlspecialchars($consulta['hora']) ?></p>
        <p><strong>Local da Consulta:</strong> <?= htmlspecialchars($consulta['local_consulta_id']) ?></p>

        <div class="obs">
            <p><strong>Observações:</strong></p>
            <p>Por favor levar todos os documentos de identificação e o código de consulta.</p>
            <p>Caso haja um atraso de 20 minutos sua consulta será cancelada.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
wwwwwwwwwwwwwww
</body>
</html>
