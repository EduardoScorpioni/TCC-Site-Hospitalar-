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

        footer {
            background: var(--russian-violet);
            color: var(--white);
            padding: 60px 0 20px;
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
            color: var(--white);
            text-decoration: none;
        }

        .social-icon:hover {
            background: var(--slate-blue);
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
            background: var(--yellow-green);
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
            color: var(--mindaro);
        }

        .footer-links a:hover {
            color: var(--mindaro);
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
            color: var(--yellow-green);
            margin-top: 4px;
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
            color: var(--white);
            font-family: inherit;
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .newsletter-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 50px;
            background: var(--yellow-green);
            color: var(--russian-violet);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .newsletter-btn:hover {
            background: var(--mindaro);
        }

        .footer-bottom {
            text-align: center;
            padding: 30px 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .payment-methods {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .payment-method {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .security-seals {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .security-seals img {
            height: 50px;
            border-radius: 5px;
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
                    A MedClick é uma plataforma inovadora que conecta pacientes a serviços de saúde com agilidade e transparência.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="footer-column">
                <h3 class="footer-title">Links Rápidos</h3>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-chevron-right"></i> Início</a></li>
                    <li><a href="AgendarConsulta.php"><i class="fas fa-chevron-right"></i> Agendar Consulta</a></li>
                    <li><a href="unidadesAfiliadas.php"><i class="fas fa-chevron-right"></i> Unidades</a></li>
                    <li><a href="farmacias.php"><i class="fas fa-chevron-right"></i> Farmácias</a></li>
                    <li><a href="contatosMedicos.php"><i class="fas fa-chevron-right"></i> Médicos</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3 class="footer-title">Contato</h3>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> Av. Saúde, 123 - Centro, São Paulo - SP</li>
                    <li><i class="fas fa-phone"></i> (11) 3456-7890</li>
                    <li><i class="fas fa-envelope"></i> contato@medclick.com.br</li>
                    <li><i class="fas fa-clock"></i> Seg a Sex: 8h às 18h | Sáb: 8h às 12h</li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3 class="footer-title">Newsletter</h3>
                <p class="footer-text">Inscreva-se para receber novidades e dicas de saúde.</p>
                <form class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="Seu e-mail" required>
                    <button type="submit" class="newsletter-btn">Inscrever</button>
                </form>
            </div>
        </div>
        
        <div class="security-seals">
            <img src="selo/google.png" alt="Selo de segurança do Google">
            <img src="https://img.shields.io/badge/SSL-Secure-green" alt="SSL Secure">
            <img src="https://img.shields.io/badge/PGP-Encrypted-blue" alt="PGP Encrypted">
        </div>
        <div class="emergency-contacts">
            <a href="tel:192" class="emergency-contact"><i class="fas fa-ambulance"></i> SAMU: 192</a>
            <a href="tel:193" class="emergency-contact"><i class="fas fa-fire"></i> Bombeiros: 193</a>
            <a href="tel:190" class="emergency-contact"><i class="fas fa-shield-alt"></i> Polícia: 190</a>
        </div>
        <div class="professional-resources">
            <a href="https://www.gov.br/saude" target="_blank" class="professional-resource"><i class="fas fa-external-link-alt"></i> Ministério da Saúde</a>
            <a href="https://www.cfm.org.br" target="_blank" class="professional-resource"><i class="fas fa-external-link-alt"></i> Conselho Federal de Medicina</a>
            <a href="SobreMed.php" class="professional-resource"><i class="fas fa-file-medical-alt"></i> Protocolos Clínicos</a>
        </div>
        
        
        <div class="footer-bottom">
            <p>CNPJ: 12.345.678/0001-90 | MedClick © <?php echo $currentYear; ?> - Todos os direitos reservados</p>
        </div>
    </footer>
</body>
</html>