<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['email'])) {
    header("Location: login1.php");
    exit;
}

// Busca ID e nome do paciente
$stmt = $pdo->prepare("SELECT id, nome FROM pacientes WHERE email = ?");
$stmt->execute(array($_SESSION['email']));
$pac = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pac) {
    // caso raro: sessão válida mas paciente removido
    die("Paciente não encontrado.");
}
$idPac = (int) $pac['id'];
$nomePaciente = $pac['nome'];

// Carrega consultas com possível documento vinculado (se houver)
$sql = "
SELECT c.id_consulta,
       c.codigo_confirmacao,
       c.status,
       e.nome AS especialidade,
       m.nome AS medico,
       COALESCE(a.data, c.data) AS data,
       COALESCE(a.hora, c.hora) AS hora,
       l.nome AS local,
       m.telefone AS telefone_medico,
       d.id AS doc_id,
       d.tipo AS doc_tipo,
       d.arquivo AS doc_arquivo
FROM consultas c
JOIN especialidades e ON c.especialidade_id = e.id
JOIN medicos m ON c.medico_id = m.id
LEFT JOIN agenda a ON c.agenda_id = a.id
LEFT JOIN locais_consulta l ON m.local_consulta_id = l.id
LEFT JOIN documentos d ON d.consulta_id = c.id_consulta AND d.paciente_id = c.paciente_id
WHERE c.paciente_id = ?
ORDER BY COALESCE(a.data, c.data) DESC, COALESCE(a.hora, c.hora) DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(array($idPac));
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar consultas por status (normaliza para minúsculas)
$contagemStatus = array(
    'agendada' => 0,
    'realizada' => 0,
    'cancelada' => 0,
    'total' => count($consultas)
);

foreach ($consultas as $c) {
    $statusKey = strtolower($c['status']);
    if (isset($contagemStatus[$statusKey])) {
        $contagemStatus[$statusKey]++;
    }
}

// Carregar documentos reais do paciente (usa coluna criado_em do seu BD)
$sqlDocs = "
SELECT d.id, d.tipo, d.arquivo, d.criado_em AS data, m.nome AS medico
FROM documentos d
LEFT JOIN medicos m ON d.medico_id = m.id
WHERE d.paciente_id = ?
ORDER BY d.criado_em DESC
";
$stmtDocs = $pdo->prepare($sqlDocs);
$stmtDocs->execute(array($idPac));
$documentos = $stmtDocs->fetchAll(PDO::FETCH_ASSOC);

