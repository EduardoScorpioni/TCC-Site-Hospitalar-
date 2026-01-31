<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'gerente') {
    header("Location: login1.php?erro=acesso");
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro_horario.php?erro=metodo");
    exit;
}

// Coleta e sanitiza os dados
$medico_id = filter_input(INPUT_POST, 'medico_id', FILTER_VALIDATE_INT);
$hora_abertura = trim($_POST['hora_abertura']);
$hora_fechamento = trim($_POST['hora_fechamento']);
$hora_almoco_inicio = !empty($_POST['hora_almoco_inicio']) ? trim($_POST['hora_almoco_inicio']) : null;
$hora_almoco_fim = !empty($_POST['hora_almoco_fim']) ? trim($_POST['hora_almoco_fim']) : null;
$atende_24h = isset($_POST['atende_24h']) ? 1 : 0;

// Validações
if (!$medico_id) {
    header("Location: cadastro_horario.php?erro=medico_invalido");
    exit;
}

// Se atende 24h, ajusta os horários
if ($atende_24h) {
    $hora_abertura = '00:00:00';
    $hora_fechamento = '23:59:59';
    $hora_almoco_inicio = null;
    $hora_almoco_fim = null;
} else {
    // Valida horários normais
    if (empty($hora_abertura) || empty($hora_fechamento)) {
        header("Location: cadastro_horario.php?erro=horarios_vazios");
        exit;
    }
    
    if ($hora_abertura >= $hora_fechamento) {
        header("Location: cadastro_horario.php?erro=horarios_invalidos");
        exit;
    }
    
    // Valida horário de almoço
    if (($hora_almoco_inicio && !$hora_almoco_fim) || (!$hora_almoco_inicio && $hora_almoco_fim)) {
        header("Location: cadastro_horario.php?erro=almoco_incompleto");
        exit;
    }
    
    if ($hora_almoco_inicio && $hora_almoco_fim && $hora_almoco_inicio >= $hora_almoco_fim) {
        header("Location: cadastro_horario.php?erro=almoco_invalido");
        exit;
    }
}

// Verifica se já existe horário para este médico
try {
    $stmt_check = $pdo->prepare("SELECT id FROM horarios_funcionamento WHERE medico_id = ?");
    $stmt_check->execute([$medico_id]);
    $existing = $stmt_check->fetch();
    
    if ($existing) {
        // Atualiza horário existente
        $sql = "UPDATE horarios_funcionamento 
                SET hora_abertura = ?, hora_fechamento = ?, 
                    hora_almoco_inicio = ?, hora_almoco_fim = ?, 
                    atende_24h = ?
                WHERE medico_id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $hora_abertura, 
            $hora_fechamento, 
            $hora_almoco_inicio, 
            $hora_almoco_fim, 
            $atende_24h, 
            $medico_id
        ]);
        
        $message = "horario_atualizado";
    } else {
        // Insere novo horário
        $sql = "INSERT INTO horarios_funcionamento 
                (medico_id, hora_abertura, hora_fechamento, 
                 hora_almoco_inicio, hora_almoco_fim, atende_24h) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $medico_id, 
            $hora_abertura, 
            $hora_fechamento, 
            $hora_almoco_inicio, 
            $hora_almoco_fim, 
            $atende_24h
        ]);
        
        $message = "horario_cadastrado";
    }
    
    header("Location: painel_gerente.php?sucesso=$message");
    exit;
    
} catch (PDOException $e) {
    error_log("Erro ao salvar horário: " . $e->getMessage());
    header("Location: cadastro_horario.php?erro=banco");
    exit;
}
?>