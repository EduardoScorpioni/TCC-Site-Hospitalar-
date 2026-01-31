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

$sql = "
  SELECT 
    c.id_consulta,
    c.data,
    c.hora,
    c.status,
    COALESCE(p.nome, c.nome_paciente_manual) AS nome_paciente
  FROM consultas c
  LEFT JOIN pacientes p ON c.paciente_id = p.id
  WHERE c.medico_id = ?
  ORDER BY c.data DESC, c.hora DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_medico]);
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerenciar Consultas - MedClick</title>
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
    
    .table-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin-bottom: 30px;
      overflow: hidden;
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
    
    .table-custom {
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
    }
    
    .table-custom thead th {
      background: linear-gradient(135deg, var(--primary), #0d47a1);
      color: white;
      font-weight: 500;
      padding: 15px;
      border: none;
    }
    
    .table-custom tbody tr {
      transition: all 0.3s;
    }
    
    .table-custom tbody tr:hover {
      background-color: var(--primary-light);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }
    
    .table-custom td {
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid #e8f0fe;
    }
    
    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .status-agendada {
      background: linear-gradient(135deg, var(--warning), #f57c00);
      color: white;
    }
    
    .status-realizada {
      background: linear-gradient(135deg, var(--secondary), #0f9d58);
      color: white;
    }
    
    .status-cancelada {
      background: linear-gradient(135deg, var(--danger), #d93025);
      color: white;
    }
    
    .status-adiada {
      background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
      color: white;
    }
    
    .btn-action {
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 0.85rem;
      font-weight: 500;
      transition: all 0.3s;
    }
    
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn-success-custom {
      background: linear-gradient(135deg, var(--secondary), #0f9d58);
      border: none;
      color: white;
    }
    
    .btn-danger-custom {
      background: linear-gradient(135deg, var(--danger), #d93025);
      border: none;
      color: white;
    }
    
    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary), #0d47a1);
      border: none;
      color: white;
    }
    
    .btn-outline-primary-custom {
      background: transparent;
      border: 1px solid var(--primary);
      color: var(--primary);
    }
    
    .btn-outline-primary-custom:hover {
      background: var(--primary);
      color: white;
    }
    
    .empty-state {
      text-align: center;
      padding: 40px 0;
      color: var(--gray);
    }
    
    .empty-state i {
      font-size: 3rem;
      margin-bottom: 15px;
      color: #dadce0;
    }
    
    footer {
      background: white;
      padding: 20px 0;
      margin-top: 40px;
      box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .filter-section {
      background: var(--primary-light);
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
    }
    
    .filter-label {
      font-weight: 500;
      color: var(--primary);
      margin-right: 10px;
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
        <h2 class="mb-1">Gerenciar Consultas</h2>
        <p class="text-muted mb-0">Visualize e gerencie todas as suas consultas</p>
      </div>
      <div class="col-md-4 text-md-end">
        <a href="pagina_medico.php" class="btn btn-outline-primary-custom">
          <i class="fas fa-arrow-left me-2"></i> Voltar ao Painel
        </a>
      </div>
    </div>
  </div>

  <!-- Table Container -->
  <div class="table-container">
    <h4 class="section-title">Lista de Consultas</h4>
    
    <!-- Filter Section -->
    <div class="filter-section mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
          <span class="filter-label"><i class="fas fa-filter me-2"></i>Filtrar por status:</span>
          <div class="btn-group btn-group-sm" role="group">
            <a href="gerenciar_consultas.php" class="btn btn-outline-primary">Todas</a>
            <a href="gerenciar_consultas.php?status=Agendada" class="btn btn-outline-primary">Agendadas</a>
            <a href="gerenciar_consultas.php?status=Realizada" class="btn btn-outline-primary">Realizadas</a>
            <a href="gerenciar_consultas.php?status=Cancelada" class="btn btn-outline-primary">Canceladas</a>
          </div>
        </div>
        <div class="col-md-6 text-md-end">
          <span class="badge bg-primary">Total: <?php echo count($consultas); ?> consultas</span>
        </div>
      </div>
    </div>
    
    <!-- Consultas Table -->
    <?php if (count($consultas) > 0): ?>
      <div class="table-responsive">
        <table class="table table-custom">
          <thead>
            <tr>
              <th>Paciente</th>
              <th>Data</th>
              <th>Horário</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($consultas as $c): 
              $status_class = '';
              switch($c['status']) {
                case 'Agendada': $status_class = 'status-agendada'; break;
                case 'Realizada': $status_class = 'status-realizada'; break;
                case 'Cancelada': $status_class = 'status-cancelada'; break;
                case 'Adiada': $status_class = 'status-adiada'; break;
                default: $status_class = 'status-agendada';
              }
            ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                      <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                      <strong><?= htmlspecialchars($c['nome_paciente']) ?></strong>
                    </div>
                  </div>
                </td>
                <td><?= date("d/m/Y", strtotime($c['data'])) ?></td>
                <td><?= date("H:i", strtotime($c['hora'])) ?></td>
                <td><span class="status-badge <?= $status_class ?>"><?= htmlspecialchars($c['status']) ?></span></td>
                <td>
                  <?php if ($c['status'] === 'Agendada'): ?>
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="atualizar_consulta.php?id=<?= $c['id_consulta'] ?>&status=Realizada"
                        class="btn btn-success-custom btn-action"
                        onclick="return confirm('Tem certeza que deseja marcar esta consulta como REALIZADA? Essa ação não poderá ser desfeita.')">
                        <i class="fas fa-check-circle me-1"></i> Realizada
                      </a>
                      <a href="atualizar_consulta.php?id=<?= $c['id_consulta'] ?>&status=Cancelada"
                        class="btn btn-danger-custom btn-action"
                        onclick="return confirm('Tem certeza que deseja CANCELAR esta consulta? Essa ação não poderá ser desfeita.')">
                        <i class="fas fa-times-circle me-1"></i> Cancelar
                      </a>
                      <a href="adiar_consulta.php?id=<?= $c['id_consulta'] ?>"
                        class="btn btn-primary-custom btn-action"
                        onclick="return confirm('Deseja realmente ADIAR esta consulta? Você poderá escolher uma nova data e horário.')">
                        <i class="fas fa-calendar-plus me-1"></i> Adiar
                      </a>
                    </div>
                  <?php else: ?>
                    <span class="text-muted">Nenhuma ação disponível</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <h5>Nenhuma consulta encontrada</h5>
        <p>Você não possui consultas agendadas no momento.</p>
      </div>
    <?php endif; ?>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include"footer_medico.php" ?>
</html>