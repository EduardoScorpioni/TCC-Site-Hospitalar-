
<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['crm'])) {
    header("Location: login_medico.php");
    exit;
}

$id_medico = $_SESSION['id'];
$nome_medico = $_SESSION['usuario'];
$especialidade = $_SESSION['especialidade'];
$imagem = $_SESSION['imagem'];
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda do Médico - MedClick</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
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
        
        .calendar-container {
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
        
        /* Estilização do FullCalendar */
        .fc {
            font-family: 'Poppins', sans-serif;
        }
        
        .fc-toolbar-title {
            font-weight: 600;
            color: var(--dark);
        }
        
        .fc-button {
            background-color: var(--primary) !important;
            border: none !important;
            border-radius: 6px !important;
            transition: all 0.3s;
        }
        
        .fc-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .fc-button-primary:not(:disabled).fc-button-active {
            background-color: var(--primary) !important;
            opacity: 0.8;
        }
        
        .fc-col-header-cell {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 500;
            padding: 10px 0;
        }
        
        .fc-daygrid-day-number {
            color: var(--dark);
            font-weight: 500;
        }
        
        .fc-day-today {
            background-color: var(--primary-light) !important;
        }
        
        .fc-event {
            border-radius: 6px;
            border: none;
            padding: 3px 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .fc-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .event-agendada {
            background: linear-gradient(135deg, var(--warning), #f57c00);
            color: white;
        }
        
        .event-realizada {
            background: linear-gradient(135deg, var(--secondary), #0f9d58);
            color: white;
        }
        
        .event-cancelada {
            background: linear-gradient(135deg, var(--danger), #d93025);
            color: white;
        }
        
        .event-adiada {
            background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
            color: white;
        }
        
        .fc-daygrid-event-dot {
            display: none;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), #0d47a1);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
            transition: all 0.3s;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(26, 115, 232, 0.4);
        }
        
        footer {
            background: white;
            padding: 20px 0;
            margin-top: 40px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .legend-item {
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
            font-size: 0.9rem;
        }
        
        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            margin-right: 5px;
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
                <h2 class="mb-1">Agenda Médica</h2>
                <p class="text-muted mb-0">Visualize e gerencie suas consultas agendadas</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="pagina_medico.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Voltar ao Painel
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-container">
        <h4 class="section-title">Calendário de Consultas</h4>
        
        <!-- Legend -->
        <div class="mb-4">
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(135deg, var(--warning), #f57c00);"></div>
                <span>Agendada</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(135deg, var(--secondary), #0f9d58);"></div>
                <span>Realizada</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(135deg, var(--danger), #d93025);"></div>
                <span>Cancelada</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);"></div>
                <span>Adiada</span>
            </div>
        </div>
        
        <!-- Calendar -->
        <div id='calendar'></div>
    </div>
</div>

<!-- Modal para detalhes da consulta -->
<div class="modal fade" id="consultaModal" tabindex="-1" aria-labelledby="consultaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consultaModalLabel">Detalhes da Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informações da Consulta</h6>
                        <p><strong>Status:</strong> <span id="consulta-status"></span></p>
                        <p><strong>Data:</strong> <span id="consulta-data"></span></p>
                        <p><strong>Hora:</strong> <span id="consulta-hora"></span></p>
                        <p><strong>Especialidade:</strong> <span id="consulta-especialidade"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Dados do Paciente</h6>
                        <p><strong>Nome:</strong> <span id="paciente-nome"></span></p>
                        <p><strong>CPF:</strong> <span id="paciente-cpf"></span></p>
                        <p><strong>Telefone:</strong> <span id="paciente-telefone"></span></p>
                        <p><strong>Email:</strong> <span id="paciente-email"></span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Endereço</h6>
                        <p id="paciente-endereco"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-warning text-white" id="btn-adiar">Adiar Consulta</button>
                <button type="button" class="btn btn-danger text-white" id="btn-cancelar">Cancelar Consulta</button>
                <button type="button" class="btn btn-success text-white" id="btn-realizada">Marcar como Realizada</button>
            </div>
        </div>
    </div>
</div>



<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var consultaModal = new bootstrap.Modal(document.getElementById('consultaModal'));
    var consultaId;
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        locale: 'pt-br',
        firstDay: 0, // Domingo
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia',
            list: 'Lista'
        },
        events: {
            url: 'carregar_eventos.php',
            method: 'GET',
            failure: function() {
                alert('Erro ao carregar consultas!');
            }
        },
        eventDidMount: function(info) {
            // Adicionar classes baseadas no status
            const status = info.event.title.toLowerCase();
            if (status.includes('agendada')) {
                info.el.classList.add('event-agendada');
            } else if (status.includes('realizada')) {
                info.el.classList.add('event-realizada');
            } else if (status.includes('cancelada')) {
                info.el.classList.add('event-cancelada');
            } else if (status.includes('adiada')) {
                info.el.classList.add('event-adiada');
            }
        },
        eventClick: function(info) {
            // Obter ID da consulta do evento
            consultaId = info.event.id;
            
            // Buscar detalhes da consulta
            fetch('obter_detalhes_consulta.php?id=' + consultaId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Preencher modal com os dados
                        document.getElementById('consulta-status').textContent = data.consulta.status;
                        document.getElementById('consulta-data').textContent = data.consulta.data;
                        document.getElementById('consulta-hora').textContent = data.consulta.hora;
                        document.getElementById('consulta-especialidade').textContent = data.consulta.especialidade;
                        
                        document.getElementById('paciente-nome').textContent = data.paciente.nome;
                        document.getElementById('paciente-cpf').textContent = data.paciente.cpf;
                        document.getElementById('paciente-telefone').textContent = data.paciente.telefone;
                        document.getElementById('paciente-email').textContent = data.paciente.email;
                        document.getElementById('paciente-endereco').textContent = data.paciente.endereco;
                        
                        // Mostrar/ocultar botões com base no status
                        const status = data.consulta.status.toLowerCase();
                        document.getElementById('btn-adiar').style.display = (status !== 'cancelada' && status !== 'realizada') ? 'block' : 'none';
                        document.getElementById('btn-cancelar').style.display = (status !== 'cancelada' && status !== 'realizada') ? 'block' : 'none';
                        document.getElementById('btn-realizada').style.display = (status !== 'realizada' && status !== 'cancelada') ? 'block' : 'none';
                        
                        // Exibir modal
                        consultaModal.show();
                    } else {
                        alert('Erro ao carregar detalhes da consulta: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao carregar detalhes da consulta.');
                });
        }
    });
    
    calendar.render();
    
    // Evento para adiar consulta
    document.getElementById('btn-adiar').addEventListener('click', function() {
        alert('Funcionalidade de adiamento será implementada em breve.');
    });
    
    // Cancelar consulta
    document.getElementById('btn-cancelar').addEventListener('click', function() {
        if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
            fetch('cancelar_consulta.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: consultaId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Consulta cancelada com sucesso!');
                    consultaModal.hide();
                    calendar.refetchEvents();
                } else {
                    alert('Erro ao cancelar consulta: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao cancelar consulta.');
            });
        }
    });
    
    // Marcar como realizada
    document.getElementById('btn-realizada').addEventListener('click', function() {
        if (confirm('Marcar esta consulta como realizada?')) {
            fetch('marcar_realizada.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: consultaId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Consulta marcada como realizada!');
                    consultaModal.hide();
                    calendar.refetchEvents();
                } else {
                    alert('Erro ao marcar consulta como realizada: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao marcar consulta como realizada.');
            });
        }
    });
});
</script>
</body>
<?php include"footer_medico.php" ?>
</html>