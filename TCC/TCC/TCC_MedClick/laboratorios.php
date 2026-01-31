<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login1.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <title>Laboratórios Médicos - MedClick</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        main {
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 2.5rem;
            color: var(--russian-violet);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-accent);
            border-radius: 2px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .controls-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 500px;
            min-width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 14px 45px 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--white);
            box-shadow: var(--shadow-sm);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--slate-blue);
            box-shadow: 0 0 0 3px rgba(112, 93, 188, 0.2);
        }

        .search-box i {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-blue);
        }

        .filter-controls {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            background-color: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--slate-blue);
        }

        .view-toggle {
            display: flex;
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .view-btn {
            padding: 10px 15px;
            border: none;
            background: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
            color: #64748b;
        }

        .view-btn.active {
            background: var(--slate-blue);
            color: var(--white);
        }

        .results-info {
            margin-bottom: 20px;
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Layout de Grade */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        /* Layout de Lista */
        .cards-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cards-list .lab-card {
            flex-direction: row;
            height: auto;
        }

        .cards-list .lab-image {
            width: 120px;
            height: 120px;
            border-radius: 10px 0 0 10px;
        }

        .cards-list .lab-content {
            flex: 1;
            padding: 25px;
        }

        /* Card de Laboratório */
        .lab-card {
            background-color: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .lab-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .lab-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .lab-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .lab-name {
            font-size: 1.3rem;
            color: var(--russian-violet);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .lab-specialty {
            color: var(--slate-blue);
            font-weight: 500;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .lab-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            color: #64748b;
        }

        .detail-item i {
            color: var(--slate-blue);
        }

        .lab-bio {
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.5;
            flex: 1;
        }

        .lab-services {
            margin-bottom: 15px;
        }

        .services-title {
            font-weight: 600;
            color: var(--russian-violet);
            margin-bottom: 8px;
        }

        .services-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .service-tag {
            background: var(--mindaro);
            color: var(--russian-violet);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .lab-actions {
            display: flex;
            gap: 12px;
            margin-top: auto;
        }

        .action-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--slate-blue);
            border: 2px solid var(--slate-blue);
        }

        .btn-whatsapp {
            background: #25D366;
            color: var(--white);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }

        .no-results i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .no-results h3 {
            color: #64748b;
            margin-bottom: 10px;
        }

        .no-results p {
            color: #94a3b8;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }

        .pagination-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            background: var(--white);
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination-btn.active {
            background: var(--slate-blue);
            color: var(--white);
            border-color: var(--slate-blue);
        }

        .pagination-btn:hover:not(.active) {
            border-color: var(--slate-blue);
            color: var(--slate-blue);
        }

        .lab-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: var(--white);
            border-radius: 15px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            padding: 30px;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--white);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            box-shadow: var(--shadow-md);
        }

        .modal-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
        }

        .modal-info h2 {
            color: var(--russian-violet);
            margin-bottom: 5px;
        }

        .modal-info p {
            color: var(--slate-blue);
            font-weight: 500;
        }

        .modal-details {
            margin-bottom: 25px;
        }

        .modal-section {
            margin-bottom: 20px;
        }

        .modal-section h3 {
            color: var(--russian-violet);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-section p {
            color: #64748b;
            line-height: 1.6;
        }

        .modal-services {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .modal-service-item {
            background: #f1f5f9;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .controls-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                max-width: 100%;
            }
            
            .filter-controls {
                justify-content: center;
            }
            
            .cards-list .lab-card {
                flex-direction: column;
            }
            
            .cards-list .lab-image {
                width: 100%;
                border-radius: 10px 10px 0 0;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .lab-actions {
                flex-direction: column;
            }
            
            .modal-header {
                flex-direction: column;
                text-align: center;
            }
            
            .modal-image {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .modal-services {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            main {
                padding: 20px 15px;
            }
            
            .cards-grid {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .filter-select {
                width: 100%;
            }
            
            .modal-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <div class="page-header">
    <h1 class="page-title">Laboratórios da Região</h1>
    <p class="page-subtitle">Encontre os melhores laboratórios de Presidente Prudente e região para seus exames</p>
  </div>

  <div class="controls-row">
    <div class="search-box">
      <input type="text" id="search" placeholder="Buscar laboratório por nome, especialidade ou exame...">
      <i class="fas fa-search"></i>
    </div>
    
    <div class="filter-controls">
      <select class="filter-select" id="filter-specialty">
        <option value="">Todos os tipos</option>
        <option value="analises-clinicas">Análises Clínicas</option>
        <option value="imagem">Diagnóstico por Imagem</option>
        <option value="patologia">Patologia</option>
        <option value="genetica">Genética</option>
      </select>
      
      <select class="filter-select" id="filter-availability">
        <option value="">Disponibilidade</option>
        <option value="24horas">Aberto 24h</option>
        <option value="domingo">Aberto aos domingos</option>
        <option value="coleta-domiciliar">Coleta domiciliar</option>
      </select>
      
      <div class="view-toggle">
        <button class="view-btn active" id="view-grid"><i class="fas fa-th"></i></button>
        <button class="view-btn" id="view-list"><i class="fas fa-list"></i></button>
      </div>
    </div>
  </div>
  
  <div class="results-info" id="results-info">
    Mostrando <span id="results-count">9</span> de 9 laboratórios
  </div>

  <div id="cards-container" class="cards-grid">
    <!-- Laboratório 1 -->
    <div class="lab-card" data-specialty="analises-clinicas" data-availability="24horas">
      <img src="img/lab1.jpg" alt="LabPrudente" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">LabPrudente</h3>
        <p class="lab-specialty">Análises Clínicas</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.8</span>
          <span class="detail-item"><i class="fas fa-clock"></i> 24h</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Centro</span>
        </div>
        
        <p class="lab-bio">Laboratório de referência em Presidente Prudente, com mais de 20 anos de experiência em análises clínicas e atendimento humanizado.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">Hemograma</span>
            <span class="service-tag">Colesterol</span>
            <span class="service-tag">Glicemia</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-prudente')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-prudente')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Laboratório 2 -->
    <div class="lab-card" data-specialty="imagem" data-availability="domingo">
      <img src="img/lab2.jpg" alt="Imagem Diagnóstica" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">Diagnóstica Prudente</h3>
        <p class="lab-specialty">Diagnóstico por Imagem</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.9</span>
          <span class="detail-item"><i class="fas fa-calendar-day"></i> Domingos</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Vila Formosa</span>
        </div>
        
        <p class="lab-bio">Especializado em exames de imagem de alta complexidade, com equipamentos de última geração e laudos rápidos.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">Ressonância</span>
            <span class="service-tag">Tomografia</span>
            <span class="service-tag">Ultrassom</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-imagem')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-imagem')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Laboratório 3 -->
    <div class="lab-card" data-specialty="analises-clinicas" data-availability="coleta-domiciliar">
      <img src="img/lab3.jpg" alt="LabVip" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">LabVip Saúde</h3>
        <p class="lab-specialty">Análises Clínicas</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.7</span>
          <span class="detail-item"><i class="fas fa-home"></i> Coleta em casa</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Jardim Planalto</span>
        </div>
        
        <p class="lab-bio">Laboratório focado em comodidade, oferecendo coleta domiciliar e resultados online. Atendimento personalizado e ágil.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">PCR</span>
            <span class="service-tag">Vitamina D</span>
            <span class="service-tag">TSH</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-vip')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-vip')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Laboratório 4 -->
    <div class="lab-card" data-specialty="patologia" data-availability="24horas">
      <img src="img/lab4.jpg" alt="Patolab" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">Patolab Oeste Paulista</h3>
        <p class="lab-specialty">Patologia</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.9</span>
          <span class="detail-item"><i class="fas fa-clock"></i> 24h</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Centro</span>
        </div>
        
        <p class="lab-bio">Referência em patologia cirúrgica e citopatologia na região, com equipe especializada e laudos precisos.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">Biópsia</span>
            <span class="service-tag">Citologia</span>
            <span class="service-tag">Anatomopatológico</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-patolab')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-patolab')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Laboratório 5 -->
    <div class="lab-card" data-specialty="imagem" data-availability="domingo">
      <img src="img/lab5.jpg" alt="Ultrason" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">Ultrason Diagnóstico</h3>
        <p class="lab-specialty">Diagnóstico por Imagem</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.6</span>
          <span class="detail-item"><i class="fas fa-calendar-day"></i> Domingos</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Parque do Povo</span>
        </div>
        
        <p class="lab-bio">Especializado em ultrassonografia com Doppler, ecocardiograma e exames de imagem para todas as idades.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">Ultrassom</span>
            <span class="service-tag">Doppler</span>
            <span class="service-tag">Ecocardiograma</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-ultrason')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-ultrason')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Laboratório 6 -->
    <div class="lab-card" data-specialty="analises-clinicas" data-availability="coleta-domiciliar">
      <img src="img/lab6.jpg" alt="LabMais" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">LabMais Saúde</h3>
        <p class="lab-specialty">Análises Clínicas</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.5</span>
          <span class="detail-item"><i class="fas fa-home"></i> Coleta em casa</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Jardim Aviação</span>
        </div>
        
        <p class="lab-bio">Laboratório moderno com foco em prevenção, oferecendo check-ups completos e resultados em tempo recorde.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">Check-up</span>
            <span class="service-tag">Hormônios</span>
            <span class="service-tag">Marcadores</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-mais')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-mais')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Laboratório 7 -->
    <div class="lab-card" data-specialty="genetica" data-availability="24horas">
      <img src="img/lab7.jpg" alt="GeneLab" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">GeneLab Prudente</h3>
        <p class="lab-specialty">Genética</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.8</span>
          <span class="detail-item"><i class="fas fa-clock"></i> 24h</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Vila Maristela</span>
        </div>
        
        <p class="lab-bio">Pioneiro em exames genéticos na região, oferecendo testes de paternidade, predisposição a doenças e farmacogenética.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">Teste de Paternidade</span>
            <span class="service-tag">Painel Genético</span>
            <span class="service-tag">Farmacogenética</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-gene')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-gene')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Laboratório 8 -->
    <div class="lab-card" data-specialty="imagem" data-availability="domingo">
      <img src="img/lab8.jpg" alt="Radiolab" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">Radiolab Diagnóstico</h3>
        <p class="lab-specialty">Diagnóstico por Imagem</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.7</span>
          <span class="detail-item"><i class="fas fa-calendar-day"></i> Domingos</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Residencial São Lucas</span>
        </div>
        
        <p class="lab-bio">Especializado em radiologia geral e contrastada, com equipamentos digitais de última geração para diagnósticos precisos.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">Raio-X</span>
            <span class="service-tag">Mamografia</span>
            <span class="service-tag">Densitometria</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-radiolab')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-radiolab')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Laboratório 9 -->
    <div class="lab-card" data-specialty="analises-clinicas" data-availability="coleta-domiciliar">
      <img src="img/lab9.jpg" alt="LabExpress" class="lab-image">
      <div class="lab-content">
        <h3 class="lab-name">LabExpress</h3>
        <p class="lab-specialty">Análises Clínicas</p>
        
        <div class="lab-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.6</span>
          <span class="detail-item"><i class="fas fa-home"></i> Coleta em casa</span>
          <span class="detail-item"><i class="fas fa-map-marker-alt"></i> Jardim Bongiovani</span>
        </div>
        
        <p class="lab-bio">Focado em agilidade e qualidade, oferecendo resultados em poucas horas para exames de rotina e urgência.</p>
        
        <div class="lab-services">
          <div class="services-title">Exames Principais:</div>
          <div class="services-list">
            <span class="service-tag">Urgência</span>
            <span class="service-tag">Rotina</span>
            <span class="service-tag">COVID-19</span>
          </div>
        </div>
        
        <div class="lab-actions">
          <button class="action-btn btn-primary" onclick="showLabModal('lab-express')">
            <i class="fas fa-calendar"></i> Agendar Exame
          </button>
          <button class="action-btn btn-secondary" onclick="showLabModal('lab-express')">
            <i class="fas fa-info-circle"></i> Ver Detalhes
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="pagination">
    <button class="pagination-btn active">1</button>
    <button class="pagination-btn">2</button>
    <button class="pagination-btn">3</button>
  </div>
