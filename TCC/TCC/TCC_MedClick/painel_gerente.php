<?php
session_start();

// Checa se está logado e se é GERENTE
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'gerente') {
    header("Location: login1.php?erro=acesso");
    exit();
}

require_once 'conexao.php'; // Arquivo com as configurações de conexão

// ===== PROCESSAR AÇÕES DE EDIÇÃO E EXCLUSÃO =====
$mensagem = '';
$tipo_mensagem = '';

// Processar exclusões
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'] ;
    $tipo = $_POST['tipo'] ;
    
    if ($id && $tipo) {
        try {
            switch ($tipo) {
                case 'medico':
                    if ($action === 'delete') {
                        $stmt = $pdo->prepare("DELETE FROM medicos WHERE id = ?");
                        $stmt->execute([$id]);
                        $mensagem = "Médico excluído com sucesso!";
                        $tipo_mensagem = 'success';
                    }
                    break;
                    
                case 'farmacia':
                    if ($action === 'delete') {
                        $stmt = $pdo->prepare("DELETE FROM farmacias WHERE id = ?");
                        $stmt->execute([$id]);
                        $mensagem = "Farmácia excluída com sucesso!";
                        $tipo_mensagem = 'success';
                    }
                    break;
                    
                case 'hospital':
                    if ($action === 'delete') {
                        $stmt = $pdo->prepare("DELETE FROM hospitais WHERE id = ?");
                        $stmt->execute([$id]);
                        $mensagem = "Hospital excluído com sucesso!";
                        $tipo_mensagem = 'success';
                    }
                    break;
                    
                case 'contato':
                    if ($action === 'delete') {
                        $stmt = $pdo->prepare("DELETE FROM contatos_medicos WHERE id = ?");
                        $stmt->execute([$id]);
                        $mensagem = "Contato excluído com sucesso!";
                        $tipo_mensagem = 'success';
                    }
                    break;
            }
            
        } catch (PDOException $e) {
            $mensagem = "Erro ao excluir: " . $e->getMessage();
            $tipo_mensagem = 'error';
        }
    }
}

// Processar edições (será feito via modal no mesmo arquivo)
$item_editar = null;
$tipo_editar = '';

