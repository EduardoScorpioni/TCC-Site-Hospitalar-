<?php
session_start();

// Redirecionar para login se não estiver logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login1.php");
    exit;
}

// Dados de exemplo para eventos de prevenção
$eventos = [
    [
        'data' => date('Y-m-d'),
        'titulo' => 'Dia de Vacinação contra Gripe',
        'tipo' => 'vacina',
        'local' => 'UBS Centro',
        'horario' => '08:00 - 17:00',
        'prioridade' => 'alta'
    ],
    [
        'data' => date('Y-m-d', strtotime('+3 days')),
        'titulo' => 'Campanha de Prevenção ao Diabetes',
        'tipo' => 'campanha',
        'local' => 'Praça Central',
        'horario' => '09:00 - 16:00',
        'prioridade' => 'media'
    ],
    [
        'data' => date('Y-m-d', strtotime('+7 days')),
        'titulo' => 'Exames de Pressão Arterial Gratuitos',
        'tipo' => 'exame',
        'local' => 'Shopping Prudenshopping',
        'horario' => '10:00 - 18:00',
        'prioridade' => 'media'
    ],
    [
        'data' => date('Y-m-d', strtotime('+10 days')),
        'titulo' => 'Vacinação Infantil - Meningite',
        'tipo' => 'vacina',
        'local' => 'UBS Vila Maré',
        'horario' => '08:00 - 16:00',
        'prioridade' => 'alta'
    ],
    [
        'data' => date('Y-m-d', strtotime('+15 days')),
        'titulo' => 'Alerta: Surto de Dengue na Região',
        'tipo' => 'alerta',
        'local' => 'Zona Norte',
        'horario' => 'Prevenção Contínua',
        'prioridade' => 'urgente'
    ],
    [
        'data' => date('Y-m-d', strtotime('+20 days')),
        'titulo' => 'Palestra sobre Saúde Mental',
        'tipo' => 'palestra',
        'local' => 'Centro Comunitário',
        'horario' => '19:00 - 21:00',
        'prioridade' => 'baixa'
    ]
];

// Mês e ano atual
$mesAtual = date('n');
$anoAtual = date('Y');

// Função para gerar o calendário
function gerarCalendario($mes, $ano, $eventos) {
    $primeiroDia = mktime(0, 0, 0, $mes, 1, $ano);
    $numeroDias = date('t', $primeiroDia);
    $diaInicial = date('w', $primeiroDia);
    
    // Ajuste para domingo ser o primeiro dia (0 -> 7)
    if ($diaInicial == 0) $diaInicial = 7;
    
    $calendario = [];
    $dia = 1;
    
    for ($i = 1; $i <= 6; $i++) {
        $semana = [];
        for ($j = 1; $j <= 7; $j++) {
            if (($i == 1 && $j < $diaInicial) || $dia > $numeroDias) {
                $semana[] = null;
            } else {
                $data = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
                $eventosDia = array_filter($eventos, function($evento) use ($data) {
                    return $evento['data'] == $data;
                });
                
                $semana[] = [
                    'dia' => $dia,
                    'eventos' => array_values($eventosDia)
                ];
                $dia++;
            }
        }
        $calendario[] = $semana;
    }
    
    return $calendario;
}

