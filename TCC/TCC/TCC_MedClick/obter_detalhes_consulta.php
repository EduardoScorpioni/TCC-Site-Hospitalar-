<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'conexao.php';

// Verifica se o médico está logado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado.']);
    exit;
}

// Verifica se recebeu o ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID da consulta não informado.']);
    exit;
}

$id_consulta = intval($_GET['id']);

try {
    $sql = "
        SELECT 
            c.id_consulta,
            c.data AS data,
            c.hora AS hora,
            c.status,
            e.id AS especialidade,
            p.nome,
            p.cpf,
            p.telefone,
            p.email,
            p.endereco
        FROM consultas c
        INNER JOIN pacientes p ON c.paciente_id = p.id
        INNER JOIN especialidades e ON c.especialidade_id = e.id
        WHERE c.id_consulta = ?
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_consulta]);

    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Consulta não encontrada.']);
        exit;
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $dados = [
        'success' => true,
        'consulta' => [
            'status' => $row['status'],
            'data' => $row['data'],
            'hora' => $row['hora'],
            'especialidade' => $row['especialidade']
        ],
        'paciente' => [
            'nome' => $row['nome'],
            'cpf' => $row['cpf'],
            'telefone' => $row['telefone'],
            'email' => $row['email'],
            'endereco' => $row['endereco']
        ]
    ];

    echo json_encode($dados, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro no banco de dados: ' . $e->getMessage()
    ]);
}
?>
