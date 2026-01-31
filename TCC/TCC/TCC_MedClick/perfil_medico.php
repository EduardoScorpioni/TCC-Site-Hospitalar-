<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    header("Location: login_medico.php");
    exit();
}

$medico_id = $_SESSION['id'];

// Busca informações do médico
$sql = "SELECT m.*, e.nome AS especialidade_nome 
        FROM medicos m
        LEFT JOIN especialidades e ON m.especialidade_id = e.id
        WHERE m.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$medico_id]);
$medico = $stmt->fetch(PDO::FETCH_ASSOC);

if ($medico) {
    $nome = $medico['nome'];
    $crm = $medico['crm'];
    $especialidade = $medico['especialidade_nome'];
    $email = isset($medico['email']) ? $medico['email'] : '';
    $telefone = isset($medico['telefone']) ? $medico['telefone'] : '';
    $imagem = $medico['imagem'] ?: 'default_doctor.jpg';
    
    // Verificar se a imagem existe no diretório uploads/
    $caminho_imagem_uploads = __DIR__ . '/uploads/' . $imagem;
    $imagem_existe = (!empty($imagem) && file_exists($caminho_imagem_uploads));
    
    // Se não existir, usar imagem padrão (na pasta img/)
    if (!$imagem_existe) {
        $imagem = 'default_doctor.jpg';
        $caminho_imagem = 'img/' . $imagem;
    } else {
        $caminho_imagem = 'uploads/' . $imagem;
    }
    
} else {
    echo "Médico não encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Médico - MedClick</title>
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
        
        .profile-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .profile-picture {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .profile-upload {
            position: relative;
            display: inline-block;
            margin-top: 15px;
        }
        
        .profile-upload-label {
            background: linear-gradient(135deg, var(--primary), #0d47a1);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .profile-upload-label:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
        }
        
        .profile-upload input[type="file"] {
            position: absolute;
            width: 0;
            height: 0;
            opacity: 0;
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
        
        .form-label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #dadce0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }
        
        .form-control:read-only {
            background-color: #f8f9fa;
            color: var(--gray);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), #0d47a1);
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
            transition: all 0.3s;
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(26, 115, 232, 0.4);
            color: white;
        }
        
        .btn-outline-primary-custom {
            background: transparent;
            border: 1px solid var(--primary);
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            color: var(--primary);
            transition: all 0.3s;
        }
        
        .btn-outline-primary-custom:hover {
            background: var(--primary);
            color: white;
        }
        
        .profile-info-card {
            background: var(--primary-light);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .profile-info-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .profile-info-label {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 5px;
        }
        
        .profile-info-value {
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
        }
        
        footer {
            background: white;
            padding: 20px 0;
            margin-top: 40px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .info-text {
            color: var(--gray);
            font-size: 0.95rem;
            margin-top: 10px;
        }
        
        .telefone-mask {
            position: relative;
        }
        
        .default-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #0d47a1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .image-error {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 5px;
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
                <h2 class="mb-1">Meu Perfil Médico</h2>
                <p class="text-muted mb-0">Gerencie suas informações pessoais e profissionais</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="pagina_medico.php" class="btn btn-outline-primary-custom">
                    <i class="fas fa-arrow-left me-2"></i> Voltar ao Painel
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Coluna da Foto e Informações Básicas -->
        <div class="col-lg-4 mb-4">
            <div class="profile-container text-center">
                <?php if ($imagem_existe && $imagem !== 'default_doctor.jpg'): ?>
                    <img src="uploads/<?php echo htmlspecialchars($imagem); ?>" 
                         alt="Foto de perfil do Dr. <?php echo htmlspecialchars($nome); ?>" 
                         class="profile-picture mb-3"
                         onerror="this.style.display='none'; document.getElementById('defaultAvatar').style.display='flex';">
                <?php endif; ?>
                
                <div id="defaultAvatar" class="default-avatar mx-auto mb-3" 
                     style="<?php echo ($imagem_existe && $imagem !== 'default_doctor.jpg') ? 'display: none;' : ''; ?>">
                    <i class="fas fa-user-md"></i>
                </div>
                
                <h4 class="mb-1"><?php echo htmlspecialchars($nome); ?></h4>
                <p class="text-muted mb-3"><?php echo htmlspecialchars($especialidade); ?></p>
                
                <?php if (!$imagem_existe && $imagem !== 'default_doctor.jpg'): ?>
                    <div class="image-error">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Imagem não encontrada: <?php echo htmlspecialchars($imagem); ?>
                    </div>
                <?php endif; ?>
                
                <div class="profile-upload mt-3">
                    <label for="imagem" class="profile-upload-label">
                        <i class="fas fa-camera me-2"></i> Alterar Foto
                    </label>
                    <input form="formEditar" type="file" name="imagem" id="imagem" accept="image/*" onchange="previewImage(this)">
                </div>
                
                <div class="mt-4">
                    <div class="profile-info-card">
                        <div class="profile-info-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="profile-info-label">CRM</div>
                        <div class="profile-info-value"><?php echo htmlspecialchars($crm); ?></div>
                    </div>
                    
                    <div class="profile-info-card">
                        <div class="profile-info-icon">
                            <i class="fas fa-briefcase-medical"></i>
                        </div>
                        <div class="profile-info-label">Especialidade</div>
                        <div class="profile-info-value"><?php echo htmlspecialchars($especialidade); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Coluna do Formulário de Edição -->
        <div class="col-lg-8">
            <div class="profile-container">
                <h4 class="section-title">Informações Pessoais</h4>
                
                <form id="formEditar" method="post" action="atualizar_perfil_medico.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CRM</label>
                            <input type="text" class="form-control" name="crm" value="<?php echo htmlspecialchars($crm); ?>" readonly>
                            <p class="info-text">O CRM não pode ser alterado após o cadastro.</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Especialidade</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($especialidade); ?>" readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefone</label>
                            <div class="telefone-mask">
                                <input type="text" class="form-control" name="telefone" id="telefone" 
                                       value="<?php echo htmlspecialchars($telefone); ?>" 
                                       oninput="formatarTelefone(this)"
                                       placeholder="(00) 00000-0000">
                            </div>
                            <p class="info-text">Digite o telefone com DDD para contato.</p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="painel_medico.php" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Formatar telefone enquanto digita
function formatarTelefone(input) {
    // Remove tudo que não é número
    let value = input.value.replace(/\D/g, '');
    
    // Aplica a máscara
    if (value.length <= 10) {
        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    } else {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }
    
    input.value = value;
}

// Preview da imagem selecionada
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Mostra a nova imagem e esconde o avatar padrão
            const imgElement = document.querySelector('.profile-picture');
            const defaultAvatar = document.getElementById('defaultAvatar');
            
            if (!imgElement) {
                // Se não existir uma tag img, cria uma
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.alt = 'Nova foto de perfil';
                newImg.className = 'profile-picture mb-3';
                newImg.onerror = function() {
                    this.style.display = 'none';
                    defaultAvatar.style.display = 'flex';
                };
                
                defaultAvatar.parentNode.insertBefore(newImg, defaultAvatar);
            } else {
                imgElement.src = e.target.result;
                imgElement.style.display = 'block';
            }
            
            defaultAvatar.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Inicializar formatação do telefone se já houver valor
document.addEventListener('DOMContentLoaded', function() {
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput && telefoneInput.value) {
        formatarTelefone(telefoneInput);
    }
});
</script>
</body>
<?php include"footer_medico.php" ?>
</html>
