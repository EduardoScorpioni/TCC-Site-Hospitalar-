<?php
session_start();
require 'conexao.php';

// Verifica se o usuário é médico
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login_medico.php");
    exit();
}

$medico_id = $_SESSION['medico_id'];
if (!$medico_id) {
    echo "Erro: médico não identificado. Faça login novamente.";
    exit();
}

// Pegamos especialidade do médico
$stmt = $pdo->prepare("SELECT especialidade_id FROM medicos WHERE id = ?");
$stmt->execute([$medico_id]);
$especialidade_id = $stmt->fetchColumn();
if (!$especialidade_id) {
    echo "Erro: especialidade do médico não encontrada.";
    exit();
}

// Dados do formulário
$paciente_id       = !empty($_POST['paciente_id']) ? (int)$_POST['paciente_id'] : null;
$nome_paciente     = trim($_POST['nome_paciente']);
$telefone_paciente = trim($_POST['telefone_paciente']);
$data              = $_POST['data'];
$hora              = $_POST['hora'];

// Validar campos obrigatórios
if (!$nome_paciente || !$data || !$hora) {
    echo "Preencha todos os campos obrigatórios.";
    exit();
}

// Validar telefone se preenchido
$telefone_formatado = null;
if ($telefone_paciente) {
    $telefone_limpo = preg_replace('/\D/', '', $telefone_paciente);
    if (strlen($telefone_limpo) < 10 || strlen($telefone_limpo) > 11) {
        echo "Telefone inválido. Informe um número com DDD (10 ou 11 dígitos).";
        exit();
    }
    $telefone_formatado = $telefone_limpo;
}

// Se for paciente já cadastrado
if ($paciente_id) {
    // Verificar se o paciente existe
    $stmt = $pdo->prepare("SELECT id, telefone FROM pacientes WHERE id = ?");
    $stmt->execute([$paciente_id]);
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$paciente) {
        echo "Erro: Paciente não encontrado.";
        exit();
    }
    
    // Atualizar telefone se estiver vazio ou diferente
    if ($telefone_formatado && (empty($paciente['telefone']) || $paciente['telefone'] !== $telefone_formatado)) {
        $stmt = $pdo->prepare("UPDATE pacientes SET telefone = ? WHERE id = ?");
        $stmt->execute([$telefone_formatado, $paciente_id]);
    }
    
    // Inserir consulta
    $stmt = $pdo->prepare("INSERT INTO consultas 
        (paciente_id, medico_id, especialidade_id, data, hora, status) 
        VALUES (?, ?, ?, ?, ?, 'Agendada')");
    $stmt->execute([$paciente_id, $medico_id, $especialidade_id, $data, $hora]);

} else {
    // Consulta sem vínculo direto com paciente do sistema (nome livre)
    $stmt = $pdo->prepare("INSERT INTO consultas 
        (nome_paciente_manual, medico_id, especialidade_id, data, hora, status) 
        VALUES (?, ?, ?, ?, ?, 'Agendada')");
    $stmt->execute([$nome_paciente, $medico_id, $especialidade_id, $data, $hora]);
}

// Gerar código de confirmação
$codigo_confirmacao = substr(md5(uniqid() . time()), 0, 8);
$ultimo_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("UPDATE consultas SET codigo_confirmacao = ? WHERE id_consulta = ?");
$stmt->execute([$codigo_confirmacao, $ultimo_id]);

header("Location: cadastrar_consulta_medico.php?sucesso=1");
exit();
