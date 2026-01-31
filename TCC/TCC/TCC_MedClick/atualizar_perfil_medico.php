<?php
session_start();
require 'conexao.php'; // conexão PDO

if (!isset($_SESSION['id'])) {
    header("Location: login_medico.php");
    exit();
}

$id = $_SESSION['id'];

$nome     = isset($_POST['nome']) ? $_POST['nome'] : '';
$email    = isset($_POST['email']) ? $_POST['email'] : '';
$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
$crm      = isset($_POST['crm']) ? $_POST['crm'] : '';
$senha    = isset($_POST['senha']) ? $_POST['senha'] : '';

$imagem = isset($_SESSION['imagem']) ? $_SESSION['imagem'] : null;


// 1) Caso envie imagem em base64 (opcional - mantive compatibilidade)
//    Se você não usa isso, não tem problema deixar aqui.
if (!empty($_POST['imagem_base64'])) {
    $imagem_base64 = $_POST['imagem_base64'];
    // tenta detectar e remover o prefixo (data:image/...)
    if (preg_match('/^data:image\/(\w+);base64,/', $imagem_base64, $type)) {
        $imagem_base64 = substr($imagem_base64, strpos($imagem_base64, ',') + 1);
        $ext = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);
    } else {
        // se não veio prefixo, assume jpg
        $ext = 'jpg';
    }
    $img_data = base64_decode($imagem_base64);
    if ($img_data !== false) {
        $novo_nome_arquivo = uniqid() . "." . $ext;
        $caminho = __DIR__ . '/uploads/' . $novo_nome_arquivo;
        if (file_put_contents($caminho, $img_data) !== false) {
            $imagem = $novo_nome_arquivo;
        }
    }
}

// 2) Caso envie arquivo tradicional via multipart/form-data
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $novoNome = uniqid() . "." . strtolower($ext);
    $destino = __DIR__ . "/uploads/" . $novoNome;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
        $imagem = $novoNome;
    }
}

// 3) Atualiza no banco (apenas uma vez)
if (!empty($senha)) {
    $hash = password_hash($senha, PASSWORD_BCRYPT);
    $sql = "UPDATE medicos 
            SET nome = ?, email = ?, telefone = ?, crm = ?, imagem = ?, senha = ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $telefone, $crm, $imagem, $hash, $id]);
} else {
    $sql = "UPDATE medicos 
            SET nome = ?, email = ?, telefone = ?, crm = ?, imagem = ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $telefone, $crm, $imagem, $id]);
}

// 4) Atualiza sessão para refletir imediatamente as mudanças
$_SESSION['usuario']  = $nome;
$_SESSION['email']    = $email;
$_SESSION['telefone'] = $telefone;
$_SESSION['crm']      = $crm;
$_SESSION['imagem']   = $imagem;

// Redireciona de volta ao perfil
header("Location: perfil_medico.php");
exit();
?>
