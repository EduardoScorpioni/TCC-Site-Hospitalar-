<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'gerente') {
    header("Location: login1.php?erro=acesso");
    exit;
}

require 'conexao.php';

// Buscar médicos para o select
$medicos = [];
try {
    $stmt = $pdo->query("SELECT id, nome, crm FROM medicos ORDER BY nome");
    $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erro ao carregar médicos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Contatos Médicos</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    * { 
        box-sizing: border-box; 
        margin: 0; 
        padding: 0; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body { 
        background: linear-gradient(135deg, #e8f4f8 0%, #c9e6f2 100%); 
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        width: 700px;
        max-width: 95%;
        padding: 40px;
        position: relative;
        overflow: hidden;
        margin: 20px 0;
    }

    .container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: linear-gradient(90deg, #1a73e8, #0B0033);
    }

    .logo { 
        display: block;
        margin: 0 auto 25px;
        width: 80px;
        height: auto;
    }

    h2 { 
        text-align: center; 
        margin-bottom: 30px; 
        color: #1a73e8;
        font-size: 28px;
        font-weight: 600;
    }

    form { 
        display: flex; 
        flex-direction: column; 
        gap: 20px; 
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 650px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    label { 
        font-weight: 600; 
        margin-bottom: 8px; 
        display: block; 
        color: #444;
        font-size: 15px;
    }

    input, select {
        padding: 14px; 
        border: 1px solid #ddd; 
        border-radius: 8px; 
        font-size: 15px; 
        width: 100%;
        transition: all 0.3s ease;
        background-color: #f9fafc;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        background-color: #fff;
    }

    button {
        padding: 16px; 
        background: #1a73e8; 
        color: white; 
        border: none;
        border-radius: 8px; 
        font-size: 17px; 
        cursor: pointer; 
        margin-top: 15px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: background 0.3s ease;
        box-shadow: 0 4px 6px rgba(26, 115, 232, 0.2);
    }
    
    button:hover { 
        background: #0f5cc3; 
        box-shadow: 0 6px 8px rgba(26, 115, 232, 0.3);
    }

    .required::after {
        content: '*';
        color: #e53935;
        margin-left: 4px;
    }

    .form-note {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
        color: #1a73e8;
        text-decoration: none;
        font-weight: 500;
    }

    .back-link i {
        margin-right: 8px;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-error {
        background-color: #ffebee;
        color: #c62828;
        border: 1px solid #ef9a9a;
    }

    .alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }
  </style>
</head>
<body>
<?php include"btnvoltar.php"?>
  <div class="container">
    <a href="painel_gerente.php" class="back-link">
      <i class="fas fa-arrow-left"></i> Voltar ao Painel
    </a>
    
    <img src="img/MedClickDeLadinho.png" class="logo" alt="Logo">

    <h2><i class="fas fa-address-book"></i> Cadastro de Contatos Médicos</h2>

    <?php if (isset($_GET['sucesso'])): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Contato médico cadastrado com sucesso!
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> 
        <?php 
          $erro = $_GET['erro'];
          if ($erro == 'medico_invalido') echo 'Médico inválido.';
          elseif ($erro == 'telefone_existente') echo 'Telefone já cadastrado para este médico.';
          elseif ($erro == 'email_existente') echo 'E-mail já cadastrado para este médico.';
          else echo 'Erro ao cadastrar contato médico.';
        ?>
      </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form action="salvar_contato_medico.php" method="POST">

      <div class="form-group">
        <label for="medico_id" class="required">Médico</label>
        <select id="medico_id" name="medico_id" required>
          <option value="">Selecione um médico</option>
          <?php foreach ($medicos as $medico): ?>
            <option value="<?php echo $medico['id']; ?>">
              <?php echo htmlspecialchars($medico['nome'] . ' - CRM: ' . $medico['crm']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label for="telefone" class="required">Telefone</label>
          <input type="text" id="telefone" name="telefone" required placeholder="(xx) xxxxx-xxxx">
        </div>
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" placeholder="exemplo@medico.com">
        </div>
      </div>

      <div class="form-group">
        <label for="rede_social">Rede Social</label>
        <input type="text" id="rede_social" name="rede_social" placeholder="URL do perfil">
        <p class="form-note">Ex: https://facebook.com/usuario ou https://instagram.com/usuario</p>
      </div>

      <button type="submit"><i class="fas fa-save"></i> Cadastrar Contato</button>
    </form>
  </div>

  <script>
    // Máscara para telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 11) value = value.slice(0, 11);
      
      if (value.length > 10) {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
      } else if (value.length > 6) {
        value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
      } else if (value.length > 2) {
        value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
      } else if (value.length > 0) {
        value = value.replace(/(\d{0,2})/, '($1');
      }
      
      e.target.value = value;
    });

    // Validação de formulário
    document.querySelector('form').addEventListener('submit', function(e) {
      const telefone = document.getElementById('telefone').value;
      const email = document.getElementById('email').value;
      
      // Valida telefone (pelo menos 14 caracteres com máscara)
      if (telefone.length < 14) {
        e.preventDefault();
        alert('Por favor, informe um telefone válido.');
        return false;
      }
      
      // Valida email se preenchido
      if (email && !/\S+@\S+\.\S+/.test(email)) {
        e.preventDefault();
        alert('Por favor, informe um e-mail válido.');
        return false;
      }
    });
  </script>
</body>
</html>