</main>

<!-- Modal para detalhes do laboratório -->
<div class="lab-modal" id="labModal">
  <div class="modal-content">
    <div class="modal-close" onclick="closeLabModal()">
      <i class="fas fa-times"></i>
    </div>
    
    <div class="modal-header">
      <img src="img/lab1.jpg" alt="Laboratório" class="modal-image" id="modalImage">
      <div class="modal-info">
        <h2 id="modalName">Nome do Laboratório</h2>
        <p id="modalSpecialty">Especialidade</p>
      </div>
    </div>
    
    <div class="modal-details">
      <div class="modal-section">
        <h3><i class="fas fa-info-circle"></i> Sobre</h3>
        <p id="modalAbout">Informações sobre o laboratório...</p>
      </div>
      
      <div class="modal-section">
        <h3><i class="fas fa-map-marker-alt"></i> Localização</h3>
        <p id="modalLocation">Endereço completo do laboratório...</p>
      </div>
      
      <div class="modal-section">
        <h3><i class="fas fa-clock"></i> Horário de Funcionamento</h3>
        <p id="modalHours">Horários de atendimento...</p>
      </div>
      
      <div class="modal-section">
        <h3><i class="fas fa-stethoscope"></i> Serviços Oferecidos</h3>
        <div class="modal-services" id="modalServices">
          <!-- Serviços serão inseridos via JavaScript -->
        </div>
      </div>
    </div>
    
    <div class="modal-actions">
      <a href="AgendarExame.php" class="action-btn btn-primary">
        <i class="fas fa-calendar"></i> Agendar Exame
      </a>
      <a href="#" class="action-btn btn-whatsapp" id="modalWhatsapp">
        <i class="fab fa-whatsapp"></i> WhatsApp
      </a>
      <button class="action-btn btn-secondary" onclick="closeLabModal()">
        <i class="fas fa-times"></i> Fechar
      </button>
    </div>
  </div>