// Normaliza campos usados pela view (titulo, downloads)
foreach ($documentos as $k => $doc) {
    // se não houver título na tabela, monta um título legível
    if (!isset($doc['titulo']) || empty($doc['titulo'])) {
        $basename = '';
        if (!empty($doc['arquivo'])) {
            $basename = basename($doc['arquivo']);
        }
        $documentos[$k]['titulo'] = ucfirst($doc['tipo']) . ($basename ? ' - ' . $basename : '');
    }
    // se não houver contador de downloads na tabela, define 0
    if (!isset($doc['downloads'])) {
        $documentos[$k]['downloads'] = 0;
    }
    // garante que a data existe (se não, usa criado_em/hoje)
    if (!isset($doc['data']) || empty($doc['data'])) {
        $documentos[$k]['data'] = date('Y-m-d');
    }
}
?> 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Consultas e Documentos - MedClick</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- PDF.js para visualização de PDFs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js" integrity="sha512-ml/QKfG3+Yes6TwOzQb7aCNtJF4PUyha6R3w8pSTo/VJS5lWJYaWHJ0jhzOww9XQskYcN0SQ+1C2wE5o+Ywagg==" crossorigin="anonymous"></script>
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
            --gradient-hero: linear-gradient(135deg, var(--russian-violet) 0%, var(--slate-blue) 100%);
            
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
            background: linear-gradient(135deg, #f8fafc 0%, #f0f7ff 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Tabs Navigation */
        .tabs-navigation {
            display: flex;
            background: var(--white);
            border-radius: 15px;
            padding: 5px;
            margin: 20px 0;
            box-shadow: var(--shadow-sm);
            overflow-x: auto;
        }

        .tab-button {
            flex: 1;
            padding: 15px 20px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-weight: 600;
            color: #64748b;
            transition: all 0.3s ease;
            white-space: nowrap;
            min-width: 160px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-button.active {
            background: var(--gradient-primary);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .tab-button:hover:not(.active) {
            background: #f1f5f9;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        /* Header Section */
        .header-section {
            background: var(--gradient-hero);
            color: var(--white);
            border-radius: 20px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .welcome-message h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .welcome-message p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .stat-card {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #64748b;
        }

        .stat-agendada .stat-icon { color: var(--teal); }
        .stat-realizada .stat-icon { color: var(--kelly-green); }
        .stat-cancelada .stat-icon { color: #ef4444; }
        .stat-total .stat-icon { color: var(--slate-blue); }

        /* Filters Section */
        .filters-section {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-label {
            font-weight: 600;
            color: var(--russian-violet);
        }

        .filter-select {
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: var(--white);
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--slate-blue);
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 10px 40px 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--slate-blue);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-blue);
        }

        /* Consultas List */
        .consultas-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 20px 0;
        }

        .consulta-card {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            position: relative;
            border-left: 5px solid var(--teal);
        }

        .consulta-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .consulta-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .consulta-title {
            font-size: 1.3rem;
            color: var(--russian-violet);
            font-weight: 600;
        }

        .consulta-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-agendada {
            background: #d1fae5;
            color: #065f46;
        }

        .status-realizada {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelada {
            background: #fee2e2;
            color: #991b1b;
        }

        .consulta-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-icon {
            color: var(--slate-blue);
            font-size: 1.1rem;
            width: 20px;
        }

        .detail-content {
            color: #64748b;
        }

        .consulta-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
        }

        .btn-secondary {
            background: transparent;
            color: var(--slate-blue);
            border: 2px solid var(--slate-blue);
        }

        .btn-danger {
            background: transparent;
            color: #ef4444;
            border: 2px solid #ef4444;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            background: var(--gradient-secondary);
        }

        /* Documentos Section */
        .documentos-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .documentos-title {
            font-size: 1.8rem;
            color: var(--russian-violet);
            font-weight: 700;
        }

        .documentos-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .documentos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .documento-card {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .documento-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .documento-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-comprovante {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-atestado {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-laudo {
            background: #dcfce7;
            color: #166534;
        }

        .badge-bula {
            background: #f3e8ff;
            color: #7e22ce;
        }

        .documento-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--slate-blue);
        }

        .documento-title {
            font-size: 1.1rem;
            color: var(--russian-violet);
            margin-bottom: 10px;
            font-weight: 600;
            line-height: 1.4;
        }

        .documento-details {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #f1f5f9;
        }

        .documento-detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .documento-label {
            color: #64748b;
            font-weight: 500;
        }

        .documento-value {
            color: var(--russian-violet);
        }

        .documento-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-document {
            flex: 1;
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 0.85rem;
        }

        .btn-view {
            background: var(--gradient-primary);
            color: var(--white);
        }

        .btn-download {
            background: transparent;
            color: var(--slate-blue);
            border: 2px solid var(--slate-blue);
        }

        .btn-document:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        /* PDF Viewer Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: var(--white);
            border-radius: 15px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 15px 20px;
            background: var(--gradient-primary);
            color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            flex: 1;
            overflow: auto;
            padding: 20px;
        }

        .pdf-viewer {
            width: 100%;
            height: 70vh;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: auto;
        }

        .modal-actions {
            padding: 15px 20px;
            background: #f8fafc;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* No content */
        .no-content {
            text-align: center;
            padding: 60px 20px;
            background: var(--white);
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            grid-column: 1 / -1;
        }

        .no-content i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .no-content h3 {
            color: #64748b;
            margin-bottom: 10px;
        }

        .no-content p {
            color: #94a3b8;
            margin-bottom: 25px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
            }
            
            .header-section {
                padding: 20px;
            }
            
            .welcome-message h1 {
                font-size: 1.8rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filters-section {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                max-width: 100%;
            }
            
            .consulta-header {
                flex-direction: column;
            }
            
            .consulta-actions {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .documentos-grid {
                grid-template-columns: 1fr;
            }
            
            .documentos-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .documentos-filters {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .consulta-details {
                grid-template-columns: 1fr;
            }
            
            .stat-number {
                font-size: 1.8rem;
            }
            
            .tabs-navigation {
                flex-direction: column;
            }
            
            .tab-button {
                min-width: auto;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .consulta-card, .documento-card {
            animation: fadeIn 0.5s ease forwards;
        }

        .consulta-card:nth-child(2) { animation-delay: 0.1s; }
        .consulta-card:nth-child(3) { animation-delay: 0.2s; }
        .consulta-card:nth-child(4) { animation-delay: 0.3s; }
        .consulta-card:nth-child(5) { animation-delay: 0.4s; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="main-container">
    <!-- Tabs Navigation -->
    <div class="tabs-navigation">
        <button class="tab-button active" data-tab="consultas">
            <i class="fas fa-calendar-check"></i> Minhas Consultas
        </button>
        <button class="tab-button" data-tab="documentos">
            <i class="fas fa-file-medical"></i> Meus Documentos
        </button>
    </div>

    <!-- Consultas Tab -->
    <div class="tab-content active" id="consultas-tab">
        <!-- Header Section -->
        <section class="header-section">
            <div class="welcome-message">
                <h1>Olá, <?php echo htmlspecialchars(explode(' ', $nomePaciente)[0]); ?>!</h1>
                <p>Acompanhe todas as suas consultas agendadas e histórico médico</p>
            </div>
        </section>

        <!-- Stats Section -->
        <div class="stats-grid">
            <div class="stat-card stat-total">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number"><?php echo $contagemStatus['total']; ?></div>
                <div class="stat-label">Total de Consultas</div>
            </div>
            
            <div class="stat-card stat-agendada">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $contagemStatus['agendada']; ?></div>
                <div class="stat-label">Agendadas</div>
            </div>
            
            <div class="stat-card stat-realizada">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $contagemStatus['realizada']; ?></div>
                <div class="stat-label">Realizadas</div>
            </div>
            
            <div class="stat-card stat-cancelada">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number"><?php echo $contagemStatus['cancelada']; ?></div>
                <div class="stat-label">Canceladas</div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filter-group">
                <span class="filter-label">Filtrar por:</span>
                <select class="filter-select" id="filter-status">
                    <option value="all">Todos os status</option>
                    <option value="agendada">Agendadas</option>
                    <option value="realizada">Realizadas</option>
                    <option value="cancelada">Canceladas</option>
                </select>
            </div>
            
            <div class="filter-group">
                <span class="filter-label">Ordenar por:</span>
                <select class="filter-select" id="filter-sort">
                    <option value="recent">Mais recentes</option>
                    <option value="oldest">Mais antigas</option>
                    <option value="specialty">Especialidade</option>
                </select>
            </div>
            
            <div class="search-box">
                <input type="text" class="search-input" id="search-consultas" placeholder="Buscar consultas...">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>

        <!-- Consultas List -->
        <div class="consultas-list" id="consultas-list">
            <?php if (count($consultas) > 0): ?>
                <?php foreach ($consultas as $c): 
                    $dataFormatada = date('d/m/Y', strtotime($c['data']));
                    $horaFormatada = date('H:i', strtotime($c['hora']));
                    $statusClass = 'status-' . $c['status'];
                    $statusText = ucfirst($c['status']);
                ?>
                    <div class="consulta-card" data-status="<?php echo $c['status']; ?>" data-specialty="<?php echo htmlspecialchars($c['especialidade']); ?>" data-date="<?php echo $c['data']; ?>">
                        <div class="consulta-header">
                            <h3 class="consulta-title"><?php echo htmlspecialchars($c['especialidade']); ?></h3>
                            <span class="consulta-status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                        </div>
                        
                        <div class="consulta-details">
                            <div class="detail-item">
                                <i class="fas fa-user-md detail-icon"></i>
                                <span class="detail-content">Dr(a). <?php echo htmlspecialchars($c['medico']); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class="fas fa-calendar-day detail-icon"></i>
                                <span class="detail-content"><?php echo $dataFormatada; ?> às <?php echo $horaFormatada; ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt detail-icon"></i>
                                <span class="detail-content"><?php echo htmlspecialchars($c['local']); ?></span>
                            </div>
                            
                            <?php if (!empty($c['telefone_medico'])): ?>
                            <div class="detail-item">
                                <i class="fas fa-phone detail-icon"></i>
                                <span class="detail-content"><?php echo htmlspecialchars($c['telefone_medico']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                            <?php if (!empty($c['doc_id'])): ?>
                                <a href="download_documento.php?id=<?= $c['doc_id'] ?>" class="action-btn btn-primary">
                                    <i class="fas fa-file-download"></i> Comprovante
                                </a>
                            <?php endif; ?>

                            <?php if ($c['status'] == 'agendada'): ?>
                            <button class="action-btn btn-secondary" onclick="reenviarLembrete('<?= urlencode($c['codigo_confirmacao']) ?>')">
                                <i class="fas fa-bell"></i> Lembrete
                            </button>
                            
                            <button class="action-btn btn-danger" onclick="cancelarConsulta('<?= urlencode($c['codigo_confirmacao']) ?>')">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-content">
                    <i class="fas fa-calendar-times"></i>
                    <h3>Nenhuma consulta agendada</h3>
                    <p>Você ainda não possui consultas agendadas em sua conta.</p>
                    <a href="AgendarConsulta.php" class="action-btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Agendar Primeira Consulta
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Documentos Tab -->
    <div class="tab-content" id="documentos-tab">
        <!-- Header Section -->
        <section class="header-section">
            <div class="welcome-message">
                <h1>Meus Documentos Médicos</h1>
                <p>Acesse e gerencie todos os seus comprovantes, atestados, laudos e bulas de medicamentos</p>
            </div>
        </section>

        <!-- Documentos Header -->
        <div class="documentos-header">
            <h2 class="documentos-title">Todos os Documentos</h2>
            <div class="documentos-filters">
                <select class="filter-select" id="filter-document-type">
                    <option value="all">Todos os tipos</option>
                    <option value="comprovante">Comprovantes</option>
                    <option value="atestado">Atestados</option>
                    <option value="laudo">Laudos</option>
                    <option value="bula">Bulas</option>
                </select>
                <div class="search-box">
                    <input type="text" class="search-input" id="search-documentos" placeholder="Buscar documentos...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
        </div>

        <!-- Documentos Grid -->
        <div class="documentos-grid" id="documentos-grid">
            <?php if (count($documentos) > 0): ?>
                <?php foreach ($documentos as $doc): 
                    $dataFormatada = date('d/m/Y', strtotime($doc['data']));
                    $badgeClass = 'badge-' . $doc['tipo'];
                    $iconClass = '';
                    
                    switch ($doc['tipo']) {
                        case 'comprovante':
                            $iconClass = 'fas fa-file-invoice';
                            break;
                        case 'atestado':
                            $iconClass = 'fas fa-file-medical';
                            break;
                        case 'laudo':
                            $iconClass = 'fas fa-file-medical-alt';
                            break;
                        case 'bula':
                            $iconClass = 'fas fa-pills';
                            break;
                    }
                ?>
                    <div class="documento-card" data-type="<?php echo $doc['tipo']; ?>" data-date="<?php echo $doc['data']; ?>">
                        <span class="documento-badge <?php echo $badgeClass; ?>"><?php echo ucfirst($doc['tipo']); ?></span>
                        <i class="documento-icon <?php echo $iconClass; ?>"></i>
                        <h3 class="documento-title"><?php echo htmlspecialchars($doc['titulo']); ?></h3>
                        
                        <div class="documento-details">
                            <div class="documento-detail">
                                <span class="documento-label">Data:</span>
                                <span class="documento-value"><?php echo $dataFormatada; ?></span>
                            </div>
                            <div class="documento-detail">
                                <span class="documento-label">Médico:</span>
                                <span class="documento-value"><?php echo htmlspecialchars($doc['medico']); ?></span>
                            </div>
                            <div class="documento-detail">
                                <span class="documento-label">Downloads:</span>
                                <span class="documento-value"><?php echo $doc['downloads']; ?></span>
                            </div>
                        </div>
                        
                        <div class="documento-actions">
                            <button class="btn-document btn-view" onclick="viewDocument(<?php echo $doc['id']; ?>, '<?php echo $doc['titulo']; ?>')">
                                <i class="fas fa-eye"></i> Visualizar
                            </button>
                            <button class="btn-document btn-download" onclick="downloadDocument(<?php echo $doc['id']; ?>, '<?php echo $doc['titulo']; ?>')">
                                <i class="fas fa-download"></i> Baixar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-content">
                    <i class="fas fa-file-medical-alt"></i>
                    <h3>Nenhum documento encontrado</h3>
                    <p>Você ainda não possui documentos médicos em sua conta.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Cancelamento -->
<div class="modal" id="cancelModal">
    <div class="modal-content">
        <div class="modal-close" onclick="closeModal('cancelModal')">
            <i class="fas fa-times"></i>
        </div>
        <h3 class="modal-title">Cancelar Consulta</h3>
        <form class="modal-form" id="cancelForm">
            <input type="hidden" id="cancelCodigo" name="codigo_confirmacao">
            <div class="form-group">
                <label class="form-label" for="cancelReason">Motivo do cancelamento:</label>
                <textarea class="form-textarea" id="cancelReason" name="motivo" required placeholder="Informe o motivo do cancelamento..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="action-btn btn-secondary" onclick="closeModal('cancelModal')">
                    <i class="fas fa-times"></i> Voltar
                </button>
                <button type="submit" class="action-btn btn-danger">
                    <i class="fas fa-check"></i> Confirmar Cancelamento
                </button>
            </div>
        </form>
    </div>
</div>

<!-- PDF Viewer Modal -->
<div class="modal" id="pdfModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="pdfModalTitle">Visualizar Documento</h3>
            <button class="modal-close" onclick="closeModal('pdfModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="pdf-viewer" id="pdfViewer">
                <div style="padding: 20px; text-align: center;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #64748b;"></i>
                    <p style="margin-top: 15px;">Carregando documento...</p>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-secondary" onclick="closeModal('pdfModal')">
                <i class="fas fa-times"></i> Fechar
            </button>
            <button class="action-btn btn-primary" id="downloadPdfBtn">
                <i class="fas fa-download"></i> Baixar PDF
            </button>
        </div>
    </div>
</div>

<!-- PDF.js de CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>

<script>
// Tabs functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        const tabName = this.getAttribute('data-tab');
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.getElementById(`${tabName}-tab`).classList.add('active');
    });
});

// Filtros e busca para consultas
document.addEventListener('DOMContentLoaded', function() {
    const filterStatus = document.getElementById('filter-status');
    const filterSort = document.getElementById('filter-sort');
    const searchInput = document.getElementById('search-consultas');
    const consultasList = document.getElementById('consultas-list');
    const consultas = document.querySelectorAll('.consulta-card');
    
    function filtrarConsultas() {
        const status = filterStatus.value;
        const sort = filterSort.value;
        const searchTerm = searchInput.value.toLowerCase();
        
        consultas.forEach(consulta => {
            const consultaStatus = consulta.getAttribute('data-status');
            const especialidade = consulta.querySelector('.consulta-title').textContent.toLowerCase();
            const medico = consulta.querySelector('.detail-item:nth-child(1)').textContent.toLowerCase();
            
            const statusMatch = status === 'all' || consultaStatus === status;
            const searchMatch = especialidade.includes(searchTerm) || medico.includes(searchTerm);
            
            consulta.style.display = statusMatch && searchMatch ? 'block' : 'none';
            
            if (sort === 'recent') {
                consultasList.prepend(consulta);
            } else if (sort === 'oldest') {
                consultasList.appendChild(consulta);
            }
        });
    }
    
    filterStatus.addEventListener('change', filtrarConsultas);
    filterSort.addEventListener('change', filtrarConsultas);
    searchInput.addEventListener('input', filtrarConsultas);
    
    filtrarConsultas();
});

// Filtros e busca para documentos
document.addEventListener('DOMContentLoaded', function() {
    const filterType = document.getElementById('filter-document-type');
    const searchInput = document.getElementById('search-documentos');
    const documentos = document.querySelectorAll('.documento-card');
    
    function filtrarDocumentos() {
        const type = filterType.value;
        const searchTerm = searchInput.value.toLowerCase();
        
        documentos.forEach(documento => {
            const documentType = documento.getAttribute('data-type');
            const titulo = documento.querySelector('.documento-title').textContent.toLowerCase();
            
            const typeMatch = type === 'all' || documentType === type;
            const searchMatch = titulo.includes(searchTerm);
            
            documento.style.display = typeMatch && searchMatch ? 'block' : 'none';
        });
    }
    
    filterType.addEventListener('change', filtrarDocumentos);
    searchInput.addEventListener('input', filtrarDocumentos);
    
    filtrarDocumentos();
});

// Modal functions
function cancelarConsulta(codigo) {
    document.getElementById('cancelCodigo').value = codigo;
    document.getElementById('cancelModal').style.display = 'flex';
}

function reenviarLembrete(codigo) {
    alert('Lembrete da consulta reenviado para seu e-mail e telefone!');
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

document.getElementById('cancelForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const codigo = formData.get('codigo_confirmacao');
    const motivo = formData.get('motivo');
    
    alert(`Consulta ${codigo} cancelada com sucesso! Motivo: ${motivo}`);
    closeModal('cancelModal');
});

// Visualizar documento no modal com PDF.js
function viewDocument(id, title) {
    document.getElementById('pdfModalTitle').textContent = title;
    document.getElementById('pdfModal').style.display = 'flex';

    const viewer = document.getElementById('pdfViewer');
    viewer.innerHTML = `
        <iframe 
            src="download_documento.php?id=${id}" 
            width="100%" 
            height="600px" 
            style="border: none;">
        </iframe>
    `;

    // Configura o botão de download real
    document.getElementById('downloadPdfBtn').onclick = function() {
        downloadDocument(id);
    };
}

function downloadDocument(id) {
    window.location.href = `download_documento.php?id=${id}`;
}
</script>

</body>
<?php include"footer.php" ?>
</html>