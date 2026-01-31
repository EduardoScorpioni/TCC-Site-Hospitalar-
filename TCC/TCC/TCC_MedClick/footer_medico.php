<?php
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* ===== VARIÁVEIS DE CORES ===== */
        :root {
            --primary: #1a73e8;
            --primary-light: #e8f0fe;
            --secondary: #34a853;
            --warning: #f9ab00;
            --danger: #ea4335;
            --dark: #202124;
            --light: #f8f9fa;
            --gray: #5f6368;
            
            --teal: #00838fff;
            --caribbean-current: #1b767eff;
            --slate-blue: #705dbcff;
            --russian-violet: #0b0033ff;
            --russian-violet-2: #1c0f4dff;
            
            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, #0d47a1 100%);
            --gradient-secondary: linear-gradient(135deg, var(--slate-blue) 0%, var(--russian-violet-2) 100%);
            
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        footer {
            background: var(--russian-violet);
            color: var(--light);
            padding: 60px 0 20px;
            margin-top: 40px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        .footer-column {
            display: flex;
            flex-direction: column;
        }

        .footer-logo {
            margin-bottom: 20px;
        }

        .footer-logo img {
            height: 50px;
        }

        .footer-text {
            margin-bottom: 20px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.8);
            max-width: 300px;
        }

        .footer-social {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: var(--light);
            text-decoration: none;
        }

        .social-icon:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-title {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
            font-weight: 600;
        }

        .footer-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--primary);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .footer-links a i {
            margin-right: 10px;
            font-size: 0.8rem;
            color: var(--primary-light);
        }

        .footer-links a:hover {
            color: var(--primary-light);
            padding-left: 5px;
        }

        .footer-contact {
            list-style: none;
        }

        .footer-contact li {
            display: flex;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.8);
            align-items: flex-start;
        }

        .footer-contact i {
            margin-right: 15px;
            color: var(--primary);
            margin-top: 4px;
        }

        .footer-newsletter p {
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .newsletter-input {
            flex: 1;
            min-width: 200px;
            padding: 12px 15px;
            border: none;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
            font-family: inherit;
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .newsletter-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 50px;
            background: var(--primary);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .newsletter-btn:hover {
            background: #0d47a1;
        }

        .footer-bottom {
            text-align: center;
            padding: 30px 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .professional-resources {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .professional-resource {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .professional-resource:hover {
            background: var(--primary);
            color: white;
        }

        .emergency-contacts {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .emergency-contact {
            background: rgba(234, 67, 53, 0.2);
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border: 1px solid rgba(234, 67, 53, 0.3);
            transition: all 0.3s ease;
        }

        .emergency-contact:hover {
            background: rgba(234, 67, 53, 0.3);
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .newsletter-form {
                flex-direction: column;
            }
            
            .newsletter-input {
                min-width: 100%;
            }
            
            .professional-resources, .emergency-contacts {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <div class="footer-logo">
                    <img src="img/MedClickDeLadinho.png" alt="MedClick Logo">
                </div>
                <p class="footer-text">
                    Plataforma especializada para profissionais de saúde. Conectamos médicos a pacientes com agilidade e eficiência.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-researchgate"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="footer-column">
                <h3 class="footer-title">Área Médica</h3>
                <ul class="footer-links">
                    <li><a href="agenda_medico.php"><i class="fas fa-calendar-alt"></i> Minha Agenda</a></li>
                    <li><a href="cadastrar_horarios.php"><i class="fas fa-clock"></i> Cadastrar Horários</a></li>
                    <li><a href="historico_consultas.php"><i class="fas fa-history"></i> Histórico de Consultas</a></li>
                    <li><a href="prontuarios.php"><i class="fas fa-file-medical"></i> Prontuários Eletrônicos</a></li>
                    <li><a href="prescricoes.php"><i class="fas fa-prescription"></i> Prescrições</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3 class="footer-title">Suporte Médico</h3>
                <ul class="footer-contact">
                    <li><i class="fas fa-headset"></i> Suporte Técnico: (11) 3456-7891</li>
                    <li><i class="fas fa-envelope"></i> medicos@medclick.com.br</li>
                    <li><i class="fas fa-clock"></i> Seg a Sex: 7h às 22h | Sáb: 8h às 14h</li>
                    <li><i class="fas fa-video"></i> Tutoriais: capacitação.medclick.com.br</li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3 class="footer-title">Atualizações Médicas</h3>
                <p class="footer-text">Receba atualizações sobre protocolos, eventos e novidades da plataforma.</p>
                <form class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="E-mail profissional" required>
                    <button type="submit" class="newsletter-btn">Assinar</button>
                </form>
            </div>
        </div>
        
        <div class="emergency-contacts">
            <a href="tel:192" class="emergency-contact"><i class="fas fa-ambulance"></i> SAMU: 192</a>
            <a href="tel:193" class="emergency-contact"><i class="fas fa-fire"></i> Bombeiros: 193</a>
            <a href="tel:190" class="emergency-contact"><i class="fas fa-shield-alt"></i> Polícia: 190</a>
        </div>
        
        <div class="professional-resources">
            <a href="https://www.gov.br/saude" target="_blank" class="professional-resource"><i class="fas fa-external-link-alt"></i> Ministério da Saúde</a>
            <a href="https://www.cfm.org.br" target="_blank" class="professional-resource"><i class="fas fa-external-link-alt"></i> Conselho Federal de Medicina</a>
            <a href="protocolos.php" class="professional-resource"><i class="fas fa-file-medical-alt"></i> Protocolos Clínicos</a>
        </div>
        
        <div class="footer-bottom">
            <p>CNPJ: 12.345.678/0001-90 | Plataforma certificada para profissionais de saúde | MedClick © <?php echo $currentYear; ?> - Todos os direitos reservados</p>
        </div>
    </footer>
</body>
</html>