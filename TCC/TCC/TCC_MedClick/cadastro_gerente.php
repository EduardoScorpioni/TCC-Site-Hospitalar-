<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'gerente') {
    header("Location: login1.php?erro=acesso");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Gerente</title>
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

    .password-toggle {
        position: relative;
    }

    .password-toggle i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #666;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="painel_gerente.php" class="back-link">
      <i class="fas fa-arrow-left"></i> Voltar ao Painel
    </a>
    
    <img src="img/MedClickDeLadinho.png" class="logo" alt="Logo">

    <h2><i class="fas fa-user-shield"></i> Cadastro de Gerente</h2>

    <?php if (isset($_GET['sucesso'])): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Gerente cadastrado com sucesso!
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> 
        <?php 
          $erro = $_GET['erro'];
          if ($erro == 'email_existente') echo 'E-mail já cadastrado.';
          elseif ($erro == 'cpf_existente') echo 'CPF já cadastrado.';
          elseif ($erro == 'senhas_nao_conferem') echo 'As senhas não conferem.';
          else echo 'Erro ao cadastrar gerente.';
        ?>
      </div>
    <?php endif; ?>

    <form action="salvar_gerente.php" method="POST" enctype="multipart/form-data">

      <div class="grid-2">
        <div class="form-group">
          <label for="nome" class="required">Nome Completo</label>
          <input type="text" id="nome" name="nome" required>
        </div>
        <div class="form-group">
          <label for="cpf" class="required">CPF</label>
          <input type="text" id="cpf" name="cpf" required placeholder="000.000.000-00">
        </div>
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label for="email" class="required">E-mail</label>
          <input type="email" id="email" name="email" required placeholder="exemplo@medclick.com">
        </div>
        <div class="form-group">
          <label for="telefone" class="required">Telefone</label>
          <input type="text" id="telefone" name="telefone" required placeholder="(xx) xxxxx-xxxx">
        </div>
      </div>

      <div class="grid-2">
        <div class="form-group password-toggle">
          <label for="senha" class="required">Senha</label>
          <input type="password" id="senha" name="senha" required minlength="6">
          <i class="fas fa-eye" id="toggleSenha"></i>
        </div>
        <div class="form-group password-toggle">
          <label for="confirmar_senha" class="required">Confirmar Senha</label>
          <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
          <i class="fas fa-eye" id="toggleConfirmarSenha"></i>
        </div>
      </div>

      <div class="form-group">
        <label for="imagem">Foto de Perfil</label>
        <input type="file" id="imagem" name="imagem" accept="image/*">
        <p class="form-note">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</p>
      </div>

      <button type="submit"><i class="fas fa-save"></i> Cadastrar Gerente</button>
    </form>
  </div>

  <script>
    // Máscara para CPF
    document.getElementById('cpf').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 11) value = value.slice(0, 11);
      
      if (value.length > 9) {
        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
      } else if (value.length > 6) {
        value = value.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
      } else if (value.length > 3) {
        value = value.replace(/(\d{3})(\d{0,3})/, '$1.$2');
      }
      
      e.target.value = value;
    });

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

    // Toggle de visibilidade de senha
    document.getElementById('toggleSenha').addEventListener('click', function() {
      const senhaInput = document.getElementById('senha');
      const type = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
      senhaInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmarSenha').addEventListener('click', function() {
      const confirmarSenhaInput = document.getElementById('confirmar_senha');
      const type = confirmarSenhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmarSenhaInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    // Validação de formulário
    document.querySelector('form').addEventListener('submit', function(e) {
      const senha = document.getElementById('senha').value;
      const confirmarSenha = document.getElementById('confirmar_senha').value;
      const cpf = document.getElementById('cpf').value;
      
      // Verifica se as senhas conferem
      if (senha !== confirmarSenha) {
        e.preventDefault();
        alert('As senhas não conferem. Por favor, verifique.');
        return false;
      }
      
      // Verifica se o CPF está completo
      if (cpf.replace(/\D/g, '').length !== 11) {
        e.preventDefault();
        alert('Por favor, informe um CPF válido.');
        return false;
      }
    });
  </script>
</body>
</html>