if (isset($_GET['editar'])) {
    $tipo_editar = $_GET['tipo'] ;
    $id_editar = $_GET['id'] ;
    
    if ($id_editar && $tipo_editar) {
        try {
            switch ($tipo_editar) {
                case 'medico':
                    $stmt = $pdo->prepare("SELECT m.*, e.nome as especialidade FROM medicos m 
                                         LEFT JOIN especialidades e ON m.especialidade_id = e.id 
                                         WHERE m.id = ?");
                    $stmt->execute([$id_editar]);
                    $item_editar = $stmt->fetch(PDO::FETCH_ASSOC);
                    break;
                    
                case 'farmacia':
                    $stmt = $pdo->prepare("SELECT * FROM farmacias WHERE id = ?");
                    $stmt->execute([$id_editar]);
                    $item_editar = $stmt->fetch(PDO::FETCH_ASSOC);
                    break;
                    
                case 'hospital':
                    $stmt = $pdo->prepare("SELECT * FROM hospitais WHERE id = ?");
                    $stmt->execute([$id_editar]);
                    $item_editar = $stmt->fetch(PDO::FETCH_ASSOC);
                    break;
                    
                case 'contato':
                    $stmt = $pdo->prepare("SELECT cm.*, m.nome as medico_nome FROM contatos_medicos cm 
                                         LEFT JOIN medicos m ON cm.medico_id = m.id 
                                         WHERE cm.id = ?");
                    $stmt->execute([$id_editar]);
                    $item_editar = $stmt->fetch(PDO::FETCH_ASSOC);
                    break;
            }
        } catch (PDOException $e) {
            $mensagem = "Erro ao carregar dados para edição: " . $e->getMessage();
            $tipo_mensagem = 'error';
        }
    }
}

// Processar atualização de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_edicao'])) {
    $tipo_editar = $_POST['tipo'] ;
    $id_editar = $_POST['id'] ;
    
    if ($id_editar && $tipo_editar) {
        try {
            switch ($tipo_editar) {
                case 'medico':
                    $stmt = $pdo->prepare("UPDATE medicos SET nome = ?, crm = ?, email = ?, telefone = ? WHERE id = ?");
                    $stmt->execute([
                        $_POST['nome'],
                        $_POST['crm'],
                        $_POST['email'],
                        $_POST['telefone'],
                        $id_editar
                    ]);
                    $mensagem = "Médico atualizado com sucesso!";
                    $tipo_mensagem = 'success';
                    break;
                    
                case 'farmacia':
                    $stmt = $pdo->prepare("UPDATE farmacias SET nome = ?, endereco = ?, cidade = ?, estado = ?, telefone = ?, abertura = ?, fechamento = ?, is_24h = ? WHERE id = ?");
                    $stmt->execute([
                        $_POST['nome'],
                        $_POST['endereco'],
                        $_POST['cidade'],
                        $_POST['estado'],
                        $_POST['telefone'],
                        $_POST['abertura'],
                        $_POST['fechamento'],
                        isset($_POST['is_24h']) ? 1 : 0,
                        $id_editar
                    ]);
                    $mensagem = "Farmácia atualizada com sucesso!";
                    $tipo_mensagem = 'success';
                    break;
                    
                case 'hospital':
                    $stmt = $pdo->prepare("UPDATE hospitais SET nome = ?, endereco = ?, cidade = ?, estado = ?, telefone = ?, email = ? WHERE id = ?");
                    $stmt->execute([
                        $_POST['nome'],
                        $_POST['endereco'],
                        $_POST['cidade'],
                        $_POST['estado'],
                        $_POST['telefone'],
                        $_POST['email'],
                        $id_editar
                    ]);
                    $mensagem = "Hospital atualizado com sucesso!";
                    $tipo_mensagem = 'success';
                    break;
                    
                case 'contato':
                    $stmt = $pdo->prepare("UPDATE contatos_medicos SET telefone = ?, email = ?, rede_social = ? WHERE id = ?");
                    $stmt->execute([
                        $_POST['telefone'],
                        $_POST['email'],
                        $_POST['rede_social'],
                        $id_editar
                    ]);
                    $mensagem = "Contato atualizado com sucesso!";
                    $tipo_mensagem = 'success';
                    break;
            }
            
            // Limpar dados de edição após salvar
            $item_editar = null;
            $tipo_editar = '';
            
        } catch (PDOException $e) {
            $mensagem = "Erro ao atualizar: " . $e->getMessage();
            $tipo_mensagem = 'error';
        }
    }
}

// Buscar dados do banco
$medicos = [];
$farmacias = [];
$hospitais = [];
$contatos_medicos = [];

try {
    // Buscar médicos
    $stmt = $pdo->query("SELECT m.*, e.nome as especialidade FROM medicos m 
                        LEFT JOIN especialidades e ON m.especialidade_id = e.id");
    $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Buscar farmácias
    $stmt = $pdo->query("SELECT * FROM farmacias");
    $farmacias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Buscar hospitais
    $stmt = $pdo->query("SELECT * FROM hospitais");
    $hospitais = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Buscar contatos médicos
    $stmt = $pdo->query("SELECT cm.*, m.nome as medico_nome FROM contatos_medicos cm 
                        LEFT JOIN medicos m ON cm.medico_id = m.id");
    $contatos_medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Erro ao carregar dados: " . $e->getMessage();
}

$nome = $_SESSION['usuario'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Gerente - MedClick</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    /* ===== VARIÁVEIS DE CORES ===== */
    :root {
        --teal: #00838fff;
        --caribbean-current: #1b767eff;
        --garnet: #713838ff;
        --kelly-green: #74af32ff;
        --yellow-green: #92e336ff;
        --mindaro: #c7f198ff;
        --white: #ffffffff;
        --slate-blue: #705dbcff;
        --russian-violet: #0b0033ff;
        --russian-violet-2: #1c0f4dff;
        
        /* Cores para gradientes */
        --gradient-primary: linear-gradient(135deg, var(--teal) 0%, var(--caribbean-current) 100%);
        --gradient-secondary: linear-gradient(135deg, var(--slate-blue) 0%, var(--russian-violet-2) 100%);
        --gradient-accent: linear-gradient(135deg, var(--kelly-green) 0%, var(--yellow-green) 100%);
        --gradient-light: linear-gradient(135deg, var(--mindaro) 0%, var(--white) 100%);
        
        /* Sombras */
        --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    /* ===== ESTILOS GERAIS ===== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        background-color: #f8fafc;
        line-height: 1.6;
        overflow-x: hidden;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .section-title {
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        color: var(--russian-violet);
        position: relative;
        font-weight: 700;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: var(--gradient-accent);
        border-radius: 2px;
    }

    .btn {
        display: inline-block;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        font-size: 0.95rem;
    }
.btn-cad {
        display: inline-block;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        font-size: 0.95rem;
        color: white;
    }
    .btn-primary {
        background: var(--gradient-primary);
        color: var(--white);
        box-shadow: var(--shadow-md);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .btn-secondary {
        background: var(--gradient-secondary);
        color: var(--white);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .btn-accent {
        background: var(--gradient-accent);
        color: var(--white);
        box-shadow: var(--shadow-md);
    }

    .btn-accent:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    /* ===== HEADER ===== */
    header {
        background: var(--russian-violet);
        padding: 15px 0;
        box-shadow: var(--shadow-lg);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    .logo img {
        height: 50px;
        transition: transform 0.3s ease;
    }

    .logo img:hover {
        transform: scale(1.05);
    }

    .user-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-welcome {
        color: var(--white);
        font-weight: 500;
    }

    .user-profile {
        position: relative;
        cursor: pointer;
    }

    .profile-img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid var(--mindaro);
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .user-profile:hover .profile-img {
        border-color: var(--yellow-green);
        transform: scale(1.05);
    }

    .profile-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: var(--white);
        min-width: 200px;
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 100;
    }

    .user-profile:hover .profile-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .profile-menu a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: var(--russian-violet);
        font-weight: 500;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(112, 93, 188, 0.1);
    }

    .profile-menu a:last-child {
        border-bottom: none;
    }

    .profile-menu a:hover {
        background: rgba(112, 93, 188, 0.1);
        padding-left: 25px;
    }

    .profile-menu a i {
        margin-right: 10px;
        color: var(--slate-blue);
    }

    /* ===== PAINEL DO GERENTE ===== */
    .admin-container {
        display: flex;
        min-height: calc(100vh - 80px);
    }

    /* Sidebar */
    .admin-sidebar {
        width: 280px;
        background: linear-gradient(to bottom, var(--russian-violet), var(--russian-violet-2));
        color: var(--white);
        padding: 20px 0;
        box-shadow: var(--shadow-md);
    }

    .admin-sidebar-header {
        padding: 0 20px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 20px;
    }

    .admin-sidebar-header h4 {
        font-weight: 700;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-sidebar-header p {
        opacity: 0.8;
        margin: 0;
        font-size: 0.9rem;
    }

    .admin-nav {
        list-style: none;
        padding: 0;
    }

    .admin-nav-item {
        margin-bottom: 5px;
    }

    .admin-nav-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: var(--white);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .admin-nav-link:hover,
    .admin-nav-link.active {
        background: rgba(255, 255, 255, 0.1);
        border-left: 4px solid var(--yellow-green);
        padding-left: 25px;
    }

    .admin-nav-link i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
    }

    .admin-nav-link .badge {
        margin-left: auto;
        background: var(--yellow-green);
        color: var(--russian-violet);
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 20px;
    }

    /* Conteúdo Principal */
    .admin-main {
        flex: 1;
        padding: 30px;
        background: #f8fafc;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .admin-title {
        font-size: 2rem;
        color: var(--russian-violet);
        font-weight: 700;
        margin: 0;
    }

    .admin-actions {
        display: flex;
        gap: 15px;
    }

    /* Cards de Dashboard */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--white);
        border-radius: 12px;
        padding: 25px;
        box-shadow: var(--shadow-md);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--russian-violet);
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    /* Tabelas e Listas */
    .admin-card {
        background: var(--white);
        border-radius: 12px;
        padding: 25px;
        box-shadow: var(--shadow-md);
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .card-title {
        font-size: 1.4rem;
        color: var(--russian-violet);
        font-weight: 600;
        margin: 0;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th {
        background: var(--gradient-secondary);
        color: var(--white);
        padding: 15px;
        text-align: left;
        font-weight: 600;
    }

    .admin-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .admin-table tr:hover {
        background: rgba(112, 93, 188, 0.05);
    }

    .table-img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-active {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .status-inactive {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
    }

    .btn-edit {
        background: rgba(32, 107, 196, 0.15);
        color: #206bc4;
        border: none;
    }

    .btn-edit:hover {
        background: #206bc4;
        color: white;
    }

    .btn-delete {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
        border: none;
    }

    .btn-delete:hover {
        background: #dc3545;
        color: white;
    }

    /* Formulários */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--russian-violet);
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--slate-blue);
        box-shadow: 0 0 0 3px rgba(112, 93, 188, 0.2);
        outline: none;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 25px;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border: none;
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--russian-violet) 0%, var(--slate-blue) 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        border: none;
    }
    
    .btn-close {
        filter: invert(1);
    }
    
    /* Confirmation Dialog */
    .confirmation-dialog {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        justify-content: center;
        align-items: center;
    }
    
    .confirmation-content {
        background: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        max-width: 400px;
        width: 90%;
        box-shadow: var(--shadow-lg);
    }
    
    .confirmation-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 20px;
    }

    /* Alert Styles */
    .alert {
        border-radius: 10px;
        border: none;
        margin-bottom: 20px;
    }

    /* Responsividade */
    @media (max-width: 992px) {
        .admin-container {
            flex-direction: column;
        }
        
        .admin-sidebar {
            width: 100%;
            position: sticky;
            top: 80px;
            z-index: 99;
        }
        
        .admin-nav {
            display: flex;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .admin-nav-item {
            flex-shrink: 0;
            margin-bottom: 0;
        }
        
        .admin-nav-link {
            border-left: none;
            border-bottom: 3px solid transparent;
            padding: 10px 15px;
        }
        
        .admin-nav-link:hover,
        .admin-nav-link.active {
            border-left: none;
            border-bottom: 3px solid var(--yellow-green);
            padding-left: 15px;
        }
    }

    @media (max-width: 768px) {
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .admin-actions {
            width: 100%;
            justify-content: space-between;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .admin-table {
            font-size: 0.9rem;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 10px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
    }
  </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="index.php" class="logo">
                <img src="img/MedClickDeLadinho.png" alt="MedClick Logo">
            </a>

            <div class="user-section">
                <span class="user-welcome">Olá, <?= htmlspecialchars($nome) ?></span>
                <div class="user-profile">
                    <img src="img/gerentes/<?php echo isset($_SESSION['imagem']) ? htmlspecialchars($_SESSION['imagem']) : 'default.jpg'; ?>" class="profile-img" alt="Foto de perfil">
                    <div class="profile-menu">
                        <a href="index.php"><i class="fas fa-home"></i> Voltar ao Site</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Painel do Administrador -->
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="admin-sidebar-header">
                <h4><i class="fas fa-user-shield"></i> Painel do Gerente</h4>
                <p>Gerencie o sistema MedClick</p>
            </div>
            
            <ul class="admin-nav">
                <li class="admin-nav-item">
                    <a href="#" class="admin-nav-link active" data-panel="dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="#" class="admin-nav-link" data-panel="medicos">
                        <i class="fas fa-user-md"></i> Médicos
                        <span class="badge"><?php echo count($medicos); ?></span>
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="#" class="admin-nav-link" data-panel="farmacias">
                        <i class="fas fa-pills"></i> Farmácias
                        <span class="badge"><?php echo count($farmacias); ?></span>
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="#" class="admin-nav-link" data-panel="hospitais">
                        <i class="fas fa-hospital"></i> Hospitais
                        <span class="badge"><?php echo count($hospitais); ?></span>
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="#" class="admin-nav-link" data-panel="contatos">
                        <i class="fas fa-address-book"></i> Contatos Médicos
                        <span class="badge"><?php echo count($contatos_medicos); ?></span>
                    </a>
                </li>
                <li class="admin-nav-item">
                  <a href="cadastro_gerente.php" class="btn-cad">Cadastrar Gerente</a>
                </li>
            </ul>
        </div>

        <!-- Conteúdo Principal -->
        <div class="admin-main">
            <!-- Mensagens -->
            <?php if ($mensagem): ?>
                <div class="alert alert-<?php echo $tipo_mensagem === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
                    <i class="fas fa-<?php echo $tipo_mensagem === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo $mensagem; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Dashboard Panel -->
            <div id="dashboard-panel" class="admin-panel active-panel">
                <!-- ... conteúdo do dashboard ... -->
            </div>

            <!-- Médicos Panel -->
            <div id="medicos-panel" class="admin-panel">
                <div class="admin-header">
                    <h1 class="admin-title">Gerenciamento de Médicos</h1>
                    <div class="admin-actions">
                        <a href="cadastro_medico.php" class="btn btn-success"><i class="fas fa-plus"></i> Novo Médico</a>
                        <button class="btn btn-primary" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Atualizar</button>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <h2 class="card-title">Médicos Cadastrados</h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nome</th>
                                    <th>CRM</th>
                                    <th>Especialidade</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($medicos) > 0): ?>
                                    <?php foreach ($medicos as $medico): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($medico['imagem'])): ?>
                                                    <img src="<?php echo htmlspecialchars($medico['imagem']); ?>" class="table-img" alt="<?php echo htmlspecialchars($medico['nome']); ?>">
                                                <?php else: ?>
                                                    <div class="table-img bg-light d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-user-md text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($medico['nome']); ?></td>
                                            <td><?php echo htmlspecialchars($medico['crm']); ?></td>
                                            <td><?php echo htmlspecialchars($medico['especialidade'] ); ?></td>
                                            <td><?php echo htmlspecialchars($medico['email'] ); ?></td>
                                            <td><?php echo htmlspecialchars($medico['telefone'] ); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-edit btn-sm" onclick="editarItem(<?php echo $medico['id']; ?>, 'medico')">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                    <button class="btn btn-delete btn-sm" onclick="confirmarExclusao(<?php echo $medico['id']; ?>, 'medico', '<?php echo htmlspecialchars(addslashes($medico['nome'])); ?>')">
                                                        <i class="fas fa-trash"></i> Excluir
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Nenhum médico cadastrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Farmácias Panel -->
            <div id="farmacias-panel" class="admin-panel">
                <div class="admin-header">
                    <h1 class="admin-title">Gerenciamento de Farmácias</h1>
                    <div class="admin-actions">
                        <a href="cadastro_farmacia.php" class="btn btn-success"><i class="fas fa-plus"></i> Nova Farmácia</a>
                        <button class="btn btn-primary" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Atualizar</button>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <h2 class="card-title">Farmácias Cadastradas</h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Imagem</th>
                                    <th>Nome</th>
                                    <th>Endereço</th>
                                    <th>Cidade/Estado</th>
                                    <th>Telefone</th>
                                    <th>Horário</th>
                                    <th>24h</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($farmacias) > 0): ?>
                                    <?php foreach ($farmacias as $farmacia): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($farmacia['imagem'])): ?>
                                                    <img src="<?php echo htmlspecialchars($farmacia['imagem']); ?>" class="table-img" alt="<?php echo htmlspecialchars($farmacia['nome']); ?>">
                                                <?php else: ?>
                                                    <div class="table-img bg-light d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-pills text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($farmacia['nome']); ?></td>
                                            <td><?php echo htmlspecialchars($farmacia['endereco']); ?></td>
                                            <td><?php echo htmlspecialchars($farmacia['cidade'] . '/' . $farmacia['estado']); ?></td>
                                            <td><?php echo htmlspecialchars($farmacia['telefone']); ?></td>
                                            <td><?php echo htmlspecialchars($farmacia['abertura'] . ' - ' . $farmacia['fechamento']); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $farmacia['is_24h'] ? 'status-active' : 'status-inactive'; ?>">
                                                    <?php echo $farmacia['is_24h'] ? 'Sim' : 'Não'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-edit btn-sm" onclick="editarItem(<?php echo $farmacia['id']; ?>, 'farmacia')">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                    <button class="btn btn-delete btn-sm" onclick="confirmarExclusao(<?php echo $farmacia['id']; ?>, 'farmacia', '<?php echo htmlspecialchars(addslashes($farmacia['nome'])); ?>')">
                                                        <i class="fas fa-trash"></i> Excluir
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Nenhuma farmácia cadastrada.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Hospitais Panel -->
            <div id="hospitais-panel" class="admin-panel">
                <div class="admin-header">
                    <h1 class="admin-title">Gerenciamento de Hospitais</h1>
                    <div class="admin-actions">
                        <a href="cadastro_hospital.php" class="btn btn-success"><i class="fas fa-plus"></i> Novo Hospital</a>
                        <button class="btn btn-primary" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Atualizar</button>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <h2 class="card-title">Hospitais Cadastrados</h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Endereço</th>
                                    <th>Cidade/Estado</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($hospitais) > 0): ?>
                                    <?php foreach ($hospitais as $hospital): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($hospital['nome']); ?></td>
                                            <td><?php echo htmlspecialchars($hospital['endereco']); ?></td>
                                            <td><?php echo htmlspecialchars($hospital['cidade'] . '/' . $hospital['estado']); ?></td>
                                            <td><?php echo htmlspecialchars($hospital['telefone']); ?></td>
                                            <td><?php echo htmlspecialchars($hospital['email'] ); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-edit btn-sm" onclick="editarItem(<?php echo $hospital['id']; ?>, 'hospital')">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                    <button class="btn btn-delete btn-sm" onclick="confirmarExclusao(<?php echo $hospital['id']; ?>, 'hospital', '<?php echo htmlspecialchars(addslashes($hospital['nome'])); ?>')">
                                                        <i class="fas fa-trash"></i> Excluir
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Nenhum hospital cadastrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Contatos Panel -->
            <div id="contatos-panel" class="admin-panel">
                <div class="admin-header">
                    <h1 class="admin-title">Gerenciamento de Contatos Médicos</h1>
                    <div class="admin-actions">
                        <a href="cadastro_contato_medico.php" class="btn btn-success"><i class="fas fa-plus"></i> Novo Contato</a>
                        <button class="btn btn-primary" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Atualizar</button>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <h2 class="card-title">Contatos Cadastrados</h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Médico</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Rede Social</th>
                                    <th>Criado em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($contatos_medicos) > 0): ?>
                                    <?php foreach ($contatos_medicos as $contato): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($contato['medico_nome'] ); ?></td>
                                            <td><?php echo htmlspecialchars($contato['telefone'] ); ?></td>
                                            <td><?php echo htmlspecialchars($contato['email'] ); ?></td>
                                            <td><?php echo htmlspecialchars($contato['rede_social'] ); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($contato['criado_em'])); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-edit btn-sm" onclick="editarItem(<?php echo $contato['id']; ?>, 'contato')">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                    <button class="btn btn-delete btn-sm" onclick="confirmarExclusao(<?php echo $contato['id']; ?>, 'contato', 'Contato de <?php echo htmlspecialchars(addslashes($contato['medico_nome'])); ?>')">
                                                        <i class="fas fa-trash"></i> Excluir
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Nenhum contato cadastrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="modalEdicao" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEdicaoTitulo">Editar Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body" id="modalEdicaoCorpo">
                        <!-- Conteúdo dinâmico será inserido aqui via JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="tipo" id="editarTipo">
                        <input type="hidden" name="id" id="editarId">
                        <input type="hidden" name="salvar_edicao" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="modalConfirmacao" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmacaoMensagem">Tem certeza que deseja excluir este item?</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" id="formExclusao">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="excluirId">
                        <input type="hidden" name="tipo" id="excluirTipo">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Panel navigation
        const sidebarLinks = document.querySelectorAll('.admin-nav-link');
        const panels = document.querySelectorAll('.admin-panel');
        const modalEdicao = new bootstrap.Modal(document.getElementById('modalEdicao'));
        const modalConfirmacao = new bootstrap.Modal(document.getElementById('modalConfirmacao'));
        
        // Function to show a specific panel
        function showPanel(panelId) {
            panels.forEach(panel => {
                panel.classList.remove('active-panel');
                panel.style.display = 'none';
            });
            
            const targetPanel = document.getElementById(panelId + '-panel');
            if (targetPanel) {
                targetPanel.style.display = 'block';
                targetPanel.classList.add('active-panel');
            }
            
            // Update active link
            sidebarLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-panel') === panelId) {
                    link.classList.add('active');
                }
            });
        }
        
        // Sidebar link click event
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const panelId = this.getAttribute('data-panel');
                showPanel(panelId);
                
                // Update browser history
                history.pushState({panel: panelId}, '', `#${panelId}`);
            });
        });
        
        // Check URL hash on load
        const hash = window.location.hash.substring(1);
        if (hash && ['dashboard', 'medicos', 'farmacias', 'hospitais', 'contatos'].includes(hash)) {
            showPanel(hash);
        }
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            const panelId = event.state ? event.state.panel : 'dashboard';
            showPanel(panelId);
        });
        
        // Função para editar item
        function editarItem(id, tipo) {
            // Redirecionar para a URL de edição
            window.location.href = `?editar=1&tipo=${tipo}&id=${id}#${tipo}s`;
        }
        
        // Função para confirmar exclusão
        function confirmarExclusao(id, tipo, nome) {
            document.getElementById('excluirId').value = id;
            document.getElementById('excluirTipo').value = tipo;
            document.getElementById('confirmacaoMensagem').textContent = 
                `Tem certeza que deseja excluir "${nome}"? Esta ação não pode ser desfeita.`;
            modalConfirmacao.show();
        }
        
        // Processar dados de edição quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($item_editar && $tipo_editar): ?>
                // Preencher o modal de edição com os dados
                const modalTitulo = document.getElementById('modalEdicaoTitulo');
                const modalCorpo = document.getElementById('modalEdicaoCorpo');
                const editarTipo = document.getElementById('editarTipo');
                const editarId = document.getElementById('editarId');
                
                editarTipo.value = '<?php echo $tipo_editar; ?>';
                editarId.value = '<?php echo $item_editar['id']; ?>';
                
                // Gerar formulário baseado no tipo
                let formulario = '';
                
                switch('<?php echo $tipo_editar; ?>') {
                    case 'medico':
                        modalTitulo.textContent = 'Editar Médico';
                        formulario = `
                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($item_editar['nome']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">CRM</label>
                                <input type="text" class="form-control" name="crm" value="<?php echo htmlspecialchars($item_editar['crm']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($item_editar['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control" name="telefone" value="<?php echo htmlspecialchars($item_editar['telefone']); ?>">
                            </div>
                        `;
                        break;
                        
                    case 'farmacia':
                        modalTitulo.textContent = 'Editar Farmácia';
                        formulario = `
                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($item_editar['nome']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Endereço</label>
                                <input type="text" class="form-control" name="endereco" value="<?php echo htmlspecialchars($item_editar['endereco']); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cidade</label>
                                        <input type="text" class="form-control" name="cidade" value="<?php echo htmlspecialchars($item_editar['cidade']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <input type="text" class="form-control" name="estado" value="<?php echo htmlspecialchars($item_editar['estado']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control" name="telefone" value="<?php echo htmlspecialchars($item_editar['telefone']); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Abertura</label>
                                        <input type="time" class="form-control" name="abertura" value="<?php echo htmlspecialchars($item_editar['abertura']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fechamento</label>
                                        <input type="time" class="form-control" name="fechamento" value="<?php echo htmlspecialchars($item_editar['fechamento']); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_24h" id="is_24h" <?php echo $item_editar['is_24h'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_24h">Aberto 24 horas</label>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'hospital':
                        modalTitulo.textContent = 'Editar Hospital';
                        formulario = `
                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($item_editar['nome']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Endereço</label>
                                <input type="text" class="form-control" name="endereco" value="<?php echo htmlspecialchars($item_editar['endereco']); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cidade</label>
                                        <input type="text" class="form-control" name="cidade" value="<?php echo htmlspecialchars($item_editar['cidade']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <input type="text" class="form-control" name="estado" value="<?php echo htmlspecialchars($item_editar['estado']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control" name="telefone" value="<?php echo htmlspecialchars($item_editar['telefone']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($item_editar['email']); ?>">
                            </div>
                        `;
                        break;
                        
                    case 'contato':
                        modalTitulo.textContent = 'Editar Contato Médico';
                        formulario = `
                            <div class="mb-3">
                                <label class="form-label">Médico</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($item_editar['medico_nome']); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control" name="telefone" value="<?php echo htmlspecialchars($item_editar['telefone']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($item_editar['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rede Social</label>
                                <input type="text" class="form-control" name="rede_social" value="<?php echo htmlspecialchars($item_editar['rede_social']); ?>">
                            </div>
                        `;
                        break;
                }
                
                modalCorpo.innerHTML = formulario;
                modalEdicao.show();
                
                // Mostrar o painel correto
                showPanel('<?php echo $tipo_editar; ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>