<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Med Click - Página</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome e estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Cabeçalho com login -->
    <?php include 'header.php'; ?>

    <!-- Conteúdo da página -->
    <main style="padding: 40px; min-height: 70vh;">
        <h1>Título da Página</h1>
        <p>Aqui vai o conteúdo principal desta página.</p>
    </main>

    <!-- Rodapé -->
    <?php include 'footer.php'; ?>

</body>
</html>
