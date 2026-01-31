<?php
session_start();
require 'conexao.php';


?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fale Conosco - MedClick</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        /* ===== SAC SECTION ===== */
        .sac {
            padding: 80px 0;
            background: #f8fafc;
        }

        .sac-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: start;
        }

        .contact-info {
            background: var(--white);
            border-radius: 12px;
            padding: 40px;
            box-shadow: var(--shadow-md);
        }

        .contact-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--russian-violet);
            font-weight: 600;
        }

        .contact-text {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .contact-methods {
            margin-bottom: 30px;
        }

        .contact-method {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .contact-method:hover {
            background: rgba(112, 93, 188, 0.05);
            transform: translateX(5px);
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--white);
            font-size: 1.2rem;
        }

        .contact-details h4 {
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: var(--russian-violet);
        }

        .contact-details p {
            color: #666;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--gradient-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            transition: all 0.3s ease;
        }

        .social-link:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .contact-form {
            background: var(--white);
            border-radius: 12px;
            padding: 40px;
            box-shadow: var(--shadow-md);
        }

        .form-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--russian-violet);
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
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
            box-shadow: 0 0 0 2px rgba(112, 93, 188, 0.2);
            outline: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .form-submit {
            width: 100%;
            padding: 14px;
            background: var(--gradient-accent);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-submit:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .message-status {
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            display: none;
        }

        .message-success {
            background: rgba(116, 175, 50, 0.1);
            color: var(--kelly-green);
            border: 1px solid var(--kelly-green);
        }

        .message-error {
            background: rgba(113, 56, 56, 0.1);
            color: var(--garnet);
            border: 1px solid var(--garnet);
        }

        /* ===== FAQ SECTION ===== */
        .faq {
            padding: 80px 0;
            background: var(--white);
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .faq-question {
            padding: 20px;
            background: #f8fafc;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--russian-violet);
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: rgba(112, 93, 188, 0.05);
        }

        .faq-question.active {
            background: rgba(112, 93, 188, 0.1);
        }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: var(--white);
        }

        .faq-answer.active {
            padding: 20px;
            max-height: 500px;
        }

        /* ===== FOOTER ===== */
        footer {
            background: var(--russian-violet);
            color: var(--white);
            padding: 60px 0 20px;
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

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
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
        }

        .footer-social {
            display: flex;
            gap: 15px;
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
        }

        .footer-links a i {
            margin-right: 10px;
            font-size: 0.8rem;
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
        }

        .footer-contact i {
            margin-right: 15px;
            color: var(--yellow-green);
        }

        .footer-newsletter p {
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.8);
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
        }

        .newsletter-input {
            flex: 1;
            padding: 12px 15px;
            border: none;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
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
        }

        .newsletter-btn:hover {
            background: var(--mindaro);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .payment-methods {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .payment-method {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.8rem;
        }

        .security-seals {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
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
        
        /* Alto contraste */
        body.high-contrast {
            filter: contrast(140%) brightness(140%);
        }

        body.high-contrast * {
            background: #000 !important;
            color: #fff !important;
            border-color: #fff !important;
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

        .accessibility-btn.active {
            background: var(--slate-blue);
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
            font-size: 14px;
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
            font-size: 14px;
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

        /* Estilo para elementos sendo lidos pelo narrador */
        .reading {
            background-color: rgba(146, 227, 54, 0.2);
            outline: 2px solid var(--yellow-green);
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

            .sac-container {
                grid-template-columns: 1fr;
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

            .contact-info, .contact-form {
                padding: 25px;
            }

            .section-title {
                font-size: 2rem;
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

    <!-- SAC Section -->
    <section class="sac">
        <div class="container">
            <h2 class="section-title">Fale Conosco</h2>
            <div class="sac-container">
                <div class="contact-info">
                    <h3 class="contact-title">Entre em Contato</h3>
                    <p class="contact-text">
                        Estamos aqui para ajudar! Entre em contato conosco através dos canais abaixo ou preencha o formulário ao lado.
                    </p>
                    
                    <div class="contact-methods">
                        <div class="contact-method">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Telefone</h4>
                                <p>(11) 3456-7890</p>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h4>E-mail</h4>
                                <p>contato@medclick.com.br</p>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Endereço</h4>
                                <p>Av. Saúde, 123 - Centro, São Paulo - SP</p>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Horário de Atendimento</h4>
                                <p>Segunda a Sexta: 8h às 18h<br>Sábado: 8h às 12h</p>
                            </div>
                        </div>
                    </div>
                    
                    <h4 style="margin-bottom: 15px;">Siga-nos nas redes sociais:</h4>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3 class="form-title">Envie sua Mensagem</h3>
                    <form id="contactForm" action="enviar_email.php" method="POST">
                        <div class="form-group">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" id="nome" name="nome" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="assunto" class="form-label">Assunto</label>
                            <select id="assunto" name="assunto" class="form-control" required>
                                <option value="">Selecione um assunto</option>
                                <option value="Dúvida">Dúvida</option>
                                <option value="Sugestão">Sugestão</option>
                                <option value="Reclamação">Reclamação</option>
                                <option value="Elogio">Elogio</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="mensagem" class="form-label">Mensagem</label>
                            <textarea id="mensagem" name="mensagem" class="form-control" required></textarea>
                        </div>
                        
                        <button type="submit" class="form-submit">Enviar Mensagem</button>
                    </form>
                    
                    <div id="messageStatus" class="message-status"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <h2 class="section-title">Perguntas Frequentes</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Como agendar uma consulta através da MedClick?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Para agendar uma consulta, acesse a seção "Agendar Consulta" no menu principal, selecione a especialidade desejada, escolha o médico e a data/horário disponíveis. Preencha seus dados e confirme o agendamento.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Quais são as formas de pagamento aceitas?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Aceitamos cartões de crédito (Visa, Mastercard, Elo, American Express), cartões de débito, PIX e boleto bancário. Para consultas particulares, também aceitamos reembolso de convênios.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Como cancelar ou remarcar uma consulta?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Para cancelar ou remarcar uma consulta, acesse "Minhas Consultas" em seu perfil ou entre em contato conosco pelo telefone (11) 3456-7890 com pelo menos 24 horas de antecedência.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>A MedClick atende pelo meu convênio médico?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Atendemos a maioria dos principais convênios médicos. Para verificar se seu convênio é aceito, entre em contato conosco ou consulte a lista completa na seção "Convênios" do nosso site.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Como acessar resultados de exames?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Os resultados de exames ficam disponíveis em sua área do paciente após a liberação pelo laboratório. Basta acessar "Meus Exames" em seu perfil para visualizar e baixar os resultados.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
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
                <img src="selo/google.png" height="50" alt="Selo de segurança do Google">
                <img src="https://img.shields.io/badge/SSL-Secure-green" height="50" alt="SSL Secure">
                <img src="https://img.shields.io/badge/PGP-Encrypted-blue" height="50" alt="PGP Encrypted">
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
                <p>CNPJ: 12.345.678/0001-90 | MedClick © 2025 - Todos os direitos reservados</p>
            </div>
        </div>
    </footer>

    <script>
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
                
                // Feedback visual no botão
                if (isHighContrast) {
                    contrastBtn.classList.add('active');
                } else {
                    contrastBtn.classList.remove('active');
                }
            });
            
            // Carregar preferência de contraste
            const savedHighContrast = localStorage.getItem('highContrast') === 'true';
            if (savedHighContrast) {
                document.body.classList.add('high-contrast');
                contrastBtn.classList.add('active');
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
                console.log('Navegador não suporta síntese de voz');
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
                    
                    currentUtterance.onerror = function() {
                        console.log('Erro na síntese de voz');
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
                            
                            utterance.onerror = function() {
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
                    });
                });
            }

            // Fechar menus ao clicar fora
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.font-controls')) {
                    document.querySelectorAll('.font-options').forEach(menu => {
                        menu.style.display = 'none';
                    });
                }
                if (!e.target.closest('.search-container')) {
                    document.querySelectorAll('.search-box').forEach(menu => {
                        menu.style.display = 'none';
                    });
                }
            });

            // Mobile Menu Toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navMenu = document.querySelector('.nav-menu');
            
            mobileMenuBtn.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                mobileMenuBtn.innerHTML = navMenu.classList.contains('active') ? 
                    '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
            });

            // FAQ Accordion
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const answer = question.nextElementSibling;
                    const isActive = question.classList.contains('active');
                    
                    // Fechar todas as respostas
                    document.querySelectorAll('.faq-question').forEach(q => {
                        q.classList.remove('active');
                    });
                    document.querySelectorAll('.faq-answer').forEach(a => {
                        a.classList.remove('active');
                    });
                    
                    // Abrir a resposta clicada se não estava ativa
                    if (!isActive) {
                        question.classList.add('active');
                        answer.classList.add('active');
                    }
                });
            });

            // Form Submission
            const contactForm = document.getElementById('contactForm');
            const messageStatus = document.getElementById('messageStatus');
            
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Simulação de envio (substituir por código real de envio)
                const formData = new FormData(contactForm);
                
                // Mostrar mensagem de sucesso
                messageStatus.textContent = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
                messageStatus.className = 'message-status message-success';
                messageStatus.style.display = 'block';
                
                // Limpar formulário
                contactForm.reset();
                
                // Esconder mensagem após 5 segundos
                setTimeout(() => {
                    messageStatus.style.display = 'none';
                }, 5000);
                
                // Aqui você faria a requisição AJAX para enviar o email
                /*
                fetch('enviar_email.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageStatus.textContent = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
                        messageStatus.className = 'message-status message-success';
                        messageStatus.style.display = 'block';
                        contactForm.reset();
                    } else {
                        messageStatus.textContent = 'Erro ao enviar mensagem. Tente novamente.';
                        messageStatus.className = 'message-status message-error';
                        messageStatus.style.display = 'block';
                    }
                })
                .catch(error => {
                    messageStatus.textContent = 'Erro ao enviar mensagem. Tente novamente.';
                    messageStatus.className = 'message-status message-error';
                    messageStatus.style.display = 'block';
                });
                */
            });
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