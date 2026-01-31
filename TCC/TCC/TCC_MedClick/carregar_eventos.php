<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

session_start();
require 'conexao.php';

// Verifica se $pdo foi criado corretamente
if (!isset($pdo) || !$pdo instanceof PDO) {
    echo json_encode(['error' => 'Conexão com o banco de dados falhou']);
    exit;
}

// Verifica autenticação
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autenticado']);
    exit;
}

$id_medico = $_SESSION['id'];

// Consulta as consultas do médico logado
$sql = "
    SELECT 
        c.id_consulta,
        c.data AS data,
        c.hora AS hora,
        c.status,
        p.nome AS nome_paciente
    FROM consultas c
    INNER JOIN pacientes p ON c.paciente_id = p.id
    WHERE c.medico_id = ?
    ORDER BY c.data, c.hora
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_medico]);
    $eventos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($row['data'])) {

            // Define cor com base no status
            $color = '#ffc107'; // Padrão = Agendada
            switch (strtolower($row['status'])) {
                case 'cancelada':
                    $color = '#dc3545'; break; // Vermelho
                case 'realizada':
                    $color = '#198754'; break; // Verde
                case 'adiada':
                    $color = '#0dcaf0'; break; // Azul
            }

            $eventos[] = [
                'id' => $row['id_consulta'],
                'title' => $row['nome_paciente'] . ' (' . ucfirst(strtolower($row['status'])) . ')',
                'start' => $row['data'] . 'T' . $row['hora'],
                'color' => $color
            ];
        }
    }

    echo json_encode($eventos, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // Captura e exibe erros de SQL
    echo json_encode(['error' => 'Erro no banco de dados', 'message' => $e->getMessage()]);
}
?>
