<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$tipoUsuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="ico/Med-Click_1.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
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
            font-size: 16px; /* Tamanho base da fonte */
            line-height: 1.6;
            transition: font-size 0.3s ease;
        }
        
        /* Classes para controle de tamanho de fonte */
        body.font-small {
            font-size: 14px;
        }
        
        body.font-large {
            font-size: 18px;
        }
        
        body.font-xlarge {
            font-size: 20px;
        }
        
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
            padding: 0 40px;
        }

        .logo img {
            height: 50px;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        .nav-link {
            color: var(--white);
            font-weight: 500;
            padding: 8px 0;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--yellow-green);
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: var(--mindaro);
        }

        .nav-link:hover:after {
            width: 100%;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            position: absolute;
            top: 100%;
            left: 0;
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

        .dropdown:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-content a {
            display: block;
            padding: 12px 20px;
            color: var(--russian-violet);
            font-weight: 500;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(112, 93, 188, 0.1);
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown-content a:hover {
            background: rgba(112, 93, 188, 0.1);
            padding-left: 25px;
        }

        .dropdown-content a i {
            margin-right: 10px;
            color: var(--slate-blue);
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .accessibility-tools {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 15px;
        }

        .accessibility-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: var(--white);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .accessibility-btn:hover {
            background: var(--slate-blue);
            transform: scale(1.1);
        }

        .font-controls {
            position: relative;
        }

        .font-options {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            padding: 10px;
            display: none;
            flex-direction: column;
            gap: 5px;
            min-width: 120px;
            z-index: 1000;
        }

        .font-controls:hover .font-options {
            display: flex;
        }

        .font-option {
            background: #f8f9fa;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-align: left;
            transition: background 0.3s ease;
        }

        .font-option:hover {
            background: #e9ecef;
        }

        .search-container {
            position: relative;
        }

        .search-box {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            padding: 10px;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1000;
        }

        .search-container:hover .search-box {
            display: block;
        }

        .search-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }

        .search-btn {
            background: var(--slate-blue);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 5px;
        }

        .high-contrast {
            filter: contrast(140%);
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
            width: 45px;a
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

        .login-btn {
            background: var(--yellow-green);
            color: var(--russian-violet);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: var(--mindaro);
            transform: translateY(-2px);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Estilo para elementos sendo lidos pelo narrador */
        .reading {
            background-color: rgba(146, 227, 54, 0.2);
            outline: 2px solid var(--yellow-green);
        }
        .zoom-container {
        position: relative;
        display: inline-block;
        }

        .lupa {
        position: absolute;
        border: 3px solid #705dbc;
        border-radius: 50%;
        cursor: none;
        /* tamanho da lente */
        width: 120px;
        height: 120px;
        overflow: hidden;
        display: none;
        z-index: 2000;
        }

        .lupa img {
        position: absolute;
        transform: scale(2); /* nível de zoom */
        }
        @media (max-width: 992px) {
            .nav-menu {
                position: fixed;
                top: 80px;
                left: -100%;
                background: var(--russian-violet);
                width: 100%;
                height: calc(100vh - 80px);
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 30px;
                transition: left 0.3s ease;
                z-index: 999;
            }

            .nav-menu.active {
                left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }
            
            .accessibility-tools {
                margin-right: 0;
            }
            
            .search-box {
                right: -100px;
            }
        }

        @media (max-width: 576px) {
            .navbar {
                padding: 0 20px;
            }
            
            .logo img {
                height: 40px;
            }
            
            .accessibility-tools {
                gap: 5px;
            }
            
            .accessibility-btn {
                width: 35px;
                height: 35px;
            }
            
            .search-input {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php" class="logo">
                <img src="img/MedClickDeLadinho.png" alt="MedClick Logo">
            </a>

            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Início</a></li>
                <li class="dropdown">
                    <a href="#" class="nav-link">Nossos Serviços <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="AgendarConsulta.php"><i class="fas fa-calendar-check"></i> Agendar Consulta</a>
                        <a href="AgendarConsulta.php"><i class="fas fa-clipboard"></i> Agendar Atendimento</a>
                        <a href="unidadesAfiliadas.php"><i class="fas fa-hospital"></i> Hospitais</a>
                        <a href="farmacias.php"><i class="fas fa-pills"></i> Farmácias</a>
                        <a href="contatosMedicos.php"><i class="fas fa-user-md"></i> Contatos Médicos</a>
                        <a href="laboratorios.php"><i class="fas fa-flask-vial"></i> Laboratórios</a>
                        <a href="calendario_prevencao.php"><i class="fas fa-calendar-days"></i> Calendario de Prevenção</a>
                    </div>
                </li>
                <li><a href="SobreMed.php" class="nav-link">Conheça a MedClick</a></li>
                <li><a href="doe.php" class="nav-link">Ajude Vidas</a></li>
            </ul>

            <div class="user-section">
                <!-- Ferramentas de Acessibilidade -->
                <div class="accessibility-tools">
                    <!-- Controle de tamanho de fonte -->
                    <div class="font-controls">
                        <button class="accessibility-btn" title="Ajustar tamanho do texto">
                            <i class="fas fa-text-height"></i>
                        </button>
                        <div class="font-options">
                            <button class="font-option" data-size="small">A- Texto Pequeno</button>
                            <button class="font-option" data-size="normal">A Texto Normal</button>
                            <button class="font-option" data-size="large">A+ Texto Grande</button>
                            <button class="font-option" data-size="xlarge">A++ Texto Maior</button>
                        </div>
                    </div>
                    
                    <!-- Alto contraste -->
                    <button class="accessibility-btn" id="contrast-btn" title="Alto Contraste">
                        <i class="fas fa-adjust"></i>
                    </button>
                    
                    <!-- Lupa de pesquisa -->
                    <div class="search-container">
                        <button class="accessibility-btn" title="Pesquisar no site">
                            <i class="fas fa-search"></i>
                        </button>
                        <div class="search-box">
                            <input type="text" class="search-input" placeholder="Pesquisar..." id="search-input">
                            <button class="search-btn" id="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Narrador de texto -->
                    <button class="accessibility-btn" id="read-btn" title="Ouvir texto da página">
                        <i class="fas fa-volume-up"></i>
                    </button>
                    
                    <!-- Pausar narrador -->
                    <button class="accessibility-btn" id="stop-read-btn" title="Parar leitura" style="display: none;">
                        <i class="fas fa-stop"></i>
                    </button>
                </div>

                <?php if (isset($_SESSION['usuario'])): ?>
                    <span class="user-welcome">Olá, <?php echo $_SESSION['usuario']; ?></span>
                    <div class="user-profile">
                        <img src="img/<?php echo htmlspecialchars($_SESSION['imagem']); ?>" class="profile-img" alt="Foto de perfil">
                        <div class="profile-menu">
                            <?php if ($_SESSION['tipo'] === 'medico'): ?>
                                <a href="perfil_medico.php"><i class="fas fa-user-md"></i> Meu Perfil Médico</a>
                                <a href="pagina_medico.php"><i class="fas fa-calendar-alt"></i> Minha Agenda</a>
                            <?php elseif ($_SESSION['tipo'] === 'gerente'): ?>  
                            <a href="painel_gerente.php"><i class="fas fa-calendar-alt"></i> Pagina Gerente</a>      
                            <?php else: ?>
                                <a href="perfil.php"><i class="fas fa-user"></i> Meu Perfil</a>
                                <a href="minha_consulta.php"><i class="fas fa-calendar-check"></i> Minhas Consultas</a>
                                <a href="Sac.php"><i class="fa-solid fa-headset"></i> Fale Conosco</a>
                            <?php endif; ?>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login1.php" class="login-btn">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navMenu = document.querySelector('.nav-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileMenuBtn.innerHTML = navMenu.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });

        // Sistema de Acessibilidade
        document.addEventListener('DOMContentLoaded', function() {
            // Controle de tamanho de fonte
            const fontOptions = document.querySelectorAll('.font-option');
            fontOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const size = this.getAttribute('data-size');
                    document.body.classList.remove('font-small', 'font-large', 'font-xlarge');
                    if (size !== 'normal') {
                        document.body.classList.add('font-' + size);
                    }
                    // Salvar preferência
                    localStorage.setItem('fontSize', size);
                });
            });
            
            // Carregar preferência salva
            const savedFontSize = localStorage.getItem('fontSize');
            if (savedFontSize && savedFontSize !== 'normal') {
                document.body.classList.add('font-' + savedFontSize);
            }
            
            // Alto contraste
            const contrastBtn = document.getElementById('contrast-btn');
            contrastBtn.addEventListener('click', function() {
                document.body.classList.toggle('high-contrast');
                const isHighContrast = document.body.classList.contains('high-contrast');
                localStorage.setItem('highContrast', isHighContrast);
            });
            
            // Carregar preferência de contraste
            const savedHighContrast = localStorage.getItem('highContrast') === 'true';
            if (savedHighContrast) {
                document.body.classList.add('high-contrast');
            }
            
            // Sistema de pesquisa
            const searchBtn = document.getElementById('search-btn');
            const searchInput = document.getElementById('search-input');
            
            searchBtn.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            function performSearch() {
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    // Redirecionar para página de busca ou realizar busca
                    window.location.href = `busca.php?q=${encodeURIComponent(searchTerm)}`;
                }
            }
            
            // Narrador de texto
            const readBtn = document.getElementById('read-btn');
            const stopReadBtn = document.getElementById('stop-read-btn');
            let speechSynthesis = window.speechSynthesis;
            let isReading = false;
            let currentUtterance = null;
            
            // Verificar suporte à API de síntese de voz
            if (!speechSynthesis) {
                readBtn.style.display = 'none';
            }
            
            readBtn.addEventListener('click', function() {
                if (isReading) {
                    stopReading();
                } else {
                    startReading();
                }
            });
            
            stopReadBtn.addEventListener('click', stopReading);
            
            function startReading() {
                if (isReading) return;
                
                // Obter texto principal da página
                const mainContent = document.querySelector('main') || document.body;
                const textToRead = mainContent.innerText.replace(/\s+/g, ' ').trim();
                
                if (textToRead) {
                    isReading = true;
                    readBtn.style.display = 'none';
                    stopReadBtn.style.display = 'flex';
                    
                    currentUtterance = new SpeechSynthesisUtterance(textToRead);
                    currentUtterance.lang = 'pt-BR';
                    currentUtterance.rate = 0.9;
                    currentUtterance.pitch = 1;
                    
                    currentUtterance.onend = function() {
                        stopReading();
                    };
                    
                    speechSynthesis.speak(currentUtterance);
                }
            }
            
            function stopReading() {
                if (speechSynthesis.speaking) {
                    speechSynthesis.cancel();
                }
                isReading = false;
                readBtn.style.display = 'flex';
                stopReadBtn.style.display = 'none';
                
                // Remover destaque de leitura
                const readingElements = document.querySelectorAll('.reading');
                readingElements.forEach(el => {
                    el.classList.remove('reading');
                });
            }
            
            // Ler texto específico ao passar o mouse (opcional)
            if (speechSynthesis) {
                const elementsToRead = document.querySelectorAll('h1, h2, h3, h4, h5, h6, p, a, button');
                elementsToRead.forEach(element => {
                    element.addEventListener('mouseenter', function() {
                        if (isReading) return;
                        
                        const text = this.getAttribute('aria-label') || this.title || this.innerText;
                        if (text && text.trim().length > 0) {
                            // Destacar elemento sendo lido
                            this.classList.add('reading');
                            
                            const utterance = new SpeechSynthesisUtterance(text);
                            utterance.lang = 'pt-BR';
                            utterance.rate = 1;
                            utterance.onend = function() {
                                element.classList.remove('reading');
                            };
                            speechSynthesis.speak(utterance);
                        }
                    });
                    
                    element.addEventListener('mouseleave', function() {
                        if (speechSynthesis.speaking) {
                            speechSynthesis.cancel();
                        }
                        this.classList.remove('reading');
                        document.addEventListener("DOMContentLoaded", () => {
    const imagens = document.querySelectorAll(".zoom-lupa");

    imagens.forEach(img => {
        const lupa = document.createElement("div");
        lupa.classList.add("lupa");

        const zoomImg = document.createElement("img");
        zoomImg.src = img.src;

        lupa.appendChild(zoomImg);
        img.parentElement.style.position = "relative";
        img.parentElement.appendChild(lupa);

        img.addEventListener("mousemove", e => {
            const rect = img.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            lupa.style.display = "block";
            lupa.style.left = (x - lupa.offsetWidth / 2) + "px";
            lupa.style.top = (y - lupa.offsetHeight / 2) + "px";

            zoomImg.style.left = -(x * 2 - lupa.offsetWidth / 2) + "px";
            zoomImg.style.top = -(y * 2 - lupa.offsetHeight / 2) + "px";
        });

        img.addEventListener("mouseleave", () => {
            lupa.style.display = "none";
        });
    });
});
                    });
                });
            }
        });
    </script>
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