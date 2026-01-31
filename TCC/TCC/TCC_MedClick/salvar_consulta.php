<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['email'])) {
    header("Location: login1.php");
    exit();
}

// Verificar se todos os campos necessários foram enviados
$required_fields = ['paciente_id', 'agenda_id', 'especialidade_id', 'medico_id'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['erro_agendamento'] = "Por favor, preencha todos os campos obrigatórios.";
        header("Location: AgendarConsulta.php");
        exit();
    }
}

// Captura e validação dos dados
$paciente_id = (int)$_POST['paciente_id'];
$agenda_id = (int)$_POST['agenda_id'];
$especialidade_id = (int)$_POST['especialidade_id']; // Agora vem do campo hidden
$medico_id = (int)$_POST['medico_id'];



try {
    // Verifica se o horário ainda está disponível e pertence a um médico com essa especialidade
    $stmt = $pdo->prepare("
        SELECT a.medico_id, a.data, a.hora, m.especialidade_id
        FROM agenda a
        JOIN medicos m ON a.medico_id = m.id
        WHERE a.id = ? AND a.disponivel = 1
    ");
    $stmt->execute([$agenda_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Horário inválido ou já ocupado.");
    }

    // Valida se a especialidade do médico bate com a escolhida
    if ((int)$row['especialidade_id'] !== $especialidade_id) {
        throw new Exception("Especialidade não compatível com o médico.");
    }

    // Valida se o médico selecionado é o mesmo da agenda
    if ((int)$row['medico_id'] !== $medico_id) {
        throw new Exception("Médico selecionado não corresponde ao horário.");
    }

    $dataAgenda = $row['data'];
    $horaAgenda = $row['hora'];

    // Verifica se já existe consulta agendada neste mesmo horário
    $stmt_check = $pdo->prepare("
        SELECT COUNT(*) 
        FROM consultas 
        WHERE medico_id = ? 
        AND data = ? 
        AND hora = ? 
        AND status = 'Agendada'
    ");
    $stmt_check->execute([$medico_id, $dataAgenda, $horaAgenda]);
    
    if ($stmt_check->fetchColumn() > 0) {
        throw new Exception("Este horário já foi reservado por outro paciente.");
    }

    // Verifica se já existe consulta do paciente com essa especialidade nesse dia
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM consultas
        WHERE paciente_id = ? 
        AND especialidade_id = ? 
        AND data = ?
        AND status = 'Agendada'
    ");
    $stmt->execute([$paciente_id, $especialidade_id, $dataAgenda]);
    
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("Você já tem uma consulta agendada nesta especialidade para este dia.");
    }

    // Gera código único da consulta
    $codigo_confirmacao = strtoupper("CONF-" . substr(md5(uniqid(mt_rand(), true)), 0, 6));

    // Insere a consulta já com data e hora
    $sql = "INSERT INTO consultas (paciente_id, medico_id, especialidade_id, agenda_id, data, hora, status, codigo_confirmacao)
            VALUES (?, ?, ?, ?, ?, ?, 'Agendada', ?)";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([$paciente_id, $medico_id, $especialidade_id, $agenda_id, $dataAgenda, $horaAgenda, $codigo_confirmacao]);

    if (!$success) {
        throw new Exception("Erro ao salvar a consulta no banco de dados.");
    }

    // Atualiza a agenda como ocupada
    $stmt_update = $pdo->prepare("UPDATE agenda SET disponivel = 0 WHERE id = ?");
    $update_success = $stmt_update->execute([$agenda_id]);

    if (!$update_success) {
        // Se falhar ao atualizar a agenda, faz rollback da consulta
        $pdo->prepare("DELETE FROM consultas WHERE codigo_confirmacao = ?")->execute([$codigo_confirmacao]);
        throw new Exception("Erro ao atualizar a disponibilidade do horário.");
    }

    // Redireciona para a página de confirmação
    $_SESSION['sucesso_agendamento'] = "Consulta agendada com sucesso! Código: " . $codigo_confirmacao;
    header("Location: confirmar_consulta.php?codigo=" . urlencode($codigo_confirmacao));
    exit();

} catch (Exception $e) {
    $_SESSION['erro_agendamento'] = $e->getMessage();
    header("Location: AgendarConsulta.php");
    exit();
}
?>