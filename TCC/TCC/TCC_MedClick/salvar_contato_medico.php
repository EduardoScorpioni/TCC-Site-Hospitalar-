<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'gerente') {
    header("Location: login1.php?erro=acesso");
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro_contato_medico.php?erro=metodo");
    exit;
}

// Coleta e sanitiza os dados
$medico_id = filter_input(INPUT_POST, 'medico_id', FILTER_VALIDATE_INT);
$telefone = trim($_POST['telefone']);
$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
$rede_social = !empty($_POST['rede_social']) ? trim($_POST['rede_social']) : null;
$criado_por = $_SESSION['id'];

// Validações
if (!$medico_id) {
    header("Location: cadastro_contato_medico.php?erro=medico_invalido");
    exit;
}

// Remove caracteres não numéricos do telefone
$telefone_limpo = preg_replace('/\D/', '', $telefone);

// Verifica se já existe contato com este telefone para o mesmo médico
try {
    $stmt_check = $pdo->prepare("SELECT id FROM contatos_medicos WHERE medico_id = ? AND telefone = ?");
    $stmt_check->execute([$medico_id, $telefone_limpo]);
    $existing_telefone = $stmt_check->fetch();
    
    if ($existing_telefone) {
        header("Location: cadastro_contato_medico.php?erro=telefone_existente");
        exit;
    }
    
    // Verifica se já existe contato com este email para o mesmo médico
    if ($email) {
        $stmt_check = $pdo->prepare("SELECT id FROM contatos_medicos WHERE medico_id = ? AND email = ?");
        $stmt_check->execute([$medico_id, $email]);
        $existing_email = $stmt_check->fetch();
        
        if ($existing_email) {
            header("Location: cadastro_contato_medico.php?erro=email_existente");
            exit;
        }
    }
    
    // Inserção no banco
    $sql = "INSERT INTO contatos_medicos 
            (medico_id, telefone, email, rede_social, criado_por, criado_em) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$medico_id, $telefone_limpo, $email, $rede_social, $criado_por]);
    
    header("Location: cadastro_contato_medico.php?sucesso=1");
    exit;
    
} catch (PDOException $e) {
    error_log("Erro ao cadastrar contato médico: " . $e->getMessage());
    header("Location: cadastro_contato_medico.php?erro=banco");
    exit;
}
?>