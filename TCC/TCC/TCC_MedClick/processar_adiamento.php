<?php
require 'conexao.php';

if (!isset($_POST['id_consulta'], $_POST['data'], $_POST['hora'])) {
    header("Location: consultas_medico.php");
    exit;
}

$id_consulta = $_POST['id_consulta'];
$nova_data = $_POST['data'];
$nova_hora = $_POST['hora'];

// Atualiza a consulta
$stmt = $pdo->prepare("UPDATE consultas SET data = ?, hora = ? WHERE id_consulta = ?");
$stmt->execute([$nova_data, $nova_hora, $id_consulta]);

header("Location: consultas_medico.php");
exit;
?>
