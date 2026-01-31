<?php
session_start();
require dirname(__FILE__) . '/conexao.php';
require dirname(__FILE__) . '/utils_pdf.php'; // Função que chama o Python

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: consultas_medico.php");
    exit;
}

$id     = (int) $_GET['id'];
$status = $_GET['status'];

// status válidos agora incluem "Adiada"
$validos = ['Realizada', 'Cancelada', 'Adiada'];
if (!in_array($status, $validos)) {
    die("Status inválido.");
}

// pega dados da consulta
$stmtCheck = $pdo->prepare("
    SELECT c.id_consulta, c.status, c.data, c.hora, c.paciente_id, m.nome AS medico, m.crm, p.nome AS paciente
    FROM consultas c
    INNER JOIN pacientes p ON c.paciente_id = p.id
    INNER JOIN medicos m ON c.medico_id = m.id
    WHERE c.id_consulta = ?
");
$stmtCheck->execute([$id]);
$consulta = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if (!$consulta || $consulta['status'] === $status) {
    // já está com esse status ou não encontrada
    header("Location: consultas_medico.php");
    exit;
}

// atualiza status
$stmt = $pdo->prepare("UPDATE consultas SET status = ? WHERE id_consulta = ?");
$stmt->execute([$status, $id]);

// se foi realizada, gera automaticamente o comprovante
if ($status === 'Realizada') {
    $data = array(
        'tipo' => 'comprovante',
        'paciente_id' => $consulta['paciente_id'],
        'medico_id'   => $_SESSION['id'],
        'title'       => 'Comprovante de Consulta',
        'paciente'    => $consulta['paciente'],
        'medico'      => $consulta['medico'],
        'crm'         => $consulta['crm'],
        'especialidade' => '', // adiciona depois se tiver
        'data'        => date('d/m/Y', strtotime($consulta['data'])),
        'hora'        => date('H:i', strtotime($consulta['hora'])),
        'content'     => 'Consulta realizada com sucesso.',
        'timestamp'   => time()
    );

    $res = php_call_python_generate($data, 'python');

    if (isset($res['path']) && !empty($res['path'])) {
        $fileFullPath = $res['path'];
        $relative = str_replace('\\', '/', str_replace(dirname(__FILE__), '', $fileFullPath));
        $relative = ltrim($relative, '/\\');

        // salva no banco de documentos
        $stmtDoc = $pdo->prepare("INSERT INTO documentos (paciente_id, medico_id, consulta_id, tipo, arquivo) VALUES (?, ?, ?, ?, ?)");
        $stmtDoc->execute([$consulta['paciente_id'], $_SESSION['id'], $consulta['id_consulta'], 'comprovante', $relative]);
    } else {
        error_log("Erro: Python não retornou caminho do PDF.");
    }
}

// redireciona de volta
header("Location: consultas_medico.php");
exit;
?>
