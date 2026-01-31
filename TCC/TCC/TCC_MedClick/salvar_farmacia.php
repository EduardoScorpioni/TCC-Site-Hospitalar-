<?php
session_start();
require 'conexao.php';

$nome       = trim($_POST['nome']);
$endereco   = trim($_POST['endereco']);
$cidade     = trim($_POST['cidade']);
$estado     = trim($_POST['estado']);
$telefone   = trim($_POST['telefone']);

// Se for marcada como 24h, define abertura e fechamento fixos
$farmacia24h = isset($_POST['farmacia_24h']) ? 1 : 0;

if ($farmacia24h) {
    $abertura   = "00:00:00";
    $fechamento = "23:59:59";
} else {
    $abertura   = $_POST['abertura'];
    $fechamento = $_POST['fechamento'];
}

// Upload de imagem (opcional)
$nome_arq = null;
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $nome_arq = uniqid("farmacia_", true) . "." . $ext;

    // cria pasta se não existir
    if (!is_dir("img/farmacias")) {
        mkdir("img/farmacias", 0777, true);
    }

    move_uploaded_file($_FILES['imagem']['tmp_name'], "img/farmacias/$nome_arq");
}

// Inserção no banco
$sql = "INSERT INTO farmacias 
        (nome, endereco, cidade, estado, telefone, abertura, fechamento, is_24h, imagem) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $nome,
    $endereco,
    $cidade,
    $estado,
    $telefone,
    $abertura,
    $fechamento,
    $farmacia24h,
    $nome_arq // pode ser null se não tiver imagem
]);

header("Location: farmacias.php?sucesso=1");
exit;
?>
