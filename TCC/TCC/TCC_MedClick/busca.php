<?php
session_start();
require "conexao.php"; // ajuste se necessário

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resultados da Pesquisa</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { margin-bottom: 20px; }
        .resultado { border-bottom: 1px solid #ddd; padding: 15px 0; }
        .resultado a { font-weight: bold; color: #007BFF; text-decoration: none; }
        .resultado a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Resultados para: <em><?php echo htmlspecialchars($q); ?></em></h2>
    <hr>

    <?php
    if ($q !== '') {
        // Exemplo: busca em tabela "produtos"
        $sql = "SELECT id, nome, descricao FROM produtos 
                WHERE nome LIKE ? OR descricao LIKE ? 
                LIMIT 20";
        $stmt = $conn->prepare($sql);
        $like = "%$q%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='resultado'>";
                echo "<a href='produto.php?id=".$row['id']."'>".htmlspecialchars($row['nome'])."</a>";
                echo "<p>".htmlspecialchars(substr($row['descricao'],0,150))."...</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum resultado encontrado.</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Digite algo para pesquisar.</p>";
    }
    $conn->close();
    ?>
</body>
</html>
