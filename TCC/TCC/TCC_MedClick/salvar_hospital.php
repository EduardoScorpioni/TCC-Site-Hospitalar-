<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'gerente') {
    header("Location: login1.php?erro=acesso");
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro_hospital.php?erro=metodo");
    exit;
}

// Coleta e sanitiza os dados
$nome = trim($_POST['nome']);
$endereco = trim($_POST['endereco']);
$cidade = trim($_POST['cidade']);
$estado = trim($_POST['estado']);
$telefone = trim($_POST['telefone']);
$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
$criado_por = $_SESSION['id'];

// Upload de imagem (opcional)
$nome_arq = null;
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    // Verifica se é uma imagem válida
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = $_FILES['imagem']['type'];
    
    if (!in_array($file_type, $allowed_types)) {
        header("Location: cadastro_hospital.php?erro=tipo_imagem");
        exit;
    }
    
    // Gera nome único para o arquivo
    $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $nome_arq = uniqid("hospital_", true) . "." . $ext;

    // Cria pasta se não existir
    if (!is_dir("img/hospitais")) {
        mkdir("img/hospitais", 0777, true);
    }

    // Move o arquivo para o diretório
    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], "img/hospitais/$nome_arq")) {
        header("Location: cadastro_hospital.php?erro=upload");
        exit;
    }
}

// Inserção no banco
try {
    $sql = "INSERT INTO hospitais 
            (nome, endereco, cidade, estado, telefone, email, criado_por, criado_em) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $endereco, $cidade, $estado, $telefone, $email, $criado_por]);
    
    // Se foi feito upload de imagem, atualiza o registro
    if ($nome_arq) {
        $hospital_id = $pdo->lastInsertId();
        $sql_update = "UPDATE hospitais SET imagem = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$nome_arq, $hospital_id]);
    }
    
    header("Location: painel_gerente.php?sucesso=hospital_cadastrado");
    exit;
    
} catch (PDOException $e) {
    // Log do erro (em produção)
    error_log("Erro ao cadastrar hospital: " . $e->getMessage());
    
    // Remove a imagem se foi feita upload mas ocorreu erro no banco
    if ($nome_arq && file_exists("img/hospitais/$nome_arq")) {
        unlink("img/hospitais/$nome_arq");
    }
    
    header("Location: cadastro_hospital.php?erro=banco");
    exit;
}
?>