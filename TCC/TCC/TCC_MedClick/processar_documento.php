<?php
session_start();
require dirname(__FILE__) . '/conexao.php';
require dirname(__FILE__) . '/utils_pdf.php';

if (!isset($_POST['tipo'], $_POST['paciente_id'], $_POST['conteudo'])) {
    die("Dados incompletos.");
}

$tipo = $_POST['tipo'];
$paciente_id = (int) $_POST['paciente_id'];
$conteudo_raw = $_POST['conteudo'];
$medico_id = $_SESSION['id'];

// busca paciente
$stmt = $pdo->prepare("SELECT nome FROM pacientes WHERE id = ?");
$stmt->execute([$paciente_id]);
$pac = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pac) die("Paciente não encontrado.");

// busca medico (com especialidade)
$stmt2 = $pdo->prepare("
    SELECT m.nome, m.crm, e.nome as especialidade 
    FROM medicos m 
    LEFT JOIN especialidades e ON m.especialidade_id = e.id 
    WHERE m.id = ?
");
$stmt2->execute([$medico_id]);
$med = $stmt2->fetch(PDO::FETCH_ASSOC);
if (!$med) die("Médico não encontrado.");

// Define título baseado no tipo
if ($tipo === 'atestado') {
    $titulo = 'Atestado Médico';
} elseif ($tipo === 'receita') {
    $titulo = 'Receita Médica';
} else {
    $titulo = 'Comprovante de Consulta';
}

// prepara dados
$data = array(
    'tipo' => $tipo,
    'paciente_id' => $paciente_id,
    'medico_id'   => $medico_id,
    'title'       => $titulo,
    'paciente'    => $pac['nome'],
    'medico'      => $med['nome'],
    'crm'         => $med['crm'],
    'especialidade' => isset($med['especialidade']) ? $med['especialidade'] : '',
    'data'        => date('d/m/Y'),
    'hora'        => date('H:i'),
    'content'     => $conteudo_raw,
    'timestamp'   => time()
);

$res = php_call_python_generate($data, 'python');

if (!$res['success']) {
    die("Erro ao gerar PDF: " . implode("\n", $res['error_output']));
}

if (isset($res['path']) && !empty($res['path'])) {
    $fileFullPath = $res['path'];
    $relative = str_replace('\\', '/', str_replace(dirname(__FILE__), '', $fileFullPath));
    $relative = ltrim($relative, '/\\');

    // salva no banco com mais informações
    $stmt3 = $pdo->prepare("
        INSERT INTO documentos (paciente_id, medico_id, tipo, titulo, arquivo, descricao) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt3->execute([
        $paciente_id, 
        $medico_id, 
        $tipo, 
        $titulo,
        $relative, 
        substr($conteudo_raw, 0, 200) // primeiros 200 caracteres como descrição
    ]);

    header("Location: consultas_medico.php?gerado=1");
    exit;
} else {
    error_log("Erro: Python não retornou caminho do PDF.");
    die("Erro ao gerar PDF: caminho não encontrado.");
}
?>