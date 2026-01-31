<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Variáveis de sessão
$tipoUsuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : '';
$nomeMedico = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Médico';
$fotoMedico = isset($_SESSION['imagem']) ? $_SESSION['imagem'] : 'default.jpg';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
        

    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="ico/Med-Click_1.ico">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #003366;
            padding: 15px 20px;
            font-family: Arial, sans-serif;
            color: white;
        }
        .logo img {
            height: 50px;
        }
        .nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .perfil-area {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }
        .foto-perfil {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        .perfil-menu {
            display: none;
            position: absolute;
            top: 48px;
            right: 0;
            background: white;
            color: black;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 9999;
            min-width: 200px;
        }
        .perfil-menu a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: black;
            font-size: 15px;
        }
        .perfil-menu a:hover {
            background-color: #f1f1f1;
        }
        .perfil-dropdown:hover .perfil-menu {
            display: block;
        }
        .nav-button.login {
            background-color: white;
            color: #003366; 
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .nav-button.login:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="pagina_medico.php">
            <img src="img/MedClickDeLadinho.png" alt="Logo da MedClick">
        </a>
    </div>

    <nav class="nav">
        <a href="pagina_medico.php">Painel</a>
        <a href="calendario_medico.php">Calendário</a>
        <a href="consultas_medico.php">Gerenciar Consultas</a>
         <a href="liberar_horarios.php">Liberar Horarios</a>
          <a href="cadastrar_consulta_medico.php">Cadastrar Consulta</a>
          <!-- Adicione este botão no menu do médico -->
        <a href="gerar_documento.php" style="background: #28a745; color: white; padding: 10px; border-radius: 5px; text-decoration: none; margin-right: 10px;">
           Gerar Documentos
        </a>
    </nav>

    <div class="user-section">
        <?php if (isset($_SESSION['usuario'])): ?>
            <div class="perfil-dropdown">
                <div class="perfil-area">
                    <img src="img/<?php echo htmlspecialchars($_SESSION['imagem']); ?>" 
                         class="foto-perfil" 
                         alt="Foto de perfil">
                    <div class="perfil-menu">
                        <?php if ($tipoUsuario === 'medico'): ?>
                            <a href="perfil_medico.php">Meu Perfil Médico</a>
                            <a href="agenda_medico.php">Minha Agenda</a>
                        <?php else: ?>
                            <a href="perfil.php">Meu Perfil</a>
                            <a href="minha_consulta.php">Minhas Consultas</a>
                        <?php endif; ?>
                        <a href="logout.php">Sair</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <a href="login_medico.php">
                <button class="nav-button login">Login</button>
            </a>
        <?php endif; ?>
    </div>
</header>
</body>
<!-- VLibras - Acessibilidade em Libras -->
<div vw class="enabled">
  <div vw-access-button class="active"></div>
  <div vw-plugin-wrapper>
    <div class="vw-plugin-top-wrapper"></div>
  </div>
</div>

<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
  new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>

</html>
