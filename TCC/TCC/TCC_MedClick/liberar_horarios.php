    <?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login_medico.php");
    exit();
}

$nome_medico = $_SESSION['usuario'];
$especialidade = $_SESSION['especialidade'];
$imagem = $_SESSION['imagem'] ;
$medico_id = $_SESSION['medico_id'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liberar Horários - MedClick</title>
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
    
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--primary), #0d47a1);
        color: white;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        border: none;
    }
    
    .btn-close {
        filter: invert(1);
    }
    
    footer {
        background: white;
        padding: 20px 0;
        margin-top: 40px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
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
    
    .info-text {
        color: var(--gray);
        font-size: 0.95rem;
        margin-top: 10px;
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
                <h2 class="mb-1">Liberar Horários</h2>
                <p class="text-muted mb-0">Disponibilize horários para seus pacientes agendarem consultas</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="pagina_medico.php" class="btn btn-outline-primary-custom">
                    <i class="fas fa-arrow-left me-2"></i> Voltar ao Painel
                </a>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <h4 class="section-title">Liberar Novos Horários</h4>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card-icon mx-auto">
                    <i class="fas fa-calendar-plus"></i>
                </div>
            </div>
        </div>
        
        <form method="post" action="processa_liberar_horarios.php">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Data Inicial</label>
                    <input type="date" class="form-control" name="data_inicio" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Data Final</label>
                    <input type="date" class="form-control" name="data_fim" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hora Inicial</label>
                    <input type="time" class="form-control" name="hora_inicio" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hora Final</label>
                    <input type="time" class="form-control" name="hora_fim" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Intervalo entre consultas</label>
                    <select class="form-select" name="intervalo" required>
                        <option value="15">15 minutos</option>
                        <option value="30">30 minutos</option>
                        <option value="45">45 minutos</option>
                        <option value="60">1 hora</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="fas fa-unlock me-2"></i> Liberar Horários
                    </button>
                </div>
            </div>
            
            <p class="info-text">
                <i class="fas fa-info-circle me-2"></i> 
                O sistema irá gerar horários disponíveis para agendamento no período especificado, 
                considerando o intervalo selecionado entre as consultas.
            </p>
        </form>
    </div>
    
    <!-- Config Button -->
    <div class="text-center mt-4">
        <button type="button" class="btn btn-outline-primary-custom" data-bs-toggle="modal" data-bs-target="#horarioModal">
            <i class="fas fa-cog me-2"></i> Configurar Horário de Funcionamento
        </button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="horarioModal" tabindex="-1" aria-labelledby="horarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="horarioModalLabel">
                    <i class="fas fa-clock me-2"></i> Horário de Funcionamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="processa_horario_funcionamento.php">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card-icon mx-auto">
                                <i class="fas fa-business-time"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Atende 24 horas?</label>
                        <select class="form-select" name="atende_24h" id="atende_24h" onchange="toggleFields(this.value)">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    
                    <div id="campos_horario">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hora de Abertura</label>
                                <input type="time" class="form-control" name="hora_abertura" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hora de Fechamento</label>
                                <input type="time" class="form-control" name="hora_fechamento" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hora de Início do Almoço (Opcional)</label>
                                <input type="time" class="form-control" name="hora_almoco_inicio">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hora de Fim do Almoço (Opcional)</label>
                                <input type="time" class="form-control" name="hora_almoco_fim">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="button" class="btn btn-secondary me-md-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary-custom">Salvar Configuração</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleFields(val) {
    const camposHorario = document.getElementById('campos_horario');
    const inputs = camposHorario.querySelectorAll('input');
    
    if (val == 1) {
        camposHorario.style.display = 'none';
        inputs.forEach(input => input.removeAttribute('required'));
    } else {
        camposHorario.style.display = 'block';
        // Apenas os campos obrigatórios
        document.querySelector('input[name="hora_abertura"]').setAttribute('required', 'true');
        document.querySelector('input[name="hora_fechamento"]').setAttribute('required', 'true');
    }
}

// Inicializar estado dos campos
document.addEventListener('DOMContentLoaded', function() {
    toggleFields(document.getElementById('atende_24h').value);
});
</script>
</body>
<?php include"footer_medico.php" ?>
</html>     