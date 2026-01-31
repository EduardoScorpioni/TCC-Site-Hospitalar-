<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login1.php");
    exit();
}

include 'conexao.php';

$email_sessao = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (empty($email_sessao)) {
    echo "Erro: usuário não está logado corretamente.";
    exit();
}

$sql = "SELECT * FROM pacientes WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email_sessao]);
$usuario = $stmt->fetch();

if ($usuario) {
    $nome = $usuario['nome'];
    $email = $usuario['email'];
    $telefone = $usuario['telefone'];
    $imagem = $usuario['imagem'];
    $cpf = $usuario['cpf'] ;
    $data_nascimento = $usuario['data_nascimento'] ;
    $endereco = $usuario['endereco'] ;
} else {
    echo "Usuário não encontrado.";
    exit();
}

// Buscar informações adicionais (estatísticas)
$sql_consultas = "SELECT COUNT(*) as total_consultas FROM consultas WHERE paciente_id = ?";
$stmt_consultas = $pdo->prepare($sql_consultas);
$stmt_consultas->execute([$usuario['id']]);
$total_consultas = $stmt_consultas->fetch()['total_consultas'];

// Consultas recentes
$sql_recentes = "SELECT e.nome as especialidade, m.nome as medico, a.data 
                 FROM consultas c 
                 JOIN especialidades e ON c.especialidade_id = e.id 
                 JOIN medicos m ON c.medico_id = m.id 
                 JOIN agenda a ON c.agenda_id = a.id 
                 WHERE c.paciente_id = ? 
                 ORDER BY a.data DESC 
                 LIMIT 3";
