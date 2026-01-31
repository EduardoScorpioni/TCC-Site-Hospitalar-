<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['crm'])) {
    header("Location: login_medico.php");
    exit;
}

$id_medico = $_SESSION['id'];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $permitidos = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array(strtolower($extensao), $permitidos)) {
        $novo_nome = uniqid() . "." . $extensao;
        $pasta = "uploads/";

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $pasta . $novo_nome)) {
            $stmt = $pdo->prepare("UPDATE medicos SET imagem = ? WHERE id = ?");
            $stmt->execute([$novo_nome, $id_medico]);
            $_SESSION['imagem'] = $novo_nome;
            header("Location: pagina_medico.php?upload=ok");
            exit;
        }
    }
}

header("Location: pagina_medico.php?upload=erro");
exit;
?>