$calendario = gerarCalendario($mesAtual, $anoAtual, $eventos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Prevenção - MedClick</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <style>
        /* ===== VARIÁVEIS DE CORES ===== */
        :root {
            --teal: #00838fff;
            --caribbean-current: #1b767eff;
            --garnet: #713838ff;
            --kelly-green: #74af32ff;
            --yellow-green: #92e336ff;
            --mindaro: #c7f198ff;
            --white: #ffffffff;
            --slate-blue: #705dbcff;
            --russian-violet: #0b0033ff;
            --russian-violet-2: #1c0f4dff;
            
            --gradient-primary: linear-gradient(135deg, var(--teal) 0%, var(--caribbean-current) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--slate-blue) 0%, var(--russian-violet-2) 100%);
            --gradient-accent: linear-gradient(135deg, var(--kelly-green) 0%, var(--yellow-green) 100%);
            --gradient-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            --gradient-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--russian-violet);
            position: relative;
            font-weight: 700;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-accent);
            border-radius: 2px;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-accent {
            background: var(--gradient-accent);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .btn-accent:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-warning {
            background: var(--gradient-warning);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        /* ===== HERO SECTION ===== */
        .prevention-hero {
            background: var(--gradient-secondary);
            color: var(--white);
            padding: 80px 0;
            text-align: center;
        }

        .prevention-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .prevention-hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto 30px;
        }

        /* ===== CALENDÁRIO ===== */
        .calendar-container {
            background: var(--white);
            border-radius: 15px;
            padding: 40px;
            box-shadow: var(--shadow-lg);
            margin: 80px 0;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .calendar-nav {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .calendar-title {
            font-size: 2rem;
            color: var(--russian-violet);
            font-weight: 700;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .calendar-day-header {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 15px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
        }

        .calendar-day {
            background: var(--white);
            padding: 10px;
            min-height: 120px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .calendar-day:hover {
            background: #f8fafc;
            transform: scale(1.02);
        }

        .calendar-day.empty {
            background: #f5f5f5;
        }

        .day-number {
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--russian-violet);
        }

        .day-number.today {
            background: var(--gradient-accent);
            color: var(--white);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .event-indicator {
            display: flex;
            flex-direction: column;
            gap: 3px;
            margin-top: 5px;
        }

        .event-dot {
            width: 100%;
            height: 4px;
            border-radius: 2px;
            font-size: 0.7rem;
            padding: 2px 4px;
            color: white;
            text-align: center;
        }

        .event-dot.vacina { background: var(--teal); }
        .event-dot.campanha { background: var(--kelly-green); }
        .event-dot.exame { background: var(--slate-blue); }
        .event-dot.alerta { background: #e74c3c; }
        .event-dot.palestra { background: #f39c12; }

        /* ===== LEGENDA ===== */
        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
        }

        /* ===== LISTA DE EVENTOS ===== */
        .events-sidebar {
            background: var(--white);
            border-radius: 15px;
            padding: 30px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 80px;
        }

        .events-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .event-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid var(--teal);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .event-card:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }

        .event-card.urgente {
            border-left-color: #e74c3c;
            background: #ffeaea;
        }

        .event-card.alta {
            border-left-color: #f39c12;
            background: #fff5e6;
        }

        .event-card.media {
            border-left-color: #3498db;
            background: #eaf2f8;
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .event-title {
            font-weight: 700;
            color: var(--russian-violet);
            font-size: 1.1rem;
        }

        .event-date {
            background: var(--gradient-primary);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .event-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .event-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 0.9rem;
        }

        .event-detail i {
            color: var(--teal);
        }

        /* ===== ALERTAS IMPORTANTES ===== */
        .alerts-section {
            margin-top: 80px 80px;
        }

        .alerts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .alert-card {
            background: var(--white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--shadow-lg);
            text-align: center;
            transition: all 0.3s ease;
        }

        .alert-card:hover {
            transform: translateY(-5px);
        }

        .alert-card.critical {
            border-top: 4px solid #e74c3c;
        }

        .alert-card.warning {
            border-top: 4px solid #f39c12;
        }

        .alert-card.info {
            border-top: 4px solid #3498db;
        }

        .alert-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .alert-card.critical .alert-icon { color: #e74c3c; }
        .alert-card.warning .alert-icon { color: #f39c12; }
        .alert-card.info .alert-icon { color: #3498db; }

        /* ===== CARTÃO DE VACINAS ===== */
        .vaccine-card {
            background: var(--white);
            border-radius: 15px;
            padding: 40px;
            box-shadow: var(--shadow-lg);
            margin: 80px 0;
        }

        .vaccine-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .vaccine-item {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid var(--kelly-green);
            transition: all 0.3s ease;
        }

        .vaccine-item:hover {
            transform: translateX(5px);
        }

        .vaccine-name {
            font-weight: 700;
            color: var(--russian-violet);
            margin-bottom: 10px;
        }

        .vaccine-details {
            color: #666;
            font-size: 0.9rem;
        }

        .vaccine-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-pendente { background: #ffeaa7; color: #d63031; }
        .status-concluida { background: #c7f198; color: #27ae60; }

        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 768px) {
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                font-size: 0.8rem;
            }
            
            .calendar-day {
                min-height: 80px;
                padding: 5px;
            }
            
            .calendar-title {
                font-size: 1.5rem;
            }
            
            .event-details {
                grid-template-columns: 1fr;
            }
            
            .alerts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .calendar-day-header {
                padding: 10px 5px;
                font-size: 0.7rem;
            }
            
            .calendar-day {
                min-height: 60px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="prevention-hero">
        <div class="container">
            <h1>Calendário de Prevenção</h1>
            <p>Acompanhe campanhas de vacinação, alertas de saúde e eventos de prevenção em sua região</p>
            <div class="btn-group">
                <button class="btn btn-accent" onclick="scrollToSection('calendario')">
                    <i class="fas fa-calendar-alt"></i> Ver Calendário
                </button>
                <button class="btn btn-warning" onclick="scrollToSection('alertas')">
                    <i class="fas fa-exclamation-triangle"></i> Alertas Atuais
                </button>
            </div>
        </div>
    </section>

    <!-- Calendário -->
    <section id="calendario" class="container">
        <div class="calendar-container">
            <div class="calendar-header">
                <div class="calendar-nav">
                    <button class="btn btn-primary" onclick="mudarMes(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h2 class="calendar-title" id="mesAnoAtual">
                        <?php echo date('F Y'); ?>
                    </h2>
                    <button class="btn btn-primary" onclick="mudarMes(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <button class="btn btn-accent" onclick="irParaHoje()">
                    <i class="fas fa-calendar-day"></i> Hoje
                </button>
            </div>

            <!-- Legenda -->
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: var(--teal);"></div>
                    <span>Vacinação</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: var(--kelly-green);"></div>
                    <span>Campanhas</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: var(--slate-blue);"></div>
                    <span>Exames</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #e74c3c;"></div>
                    <span>Alertas</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #f39c12;"></div>
                    <span>Palestras</span>
                </div>
            </div>

            <!-- Grid do Calendário -->
            <div class="calendar-grid">
                <!-- Cabeçalho dos dias -->
                <div class="calendar-day-header">Dom</div>
                <div class="calendar-day-header">Seg</div>
                <div class="calendar-day-header">Ter</div>
                <div class="calendar-day-header">Qua</div>
                <div class="calendar-day-header">Qui</div>
                <div class="calendar-day-header">Sex</div>
                <div class="calendar-day-header">Sáb</div>

                <!-- Dias do calendário -->
                <?php foreach ($calendario as $semana): ?>
                    <?php foreach ($semana as $dia): ?>
                        <?php if ($dia === null): ?>
                            <div class="calendar-day empty"></div>
                        <?php else: ?>
                            <div class="calendar-day <?php echo ($dia['dia'] == date('j') && $mesAtual == date('n')) ? 'today' : ''; ?>">
                                <div class="day-number <?php echo ($dia['dia'] == date('j') && $mesAtual == date('n')) ? 'today' : ''; ?>">
                                    <?php echo $dia['dia']; ?>
                                </div>
                                <div class="event-indicator">
                                    <?php foreach ($dia['eventos'] as $evento): ?>
                                        <div class="event-dot <?php echo $evento['tipo']; ?>" 
                                             title="<?php echo $evento['titulo']; ?>">
                                            ●
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Lista de Eventos -->
    <section class="container">
        <div class="events-sidebar">
            <h2 class="section-title">Próximos Eventos</h2>
            <div class="events-list">
                <?php 
                // Ordenar eventos por data
                usort($eventos, function($a, $b) {
                    return strtotime($a['data']) - strtotime($b['data']);
                });
                
                foreach ($eventos as $evento): 
                    $dataFormatada = date('d/m', strtotime($evento['data']));
                    $diasRestantes = floor((strtotime($evento['data']) - time()) / (60 * 60 * 24));
                ?>
                    <div class="event-card <?php echo $evento['prioridade']; ?>" 
                         onclick="mostrarDetalhesEvento(<?php echo htmlspecialchars(json_encode($evento)); ?>)">
                        <div class="event-header">
                            <div class="event-title"><?php echo $evento['titulo']; ?></div>
                            <div class="event-date"><?php echo $dataFormatada; ?></div>
                        </div>
                        <div class="event-details">
                            <div class="event-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo $evento['local']; ?></span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-clock"></i>
                                <span><?php echo $evento['horario']; ?></span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-bell"></i>
                                <span>
                                    <?php 
                                    if ($diasRestantes == 0) echo 'Hoje';
                                    elseif ($diasRestantes == 1) echo 'Amanhã';
                                    else echo "Em {$diasRestantes} dias";
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Alertas Importantes -->
    <section id="alertas" class="container alerts-section">
        <h2 class="section-title">Alertas de Saúde</h2>
        <div class="alerts-grid">
            <div class="alert-card critical">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Alerta: Surto de Dengue</h3>
                <p>Região de Presidente Prudense registra aumento de 150% nos casos. Elimine focos de água parada.</p>
                <button class="btn btn-primary" style="margin-top: 15px;">
                    Saiba Mais
                </button>
            </div>
            
            <div class="alert-card warning">
                <div class="alert-icon">
                    <i class="fas fa-temperature-high"></i>
                </div>
                <h3>Onda de Calor</h3>
                <p>Temperaturas acima de 35°C previstas para a semana. Mantenha-se hidratado e evite exposição solar.</p>
                <button class="btn btn-warning" style="margin-top: 15px;">
                    Dicas de Prevenção
                </button>
            </div>
            
            <div class="alert-card info">
                <div class="alert-icon">
                    <i class="fas fa-syringe"></i>
                </div>
                <h3>Campanha de Vacinação</h3>
                <p>Vacina contra influenza disponível para todos os grupos. Proteja-se antes do inverno.</p>
                <button class="btn btn-accent" style="margin-top: 15px;">
                    Ver Locais
                </button>
            </div>
        </div>
    </section>

    <!-- Cartão de Vacinas -->
    <section class="container">
        <div class="vaccine-card">
            <h2 class="section-title">Minhas Vacinas</h2>
            <div class="vaccine-grid">
                <div class="vaccine-item">
                    <div class="vaccine-name">Influenza (Gripe)</div>
                    <div class="vaccine-details">Dose anual recomendada</div>
                    <span class="vaccine-status status-pendente">Pendente</span>
                </div>
                
                <div class="vaccine-item">
                    <div class="vaccine-name">COVID-19</div>
                    <div class="vaccine-details">Reforço anual</div>
                    <span class="vaccine-status status-concluida">Concluída</span>
                </div>
                
                <div class="vaccine-item">
                    <div class="vaccine-name">Febre Amarela</div>
                    <div class="vaccine-details">Dose única</div>
                    <span class="vaccine-status status-concluida">Concluída</span>
                </div>
                
                <div class="vaccine-item">
                    <div class="vaccine-name">Hepatite B</div>
                    <div class="vaccine-details">3 doses necessárias</div>
                    <span class="vaccine-status status-pendente">2ª Dose Pendente</span>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button class="btn btn-primary">
                    <i class="fas fa-print"></i> Imprimir Carteira de Vacinação
                </button>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- Modal para detalhes do evento -->
    <div id="eventoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 15px; max-width: 500px; width: 90%;">
            <h3 id="modalTitulo"></h3>
            <div id="modalConteudo"></div>
            <button class="btn btn-primary" style="margin-top: 20px; width: 100%;" onclick="fecharModal()">
                Fechar
            </button>
        </div>
    </div>

    <script>
        // Variáveis globais para controle do calendário
        let mesAtual = <?php echo $mesAtual; ?>;
        let anoAtual = <?php echo $anoAtual; ?>;

        // Função para mudar o mês
        function mudarMes(direcao) {
            mesAtual += direcao;
            if (mesAtual > 12) {
                mesAtual = 1;
                anoAtual++;
            } else if (mesAtual < 1) {
                mesAtual = 12;
                anoAtual--;
            }
            atualizarCalendario();
        }

        // Ir para a data atual
        function irParaHoje() {
            mesAtual = new Date().getMonth() + 1;
            anoAtual = new Date().getFullYear();
            atualizarCalendario();
        }

        // Scroll suave para seções
        function scrollToSection(id) {
            document.getElementById(id).scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Mostrar detalhes do evento
        function mostrarDetalhesEvento(evento) {
            const modal = document.getElementById('eventoModal');
            const titulo = document.getElementById('modalTitulo');
            const conteudo = document.getElementById('modalConteudo');
            
            titulo.textContent = evento.titulo;
            conteudo.innerHTML = `
                <p><strong>Data:</strong> ${new Date(evento.data).toLocaleDateString('pt-BR')}</p>
                <p><strong>Local:</strong> ${evento.local}</p>
                <p><strong>Horário:</strong> ${evento.horario}</p>
                <p><strong>Tipo:</strong> ${evento.tipo.charAt(0).toUpperCase() + evento.tipo.slice(1)}</p>
                <p><strong>Prioridade:</strong> ${evento.prioridade.charAt(0).toUpperCase() + evento.prioridade.slice(1)}</p>
            `;
            
            modal.style.display = 'flex';
        }

        // Fechar modal
        function fecharModal() {
            document.getElementById('eventoModal').style.display = 'none';
        }

        // Atualizar calendário (simulação - em produção faria requisição AJAX)
        function atualizarCalendario() {
            const meses = [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ];
            
            document.getElementById('mesAnoAtual').textContent = 
                `${meses[mesAtual - 1]} ${anoAtual}`;
            
            // Aqui iria uma requisição AJAX para carregar os eventos do mês
            console.log(`Carregando eventos de ${mesAtual}/${anoAtual}`);
        }

        // Fechar modal ao clicar fora
        document.getElementById('eventoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });

        // Notificação de eventos do dia
        window.addEventListener('load', function() {
            const hoje = new Date().toISOString().split('T')[0];
            const eventosHoje = <?php echo json_encode($eventos); ?>.filter(evento => evento.data === hoje);
            
            if (eventosHoje.length > 0) {
                setTimeout(() => {
                    alert(`📅 Você tem ${eventosHoje.length} evento(s) de prevenção hoje!`);
                }, 1000);
            }
        });
    </script>
</body>
</html>