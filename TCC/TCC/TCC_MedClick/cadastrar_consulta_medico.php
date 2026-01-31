<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login_medico.php");
    exit();
}

// Tenta pegar medico_id da sessão; se não existir, tenta recuperar pelo email ou nome e salva na sessão
$medico_id = isset($_SESSION['medico_id']) && $_SESSION['medico_id'] ? (int) $_SESSION['medico_id'] : null;

if (!$medico_id) {
    if (isset($_SESSION['email']) && $_SESSION['email']) {
        $stmt = $pdo->prepare("SELECT id FROM medicos WHERE email = ? LIMIT 1");
        $stmt->execute([$_SESSION['email']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $medico_id = (int)$row['id'];
            $_SESSION['medico_id'] = $medico_id;
        }
    }
}

if (!$medico_id && isset($_SESSION['usuario'])) {
    // Atenção: nomes podem repetir; é fallback apenas
    $stmt = $pdo->prepare("SELECT id FROM medicos WHERE nome = ? LIMIT 1");
    $stmt->execute([$_SESSION['usuario']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $medico_id = (int)$row['id'];
        $_SESSION['medico_id'] = $medico_id;
    }
}

if (!$medico_id) {
    echo "Erro: ID do médico não encontrado na sessão. Faça login novamente.";
    exit();
}

// Pega a especialidade do médico (prepared)
$stmt = $pdo->prepare("SELECT especialidade_id FROM medicos WHERE id = ?");
$stmt->execute([$medico_id]);
$especialidade_id = $stmt->fetchColumn();
if ($especialidade_id === false) $especialidade_id = null;

// Lista pacientes para o autocomplete
$pacientes = $pdo->query("SELECT id, nome, telefone FROM pacientes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

$nome_medico = $_SESSION['usuario'];
$imagem = $_SESSION['imagem'] ;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
<title>Cadastrar Consulta Manual - MedClick</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #1a73e8;
        --primary-light: #e8f0fe;
        --secondary: #34a853;
        --warning: #f9ab00;
        --danger: #ea4335;
        --dark: #202124;
        --light: #f8f9fa;
        --gray: #5f6368;
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
        color: var(--dark);
    }
    
    .navbar-custom {
        background: linear-gradient(135deg, var(--primary), #0d47a1);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }
    
    .profile-img {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .header-content {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .form-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .section-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 25px;
        font-weight: 600;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--primary);
        border-radius: 3px;
    }
    
    .form-label {
        font-weight: 500;
        color: var(--dark);
        margin-bottom: 8px;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #dadce0;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary), #0d47a1);
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
        transition: all 0.3s;
        color: white;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(26, 115, 232, 0.4);
        color: white;
    }
    
    .btn-outline-primary-custom {
        background: transparent;
        border: 1px solid var(--primary);
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 500;
        color: var(--primary);
        transition: all 0.3s;
    }
    
    .btn-outline-primary-custom:hover {
        background: var(--primary);
        color: white;
    }
    
    .alert-success {
        background: linear-gradient(135deg, var(--secondary), #0f9d58);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .pacientes-list {
        background: white;
        border: 1px solid #dadce0;
        border-radius: 8px;
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .paciente-item {
        padding: 12px 15px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .paciente-item:hover {
        background-color: var(--primary-light);
    }
    
    .paciente-item:last-child {
        border-bottom: none;
    }
    
    .paciente-telefone {
        font-size: 0.85rem;
        color: var(--gray);
        margin-top: 3px;
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    footer {
        background: white;
        padding: 20px 0;
        margin-top: 40px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .info-text {
        color: var(--gray);
        font-size: 0.95rem;
        margin-top: 10px;
    }
    
    .form-group {
        position: relative;
        margin-bottom: 20px;
    }
    
    .telefone-mask {
        position: relative;
    }
    
    .telefone-mask::before {
        content: "(00) 00000-0000";
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #aaa;
        pointer-events: none;
        z-index: 1;
        display: none;
    }
    
    .telefone-mask input {
        position: relative;
        z-index: 2;
        background: transparent;
    }
    
    .telefone-mask input:focus::placeholder {
        color: transparent;
    }
</style>
</head>
<body>

<?php include 'header_medico.php'; ?>

<div class="container py-4">
    <!-- Header Content -->
    <div class="header-content">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-1">Cadastrar Consulta Manual</h2>
                <p class="text-muted mb-0">Cadastre uma consulta manualmente para um paciente</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="painel_medico.php" class="btn btn-outline-primary-custom">
                    <i class="fas fa-arrow-left me-2"></i> Voltar ao Painel
                </a>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <h4 class="section-title">Dados da Consulta</h4>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card-icon mx-auto">
                    <i class="fas fa-calendar-plus"></i>
                </div>
            </div>
        </div>
        
        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle me-2"></i> Consulta cadastrada com sucesso.
            </div>
        <?php endif; ?>

        <form method="post" action="processa_cadastrar_consulta.php">
            <div class="form-group">
                <label class="form-label">Buscar Paciente Cadastrado (opcional)</label>
                <input type="text" class="form-control" id="buscarPaciente" onkeyup="buscarPacientes(this.value)" 
                       placeholder="Digite pelo menos 2 caracteres para buscar...">
                <div id="listaPacientes" class="pacientes-list" style="display: none;"></div>
                <p class="info-text">
                    <i class="fas fa-info-circle me-2"></i> 
                    Busque por pacientes já cadastrados no sistema. Se não encontrar, preencha os campos abaixo.
                </p>
            </div>

            <input type="hidden" name="paciente_id" id="paciente_id">

            <div class="form-group">
                <label class="form-label">Nome do Paciente <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nome_paciente" id="nome_paciente" 
                       placeholder="Nome completo do paciente" required>
            </div>

            <div class="form-group">
                <label class="form-label">Telefone do Paciente <span class="text-danger">*</span></label>
                <div class="telefone-mask">
                    <input type="text" class="form-control" name="telefone_paciente" id="telefone_paciente" 
                           placeholder="(00) 00000-0000" required maxlength="15"
                           oninput="formatarTelefone(this)">
                </div>
                <p class="info-text">
                    <i class="fas fa-info-circle me-2"></i> 
                    Digite o telefone com DDD. Será usado para confirmar a consulta.
                </p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Data da Consulta <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="data" required min="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Hora da Consulta <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="hora" required>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="fas fa-save me-2"></i> Cadastrar Consulta
                </button>
            </div>
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function buscarPacientes(nome) {
    if (nome.length < 2) {
        document.getElementById("listaPacientes").style.display = "none";
        document.getElementById("listaPacientes").innerHTML = "";
        return;
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "buscar_pacientes.php?nome=" + encodeURIComponent(nome), true);
    xhr.onload = function() {
        if (this.status == 200) {
            document.getElementById("listaPacientes").innerHTML = this.responseText;
            if (this.responseText.trim() !== "") {
                document.getElementById("listaPacientes").style.display = "block";
            } else {
                document.getElementById("listaPacientes").style.display = "none";
            }
        }
    };
    xhr.send();
}

function selecionarPaciente(id, nome, telefone) {
    document.getElementById("paciente_id").value = id;
    document.getElementById("nome_paciente").value = nome;
    
    // Preenche o telefone se estiver disponível
    if (telefone && telefone !== 'null' && telefone !== '') {
        document.getElementById("telefone_paciente").value = formatarTelefoneParaInput(telefone);
    }
    
    document.getElementById("listaPacientes").style.display = "none";
    document.getElementById("listaPacientes").innerHTML = "";
}

// Fechar a lista de pacientes ao clicar fora
document.addEventListener('click', function(e) {
    const listaPacientes = document.getElementById('listaPacientes');
    const buscarPaciente = document.getElementById('buscarPaciente');
    
    if (listaPacientes.style.display === 'block' && 
        e.target !== buscarPaciente && 
        !listaPacientes.contains(e.target)) {
        listaPacientes.style.display = 'none';
    }
});

// Formatar telefone enquanto digita
function formatarTelefone(input) {
    // Remove tudo que não é número
    let value = input.value.replace(/\D/g, '');
    
    // Aplica a máscara
    if (value.length <= 10) {
        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    } else {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }
    
    input.value = value;
}

// Formatar telefone para o campo de input
function formatarTelefoneParaInput(telefone) {
    // Remove tudo que não é número
    let value = telefone.replace(/\D/g, '');
    
    // Aplica a máscara
    if (value.length <= 10) {
        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    } else {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }
    
    return value;
}

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const telefone = document.getElementById('telefone_paciente').value.replace(/\D/g, '');
    
    if (telefone.length < 10 || telefone.length > 11) {
        e.preventDefault();
        alert('Por favor, informe um telefone válido com DDD.');
        document.getElementById('telefone_paciente').focus();
    }
});
</script>
</body>
<?php include"footer_medico.php" ?>
</html>