</div>

<script>
// Dados dos laboratórios (em um cenário real, isso viria do banco de dados)
const labsData = {
  'lab-prudente': {
    name: 'LabPrudente',
    specialty: 'Análises Clínicas',
    about: 'Laboratório de referência em Presidente Prudente, com mais de 20 anos de experiência em análises clínicas e atendimento humanizado. Possui certificação de qualidade e equipe altamente qualificada.',
    location: 'Rua João de Góes, 1234 - Centro, Presidente Prudente - SP',
    hours: 'Segunda a Sexta: 6h às 20h | Sábado: 6h às 18h | Domingo: 7h às 12h | Plantão 24h para urgências',
    services: ['Hemograma Completo', 'Colesterol Total e Frações', 'Glicemia', 'Triglicerídeos', 'Função Renal', 'Função Hepática', 'TSH e T4 Livre', 'Vitamina D', 'PCR', 'COVID-19'],
    whatsapp: '5518999999999'
  },
  'lab-imagem': {
    name: 'Imagem Diagnóstica Prudente',
    specialty: 'Diagnóstico por Imagem',
    about: 'Especializado em exames de imagem de alta complexidade, com equipamentos de última geração e laudos rápidos. Atendimento humanizado e ambiente climatizado.',
    location: 'Av. Coronel Marcondes, 567 - Vila Formosa, Presidente Prudente - SP',
    hours: 'Segunda a Sexta: 7h às 19h | Sábado: 7h às 13h | Domingo: 8h às 12h',
    services: ['Ressonância Magnética', 'Tomografia Computadorizada', 'Ultrassonografia', 'Mamografia Digital', 'Densitometria Óssea', 'Raio-X Digital', 'Doppler Colorido', 'Ecocardiograma'],
    whatsapp: '5518999999998'
  },
  'lab-vip': {
    name: 'LabVip Saúde',
    specialty: 'Análises Clínicas',
    about: 'Laboratório focado em comodidade, oferecendo coleta domiciliar e resultados online. Atendimento personalizado e ágil, com foco na experiência do paciente.',
    location: 'Rua Siqueira Campos, 890 - Jardim Planalto, Presidente Prudente - SP',
    hours: 'Segunda a Sexta: 6h às 18h | Sábado: 6h às 12h | Coleta domiciliar: Segunda a Sábado, 7h às 17h',
    services: ['Coleta Domiciliar', 'Resultados Online', 'Check-up Executivo', 'PCR', 'Vitamina D', 'TSH', 'Proteína C Reativa', 'Ferritina', 'Ácido Úrico', 'Creatinina'],
    whatsapp: '5518999999997'
  }
};

