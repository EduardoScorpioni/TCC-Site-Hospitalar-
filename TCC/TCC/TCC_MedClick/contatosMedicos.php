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
    <title>Contatos Médicos - MedClick</title>
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

        .cards-list .doctor-card {
            flex-direction: row;
            height: auto;
        }

        .cards-list .doctor-image {
            width: 120px;
            height: 120px;
            border-radius: 10px 0 0 10px;
        }

        .cards-list .doctor-content {
            flex: 1;
            padding: 25px;
        }

        /* Card de Médico */
        .doctor-card {
            background-color: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .doctor-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .doctor-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .doctor-name {
            font-size: 1.3rem;
            color: var(--russian-violet);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .doctor-specialty {
            color: var(--slate-blue);
            font-weight: 500;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .doctor-details {
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

        .doctor-bio {
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.5;
            flex: 1;
        }

        .doctor-actions {
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

        .doctor-modal {
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
            max-width: 600px;
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
            
            .cards-list .doctor-card {
                flex-direction: column;
            }
            
            .cards-list .doctor-image {
                width: 100%;
                border-radius: 10px 10px 0 0;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .doctor-actions {
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
    <h1 class="page-title">Nossos Médicos</h1>
    <p class="page-subtitle">Conheça nossa equipe de profissionais qualificados e agende sua consulta</p>
  </div>

  <div class="controls-row">
    <div class="search-box">
      <input type="text" id="search" placeholder="Buscar médico por nome, especialidade ou tratamento...">
      <i class="fas fa-search"></i>
    </div>
    
    <div class="filter-controls">
      <select class="filter-select" id="filter-specialty">
        <option value="">Todas as especialidades</option>
        <option value="cardiologia">Cardiologia</option>
        <option value="dermatologia">Dermatologia</option>
        <option value="ortopedia">Ortopedia</option>
        <option value="pediatria">Pediatria</option>
        <option value="ginecologia">Ginecologia</option>
        <option value="neurologia">Neurologia</option>
      </select>
      
      <select class="filter-select" id="filter-availability">
        <option value="">Disponibilidade</option>
        <option value="disponivel">Disponível agora</option>
        <option value="consulta">Aceita novos pacientes</option>
      </select>
      
      <div class="view-toggle">
        <button class="view-btn active" id="view-grid"><i class="fas fa-th"></i></button>
        <button class="view-btn" id="view-list"><i class="fas fa-list"></i></button>
      </div>
    </div>
  </div>
  
  <div class="results-info" id="results-info">
    Mostrando <span id="results-count">6</span> de 6 médicos
  </div>

  <div id="cards-container" class="cards-grid">
    <!-- Médico 1 -->
    <div class="doctor-card" data-specialty="cardiologia" data-availability="disponivel">
      <img src="https://grupobrmed.com.br/blog/wp-content/uploads/elementor/thumbs/Artigo-26-Capa-Medico-do-Trabalho-r1za4k0v71ndz34nrixvqm1qohd7n84njtuy03a35m.jpg" alt="Dr. João Silva" class="doctor-image">
      <div class="doctor-content">
        <h3 class="doctor-name">Dr. João Silva</h3>
        <p class="doctor-specialty">Cardiologista</p>
        
        <div class="doctor-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.9</span>
          <span class="detail-item"><i class="fas fa-calendar-check"></i> Disponível</span>
          <span class="detail-item"><i class="fas fa-graduation-cap"></i> USP</span>
        </div>
        
        <p class="doctor-bio">Especialista em cardiologia com 15 anos de experiência. Atua principalmente em cardiologia preventiva e tratamento de doenças coronarianas.</p>
        
        <div class="doctor-actions">
          <button class="action-btn btn-primary" onclick="showDoctorModal('dr-joao')">
            <i class="fas fa-calendar"></i> Agendar Consulta
          </button>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dr-joao')">
            <i class="fas fa-info-circle"></i> Ver Perfil
          </button>
        </div>
      </div>
    </div>

    <!-- Médico 2 -->
    <div class="doctor-card" data-specialty="dermatologia" data-availability="consulta">
      <img src="https://blog.sinaxys.com/wp-content/uploads/2023/04/profissao-medico-profissao-medico.jpg" alt="Dra. Ana Souza" class="doctor-image">
      <div class="doctor-content">
        <h3 class="doctor-name">Dra. Ana Souza</h3>
        <p class="doctor-specialty">Dermatologista</p>
        
        <div class="doctor-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.8</span>
          <span class="detail-item"><i class="fas fa-user-plus"></i> Novos pacientes</span>
          <span class="detail-item"><i class="fas fa-graduation-cap"></i> UNIFESP</span>
        </div>
        
        <p class="doctor-bio">Dermatologista especializada em estética facial e corporal, tratamentos para acne e doenças da pele. Atendimento humanizado e personalizado.</p>
        
        <div class="doctor-actions">
          <button class="action-btn btn-primary" onclick="showDoctorModal('dra-ana')">
            <i class="fas fa-calendar"></i> Agendar Consulta
          </button>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dra-ana')">
            <i class="fas fa-info-circle"></i> Ver Perfil
          </button>
        </div>
      </div>
    </div>

    <!-- Médico 3 -->
    <div class="doctor-card" data-specialty="ortopedia" data-availability="disponivel">
      <img src="https://medicinasa.com.br/wp-content/uploads/2021/06/Sidney-Klajner-3b-jpg.jpg" alt="Dr. Carlos Oliveira" class="doctor-image">
      <div class="doctor-content">
        <h3 class="doctor-name">Dr. Carlos Oliveira</h3>
        <p class="doctor-specialty">Ortopedista</p>
        
        <div class="doctor-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.7</span>
          <span class="detail-item"><i class="fas fa-calendar-check"></i> Disponível</span>
          <span class="detail-item"><i class="fas fa-graduation-cap"></i> Santa Casa</span>
        </div>
        
        <p class="doctor-bio">Especialista em ortopedia e traumatologia, com foco em cirurgia do joelho e quadril. Atua há 12 anos na área com excelentes resultados.</p>
        
        <div class="doctor-actions">
          <button class="action-btn btn-primary" onclick="showDoctorModal('dr-carlos')">
            <i class="fas fa-calendar"></i> Agendar Consulta
          </button>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dr-carlos')">
            <i class="fas fa-info-circle"></i> Ver Perfil
          </button>
        </div>
      </div>
    </div>

    <!-- Médico 4 -->
    <div class="doctor-card" data-specialty="pediatria" data-availability="consulta">
      <img src="https://www.inspirali.com/app/uploads/2023/11/consultorio-medico.jpeg" alt="Dra. Maria Santos" class="doctor-image">
      <div class="doctor-content">
        <h3 class="doctor-name">Dra. Maria Santos</h3>
        <p class="doctor-specialty">Pediatra</p>
        
        <div class="doctor-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.9</span>
          <span class="detail-item"><i class="fas fa-user-plus"></i> Novos pacientes</span>
          <span class="detail-item"><i class="fas fa-graduation-cap"></i> UNICAMP</span>
        </div>
        
        <p class="doctor-bio">Pediatra com 10 anos de experiência, especializada em puericultura e acompanhamento do desenvolvimento infantil. Atendimento humanizado e acolhedor.</p>
        
        <div class="doctor-actions">
          <button class="action-btn btn-primary" onclick="showDoctorModal('dra-maria')">
            <i class="fas fa-calendar"></i> Agendar Consulta
          </button>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dra-maria')">
            <i class="fas fa-info-circle"></i> Ver Perfil
          </button>
        </div>
      </div>
    </div>

    <!-- Médico 5 -->
    <div class="doctor-card" data-specialty="ginecologia" data-availability="disponivel">
      <img src="https://media.istockphoto.com/id/1410064193/pt/foto/female-cardiologist-doctor-at-the-hospital-smiling-and-making-a-heart-shape.jpg?s=612x612&w=0&k=20&c=olUzEhNo_Vxh7wW_SzaMXk-o43w1hUWLh3hW_8ln5bc=" alt="Dra. Paula Costa" class="doctor-image">
      <div class="doctor-content">
        <h3 class="doctor-name">Dra. Paula Costa</h3>
        <p class="doctor-specialty">Ginecologista e Obstetra</p>
        
        <div class="doctor-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.8</span>
          <span class="detail-item"><i class="fas fa-calendar-check"></i> Disponível</span>
          <span class="detail-item"><i class="fas fa-graduation-cap"></i> FMABC</span>
        </div>
        
        <p class="doctor-bio">Ginecologista e obstetra com especialização em endocrinologia ginecológica e reprodução humana. Atendimento personalizado e empático.</p>
        
        <div class="doctor-actions">
          <button class="action-btn btn-primary" onclick="showDoctorModal('dra-paula')">
            <i class="fas fa-calendar"></i> Agendar Consulta
          </button>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dra-paula')">
            <i class="fas fa-info-circle"></i> Ver Perfil
          </button>
        </div>
      </div>
    </div>

    <!-- Médico 6 -->
    <div class="doctor-card" data-specialty="neurologia" data-availability="consulta">
      <img src="https://img.freepik.com/fotos-gratis/retrato-do-doutor-adulto-meados-de-bem-sucedido-com-bracos-cruzados_1262-12865.jpg" alt="Dr. Roberto Almeida" class="doctor-image">
      <div class="doctor-content">
        <h3 class="doctor-name">Dr. Roberto Almeida</h3>
        <p class="doctor-specialty">Neurologista</p>
        
        <div class="doctor-details">
          <span class="detail-item"><i class="fas fa-star"></i> 4.7</span>
          <span class="detail-item"><i class="fas fa-user-plus"></i> Novos pacientes</span>
          <span class="detail-item"><i class="fas fa-graduation-cap"></i> USP</span>
        </div>
        
        <p class="doctor-bio">Neurologista especializado em doenças cerebrovasculares e esclerose múltipla. Atua com tratamentos modernos e abordagem humanizada.</p>
        
        <div class="doctor-actions">
          <button class="action-btn btn-primary" onclick="showDoctorModal('dr-roberto')">
            <i class="fas fa-calendar"></i> Agendar Consulta
          </button>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dr-roberto')">
            <i class="fas fa-info-circle"></i> Ver Perfil
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

<!-- Modal para detalhes do médico -->
<div class="doctor-modal" id="doctorModal">
  <div class="modal-content">
    <div class="modal-close" onclick="closeDoctorModal()">
      <i class="fas fa-times"></i>
    </div>
    
    <div class="modal-header">
      <img src="car/img1 (1).jpg" alt="Médico" class="modal-image" id="modalImage">
      <div class="modal-info">
        <h2 id="modalName">Nome do Médico</h2>
        <p id="modalSpecialty">Especialidade</p>
      </div>
    </div>
    
    <div class="modal-details">
      <div class="modal-section">
        <h3><i class="fas fa-user-graduate"></i> Formação</h3>
        <p id="modalEducation">Informações de formação do médico...</p>
      </div>
      
      <div class="modal-section">
        <h3><i class="fas fa-briefcase"></i> Experiência</h3>
        <p id="modalExperience">Informações de experiência do médico...</p>
      </div>
      
      <div class="modal-section">
        <h3><i class="fas fa-stethoscope"></i> Especializações</h3>
        <p id="modalSpecializations">Especializações do médico...</p>
      </div>
      
      <div class="modal-section">
        <h3><i class="fas fa-clock"></i> Disponibilidade</h3>
        <p id="modalAvailability">Horários de disponibilidade...</p>
      </div>
    </div>
    
    <div class="modal-actions">
      <a href="AgendarConsulta.php" class="action-btn btn-primary">
        <i class="fas fa-calendar"></i> Agendar Consulta
      </a>
      <button class="action-btn btn-secondary" onclick="closeDoctorModal()">
        <i class="fas fa-times"></i> Fechar
      </button>
    </div>
  </div>
</div>

<script>
// Dados dos médicos (em um cenário real, isso viria do banco de dados)
const doctorsData = {
  'dr-joao': {
    name: 'Dr. João Silva',
    specialty: 'Cardiologista',
    education: 'Graduação em Medicina pela USP, Residência em Cardiologia pelo Incor, Especialização em Cardiologia Intervencionista',
    experience: '15 anos de experiência, ex-chefe do departamento de cardiologia do Hospital Sírio-Libanês',
    specializations: 'Cardiologia preventiva, Doenças coronarianas, Hipertensão arterial',
    availability: 'Segunda a sexta: 8h-18h, Sábado: 8h-12h'
  },
  'dra-ana': {
    name: 'Dra. Ana Souza',
    specialty: 'Dermatologista',
    education: 'Graduação em Medicina pela UNIFESP, Residência em Dermatologia pela Santa Casa, Fellowship em Dermatologia Estética',
    experience: '12 anos de experiência, membro da Sociedade Brasileira de Dermatologia',
    specializations: 'Dermatologia estética, Tratamento de acne, Dermatoscopia',
    availability: 'Terça a quinta: 9h-17h, Sexta: 9h-16h'
  },
  'dr-carlos': {
    name: 'Dr. Carlos Oliveira',
    specialty: 'Ortopedista',
    education: 'Graduação em Medicina pela Santa Casa, Residência em Ortopedia e Traumatologia, Especialização em Cirurgia do Joelho',
    experience: '12 anos de experiência, professor convidado de ortopedia na FMUSP',
    specializations: 'Cirurgia do joelho, Artroscopia, Próteses articulares',
    availability: 'Segunda a quinta: 8h-19h, Sexta: 8h-17h'
  },
  'dra-maria': {
    name: 'Dra. Maria Santos',
    specialty: 'Pediatra',
    education: 'Graduação em Medicina pela UNICAMP, Residência em Pediatria, Especialização em Puericultura',
    experience: '10 anos de experiência, autora de artigos sobre desenvolvimento infantil',
    specializations: 'Puericultura, Aleitamento materno, Vacinação',
    availability: 'Segunda a sexta: 8h-17h, Plantões aos sábados alternados'
  },
  'dra-paula': {
    name: 'Dra. Paula Costa',
    specialty: 'Ginecologista e Obstetra',
    education: 'Graduação em Medicina pela FMABC, Residência em Ginecologia e Obstetrícia, Especialização em Endocrinologia Ginecológica',
    experience: '9 anos de experiência, atuação em reprodução humana assistida',
    specializations: 'Ginecologia endócrina, Obstetrícia de alto risco, Climatério',
    availability: 'Segunda, quarta e sexta: 9h-18h, Terça e quinta: 14h-20h'
  },
  'dr-roberto': {
    name: 'Dr. Roberto Almeida',
    specialty: 'Neurologista',
    education: 'Graduação em Medicina pela USP, Residência em Neurologia, Fellowship em Doenças Cerebrovasculares',
    experience: '11 anos de experiência, pesquisador em esclerose múltipla',
    specializations: 'Doenças cerebrovasculares, Esclerose múltipla, Cefaleias',
    availability: 'Segunda a quinta: 10h-19h, Sexta: 10h-16h'
  }
};

// Filtro de médicos
function filtrarMedicos() {
  const termo = document.getElementById('search').value.toLowerCase();
  const especialidade = document.getElementById('filter-specialty').value;
  const disponibilidade = document.getElementById('filter-availability').value;
  const cards = document.querySelectorAll('.doctor-card');
  
  let visibleCount = 0;
  
  cards.forEach(card => {
    const nome = card.querySelector('.doctor-name').innerText.toLowerCase();
    const especialidadeCard = card.querySelector('.doctor-specialty').innerText.toLowerCase();
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
        <h3>Nenhum médico encontrado</h3>
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

// Modal do médico
function showDoctorModal(doctorId) {
  const modal = document.getElementById('doctorModal');
  const doctor = doctorsData[doctorId];
  
  if (doctor) {
    document.getElementById('modalName').textContent = doctor.name;
    document.getElementById('modalSpecialty').textContent = doctor.specialty;
    document.getElementById('modalEducation').textContent = doctor.education;
    document.getElementById('modalExperience').textContent = doctor.experience;
    document.getElementById('modalSpecializations').textContent = doctor.specializations;
    document.getElementById('modalAvailability').textContent = doctor.availability;
    
    modal.style.display = 'flex';
  }
}

function closeDoctorModal() {
  const modal = document.getElementById('doctorModal');
  modal.style.display = 'none';
}

// Fechar modal clicando fora do conteúdo
document.getElementById('doctorModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeDoctorModal();
  }
});

// Event listeners para os filtros
document.getElementById('search').addEventListener('input', filtrarMedicos);
document.getElementById('filter-specialty').addEventListener('change', filtrarMedicos);
document.getElementById('filter-availability').addEventListener('change', filtrarMedicos);

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
  filtrarMedicos();
});
</script>
<?php include 'footer.php'; ?>
</body>
</html>

<!--
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
    <meta charset="UTF-8">
    <title>Contatos Médicos - MedClick</title>
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
            background: linear-gradient(135deg, #f8fafc 0%, #f0f7ff 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        main {
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .page-title {
            font-size: 2.8rem;
            color: var(--russian-violet);
            margin-bottom: 15px;
            font-weight: 700;
            position: relative;
            display: inline-block;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 5px;
            background: var(--gradient-accent);
            border-radius: 3px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 1.2rem;
            max-width: 700px;
            margin: 25px auto 0;
            line-height: 1.6;
        }

        .specialty-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 40px;
            padding: 0 20px;
        }

        .specialty-tab {
            padding: 12px 25px;
            border: none;
            border-radius: 30px;
            background: var(--white);
            color: var(--slate-blue);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .specialty-tab:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .specialty-tab.active {
            background: var(--gradient-primary);
            color: var(--white);
        }

        .search-container {
            max-width: 600px;
            margin: 0 auto 40px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 16px 50px 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
            box-shadow: var(--shadow-sm);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--slate-blue);
            box-shadow: 0 0 0 3px rgba(112, 93, 188, 0.2);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-blue);
            font-size: 1.2rem;
        }

        /* Layout Hexagonal */
        .hexagon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .hexagon-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            position: relative;
            border-top: 5px solid var(--teal);
        }

        .hexagon-card:hover {
            transform: translateY(-8px) rotate(1deg);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .doctor-header {
            padding: 25px 25px 20px;
            background: linear-gradient(135deg, var(--russian-violet) 0%, var(--russian-violet-2) 100%);
            color: var(--white);
            position: relative;
        }

        .doctor-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--white);
            margin: 0 auto 15px;
            display: block;
            box-shadow: var(--shadow-md);
        }

        .doctor-name {
            font-size: 1.4rem;
            text-align: center;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .doctor-specialty {
            text-align: center;
            font-size: 1rem;
            color: var(--mindaro);
            font-weight: 500;
        }

        .doctor-content {
            padding: 25px;
        }

        .doctor-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #64748b;
        }

        .detail-item i {
            color: var(--slate-blue);
            font-size: 1.1rem;
            width: 20px;
        }

        .doctor-bio {
            color: #64748b;
            margin-bottom: 25px;
            line-height: 1.6;
            font-size: 0.95rem;
            border-left: 3px solid var(--teal);
            padding-left: 15px;
        }

        .doctor-actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .availability-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }

        .badge-available {
            background: var(--yellow-green);
            color: var(--russian-violet);
        }

        .badge-busy {
            background: #fef3c7;
            color: #92400e;
        }

        .rating {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 10px;
        }

        .rating i {
            color: #fbbf24;
            font-size: 0.9rem;
        }

        .rating-value {
            color: var(--white);
            font-weight: 600;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }

        .no-results i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .no-results h3 {
            color: #64748b;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .no-results p {
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .view-more {
            text-align: center;
            margin-top: 40px;
        }

        .view-more-btn {
            padding: 14px 35px;
            border: 2px solid var(--slate-blue);
            border-radius: 30px;
            background: transparent;
            color: var(--slate-blue);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .view-more-btn:hover {
            background: var(--slate-blue);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        @media (max-width: 1024px) {
            .hexagon-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2.2rem;
            }
            
            .specialty-tabs {
                gap: 10px;
            }
            
            .specialty-tab {
                padding: 10px 18px;
                font-size: 0.9rem;
            }
            
            .doctor-details {
                grid-template-columns: 1fr;
            }
            
            .doctor-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            main {
                padding: 20px 15px;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .hexagon-grid {
                grid-template-columns: 1fr;
            }
            
            .specialty-tabs {
                justify-content: flex-start;
                overflow-x: auto;
                padding-bottom: 10px;
            }
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hexagon-card {
            animation: fadeIn 0.6s ease forwards;
        }

        .hexagon-card:nth-child(2) { animation-delay: 0.1s; }
        .hexagon-card:nth-child(3) { animation-delay: 0.2s; }
        .hexagon-card:nth-child(4) { animation-delay: 0.3s; }
        .hexagon-card:nth-child(5) { animation-delay: 0.4s; }
        .hexagon-card:nth-child(6) { animation-delay: 0.5s; }
    </style>
</head>
<body>


<main>
  <div class="page-header">
    <h1 class="page-title">Nossa Equipe Médica</h1>
    <p class="page-subtitle">Conheça nossos especialistas dedicados ao seu bem-estar. Profissionais qualificados prontos para oferecer o melhor cuidado à sua saúde.</p>
  </div>

  <div class="specialty-tabs">
    <button class="specialty-tab active" data-specialty="all">
      <i class="fas fa-star"></i> Todos
    </button>
    <button class="specialty-tab" data-specialty="cardiologia">
      <i class="fas fa-heart"></i> Cardiologia
    </button>
    <button class="specialty-tab" data-specialty="dermatologia">
      <i class="fas fa-spa"></i> Dermatologia
    </button>
    <button class="specialty-tab" data-specialty="ortopedia">
      <i class="fas fa-bone"></i> Ortopedia
    </button>
    <button class="specialty-tab" data-specialty="pediatria">
      <i class="fas fa-baby"></i> Pediatria
    </button>
    <button class="specialty-tab" data-specialty="ginecologia">
      <i class="fas fa-female"></i> Ginecologia
    </button>
  </div>

  <div class="search-container">
    <input type="text" class="search-box" id="search" placeholder="Buscar médico por nome, especialidade ou tratamento...">
    <i class="fas fa-search search-icon"></i>
  </div>

  <div class="hexagon-grid" id="cards-container">
   Médico 1
    <div class="hexagon-card" data-specialty="cardiologia" data-availability="available">
      <span class="availability-badge badge-available">Disponível</span>
      <div class="doctor-header">
        <img src="car/img1 (1).jpg" alt="Dr. João Silva" class="doctor-image">
        <h3 class="doctor-name">Dr. João Silva</h3>
        <p class="doctor-specialty">Cardiologista</p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star-half-alt"></i>
          <span class="rating-value">4.9</span>
        </div>
      </div>
      <div class="doctor-content">
        <div class="doctor-details">
          <div class="detail-item">
            <i class="fas fa-graduation-cap"></i>
            <span>USP</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-award"></i>
            <span>15 anos</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-clock"></i>
            <span>Seg-Sex: 8h-18h</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Unidade Centro</span>
          </div>
        </div>
        <p class="doctor-bio">Especialista em cardiologia preventiva e tratamento de doenças coronarianas. Abordagem humanizada e foco na saúde integral do paciente.</p>
        <div class="doctor-actions">
          <a href="AgendarConsulta.php" class="action-btn btn-primary">
            <i class="fas fa-calendar-check"></i> Agendar
          </a>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dr-joao')">
            <i class="fas fa-user-md"></i> Perfil
          </button>
        </div>
      </div>
    </div>


    <div class="hexagon-card" data-specialty="dermatologia" data-availability="available">
      <span class="availability-badge badge-available">Disponível</span>
      <div class="doctor-header">
        <img src="car/img1 (1).jpg" alt="Dra. Ana Souza" class="doctor-image">
        <h3 class="doctor-name">Dra. Ana Souza</h3>
        <p class="doctor-specialty">Dermatologista</p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <span class="rating-value">4.8</span>
        </div>
      </div>
      <div class="doctor-content">
        <div class="doctor-details">
          <div class="detail-item">
            <i class="fas fa-graduation-cap"></i>
            <span>UNIFESP</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-award"></i>
            <span>12 anos</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-clock"></i>
            <span>Ter-Qui: 9h-17h</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Unidade Paulista</span>
          </div>
        </div>
        <p class="doctor-bio">Especializada em dermatologia estética e tratamento de doenças da pele. Desenvolve tratamentos personalizados para cada paciente.</p>
        <div class="doctor-actions">
          <a href="AgendarConsulta.php" class="action-btn btn-primary">
            <i class="fas fa-calendar-check"></i> Agendar
          </a>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dra-ana')">
            <i class="fas fa-user-md"></i> Perfil
          </button>
        </div>
      </div>
    </div>


    <div class="hexagon-card" data-specialty="ortopedia" data-availability="busy">
      <span class="availability-badge badge-busy">Consultoria</span>
      <div class="doctor-header">
        <img src="car/img1 (1).jpg" alt="Dr. Carlos Oliveira" class="doctor-image">
        <h3 class="doctor-name">Dr. Carlos Oliveira</h3>
        <p class="doctor-specialty">Ortopedista</p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <span class="rating-value">4.7</span>
        </div>
      </div>
      <div class="doctor-content">
        <div class="doctor-details">
          <div class="detail-item">
            <i class="fas fa-graduation-cap"></i>
            <span>Santa Casa</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-award"></i>
            <span>12 anos</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-clock"></i>
            <span>Seg-Qui: 8h-19h</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Unidade Centro</span>
          </div>
        </div>
        <p class="doctor-bio">Especialista em ortopedia e traumatologia, com foco em cirurgia do joelho e quadril. Atua com técnicas minimamente invasivas.</p>
        <div class="doctor-actions">
          <a href="AgendarConsulta.php" class="action-btn btn-primary">
            <i class="fas fa-calendar-check"></i> Agendar
          </a>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dr-carlos')">
            <i class="fas fa-user-md"></i> Perfil
          </button>
        </div>
      </div>
    </div>


    <div class="hexagon-card" data-specialty="pediatria" data-availability="available">
      <span class="availability-badge badge-available">Disponível</span>
      <div class="doctor-header">
        <img src="car/img1 (1).jpg" alt="Dra. Maria Santos" class="doctor-image">
        <h3 class="doctor-name">Dra. Maria Santos</h3>
        <p class="doctor-specialty">Pediatra</p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star-half-alt"></i>
          <span class="rating-value">4.9</span>
        </div>
      </div>
      <div class="doctor-content">
        <div class="doctor-details">
          <div class="detail-item">
            <i class="fas fa-graduation-cap"></i>
            <span>UNICAMP</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-award"></i>
            <span>10 anos</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-clock"></i>
            <span>Seg-Sex: 8h-17h</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Unidade Infantil</span>
          </div>
        </div>
        <p class="doctor-bio">Pediatra especializada em puericultura e acompanhamento do desenvolvimento infantil. Atendimento humanizado e acolhedor para crianças.</p>
        <div class="doctor-actions">
          <a href="AgendarConsulta.php" class="action-btn btn-primary">
            <i class="fas fa-calendar-check"></i> Agendar
          </a>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dra-maria')">
            <i class="fas fa-user-md"></i> Perfil
          </button>
        </div>
      </div>
    </div>


    <div class="hexagon-card" data-specialty="ginecologia" data-availability="available">
      <span class="availability-badge badge-available">Disponível</span>
      <div class="doctor-header">
        <img src="car/img1 (1).jpg" alt="Dra. Paula Costa" class="doctor-image">
        <h3 class="doctor-name">Dra. Paula Costa</h3>
        <p class="doctor-specialty">Ginecologista</p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <span class="rating-value">4.8</span>
        </div>
      </div>
      <div class="doctor-content">
        <div class="doctor-details">
          <div class="detail-item">
            <i class="fas fa-graduation-cap"></i>
            <span>FMABC</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-award"></i>
            <span>9 anos</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-clock"></i>
            <span>Seg-Qua-Sex: 9h-18h</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Unidade Mulher</span>
          </div>
        </div>
        <p class="doctor-bio">Ginecologista e obstetra com especialização em endocrinologia ginecológica. Atua com foco na saúde integral da mulher em todas as fases da vida.</p>
        <div class="doctor-actions">
          <a href="AgendarConsulta.php" class="action-btn btn-primary">
            <i class="fas fa-calendar-check"></i> Agendar
          </a>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dra-paula')">
            <i class="fas fa-user-md"></i> Perfil
          </button>
        </div>
      </div>
    </div>


    <div class="hexagon-card" data-specialty="neurologia" data-availability="busy">
      <span class="availability-badge badge-busy">Consultoria</span>
      <div class="doctor-header">
        <img src="car/img1 (1).jpg" alt="Dr. Roberto Almeida" class="doctor-image">
        <h3 class="doctor-name">Dr. Roberto Almeida</h3>
        <p class="doctor-specialty">Neurologista</p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star-half-alt"></i>
          <span class="rating-value">4.7</span>
        </div>
      </div>
      <div class="doctor-content">
        <div class="doctor-details">
          <div class="detail-item">
            <i class="fas fa-graduation-cap"></i>
            <span>USP</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-award"></i>
            <span>11 anos</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-clock"></i>
            <span>Ter-Qui: 10h-19h</span>
          </div>
          <div class="detail-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Unidade Especializada</span>
          </div>
        </div>
        <p class="doctor-bio">Neurologista especializado em doenças cerebrovasculares e esclerose múltipla. Desenvolve pesquisas e tratamentos inovadores na área neurológica.</p>
        <div class="doctor-actions">
          <a href="AgendarConsulta.php" class="action-btn btn-primary">
            <i class="fas fa-calendar-check"></i> Agendar
          </a>
          <button class="action-btn btn-secondary" onclick="showDoctorModal('dr-roberto')">
            <i class="fas fa-user-md"></i> Perfil
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="view-more">
    <button class="view-more-btn">
      <i class="fas fa-plus"></i> Ver Mais Médicos
    </button>
  </div>
</main>

<script>
// Filtro de médicos
function filtrarMedicos() {
  const termo = document.getElementById('search').value.toLowerCase();
  const tabs = document.querySelectorAll('.specialty-tab');
  let especialidadeAtiva = 'all';
  
  // Encontrar a especialidade ativa
  tabs.forEach(tab => {
    if (tab.classList.contains('active')) {
      especialidadeAtiva = tab.getAttribute('data-specialty');
    }
  });
  
  const cards = document.querySelectorAll('.hexagon-card');
  let visibleCount = 0;
  
  cards.forEach(card => {
    const nome = card.querySelector('.doctor-name').innerText.toLowerCase();
    const especialidadeCard = card.querySelector('.doctor-specialty').innerText.toLowerCase();
    const cardEspecialidade = card.getAttribute('data-specialty');
    
    const termoMatch = nome.includes(termo) || especialidadeCard.includes(termo);
    const especialidadeMatch = especialidadeAtiva === 'all' || cardEspecialidade === especialidadeAtiva;
    
    const exibir = termoMatch && especialidadeMatch;
    card.style.display = exibir ? 'block' : 'none';
    
    if (exibir) visibleCount++;
  });
  
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
        <h3>Nenhum médico encontrado</h3>
        <p>Tente ajustar os filtros ou termos de busca.</p>
      `;
      container.appendChild(noResultsDiv);
    }
  } else if (noResults) {
    noResults.remove();
  }
}

// Alternar abas de especialidade
document.querySelectorAll('.specialty-tab').forEach(tab => {
  tab.addEventListener('click', function() {
    document.querySelectorAll('.specialty-tab').forEach(t => t.classList.remove('active'));
    this.classList.add('active');
    filtrarMedicos();
  });
});

// Modal do médico (simplificado para este exemplo)
function showDoctorModal(doctorId) {
  alert('Visualizando perfil do médico: ' + doctorId);
  // Em implementação real, isso abriria um modal com informações detalhadas
}

// Event listeners para os filtros
document.getElementById('search').addEventListener('input', filtrarMedicos);

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
  filtrarMedicos();
});
</script>

</body>
</html>