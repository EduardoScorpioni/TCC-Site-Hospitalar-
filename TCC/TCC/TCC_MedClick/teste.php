<?php
session_start();

// Redirecionar para login se não estiver logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login1.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedClick - Sua Saúde em Primeiro Lugar</title>
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
            font-size: 16px;
            line-height: 1.6;
            transition: font-size 0.3s ease;
            cursor: default;
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

        /* Modo lupa ativo */
        body.magnifier-mode {
            cursor: none;
        }

        /* Lupa de zoom */
        .magnifier-glass {
            position: fixed;
            width: 150px;
            height: 150px;
            border: 3px solid var(--slate-blue);
            border-radius: 50%;
            background-repeat: no-repeat;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            pointer-events: none;
            z-index: 10000;
            display: none;
            overflow: hidden;
        }

        .magnifier-glass::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: inherit;
            transform: scale(2);
            transform-origin: center;
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

        .magnifier-controls {
            position: relative;
        }

        .magnifier-options {
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
            min-width: 150px;
            z-index: 1000;
        }

        .magnifier-controls:hover .magnifier-options {
            display: flex;
        }

        .magnifier-option {
            background: #f8f9fa;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-align: left;
            transition: background 0.3s ease;
            font-size: 14px;
        }

        .magnifier-option:hover {
            background: #e9ecef;
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
            
            .magnifier-options {
                right: -50px;
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
            
            .magnifier-glass {
                width: 100px;
                height: 100px;
            }
        }
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

        /* ===== CARROSSEL ===== */
        .hero-slider {
            position: relative;
            height: 80vh;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-overlay {
            background: rgba(11, 0, 51, 0.7);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide-content {
            text-align: center;
            color: var(--white);
            max-width: 800px;
            padding: 0 20px;
        }

        .slide-title {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .slide-text {
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .slider-nav {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-dot.active {
            background: var(--white);
            transform: scale(1.2);
        }

        .slider-arrows {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 30px;
            transform: translateY(-50%);
            z-index: 10;
        }

        .slider-arrow {
            background: rgba(11, 0, 51, 0.7);
            color: var(--white);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-arrow:hover {
            background: var(--slate-blue);
            transform: scale(1.1);
        }


        /* ===== FEATURES SECTION ===== */
        .features {
            padding: 80px 80px;
            background: var(--white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .feature-card {
            background: var(--white);
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--shadow-md);
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%; /* Garante que todos os cards tenham a mesma altura */
        }

        .feature-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient-primary);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-title {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--russian-violet);
            font-weight: 600;
        }

        .feature-text {
            color: #666;
            margin-bottom: 20px;
            flex-grow: 1; /* Faz o texto ocupar o espaço disponível */
        }

        /* ===== ABOUT SECTION ===== */
        .about {
            padding: 80px 0;
            background: #f8fafc;
        }

        .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .about-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            color: #555;
        }

        .about-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .about-feature {
            text-align: center;
            padding: 30px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%; /* Garante que todos os cards tenham a mesma altura */
        }

        .about-feature:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .about-feature i {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--slate-blue);
        }

        .about-feature h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--russian-violet);
        }

        .about-feature p {
            color: #666;
            flex-grow: 1; /* Faz o texto ocupar o espaço disponível */
        }

        /* ===== SERVICES SECTION ===== */
        .services {
            padding: 80px 0;
            background: #f8fafc;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .service-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .service-content {
            padding: 25px;
        }

        .service-title {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--russian-violet);
            font-weight: 600;
        }

        .service-text {
            color: #666;
            margin-bottom: 20px;
        }

        .service-features {
            margin-bottom: 20px;
        }

        .service-feature {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #555;
        }

        .service-feature i {
            color: var(--kelly-green);
            margin-right: 10px;
        }

        /* ===== STATS SECTION ===== */
        .stats {
            padding: 80px 0;
            background: var(--gradient-secondary);
            color: var(--white);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item {
            padding: 20px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--mindaro);
        }

        .stat-text {
            font-size: 1.1rem;
        }

        /* ===== TESTIMONIALS SECTION ===== */
        .testimonials {
            padding: 80px 0;
            background: var(--white);
        }

        .testimonials-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .testimonial {
            text-align: center;
            padding: 30px;
            background: #f8fafc;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }

        .testimonial-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #555;
            font-style: italic;
        }

        .testimonial-author {
            font-weight: 600;
            color: var(--russian-violet);
        }

        .testimonial-role {
            color: var(--slate-blue);
            font-size: 0.9rem;
        }

        .testimonial-nav {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .testimonial-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .testimonial-dot.active {
            background: var(--slate-blue);
            transform: scale(1.2);
        }

        /* ===== APPOINTMENT SECTION ===== */
        .appointment {
            padding: 80px 0;
            background: var(--gradient-primary);
            color: var(--white);
            text-align: center;
        }

        .appointment-text {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }

        /* ===== ABOUT SECTION ===== */
        .about {
            padding: 80px 80px;
            background: #f8fafc;
        }

        .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .about-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            color: #555;
        }

        .about-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .about-feature {
            text-align: center;
            padding: 30px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .about-feature:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .about-feature i {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--slate-blue);
        }

        .about-feature h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--russian-violet);
        }

        .about-feature p {
            color: #666;
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
        /* ===== RESPONSIVIDADE ===== */
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

            .slide-title {
                font-size: 2.2rem;
            }

            .slide-text {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }

            .hero-slider {
                height: 60vh;
            }

            .slide-title {
                font-size: 1.8rem;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .navbar {
                padding: 0 15px;
            }

            .logo img {
                height: 40px;
            }

            .hero-slider {
                height: 50vh;
            }

            .slide-title {
                font-size: 1.5rem;
            }

            .btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
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
        #magnifier {
    position: absolute;
    border: 3px solid #000;
    border-radius: 50%;
    width: 200px;  /* tamanho da lupa */
    height: 200px;
    overflow: hidden;
    pointer-events: none;
    display: none;
    z-index: 9999;
}

#magnifier .zoomed {
    position: absolute;
    transform-origin: top left;
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
    <!-- Lupa de Zoom -->
    <div class="magnifier-glass" id="magnifier"></div>

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
                    <div id="magnifier">
    <div class="zoomed"></div>
</div>
                    <!-- Lupa de Zoom -->
                    <div class="magnifier-controls">
                        <button class="accessibility-btn" id="magnifier-btn" title="Lupa de Zoom">
                            <i class="fas fa-search-plus"></i>
                        </button>
           
                        <div class="magnifier-options">
                            <button class="magnifier-option" data-zoom="2">Zoom 2x</button>
                            <button class="magnifier-option" data-zoom="3">Zoom 3x</button>
                            <button class="magnifier-option" data-zoom="4">Zoom 4x</button>
                            <button class="magnifier-option" id="toggle-magnifier">Desativar Lupa</button>
                        </div>
                    </div>
                    
                    <!-- Alto contraste -->
                    <button class="accessibility-btn" id="contrast-btn" title="Alto Contraste">
                        <i class="fas fa-adjust"></i>
                    </button>
                    
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
    <!-- Hero Slider -->
    <section class="hero-slider">
        <div class="slide active" style="background-image: url('car/foto3.jpg');">
            <div class="slide-overlay">
                <div class="slide-content">
                    <h2 class="slide-title">Cuidando de você com excelência</h2>
                    <p class="slide-text">A MedClick oferece os melhores serviços de saúde com agilidade e comodidade para você e sua família.</p>
                    <a href="AgendarConsulta.php" class="btn btn-accent">Agendar Consulta</a>
                </div>
            </div>
        </div>
        <div class="slide" style="background-image: url('car/foto2.jpg');">
            <div class="slide-overlay">
                <div class="slide-content">
                    <h2 class="slide-title">Profissionais qualificados</h2>
                    <p class="slide-text">Nossa equipe de médicos especialistas está pronta para oferecer o melhor atendimento.</p>
                    <a href="contatosMedicos.php" class="btn btn-accent">Conhecer Médicos</a>
                </div>
            </div>
        </div>
        <div class="slide" style="background-image: url('car/fot1.jpg');">
            <div class="slide-overlay">
                <div class="slide-content">
                    <h2 class="slide-title">Unidades modernas</h2>
                    <p class="slide-text">Contamos com unidades equipadas com tecnologia de ponta para seu atendimento.</p>
                    <a href="unidadesAfiliadas.php" class="btn btn-accent">Ver Unidades</a>
                </div>
            </div>
        </div>
        <div class="slider-arrows">
            <div class="slider-arrow prev"><i class="fas fa-chevron-left"></i></div>
            <div class="slider-arrow next"><i class="fas fa-chevron-right"></i></div>
        </div>
        <div class="slider-nav"></div>
    </section>

    <!-- Features Section - CORRIGIDO -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Como podemos ajudar?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <h3 class="feature-title">Unidades Afiliadas</h3>
                    <p class="feature-text">Encontre as melhores unidades de saúde perto de você com facilidade e agilidade.</p>
                    <a href="unidadesAfiliadas.php" class="btn btn-primary">Ver Unidades</a>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h3 class="feature-title">Farmácias Parceiras</h3>
                    <p class="feature-text">Encontre medicamentos com preços acessíveis nas farmácias conveniadas.</p>
                    <a href="farmacias.php" class="btn btn-primary">Ver Farmácias</a>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Agendar Consultas</h3>
                    <p class="feature-text">Agende sua consulta de forma rápida, simples e sem complicações.</p>
                    <a href="AgendarConsulta.php" class="btn btn-primary">Agendar Agora</a>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3 class="feature-title">Guia Médico</h3>
                    <p class="feature-text">Encontre o profissional ideal para suas necessidades de saúde.</p>
                    <a href="contatosMedicos.php" class="btn btn-primary">Ver Médicos</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <h2 class="section-title">Nossos Serviços</h2>
            <div class="services-grid">
                <div class="service-card">
                    <img src="img/cons.jpg" alt="Consulta Médica" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Consultas Especializadas</h3>
                        <p class="service-text">Agende consultas com especialistas de diversas áreas da medicina.</p>
                        <div class="service-features">
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Mais de 15 especialidades</span>
                            </div>
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Agendamento online</span>
                            </div>
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Resultados digitais</span>
                            </div>
                        </div>
                        <a href="AgendarConsulta.php" class="btn btn-secondary">Saiba Mais</a>
                    </div>
                </div>
                <div class="service-card">
                    <img src="https://images.unsplash.com/photo-1586773860418-d37222d8fce3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Exames" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Exames e Diagnósticos</h3>
                        <p class="service-text">Realize seus exames com conforto e receba os resultados rapidamente.</p>
                        <div class="service-features">
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Laboratório completo</span>
                            </div>
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Imagens de alta qualidade</span>
                            </div>
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Resultados online</span>
                            </div>
                        </div>
                        <a href="#" class="btn btn-secondary">Saiba Mais</a>
                    </div>
                </div>
                <div class="service-card">
                    <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Telemedicina" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Telemedicina</h3>
                        <p class="service-text">Atendimento médico online com praticidade e comodidade para você.</p>
                        <div class="service-features">
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Consultas remotas</span>
                            </div>
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Prescrição digital</span>
                            </div>
                            <div class="service-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Atendimento 24h</span>
                            </div>
                        </div>
                        <a href="#" class="btn btn-secondary">Saiba Mais</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-count="5000">0</div>
                    <div class="stat-text">Pacientes Atendidos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="250">0</div>
                    <div class="stat-text">Médicos Especialistas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="40">0</div>
                    <div class="stat-text">Unidades de Saúde</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="98">0</div>
                    <div class="stat-text">Satisfação dos Pacientes</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">O que nossos pacientes dizem</h2>
            <div class="testimonials-container">
                <div class="testimonial">
                    <p class="testimonial-text">"A MedClick revolucionou a forma como acesso serviços de saúde. Agendar consultas nunca foi tão fácil e rápido. Recomendo a todos!"</p>
                    <div class="testimonial-author">Maria Silva</div>
                    <div class="testimonial-role">Paciente desde 2023</div>
                </div>
                <div class="testimonial-nav">
                    <div class="testimonial-dot active"></div>
                    <div class="testimonial-dot"></div>
                    <div class="testimonial-dot"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointment Section -->
    <section class="appointment">
        <div class="container">
            <h2 class="section-title">Pronto para cuidar da sua saúde?</h2>
            <p class="appointment-text">Agende sua consulta agora mesmo e tenha acesso a médicos especialistas com toda a comodidade e agilidade que você merece.</p>
            <a href="AgendarConsulta.php" class="btn btn-accent">Agendar Consulta</a>
        </div>
    </section>

    <section class="about">
        <div class="container">
            <h2 class="section-title">Sobre a MedClick</h2>
            <div class="about-content">
                <p class="about-text">
                    A MedClick surgiu com o propósito de modernizar o acesso aos serviços de saúde pública. Criamos uma plataforma digital para facilitar o 
                    <strong>agendamento de consultas e exames</strong>, além de permitir a 
                    <strong>retirada de resultados online</strong>, trazendo mais agilidade, conforto e eficiência para os pacientes. 
                    Nosso compromisso é transformar a experiência dos usuários, reduzir filas e melhorar a qualidade do atendimento nos hospitais públicos.
                </p>
                <div class="about-features">
                    <div class="about-feature">
                        <i class="fas fa-rocket"></i>
                        <h3>Missão</h3>
                        <p>Democratizar o acesso à saúde de qualidade através da tecnologia.</p>
                    </div>
                    <div class="about-feature">
                        <i class="fas fa-eye"></i>
                        <h3>Visão</h3>
                        <p>Ser referência em inovação para serviços de saúde no Brasil.</p>
                    </div>
                    <div class="about-feature">
                        <i class="fas fa-heart"></i>
                        <h3>Valores</h3>
                        <p>Compromisso com o paciente, inovação constante e responsabilidade social.</p>
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
        let zoomLevel = 2;
let magnifierActive = false;

const magnifier = document.getElementById('magnifier');
const zoomed = magnifier.querySelector('.zoomed');

function activateMagnifier() {
    magnifierActive = true;
    magnifier.style.display = 'block';
    document.body.classList.add('magnifier-mode');
    document.body.style.cursor = 'none';

    // clonar toda a página dentro da lupa
    zoomed.innerHTML = document.body.innerHTML;
}

function deactivateMagnifier() {
    magnifierActive = false;
    magnifier.style.display = 'none';
    document.body.classList.remove('magnifier-mode');
    document.body.style.cursor = 'default';
    zoomed.innerHTML = '';
}

document.addEventListener('mousemove', function(e) {
    if (!magnifierActive) return;

    const glassSize = magnifier.offsetWidth;
    const halfGlass = glassSize / 2;

    magnifier.style.left = (e.pageX - halfGlass) + 'px';
    magnifier.style.top = (e.pageY - halfGlass) + 'px';

    zoomed.style.transform = `scale(${zoomLevel})`;
    zoomed.style.left = -(e.pageX * (zoomLevel - 1)) + 'px';
    zoomed.style.top = -(e.pageY * (zoomLevel - 1)) + 'px';
});

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
            // Variáveis da lupa
            let magnifierActive = false;
            let zoomLevel = 2;
            const magnifier = document.getElementById('magnifier');
            const magnifierBtn = document.getElementById('magnifier-btn');
            const toggleMagnifierBtn = document.getElementById('toggle-magnifier');

            // Controle de tamanho de fonte
            const fontOptions = document.querySelectorAll('.font-option');
            fontOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const size = this.getAttribute('data-size');
                    document.body.classList.remove('font-small', 'font-large', 'font-xlarge');
                    if (size !== 'normal') {
                        document.body.classList.add('font-' + size);
                    }
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
            
            // Sistema da Lupa de Zoom
            magnifierBtn.addEventListener('click', function() {
                if (!magnifierActive) {
                    activateMagnifier();
                }
            });

            toggleMagnifierBtn.addEventListener('click', function() {
                if (magnifierActive) {
                    deactivateMagnifier();
                } else {
                    activateMagnifier();
                }
            });

            // Opções de zoom
            document.querySelectorAll('.magnifier-option[data-zoom]').forEach(option => {
                option.addEventListener('click', function() {
                    zoomLevel = parseInt(this.getAttribute('data-zoom'));
                    if (magnifierActive) {
                        deactivateMagnifier();
                        setTimeout(activateMagnifier, 100);
                    }
                });
            });

            function activateMagnifier() {
                magnifierActive = true;
                magnifier.style.display = 'block';
                document.body.classList.add('magnifier-mode');
                magnifierBtn.classList.add('active');
                toggleMagnifierBtn.textContent = 'Desativar Lupa';
                
                // Atualizar cursor
                document.body.style.cursor = 'none';
            }

            function deactivateMagnifier() {
                magnifierActive = false;
                magnifier.style.display = 'none';
                document.body.classList.remove('magnifier-mode');
                magnifierBtn.classList.remove('active');
                toggleMagnifierBtn.textContent = 'Ativar Lupa';
                
                // Restaurar cursor
                document.body.style.cursor = 'default';
            }

            // Movimentar a lupa com o mouse
            document.addEventListener('mousemove', function(e) {
                if (!magnifierActive) return;

                const glassSize = parseInt(getComputedStyle(magnifier).width);
                const halfGlass = glassSize / 2;

                // Posicionar a lupa
                magnifier.style.left = (e.pageX - halfGlass) + 'px';
                magnifier.style.top = (e.pageY - halfGlass) + 'px';

                // Criar efeito de zoom
                const backgroundX = -(e.clientX * zoomLevel - halfGlass);
                const backgroundY = -(e.clientY * zoomLevel - halfGlass);

                magnifier.style.backgroundImage = `url(${window.location.href})`;
                magnifier.style.backgroundSize = `${window.innerWidth * zoomLevel}px ${window.innerHeight * zoomLevel}px`;
                magnifier.style.backgroundPosition = `${backgroundX}px ${backgroundY}px`;
            });

            // Desativar lupa ao rolar
            document.addEventListener('scroll', function() {
                if (magnifierActive) {
                    deactivateMagnifier();
                }
            });

            // Desativar lupa ao redimensionar a janela
            window.addEventListener('resize', function() {
                if (magnifierActive) {
                    deactivateMagnifier();
                }
            });

            // Narrador de texto
            const readBtn = document.getElementById('read-btn');
            const stopReadBtn = document.getElementById('stop-read-btn');
            let speechSynthesis = window.speechSynthesis;
            let isReading = false;
            let currentUtterance = null;
            
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
                
                const readingElements = document.querySelectorAll('.reading');
                readingElements.forEach(el => {
                    el.classList.remove('reading');
                });
            }
            
            // Ler texto específico ao passar o mouse
            if (speechSynthesis) {
                const elementsToRead = document.querySelectorAll('h1, h2, h3, h4, h5, h6, p, a, button');
                elementsToRead.forEach(element => {
                    element.addEventListener('mouseenter', function() {
                        if (isReading || magnifierActive) return;
                        
                        const text = this.getAttribute('aria-label') || this.title || this.innerText;
                        if (text && text.trim().length > 0) {
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
                if (!e.target.closest('.magnifier-controls')) {
                    document.querySelectorAll('.magnifier-options').forEach(menu => {
                        menu.style.display = 'none';
                    });
                }
            });
        });
    </script>
</body>
</html>