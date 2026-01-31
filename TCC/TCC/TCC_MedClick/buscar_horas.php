<?php
require 'conexao.php';

$medico_id = $_GET['medico_id'];
$data = $_GET['data'];

if (!$medico_id || !$data) {
    echo json_encode([]);
    exit;
}

$sql = "
    SELECT a.id, a.hora
    FROM agenda a
    LEFT JOIN consultas c 
        ON c.medico_id = a.medico_id
        AND c.data = a.data
        AND c.hora = a.hora
        AND c.status = 'Agendada'
    WHERE a.medico_id = ? 
      AND a.data = ? 
      AND a.disponivel = 1
      AND c.id_consulta IS NULL
    ORDER BY a.hora ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$medico_id, $data]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
