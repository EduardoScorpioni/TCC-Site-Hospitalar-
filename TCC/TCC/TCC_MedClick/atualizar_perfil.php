<?php
session_start();
require 'conexao.php'; // Arquivo com a conexão PDO

$id = $_SESSION['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$imagem = $_SESSION['imagem'];

$imagem_base64 = $_POST['imagem_base64'];

if ($imagem_base64) {
    $img_data = str_replace('data:image/jpeg;base64,', '', $imagem_base64);
    $img_data = base64_decode($img_data);
    $novo_nome_arquivo = uniqid() . ".jpg";
    file_put_contents("img/" . $novo_nome_arquivo, $img_data);

    $_SESSION['imagem'] = $novo_nome_arquivo;

    $sql = "UPDATE pacientes SET nome = ?, email = ?, telefone = ?, imagem = ? WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $telefone, $novo_nome_arquivo, $email_sessao]);
}


// Verifica se enviou arquivo
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $novoNome = uniqid() . "." . $ext;
    $destino = "img/" . $novoNome;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
        $imagem = $novoNome; 
    }
}

// Atualiza no banco
$sql = "UPDATE pacientes SET nome = ?, email = ?, telefone = ?, imagem = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nome, $email, $telefone, $imagem, $id]);

// Atualiza sessão
$_SESSION['usuario'] = $nome;
$_SESSION['email'] = $email;
$_SESSION['telefone'] = $telefone;
$_SESSION['imagem'] = $imagem;

// Redireciona
header("Location: perfil.php");
exit;
?>