// Filtro de laboratórios
function filtrarLaboratorios() {
  const termo = document.getElementById('search').value.toLowerCase();
  const especialidade = document.getElementById('filter-specialty').value;
  const disponibilidade = document.getElementById('filter-availability').value;
  const cards = document.querySelectorAll('.lab-card');
  
  let visibleCount = 0;
  
  cards.forEach(card => {
    const nome = card.querySelector('.lab-name').innerText.toLowerCase();
    const especialidadeCard = card.querySelector('.lab-specialty').innerText.toLowerCase();
    const cardEspecialidade = card.getAttribute('data-specialty');
    const cardDisponibilidade = card.getAttribute('data-availability');
    
    const termoMatch = nome.includes(termo) || especialidadeCard.includes(termo);
    const especialidadeMatch = !especialidade || cardEspecialidade === especialidade;
    const disponibilidadeMatch = !disponibilidade || cardDisponibilidade === disponibilidade;
    
    const exibir = termoMatch && especialidadeMatch && disponibilidadeMatch;
    card.style.display = exibir ? 'flex' : 'none';
    
    if (exibir) visibleCount++;
  });
  
  // Atualizar contador de resultados
  document.getElementById('results-count').textContent = visibleCount;
  
  // Mostrar mensagem se não houver resultados
  const noResults = document.getElementById('no-results');
  if (visibleCount === 0) {
    if (!noResults) {
      const container = document.getElementById('cards-container');
      const noResultsDiv = document.createElement('div');
      noResultsDiv.id = 'no-results';
      noResultsDiv.className = 'no-results';
      noResultsDiv.innerHTML = `
        <i class="fas fa-search"></i>
        <h3>Nenhum laboratório encontrado</h3>
        <p>Tente ajustar os filtros ou termos de busca.</p>
      `;
      container.appendChild(noResultsDiv);
    }
  } else if (noResults) {
    noResults.remove();
  }
}

