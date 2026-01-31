<?php
session_start();

// Verifica se o médico está logado
if (!isset($_SESSION['crm'])) {
    header("Location: login_medico.php");
    exit;
}

// Dados do médico vindos da sessão
$nome = $_SESSION['usuario'];
$crm = $_SESSION['crm'];
$especialidade = $_SESSION['especialidade'];
$imagem = $_SESSION['imagem'];  // Caminho da imagem do banco
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Médico - MedClick</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #0B0033;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 15px 0;
            font-weight: bold;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 100%;
        }
        .content {
            flex-grow: 1;
            padding: 30px;
        }
        .perfil {
            display: flex;
            align-items: center;
        }
        .perfil img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid #1a73e8;
            object-fit: cover;
        }
        .perfil-info {
            line-height: 1.4;
        }
        .perfil-info strong {
            display: block;
        }
        .logout {
            color: #0B0033;
            text-decoration: none;
            font-weight: bold;
        }
        .logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>MedClick</h2>
        <a href="pagina_medico.php">Início</a>
        <a href="agenda_medico.php">Agenda</a>
        <a href="#">Pacientes</a>
        <a href="perfil.php">Configurações</a>
        <a class="logout" href="logout.php">Sair</a>
    </div>

    <div class="main">
        <div class="header">
            <div class="perfil">
                <img src="uploads/<?php echo htmlspecialchars($imagem); ?>" alt="Foto do Médico">
                <div class="perfil-info">
                    <strong><?php echo htmlspecialchars($nome); ?></strong>
                    CRM: <?php echo htmlspecialchars($crm); ?><br>
                    <?php echo htmlspecialchars($especialidade); ?>
                </div>
            </div>
        </div>

        <div class="content">
            <h1>Bem-vindo(a), Dr(a). <?php echo htmlspecialchars($nome); ?>!</h1>
            <p>Use o menu ao lado para acessar suas funcionalidades.</p>
        </div>
    </div>

</body>
</html>

