<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'gerente') {
    header("Location: login1.php?erro=acesso");
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro_gerente.php?erro=metodo");
    exit;
}

// Coleta e sanitiza os dados
$nome = trim($_POST['nome']);
$cpf = preg_replace('/\D/', '', $_POST['cpf']);
$email = trim($_POST['email']);
$telefone = preg_replace('/\D/', '', $_POST['telefone']);
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

// Validações
if (strlen($cpf) !== 11) {
    header("Location: cadastro_gerente.php?erro=cpf_invalido");
    exit;
}

if ($senha !== $confirmar_senha) {
    header("Location: cadastro_gerente.php?erro=senhas_nao_conferem");
    exit;
}

if (strlen($senha) < 6) {
    header("Location: cadastro_gerente.php?erro=senha_curta");
    exit;
}

// Upload de imagem (opcional)
$nome_arq = null;
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    // Verifica se é uma imagem válida
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = $_FILES['imagem']['type'];
    $file_size = $_FILES['imagem']['size'];
    
    if (!in_array($file_type, $allowed_types)) {
        header("Location: cadastro_gerente.php?erro=tipo_imagem");
        exit;
    }
    
    if ($file_size > 2097152) { // 2MB
        header("Location: cadastro_gerente.php?erro=tamanho_imagem");
        exit;
    }
    
    // Gera nome único para o arquivo
    $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $nome_arq = uniqid("gerente_", true) . "." . $ext;

    // Cria pasta se não existir
    if (!is_dir("img/gerentes")) {
        mkdir("img/gerentes", 0777, true);
    }

    // Move o arquivo para o diretório
    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], "img/gerentes/$nome_arq")) {
        header("Location: cadastro_gerente.php?erro=upload");
        exit;
    }
}

// Verifica se email ou CPF já existem
try {
    $stmt_check = $pdo->prepare("SELECT id FROM gerentes WHERE email = ? OR cpf = ?");
    $stmt_check->execute([$email, $cpf]);
    $existing = $stmt_check->fetch();
    
    if ($existing) {
        // Remove a imagem se foi feito upload
        if ($nome_arq && file_exists("img/gerentes/$nome_arq")) {
            unlink("img/gerentes/$nome_arq");
        }
        
        // Verifica qual campo já existe
        $stmt_check_email = $pdo->prepare("SELECT id FROM gerentes WHERE email = ?");
        $stmt_check_email->execute([$email]);
        if ($stmt_check_email->fetch()) {
            header("Location: cadastro_gerente.php?erro=email_existente");
            exit;
        }
        
        $stmt_check_cpf = $pdo->prepare("SELECT id FROM gerentes WHERE cpf = ?");
        $stmt_check_cpf->execute([$cpf]);
        if ($stmt_check_cpf->fetch()) {
            header("Location: cadastro_gerente.php?erro=cpf_existente");
            exit;
        }
    }
    
    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserção no banco
    $sql = "INSERT INTO gerentes 
            (nome, cpf, email, telefone, senha, imagem, criado_em) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $cpf, $email, $telefone, $senha_hash, $nome_arq]);
    
    header("Location: cadastro_gerente.php?sucesso=1");
    exit;
    
} catch (PDOException $e) {
    // Remove a imagem se foi feito upload mas ocorreu erro no banco
    if ($nome_arq && file_exists("img/gerentes/$nome_arq")) {
        unlink("img/gerentes/$nome_arq");
    }
    
    error_log("Erro ao cadastrar gerente: " . $e->getMessage());
    header("Location: cadastro_gerente.php?erro=banco");
    exit;
}
?>