<?php
session_start();
require 'conexao.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['id'])) {
    header("Location: login_medico.php");
    exit;
}

$medico_id = $_SESSION['id'];

// Busca dados do médico (nome, imagem e especialidade)
$sql = "SELECT m.nome, m.imagem, e.nome AS especialidade
        FROM medicos m
        LEFT JOIN especialidades e ON m.especialidade_id = e.id
        WHERE m.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$medico_id]);
$med = $stmt->fetch(PDO::FETCH_ASSOC);

$nome = isset($med['nome']) ? $med['nome'] : (isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '');
$especialidade = isset($med['especialidade']) ? $med['especialidade'] : (isset($_SESSION['especialidade']) ? $_SESSION['especialidade'] : '');
$imagem = isset($med['imagem']) ? $med['imagem'] : (isset($_SESSION['imagem']) ? $_SESSION['imagem'] : '');


// Determina o src da imagem (img/ se existir, senão fallback)
$src_imagem = (!empty($imagem) && file_exists(__DIR__ . '/img/' . $imagem)) 
    ? 'img/' . $imagem 
    : 'img/default_doctor.jpg';

// Estatísticas de consultas
$stmt = $pdo->prepare("SELECT 
    SUM(status = 'Agendada') AS agendadas,
    SUM(status = 'Realizada') AS realizadas,
    SUM(status = 'Cancelada') AS canceladas
    FROM consultas WHERE medico_id = ?");
$stmt->execute([$medico_id]); 
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Próxima consulta
$stmt_proxima = $pdo->prepare("SELECT 
    c.data, c.hora, 
    COALESCE(p.nome, c.nome_paciente_manual) AS nome_paciente
    FROM consultas c
    LEFT JOIN pacientes p ON c.paciente_id = p.id
    WHERE c.medico_id = ? AND c.status = 'Agendada' AND c.data >= CURDATE()
    ORDER BY c.data, c.hora ASC
    LIMIT 1");
$stmt_proxima->execute([$medico_id]);
$proxima_consulta = $stmt_proxima->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Médico - MedClick</title>
  <link rel="shortcut icon" type="image/x-icon" href="ico/Med-Click_1.ico">
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
    
    .profile-section {
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin-bottom: 25px;
    }
    
    .profile-img {
      width: 130px;
      height: 130px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid white;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card {
      border-radius: 12px;
      border: none;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .stats-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stats-icon {
      font-size: 2.5rem;
      opacity: 0.9;
    }
    
    .bg-agendadas {
      background: linear-gradient(135deg, var(--warning), #f57c00);
    }
    
    .bg-realizadas {
      background: linear-gradient(135deg, var(--secondary), #0f9d58);
    }
    
    .bg-canceladas {
      background: linear-gradient(135deg, var(--danger), #d93025);
    }
    
    .next-appointment {
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
      padding: 25px;
    }
    
    .appointment-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--primary-light);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      font-size: 1.5rem;
    }
    
    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary), #0d47a1);
      border: none;
      border-radius: 8px;
      padding: 12px 25px;
      font-weight: 500;
      box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
      transition: all 0.3s;
    }
    
    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(26, 115, 232, 0.4);
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
    
    footer {
      background: white;
      padding: 20px 0;
      margin-top: 40px;
      box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .welcome-text {
      color: var(--gray);
      font-size: 1.1rem;
    }
    
    .no-appointment {
      background: var(--primary-light);
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      color: var(--primary);
    }
  </style>
</head>
<body>

<?php include 'header_medico.php'; ?>

<div class="container py-4">
  <!-- Profile -->
  <div class="profile-section text-center">
    <div class="row align-items-center">
      <div class="col-md-3 text-center">
        <img src="<?= htmlspecialchars($src_imagem) ?>" alt="Foto de perfil" class="profile-img mb-3">
      </div>
      <div class="col-md-9 text-md-start">
        <h2 class="mb-1"><?= htmlspecialchars($nome) ?></h2>
        <p class="text-muted mb-2"><?= htmlspecialchars($especialidade) ?></p>
        <p class="welcome-text">Bem-vindo ao seu painel MedClick. Aqui você pode gerenciar suas consultas e acompanhar seu desempenho.</p>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <h4 class="section-title">Visão Geral</h4>
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="card stats-card text-white bg-agendadas">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5>Consultas Agendadas</h5>
            <h2 class="mb-0"><?php echo isset($dados['agendadas']) ? $dados['agendadas'] : 0; ?></h2>
          </div>
          <div class="stats-icon"><i class="fas fa-calendar-check"></i></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stats-card text-white bg-realizadas">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5>Consultas Realizadas</h5>
            <h2 class="mb-0"><?php echo isset($dados['realizadas']) ? $dados['realizadas'] : 0; ?></h2>
          </div>
          <div class="stats-icon"><i class="fas fa-check-circle"></i></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stats-card text-white bg-canceladas">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5>Consultas Canceladas</h5>
            <h2 class="mb-0"><?php echo isset($dados['canceladas']) ? $dados['canceladas'] : 0; ?></h2>
          </div>
          <div class="stats-icon"><i class="fas fa-times-circle"></i></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Next Appointment -->
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="next-appointment">
        <h4 class="section-title">Próxima Consulta</h4>
        <?php if ($proxima_consulta): ?>
          <div class="d-flex align-items-center">
            <div class="appointment-icon me-4"><i class="fas fa-clock"></i></div>
            <div>
              <h5><?= htmlspecialchars($proxima_consulta['nome_paciente']) ?></h5>
              <p class="mb-0 text-muted"><?= date('d/m/Y', strtotime($proxima_consulta['data'])) ?> às <?= date('H:i', strtotime($proxima_consulta['hora'])) ?></p>
            </div>
          </div>
        <?php else: ?>
          <div class="no-appointment"><i class="fas fa-calendar-times me-2"></i> Nenhuma consulta agendada</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="next-appointment h-100">
        <h4 class="section-title">Ações Rápidas</h4>
        <div class="d-grid gap-2">
          <a href="agenda_medico.php" class="btn btn-primary-custom"><i class="fas fa-calendar-alt me-2"></i> Ver Agenda Completa</a>
          <a href="cadastrar_horarios.php" class="btn btn-outline-primary"><i class="fas fa-plus-circle me-2"></i> Cadastrar Horários</a>
          <a href="historico_consultas.php" class="btn btn-outline-secondary"><i class="fas fa-history me-2"></i> Histórico de Consultas</a>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include"footer_medico.php"?>
</html>