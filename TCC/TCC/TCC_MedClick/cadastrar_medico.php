<?php
session_start();
require 'conexao.php';

$nome = trim($_POST['nome']);
if (stripos($nome, 'dr ') !== 0 && stripos($nome, 'dra ') !== 0) {
    $prefix = strtolower(substr($nome,0,1)) === 'f' ? 'Dra. ' : 'Dr. ';
    $nome = $prefix . $nome;
}

$crm = $_POST['crm'];
$especialidade_id = (int)$_POST['especialidade_id'];
$local_consulta_id = isset($_POST['local_consulta_id']) && $_POST['local_consulta_id'] !== '' ? (int)$_POST['local_consulta_id'] : null;
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// Imagem em base64
$img = $_POST['imagem_base64'];
if ($img) {
    list(, $data) = explode(',', $img);
    $data = base64_decode($data);
    $nome_arq = uniqid() . '.jpg';
    file_put_contents("img/$nome_arq", $data);
} else {
    $nome_arq = null;
}

// Validação CRM único
$stmt = $pdo->prepare("SELECT id FROM medicos WHERE crm = ?");
$stmt->execute([$crm]);
if ($stmt->fetch()) die("CRM já cadastrado.");

// Inserção incluindo local_consulta_id
$sql = "INSERT INTO medicos (nome, crm, especialidade_id, imagem, senha, local_consulta_id) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nome, $crm, $especialidade_id, $nome_arq, $senha, $local_consulta_id]);

// Pega o ID do médico recém-cadastrado
$medico_id = $pdo->lastInsertId();

// Configura sessão para logar automaticamente
$_SESSION['usuario'] = $nome;
$_SESSION['imagem'] = $nome_arq ?: 'default.jpg';
$_SESSION['tipo'] = 'medico';
$_SESSION['medico_id'] = $medico_id;

// Redireciona para a página do médico
header("Location: pagina_medico.php");
exit;
