<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Clínica Online</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background-color: #e8f0fe;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 400px;
      padding: 40px 30px;
      position: relative;
    }

    .logo {
      position: absolute;
      top: 30px;
      left: 30px;
      width: 60px;
      height: auto;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

    button {
      padding: 12px;
      background-color: #1a73e8;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0f5cc3;
    }

    .link-cadastro {
      text-align: center;
      margin-top: 10px;
      font-size: 14px;
    }

    .link-cadastro a {
      color: #1a73e8;
      text-decoration: none;
    }

    .link-cadastro a:hover {
      text-decoration: underline;
    }
    .mensagem {
  padding: 10px 15px;
  margin-bottom: 15px;
  border-radius: 5px;
  font-weight: bold;
  text-align: center;
}

.mensagem.erro {
  background-color: #fdecea;
  color: #c53030;
  border: 1px solid #f5c6cb;
}

.mensagem.sucesso {
  background-color: #e6fffa;
  color: #2f855a;
  border: 1px solid #81e6d9;
}

  </style>
</head>
<script>
    function toggleSenha() {
      const input = document.getElementById('senha');
      input.type = input.type === 'password' ? 'text' : 'password';
    }
    </script>
    
<body>
 
  
  <div class="container">
    <img src="img/MedClickDeLadinho.png" alt="Logo da Clínica" class="logo">
    <h2>Login</h2>
    <form action="verifica_login.php" method="POST">
        <div id="mensagem" class="mensagem" style="display: none;"></div>
      <div>
      <label for="usuario">CPF ou Email</label>
        <div>
          <input type="text" id="usuario" name="usuario" required>
        </div>
      </div>
      

      <div>
        <label for="senha">Senha</label>
        <div style="position: relative;">
            <input type="password" id="senha" name="senha" required>
            <span onclick="toggleSenha()" style="position: absolute; right: 10px; top: 10px; cursor: pointer;">👁️</span>
          </div>
      </div>

      <button type="submit">Entrar</button>
    </form>
    <div class="link-cadastro">
      <p>Não possui cadastro? <a href="cadrasto.html">Cadastre-se aqui</a></p>
    </div>
  </div>
  <script>
    function mostrarMensagem(texto, tipo = 'erro') {
      const msg = document.getElementById('mensagem');
      msg.className = 'mensagem ' + tipo;
      msg.textContent = texto;
      msg.style.display = 'block';
      setTimeout(() => {
        msg.style.display = 'none';
      }, 5000);
    }
    </script>

</body>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('erro')) {
      const erro = urlParams.get('erro');
      if (erro === 'senha') {
        mostrarMensagem('Senha incorreta!');
      } else if (erro === 'usuario') {
        mostrarMensagem('Usuário não encontrado!');
      }
    }
    
    if (urlParams.has('sucesso')) {
      mostrarMensagem('Cadastro realizado com sucesso!', 'sucesso');
    }
    </script>
    
</html>
