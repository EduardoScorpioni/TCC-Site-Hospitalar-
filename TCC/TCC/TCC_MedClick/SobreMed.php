<?php
// SobreMed.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre a MedClick - Revolucionando o Acesso à Saúde</title>
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
        }

        .sobre-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Hero Section */
        .hero-section {
            background: var(--gradient-hero);
            color: var(--white);
            border-radius: 20px;
            padding: 60px 40px;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
        }

        .hero-title {
            font-size: 3rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .hero-stats {
            display: flex;
            gap: 30px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--mindaro);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 5px;
        }

        /* Content Sections */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            margin: 40px 0;
        }

        @media (min-width: 900px) {
            .content-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        .main-card, .side-card {
            background: var(--white);
            border-radius: 20px;
            padding: 35px;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .main-card:hover, .side-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .section-title {
            font-size: 1.8rem;
            color: var(--russian-violet);
            margin-bottom: 20px;
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

        .text-content {
            color: #555;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .text-content p {
            margin-bottom: 20px;
        }

        .highlight {
            background: linear-gradient(120deg, rgba(146, 227, 54, 0.15) 0%, rgba(199, 241, 152, 0.15) 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 4px solid var(--kelly-green);
            margin: 25px 0;
        }

        /* Timeline */
        .timeline {
            margin: 40px 0;
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--gradient-primary);
            border-radius: 3px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -33px;
            top: 5px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: var(--teal);
            border: 3px solid var(--white);
            box-shadow: 0 0 0 3px var(--teal);
        }

        .timeline-year {
            font-weight: 700;
            color: var(--teal);
            margin-bottom: 5px;
        }

        .timeline-content {
            color: #555;
        }

        /* Values Section */
        .values-section {
            margin: 50px 0;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .value-card {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border-top: 5px solid var(--teal);
        }

        .value-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .value-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--slate-blue);
        }

        .value-title {
            font-size: 1.2rem;
            color: var(--russian-violet);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .value-description {
            color: #666;
            font-size: 0.95rem;
        }

        /* Conduct List */
        .conduct-list {
            list-style: none;
            margin: 25px 0;
        }

        .conduct-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .conduct-item:last-child {
            border-bottom: none;
        }

        .conduct-number {
            background: var(--gradient-primary);
            color: var(--white);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .conduct-content {
            flex: 1;
        }

        /* Buttons */
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 25px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
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
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:hover {
            background: var(--gradient-secondary);
        }

        /* Team Section */
        .team-section {
            margin: 60px 0;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .team-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            text-align: center;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .team-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .team-content {
            padding: 20px;
        }

        .team-name {
            font-size: 1.2rem;
            color: var(--russian-violet);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .team-role {
            color: var(--slate-blue);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .team-description {
            color: #666;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-stats {
                gap: 20px;
                justify-content: center;
            }
            
            .stat-item {
                flex: 1;
                min-width: 120px;
            }
            
            .btn-container {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .sobre-container {
                padding: 15px;
            }
            
            .hero-section {
                padding: 40px 25px;
            }
            
            .main-card, .side-card {
                padding: 25px;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated {
            animation: fadeInUp 0.8s ease forwards;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <div class="sobre-container">
        <!-- Hero Section -->
        <section class="hero-section animated">
            <div class="hero-content">
                <h1 class="hero-title">Revolucionando o Acesso à Saúde</h1>
                <p class="hero-subtitle">Tecnologia, humanidade e eficiência trabalhando juntas para transformar sua experiência em saúde pública</p>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">50k+</span>
                        <span class="stat-label">Pacientes Atendidos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">300+</span>
                        <span class="stat-label">Profissionais</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">40+</span>
                        <span class="stat-label">Unidades</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Satisfação</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="content-grid">
            <!-- Main Card -->
            <article class="main-card animated delay-1">
                <h2 class="section-title">Nossa Jornada</h2>
                
                <div class="text-content">
                    <p>A <strong>MedClick</strong> nasceu de uma ideia simples mas poderosa: como poderíamos usar a tecnologia para transformar a experiência often frustrante e demorada de acesso aos serviços de saúde pública?</p>
                    
                    <div class="highlight">
                        <p>Nossa missão é democratizar o acesso à saúde de qualidade através de uma plataforma intuitiva que conecta pacientes, profissionais e unidades de saúde em um ecossistema integrado e eficiente.</p>
                    </div>
                    
                    <p>Começamos em 2020 com um pequeno grupo de médicos e desenvolvedores apaixonados por inovação em saúde. Hoje, somos uma plataforma completa que oferece agendamento de consultas, resultados de exames online, prontuário eletrônico e telemedicina.</p>
                    
                    <p>Acreditamos que a tecnologia deve servir às pessoas, não o contrário. Por isso, cada funcionalidade foi pensada para tornar sua experiência mais humana, ágil e menos burocrática.</p>
                </div>

                <h3 class="section-title">Nossa Trajetória</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-year">2020</div>
                        <div class="timeline-content">Fundação da MedClick com foco em agendamento online para unidades básicas de saúde</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2021</div>
                        <div class="timeline-content">Expansão para 5 cidades e lançamento do sistema de resultados online</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2022</div>
                        <div class="timeline-content">Integração com prontuário eletrônico e início dos serviços de telemedicina</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2023</div>
                        <div class="timeline-content">Lançamento do aplicativo mobile e expansão para 15 estados</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2024</div>
                        <div class="timeline-content">Parceria com o Ministério da Saúde e início da expansão internacional</div>
                    </div>
                </div>

                <div class="btn-container">
                    <a href="AgendarConsulta.php" class="btn btn-primary">
                        <i class="fas fa-calendar-check"></i> Agendar Consulta
                    </a>
                    <a href="contato.php" class="btn btn-secondary">
                        <i class="fas fa-comments"></i> Fale Conosco
                    </a>
                </div>
            </article>

            <!-- Side Card -->
            <aside class="side-card animated delay-2">
                <h2 class="section-title">Nossos Valores</h2>
                
                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="value-title">Humanidade</h3>
                        <p class="value-description">Colocamos as pessoas em primeiro lugar, com empatia e respeito</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="value-title">Eficiência</h3>
                        <p class="value-description">Otimizamos processos para reduzir tempo e burocracia</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="value-title">Segurança</h3>
                        <p class="value-description">Protegemos seus dados com os mais altos padrões de segurança</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="value-title">Inovação</h3>
                        <p class="value-description">Buscamos constantemente novas soluções para melhorar sua experiência</p>
                    </div>
                </div>

                <h3 class="section-title">Compromisso Ético</h3>
                <ul class="conduct-list">
                    <li class="conduct-item">
                        <span class="conduct-number">1</span>
                        <div class="conduct-content">
                            <strong>Transparência total</strong> em todos os processos e comunicações
                        </div>
                    </li>
                    <li class="conduct-item">
                        <span class="conduct-number">2</span>
                        <div class="conduct-content">
                            <strong>Privacidade absoluta</strong> dos dados médicos e pessoais
                        </div>
                    </li>
                    <li class="conduct-item">
                        <span class="conduct-number">3</span>
                        <div class="conduct-content">
                            <strong>Acesso inclusivo</strong> para todas as idades e necessidades
                        </div>
                    </li>
                    <li class="conduct-item">
                        <span class="conduct-number">4</span>
                        <div class="conduct-content">
                            <strong>Qualidade garantida</strong> em todos os serviços prestados
                        </div>
                    </li>
                </ul>
            </aside>
        </div>

        <!-- Team Section -->
        <section class="team-section animated delay-3">
            <h2 class="section-title">Conheça Nossa Liderança</h2>
            
            <div class="team-grid">
                <div class="team-card">
                    <img src="https://media.bizj.us/view/img/12633701/lisaerickson7390websize*900xx1200-676-0-0.jpg" alt="Dra. Ana Costa" class="team-image">
                    <div class="team-content">
                        <h3 class="team-name">Dra. Ana Costa</h3>
                        <p class="team-role">CEO & Fundadora</p>
                        <p class="team-description">Médica com 15 anos de experiência e especialista em inovação em saúde</p>
                    </div>
                </div>
                
                <div class="team-card">
                    <img src="https://png.pngtree.com/png-vector/20250813/ourmid/pngtree-elderly-latino-doctor-in-classy-physician-attire-on-white-background-png-image_17113912.webp" alt="Dr. Carlos Mendes" class="team-image">
                    <div class="team-content">
                        <h3 class="team-name">Dr. Carlos Mendes</h3>
                        <p class="team-role">Diretor Médico</p>
                        <p class="team-description">Especialista em saúde pública e gestão de unidades de saúde</p>
                    </div>
                </div>
                
                <div class="team-card">
                    <img src="https://img.freepik.com/fotos-premium/mulher-confiante-ceo-isolada-em-fundo-branco-mulher-caucasiana-ceo-em-estudio_474717-89198.jpg" alt="Marina Oliveira" class="team-image">
                    <div class="team-content">
                        <h3 class="team-name">Marina Oliveira</h3>
                        <p class="team-role">CTO</p>
                        <p class="team-description">Especialista em tecnologia da saúde e segurança de dados</p>
                    </div>
                </div>
                
                <div class="team-card">
                    <img src="https://png.pngtree.com/png-vector/20250429/ourmid/pngtree-a-businessman-in-tailored-suit-adjusting-his-tie-look-png-image_16070540.png" alt="Ricardo Santos" class="team-image">
                    <div class="team-content">
                        <h3 class="team-name">Ricardo Santos</h3>
                        <p class="team-role">Diretor de Experiência</p>
                        <p class="team-description">Focado em criar a melhor jornada para pacientes e médicos</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Animação de contagem para as estatísticas
        document.addEventListener('DOMContentLoaded', function() {
            const statNumbers = document.querySelectorAll('.stat-number');
            const statsSection = document.querySelector('.hero-stats');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        statNumbers.forEach(stat => {
                            const target = parseInt(stat.textContent);
                            let current = 0;
                            const increment = target / 50;
                            
                            const timer = setInterval(() => {
                                current += increment;
                                if (current >= target) {
                                    stat.textContent = target + '+';
                                    clearInterval(timer);
                                } else {
                                    stat.textContent = Math.floor(current) + '+';
                                }
                            }, 40);
                        });
                        
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(statsSection);
        });
    </script>
</body>
</html>

<?php
include 'footer.php';
?>