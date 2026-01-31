<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="ico/Med-Click_1.ico">
    <title>Login Médico - Clínica Online</title>
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
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            padding: 50px 40px 35px;
            position: relative;
            overflow: hidden;
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
            position: absolute;
            top: 20px;
            left: 20px;
            width: 55px;
            height: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #1a73e8;
            font-size: 28px;
            font-weight: 600;
            margin-top: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .form-group {
            margin-bottom: 5px;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #444;
            font-size: 15px;
        }

        input {
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            width: 100%;
            transition: all 0.3s ease;
            background-color: #f9fafc;
        }

        input:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
            background-color: #fff;
        }

        .senha-wrapper {
            position: relative;
        }

        .senha-wrapper span {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 18px;
        }

        button {
            padding: 16px;
            background: #1a73e8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            cursor: pointer;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: background 0.3s ease;
            box-shadow: 0 4px 6px rgba(26, 115, 232, 0.2);
            margin-top: 10px;
        }

        button:hover {
            background: #0f5cc3;
            box-shadow: 0 6px 8px rgba(26, 115, 232, 0.3);
        }

        .mensagem {
            padding: 14px 16px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
            font-size: 15px;
        }

        .erro {
            background-color: #fdecea;
            color: #c53030;
            border: 1px solid #f5c6cb;
        }

        .sucesso {
            background-color: #e6fffa;
            color: #2f855a;
            border: 1px solid #81e6d9;
        }

        .link-cadastro,
        .login-paciente {
            text-align: center;
            margin-top: 20px;
            font-size: 15px;
            color: #555;
        }

        .link-cadastro a,
        .login-paciente a {
            color: #1a73e8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .link-cadastro a:hover,
        .login-paciente a:hover {
            color: #0f5cc3;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="img/MedClickDeLadinho.png" alt="Logo da Clínica" class="logo">
        <h2>Login Médico</h2>

        <?php
        // Exibir mensagens de erro
        if (isset($_GET['erro'])) {
            $erro = $_GET['erro'];
            $mensagem = '';
            
            switch ($erro) {
                case 'senha':
                    $mensagem = 'Senha incorreta!';
                    break;
                case 'usuario':
                    $mensagem = 'CRM não encontrado!';
                    break;
                case 'campos':
                    $mensagem = 'Preencha todos os campos!';
                    break;
                default:
                    $mensagem = 'Erro desconhecido!';
            }
            
            echo '<div class="mensagem erro">' . $mensagem . '</div>';
        }

        // Exibir mensagens de sucesso
        if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'cadastro') {
            echo '<div class="mensagem sucesso">Cadastro realizado com sucesso!</div>';
        }

        if (isset($_GET['login']) && $_GET['login'] === 'ok') {
            echo '<div class="mensagem sucesso">Login realizado com sucesso!</div>';
        }
        ?>

        <form action="verifica_login_medico.php" method="POST">
            <div class="form-group">
                <label for="crm">CRM</label>
                <input type="text" id="crm" name="crm" placeholder="Digite seu CRM" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <div class="senha-wrapper">
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    <span onclick="toggleSenha()">👁️</span>
                </div>
            </div>
            
            <button type="submit">Entrar</button>
        </form>

       
        
        <div class="login-paciente">
            <p>É paciente? <a href="login1.php">Acesse o login de pacientes</a></p>
        </div>
    </div>

    <script>
        function toggleSenha() {
            const input = document.getElementById('senha');
            const toggleBtn = input.nextElementSibling;
            input.type = input.type === 'password' ? 'text' : 'password';
            toggleBtn.textContent = input.type === 'password' ? '👁️' : '🔒';
        }
    </script>
</body>
</html>