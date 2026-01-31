<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login_medico.php");
    exit();
}

$medico_id = $_SESSION['medico_id'];

$data_inicio = $_POST['data_inicio'];
$data_fim = $_POST['data_fim'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fim = $_POST['hora_fim'];
$intervalo = (int)$_POST['intervalo'];

$dataAtual = new DateTime($data_inicio);
$dataFinal = new DateTime($data_fim);

while ($dataAtual <= $dataFinal) {
    $horaAtual = new DateTime($hora_inicio);
    $horaLimite = new DateTime($hora_fim);

    while ($horaAtual < $horaLimite) {
        $dataStr = $dataAtual->format('Y-m-d');
        $horaStr = $horaAtual->format('H:i:s');

        // Verifica se já existe
        $check = $pdo->prepare("SELECT id FROM agenda WHERE data = ? AND hora = ? AND medico_id = ?");
        $check->execute([$dataStr, $horaStr, $medico_id]);

        if ($check->rowCount() == 0) {
            $stmt = $pdo->prepare("INSERT INTO agenda (data, hora, medico_id, disponivel) VALUES (?, ?, ?, 1)");
            $stmt->execute([$dataStr, $horaStr, $medico_id]);
        }

        $horaAtual->modify("+{$intervalo} minutes");
    }

    $dataAtual->modify('+1 day');
    // Pega configuração do médico
$config = $pdo->prepare("SELECT * FROM horarios_funcionamento WHERE medico_id=?");
$config->execute([$medico_id]);
$horario = $config->fetch(PDO::FETCH_ASSOC);

if ($horario) {
    $abertura = new DateTime($horario['hora_abertura']);
    $fechamento = new DateTime($horario['hora_fechamento']);
    $almocoInicio = $horario['hora_almoco_inicio'] ? new DateTime($horario['hora_almoco_inicio']) : null;
    $almocoFim = $horario['hora_almoco_fim'] ? new DateTime($horario['hora_almoco_fim']) : null;
}

// dentro do while de gerar horários:
while ($horaAtual < $horaLimite) {
    if ($horario && $horario['atende_24h'] == 0) {
        if ($horaAtual < $abertura || $horaAtual >= $fechamento) {
            $horaAtual->modify("+{$intervalo} minutes");
            continue;
        }
        if ($almocoInicio && $almocoFim && $horaAtual >= $almocoInicio && $horaAtual < $almocoFim) {
            $horaAtual->modify("+{$intervalo} minutes");
            continue;
        }
    }

    // aqui faz o insert como já estava
}

}

header("Location: liberar_horarios.php?sucesso=1");
exit();