// Alternar entre visualização em grade e lista
document.getElementById('view-grid').addEventListener('click', function() {
  const container = document.getElementById('cards-container');
  container.className = 'cards-grid';
  this.classList.add('active');
  document.getElementById('view-list').classList.remove('active');
});

document.getElementById('view-list').addEventListener('click', function() {
  const container = document.getElementById('cards-container');
  container.className = 'cards-list';
  this.classList.add('active');
  document.getElementById('view-grid').classList.remove('active');
});

// Modal do laboratório
function showLabModal(labId) {
  const modal = document.getElementById('labModal');
  const lab = labsData[labId];
  
  if (lab) {
    document.getElementById('modalName').textContent = lab.name;
    document.getElementById('modalSpecialty').textContent = lab.specialty;
    document.getElementById('modalAbout').textContent = lab.about;
    document.getElementById('modalLocation').textContent = lab.location;
    document.getElementById('modalHours').textContent = lab.hours;
    
    // Limpar e adicionar serviços
    const servicesContainer = document.getElementById('modalServices');
    servicesContainer.innerHTML = '';
    
    lab.services.forEach(service => {
      const serviceItem = document.createElement('div');
      serviceItem.className = 'modal-service-item';
      serviceItem.textContent = service;
      servicesContainer.appendChild(serviceItem);
    });
    
    // Configurar WhatsApp
    const whatsappBtn = document.getElementById('modalWhatsapp');
    whatsappBtn.href = `https://wa.me/${lab.whatsapp}?text=Olá, gostaria de mais informações sobre os exames do ${lab.name}`;
    
    modal.style.display = 'flex';
  }
}

function closeLabModal() {
  const modal = document.getElementById('labModal');
  modal.style.display = 'none';
}

// Fechar modal clicando fora do conteúdo
document.getElementById('labModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeLabModal();
  }
});

// Event listeners para os filtros
document.getElementById('search').addEventListener('input', filtrarLaboratorios);
document.getElementById('filter-specialty').addEventListener('change', filtrarLaboratorios);
document.getElementById('filter-availability').addEventListener('change', filtrarLaboratorios);

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
  filtrarLaboratorios();
});
</script>
<?php include 'footer.php'; ?>
</body>
</html>