<?php
require 'conexao.php';
$especialidade_id = (int)$_GET['especialidade_id'];

$sql = "SELECT m.id, m.nome, l.nome AS local_consulta
        FROM medicos m
        LEFT JOIN locais_consulta l ON m.local_consulta_id = l.id
        WHERE m.especialidade_id = ?
        ORDER BY m.nome";
$stmt = $pdo->prepare($sql);
$stmt->execute([$especialidade_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

