<?php
require 'conexao.php';

if (isset($_GET['nome'])) {
    $nome = $_GET['nome'];
    $stmt = $pdo->prepare("SELECT id, nome, telefone FROM pacientes WHERE nome LIKE ? ORDER BY nome LIMIT 10");
    $stmt->execute(["%$nome%"]);
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($pacientes) > 0) {
        foreach ($pacientes as $p) {
            $telefoneFormatado = '';
            if (!empty($p['telefone'])) {
                $telefone = $p['telefone'];
                if (strlen($telefone) <= 10) {
                    $telefoneFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
                } else {
                    $telefoneFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
                }
            }
            
            echo "<div class='paciente-item' onclick='selecionarPaciente(" . $p['id'] . ", \"" . htmlspecialchars($p['nome']) . "\", \"" . htmlspecialchars($p['telefone']) . "\")'>";
            echo "<div><i class='fas fa-user me-2'></i> " . htmlspecialchars($p['nome']) . "</div>";
            if (!empty($telefoneFormatado)) {
                echo "<div class='paciente-telefone'><i class='fas fa-phone me-1'></i> " . $telefoneFormatado . "</div>";
            } else {
                echo "<div class='paciente-telefone text-muted'><i class='fas fa-phone me-1'></i> Telefone não cadastrado</div>";
            }
            echo "</div>";
        }
    } else {
        echo "<div class='paciente-item text-muted'>";
        echo "<i class='fas fa-search me-2'></i> Nenhum paciente encontrado";
        echo "</div>";
    }
}
?>