$stmt_recentes = $pdo->prepare($sql_recentes);
$stmt_recentes->execute([$usuario['id']]);
$consultas_recentes = $stmt_recentes->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - MedClick</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            
            --gradient-primary: linear-gradient(135deg, var(--teal) 0%, var(--caribbean-current) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--slate-blue) 0%, var(--russian-violet-2) 100%);
            --gradient-accent: linear-gradient(135deg, var(--kelly-green) 0%, var(--yellow-green) 100%);
            --gradient-hero: linear-gradient(135deg, var(--russian-violet) 0%, var(--slate-blue) 100%);
            
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f0f7ff 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Section */
        .profile-header {
            background: var(--gradient-hero);
            color: var(--white);
            border-radius: 20px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .welcome-section {
            display: flex;
            align-items: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .profile-image-container {
            position: relative;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--white);
            box-shadow: var(--shadow-lg);
        }

        .change-image-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--white);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate-blue);
            cursor: pointer;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .change-image-btn:hover {
            transform: scale(1.1);
            background: var(--mindaro);
        }

        .welcome-text h1 {
            font-size: 2.2rem;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .welcome-text p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .stat-card {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #64748b;
        }

        .stat-consultas .stat-icon { color: var(--teal); }
        .stat-tempo .stat-icon { color: var(--kelly-green); }
        .stat-especialidades .stat-icon { color: var(--slate-blue); }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            margin: 30px 0;
        }

        @media (min-width: 900px) {
            .content-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        /* Form Section */
        .form-section {
            background: var(--white);
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow-md);
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--russian-violet);
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 4px;
            background: var(--gradient-accent);
            border-radius: 2px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-weight: 600;
            color: var(--russian-violet);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            color: var(--slate-blue);
        }

        .form-input {
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--slate-blue);
            box-shadow: 0 0 0 3px rgba(112, 93, 188, 0.2);
        }

        .form-input:disabled {
            background-color: #f8fafc;
            color: #64748b;
        }

        .file-input-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
            cursor: pointer;
        }

        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: var(--gradient-primary);
            color: var(--white);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .file-input-label:hover {
            background: var(--gradient-secondary);
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 25px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: transparent;
            color: var(--slate-blue);
            border: 2px solid var(--slate-blue);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:hover {
            background: var(--gradient-secondary);
        }

        /* Sidebar Section */
        .sidebar-section {
            background: var(--white);
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--shadow-md);
        }

        .recent-consultas {
            margin-top: 20px;
        }

        .consulta-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .consulta-item:last-child {
            border-bottom: none;
        }

        .consulta-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            flex-shrink: 0;
        }

        .consulta-info {
            flex: 1;
        }

        .consulta-title {
            font-weight: 600;
            color: var(--russian-violet);
            margin-bottom: 4px;
            font-size: 0.95rem;
        }

        .consulta-detail {
            font-size: 0.85rem;
            color: #64748b;
        }

        .security-section {
            margin-top: 30px;
        }

        .security-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .security-item:last-child {
            border-bottom: none;
        }

        .security-icon {
            color: var(--slate-blue);
            font-size: 1.2rem;
            width: 24px;
        }

        .security-text {
            flex: 1;
            font-size: 0.9rem;
        }

        .security-action {
            color: var(--teal);
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .security-action:hover {
            color: var(--caribbean-current);
        }

        /* Preview de imagem */
        .image-preview-container {
            text-align: center;
            margin: 15px 0;
            display: none;
        }

        .image-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--slate-blue);
            box-shadow: var(--shadow-md);
        }

        .remove-image {
            display: inline-block;
            margin-top: 10px;
            color: #ef4444;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-container {
                padding: 15px;
            }
            
            .profile-header {
                padding: 20px;
            }
            
            .welcome-section {
                flex-direction: column;
                text-align: center;
            }
            
            .welcome-text h1 {
                font-size: 1.8rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-number {
                font-size: 1.8rem;
            }
            
            .profile-image {
                width: 100px;
                height: 100px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section, .sidebar-section {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="profile-container">
        <!-- Header Section -->
        <section class="profile-header">
            <div class="welcome-section">
                <div class="profile-image-container">
                    <img src="img/<?php echo htmlspecialchars($imagem); ?>" alt="Foto de perfil" class="profile-image" id="profileImage">
                    <label for="imagem" class="change-image-btn" title="Alterar foto">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
                <div class="welcome-text">
                    <h1>Olá, <?php echo htmlspecialchars(explode(' ', $nome)[0]); ?>!</h1>
                    <p>Gerencie suas informações pessoais e preferências de conta</p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <div class="stats-grid">
            <div class="stat-card stat-consultas">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number"><?php echo $total_consultas; ?></div>
                <div class="stat-label">Consultas Realizadas</div>
            </div>
            
            <div class="stat-card stat-tempo">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo rand(1, 12); ?></div>
                <div class="stat-label">Meses conosco</div>
            </div>
            
            <div class="stat-card stat-especialidades">
                <div class="stat-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div class="stat-number"><?php echo rand(2, 8); ?></div>
                <div class="stat-label">Especialidades</div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Form Section -->
            <section class="form-section">
                <h2 class="section-title">Informações Pessoais</h2>
                
                <form method="post" action="atualizar_perfil.php" enctype="multipart/form-data" id="profileForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="nome"><i class="fas fa-user"></i> Nome Completo</label>
                            <input type="text" name="nome" id="nome" class="form-input" value="<?php echo htmlspecialchars($nome); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="email"><i class="fas fa-envelope"></i> E-mail</label>
                            <input type="email" name="email" id="email" class="form-input" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="telefone"><i class="fas fa-phone"></i> Telefone</label>
                            <input type="text" name="telefone" id="telefone" class="form-input" value="<?php echo htmlspecialchars($telefone); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="cpf"><i class="fas fa-id-card"></i> CPF</label>
                            <input type="text" name="cpf" id="cpf" class="form-input" value="<?php echo htmlspecialchars($cpf); ?>" placeholder="000.000.000-00">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="data_nascimento"><i class="fas fa-birthday-cake"></i> Data de Nascimento</label>
                            <input type="date" name="data_nascimento" id="data_nascimento" class="form-input" value="<?php echo htmlspecialchars($data_nascimento); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="endereco"><i class="fas fa-map-marker-alt"></i> Endereço</label>
                            <input type="text" name="endereco" id="endereco" class="form-input" value="<?php echo htmlspecialchars($endereco); ?>" placeholder="Seu endereço completo">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-camera"></i> Foto de Perfil</label>
                        <div class="file-input-container">
                            <label class="file-input-label">
                                <i class="fas fa-upload"></i> Escolher Imagem
                                <input type="file" name="imagem" id="imagem" class="file-input" accept="image/*">
                            </label>
                        </div>
                    </div>
                    
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <img src="" class="image-preview" id="imagePreview" alt="Preview da imagem">
                        <div class="remove-image" onclick="removeImage()">Remover imagem</div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </section>

            <!-- Sidebar Section -->
            <aside class="sidebar-section">
                <h2 class="section-title">Consultas Recentes</h2>
                
                <div class="recent-consultas">
                    <?php if (count($consultas_recentes) > 0): ?>
                        <?php foreach ($consultas_recentes as $consulta): 
                            $dataFormatada = date('d/m/Y', strtotime($consulta['data']));
                        ?>
                            <div class="consulta-item">
                                <div class="consulta-icon">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <div class="consulta-info">
                                    <div class="consulta-title"><?php echo htmlspecialchars($consulta['especialidade']); ?></div>
                                    <div class="consulta-detail"><?php echo htmlspecialchars($consulta['medico']); ?> • <?php echo $dataFormatada; ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #64748b; text-align: center; padding: 20px 0;">
                            Nenhuma consulta recente
                        </p>
                    <?php endif; ?>
                </div>
                
                <div class="security-section">
                    <h2 class="section-title">Segurança</h2>
                    
                    <div class="security-item">
                        <i class="fas fa-shield-alt security-icon"></i>
                        <div class="security-text">Senha da conta</div>
                        <div class="security-action" onclick="changePassword()">Alterar</div>
                    </div>
                    
                    <div class="security-item">
                        <i class="fas fa-bell security-icon"></i>
                        <div class="security-text">Notificações</div>
                        <div class="security-action" onclick="manageNotifications()">Gerenciar</div>
                    </div>
                    
                    <div class="security-item">
                        <i class="fas fa-history security-icon"></i>
                        <div class="security-text">Histórico de acesso</div>
                        <div class="security-action" onclick="viewAccessHistory()">Visualizar</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        // Preview da imagem selecionada
        document.getElementById('imagem').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            document.getElementById('imagem').value = '';
            document.getElementById('imagePreviewContainer').style.display = 'none';
        }

        // Formatação de campos
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 6) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            }
            e.target.value = value;
        });

        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 9) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d{0,3})/, '$1.$2');
            }
            e.target.value = value;
        });

        // Funções de segurança (simuladas)
        function changePassword() {
            alert('Redirecionando para a página de alteração de senha...');
            // Em implementação real: window.location.href = 'change_password.php';
        }

        function manageNotifications() {
            alert('Abrindo configurações de notificação...');
            // Em implementação real: abrir modal ou redirecionar
        }

        function viewAccessHistory() {
            alert('Exibindo histórico de acesso...');
            // Em implementação real: window.location.href = 'access_history.php';
        }

        // Validação do formulário
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const telefone = document.getElementById('telefone').value.replace(/\D/g, '');
            const cpf = document.getElementById('cpf').value.replace(/\D/g, '');
            
            if (telefone && telefone.length !== 10 && telefone.length !== 11) {
                e.preventDefault();
                alert('Por favor, insira um telefone válido com DDD + número (10 ou 11 dígitos)');
                return;
            }
            
            if (cpf && cpf.length !== 11) {
                e.preventDefault();
                alert('Por favor, insira um CPF válido com 11 dígitos');
                return;
            }
        });
    </script>
</body>
<?php include"footer.php"?>
</html>