<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['crm'])) {
    header("Location: login_medico.php");
    exit;
}

$medico_id = $_SESSION['id'];
$nome      = $_SESSION['usuario'];

// Consulta incluindo consultas manuais
$sql = "
  SELECT 
    c.id_consulta,
    c.data,
    c.hora,
    COALESCE(p.nome, c.nome_paciente_manual) AS paciente,
    e.nome AS especialidade,
    c.status
  FROM consultas c
  LEFT JOIN pacientes p 
    ON c.paciente_id = p.id
  INNER JOIN especialidades e
    ON e.id = c.especialidade_id
  WHERE c.medico_id = ?
    AND DATE(c.data) = CURDATE()
  ORDER BY c.hora
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$medico_id]);
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Minha Agenda (Hoje) – MedClick</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">

  <!-- CSS do painel -->
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
    .no-appointment {
      background: var(--primary-light);
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      color: var(--primary);
    }
    .badge-status {
      border-radius: 12px;
      padding: 5px 10px;
      color: white;
      font-weight: 500;
    }
    .btn-sm {
      padding: 6px 15px;
      font-size: 0.875rem;
    }
    .bg-warning { background-color: var(--warning) !important; }
    .bg-success { background-color: var(--secondary) !important; }
    .bg-danger { background-color: var(--danger) !important; }
  </style>
</head>
<body>

<?php include 'header_medico.php'; ?>

<div class="container my-4">
  <h2 class="section-title">Consultas de Hoje (<?= date('d/m/Y') ?>)</h2>

  <div class="table-responsive">
    <?php if ($consultas): ?>
      <table class="table table-striped shadow-sm">
        <thead class="table-primary">
          <tr>
            <th>Horário</th>
            <th>Paciente</th>
            <th>Especialidade</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($consultas as $c): 
            $hora = date('H:i', strtotime($c['hora']));
            switch ($c['status']) {
              case 'Realizada': $cor = 'success'; break;
              case 'Cancelada': $cor = 'danger';  break;
              default:          $cor = 'warning'; break;
            }
          ?>
          <tr>
            <td><?= $hora ?></td>
            <td><?= htmlspecialchars($c['paciente']) ?></td>
            <td><?= htmlspecialchars($c['especialidade']) ?></td>
            <td>
              <span class="badge-status bg-<?= $cor ?>">
                <?= htmlspecialchars($c['status']) ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="no-appointment">Nenhuma consulta agendada para hoje.</div>
    <?php endif; ?>
  </div>

  <div class="mt-3 d-flex gap-2">
    <a href="calendario_medico.php" class="btn btn-primary-custom btn-sm">Ver Calendário</a>
    <a href="pagina_medico.php" class="btn btn-primary-custom btn-sm">Voltar ao Painel</a>
  </div>
</div>

</div>

<footer class="text-center py-3 text-muted">
  &copy; <?= date("Y") ?> MedClick – Sistema de Consultas Online
</footer>

</body>
</html>
