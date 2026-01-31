<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login1.php");
    exit;
}

// Buscar especialidade do médico logado
$stmt_medico = $pdo->prepare("
    SELECT e.nome as especialidade 
    FROM medicos m 
    LEFT JOIN especialidades e ON m.especialidade_id = e.id 
    WHERE m.id = ?
");
$stmt_medico->execute([$_SESSION['id']]);
$medico_info = $stmt_medico->fetch(PDO::FETCH_ASSOC);
$especialidade_medico = isset($medico_info['especialidade']) ? $medico_info['especialidade'] : '';

// Buscar pacientes do médico
$stmt = $pdo->prepare("
    SELECT p.id, p.nome 
    FROM pacientes p 
    INNER JOIN consultas c ON p.id = c.paciente_id 
    WHERE c.medico_id = ? AND c.status = 'Realizada'
    GROUP BY p.id
    ORDER BY p.nome
");
$stmt->execute([$_SESSION['id']]);
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerar Documentos - MedClick</title>
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
    
    .tipo-documento {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
    }
    
    .tipo-option {
      flex: 1;
      padding: 15px;
      border: 2px solid #ddd;
      border-radius: 10px;
      cursor: pointer;
      text-align: center;
      transition: all 0.3s;
    }
    
    .tipo-option:hover {
      border-color: var(--primary);
      transform: translateY(-2px);
    }
    
    .tipo-option.selected {
      border-color: var(--primary);
      background: var(--primary-light);
    }
    
    .tipo-option i {
      font-size: 2rem;
      margin-bottom: 10px;
      display: block;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    label {
      font-weight: 500;
      margin-bottom: 8px;
      color: var(--dark);
    }
    
    select, textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      transition: border-color 0.3s;
    }
    
    select:focus, textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }
    
    textarea {
      height: 200px;
      resize: vertical;
    }
    
    .exemplos {
      background: var(--primary-light);
      padding: 15px;
      border-radius: 8px;
      margin-top: 10px;
      font-size: 0.9rem;
    }
    
    .hidden {
      display: none;
    }
    
    .info-medico {
      background: var(--primary-light);
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    
    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary), #0d47a1);
      border: none;
      color: white;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
    }
    
    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(26, 115, 232, 0.2);
    }
    
    .btn-outline-primary-custom {
      background: transparent;
      border: 1px solid var(--primary);
      color: var(--primary);
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
    }
    
    .btn-outline-primary-custom:hover {
      background: var(--primary);
      color: white;
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
        <h2 class="mb-1"><i class="fas fa-file-medical me-2"></i>Gerar Documento Médico</h2>
        <p class="text-muted mb-0">Emita atestados e receitas para seus pacientes</p>
      </div>
      <div class="col-md-4 text-md-end">
        <a href="consultas_medico.php" class="btn btn-outline-primary-custom">
          <i class="fas fa-arrow-left me-2"></i> Voltar às Consultas
        </a>
      </div>
    </div>
  </div>

  <!-- Form Container -->
  <div class="form-container">
    <h4 class="section-title">Preencha os dados do documento</h4>
    
    <div class="info-medico">
      <div class="row">
        <div class="col-md-6">
          <strong>Médico:</strong> <?= htmlspecialchars(isset($_SESSION['nome']) ? $_SESSION['nome'] : '') ?>
        </div>
        <div class="col-md-6">
          <strong>Especialidade:</strong> <?= htmlspecialchars($especialidade_medico) ?>
        </div>
      </div>
    </div>
    
    <form action="processar_documento.php" method="POST" id="formDocumento">
      <div class="form-group">
        <label><i class="fas fa-user-injured me-2"></i>Selecione o Paciente:</label>
        <select name="paciente_id" class="form-select" required>
          <option value="">Selecione um paciente</option>
          <?php foreach ($pacientes as $paciente): ?>
            <option value="<?= $paciente['id'] ?>"><?= htmlspecialchars($paciente['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label><i class="fas fa-file-alt me-2"></i>Tipo de Documento:</label>
        <div class="tipo-documento">
          <div class="tipo-option selected" data-tipo="atestado" onclick="selecionarTipo('atestado')">
            <i class="fas fa-file-medical"></i>
            <h5>Atestado Médico</h5>
            <p class="text-muted small">Para atestados de saúde e afastamento</p>
          </div>
          <div class="tipo-option" data-tipo="receita" onclick="selecionarTipo('receita')">
            <i class="fas fa-prescription"></i>
            <h5>Receita Médica</h5>
            <p class="text-muted small">Para prescrição de medicamentos</p>
          </div>
        </div>
        <input type="hidden" name="tipo" id="tipo" value="atestado" required>
      </div>

      <div class="form-group">
        <label id="labelConteudo"><i class="fas fa-edit me-2"></i>Conteúdo do Atestado:</label>
        <textarea name="conteudo" class="form-control" placeholder="Digite o conteúdo do documento..." required></textarea>
        
        <div id="exemploAtestado" class="exemplos">
          <strong>Exemplo de Atestado:</strong><br>
          Atesto que o(a) paciente __________________________________<br>
          esteve sob meus cuidados médicos no período de ______ a ______<br>
          necessitando de repouso/afastamento das atividades por ______ dias.
        </div>
        
        <div id="exemploReceita" class="exemplos hidden">
          <strong>Exemplo de Receita:</strong><br>
          • Paracetamol 500mg - Tomar 1 comprimido de 6/6 horas por 5 dias<br>
          • Ibuprofeno 400mg - Tomar 1 comprimido de 8/8 horas se houver dor<br>
          • Omeprazol 20mg - Tomar 1 cápsula pela manhã em jejum por 15 dias
        </div>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary-custom btn-lg">
          <i class="fas fa-file-pdf me-2"></i> Gerar PDF
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function selecionarTipo(tipo) {
    // Atualiza o hidden field
    document.getElementById('tipo').value = tipo;
    
    // Atualiza a UI
    document.querySelectorAll('.tipo-option').forEach(opt => {
      opt.classList.remove('selected');
    });
    document.querySelector(`[data-tipo="${tipo}"]`).classList.add('selected');
    
    // Atualiza label e exemplos
    const label = document.getElementById('labelConteudo');
    const exemploAtestado = document.getElementById('exemploAtestado');
    const exemploReceita = document.getElementById('exemploReceita');
    
    if (tipo === 'atestado') {
      label.innerHTML = '<i class="fas fa-edit me-2"></i>Conteúdo do Atestado:';
      exemploAtestado.classList.remove('hidden');
      exemploReceita.classList.add('hidden');
      document.querySelector('textarea').placeholder = 'Digite o conteúdo do atestado...';
    } else {
      label.innerHTML = '<i class="fas fa-edit me-2"></i>Conteúdo da Receita:';
      exemploAtestado.classList.add('hidden');
      exemploReceita.classList.remove('hidden');
      document.querySelector('textarea').placeholder = 'Digite os medicamentos e posologias...';
    }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include "footer_medico.php" ?>
</html>