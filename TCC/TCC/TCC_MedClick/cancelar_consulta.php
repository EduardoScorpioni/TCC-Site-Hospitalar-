<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$consulta_id = $input['id'];
$medico_id = $_SESSION['id'];

// Verificar se a consulta pertence ao médico
$sql_verificar = "SELECT id_consulta FROM consultas WHERE id_consulta = ? AND medico_id = ?";
$stmt = $pdo->prepare($sql_verificar);
$stmt->execute([$consulta_id, $medico_id]);

if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Consulta não encontrada']);
    exit;
}

// Atualizar status para Cancelada
$sql_atualizar = "UPDATE consultas SET status = 'Cancelada' WHERE id_consulta = ?";
$stmt = $pdo->prepare($sql_atualizar);

if ($stmt->execute([$consulta_id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao cancelar consulta']);
}
?>