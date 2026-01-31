<?php
session_start();
require 'conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['email'])) {
    header("Location: login1.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Documento não especificado.");
}

$docId = (int) $_GET['id'];

// Busca paciente logado
$stmt = $pdo->prepare("SELECT id FROM pacientes WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$pac = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pac) die("Paciente não encontrado.");
$idPac = $pac['id'];

// Verifica se o documento pertence ao paciente
$stmt = $pdo->prepare("SELECT arquivo, tipo FROM documentos WHERE id = ? AND paciente_id = ?");
$stmt->execute([$docId, $idPac]);
$doc = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doc) {
    die("Documento não encontrado ou você não tem permissão para acessá-lo.");
}

// Caminho absoluto do arquivo
$filePath = __DIR__ . '/' . ltrim($doc['arquivo'], '/\\');

// Debug opcional: loga no erro_log do PHP para verificar caminho real
 error_log("Tentando baixar: " . $filePath);

if (!file_exists($filePath)) {
    die("Arquivo não encontrado no servidor.");
}

// Força o download do PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: private');
header('Pragma: public');

// Limpa qualquer saída anterior
ob_clean();
flush();

// Lê o arquivo e envia
readfile($filePath);
exit;
