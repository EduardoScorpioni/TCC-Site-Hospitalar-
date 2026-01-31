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
    <title>Doe - MedClick</title>
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
            --gradient-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            
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
            background-color: #f8fafc;
            color: #333;
            line-height: 1.6;
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

        .btn-accent {
            background: var(--gradient-accent);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .btn-accent:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-danger {
            background: var(--gradient-danger);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        /* ===== LAYOUT PRINCIPAL ===== */
        .donation-hero {
            background: var(--gradient-secondary);
            color: var(--white);
            padding: 80px 0;
            text-align: center;
        }

        .donation-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .donation-hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto 30px;
        }

        .donation-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin: 80px 0;
        }

        @media (max-width: 768px) {
            .donation-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== SEÇÃO DE DOAÇÃO FINANCEIRA ===== */
        .donation-card {
            background: var(--white);
            border-radius: 15px;
            padding: 40px;
            box-shadow: var(--shadow-lg);
            height: fit-content;
        }

        .donation-card h2 {
            color: var(--russian-violet);
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .donation-card h3 {
            color: var(--slate-blue);
            margin: 25px 0 15px;
            font-size: 1.3rem;
        }

        .impact-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 25px 0;
        }

        .impact-stat {
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 4px solid var(--teal);
        }

        .impact-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--teal);
            margin-bottom: 5px;
        }

        .impact-text {
            color: #666;
            font-size: 0.9rem;
        }

        .donation-amounts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .amount-option {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .amount-option:hover {
            border-color: var(--teal);
            transform: translateY(-2px);
        }

        .amount-option.selected {
            border-color: var(--teal);
            background: rgba(0, 131, 143, 0.1);
        }

        .amount-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--russian-violet);
        }

        .custom-amount {
            margin: 20px 0;
        }

        .custom-amount input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1.1rem;
            text-align: center;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .payment-method {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method.selected {
            border-color: var(--teal);
            background: rgba(0, 131, 143, 0.1);
        }

        .payment-method i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--slate-blue);
        }

        /* ===== SEÇÃO DE DOAÇÃO DE SANGUE ===== */
        .blood-donation-card {
            background: var(--white);
            border-radius: 15px;
            padding: 40px;
            box-shadow: var(--shadow-lg);
            height: fit-content;
        }

        .blood-donation-card h2 {
            color: var(--russian-violet);
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .blood-info {
            background: #fff5f5;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #e74c3c;
            margin: 20px 0;
        }

        .blood-info h4 {
            color: #c0392b;
            margin-bottom: 10px;
        }

        .donation-locations {
            margin: 25px 0;
        }

        .location-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid var(--teal);
            transition: all 0.3s ease;
        }

        .location-card:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }

        .location-name {
            font-weight: 700;
            color: var(--russian-violet);
            margin-bottom: 5px;
        }

        .location-address {
            color: #666;
            margin-bottom: 10px;
        }

        .location-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .location-hours {
            color: #888;
            font-size: 0.9rem;
        }

        .location-phone {
            color: var(--teal);
            font-weight: 600;
        }

        .requirements-list {
            margin: 20px 0;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 5px;
        }

        .requirement-item i {
            color: var(--kelly-green);
            margin-right: 10px;
        }

        .blood-types {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 20px 0;
        }

        .blood-type {
            padding: 15px;
            text-align: center;
            background: #f8fafc;
            border-radius: 8px;
            font-weight: 700;
        }

        .blood-type.urgent {
            background: #ffeaa7;
            color: #d63031;
        }

        /* ===== TESTEMUNHOS ===== */
        .testimonials {
            background: var(--white);
            padding: 80px 0;
        }

        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .testimonial-card {
            background: #f8fafc;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            color: #555;
        }

        .testimonial-author {
            font-weight: 600;
            color: var(--russian-violet);
        }

        /* ===== FAQ ===== */
        .faq-section {
            background: #f8fafc;
            padding: 80px 0;
        }

        .faq-item {
            background: var(--white);
            margin-bottom: 15px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .faq-question {
            padding: 20px;
            background: var(--gradient-primary);
            color: var(--white);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-answer {
            padding: 20px;
            max-height: 500px;
        }

        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 768px) {
            .donation-hero h1 {
                font-size: 2.2rem;
            }
            
            .impact-stats {
                grid-template-columns: 1fr;
            }
            
            .donation-amounts {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .payment-methods {
                grid-template-columns: 1fr;
            }
            
            .blood-types {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="donation-hero">
        <div class="container">
            <h1>Doe e Salve Vidas</h1>
            <p>Suas doações ajudam pacientes com câncer e bancos de sangue a continuarem salvando vidas todos os dias</p>
            <div class="btn-group">
                <a href="#doacao-financeira" class="btn btn-accent">Doar para o Câncer</a>
                <a href="#doacao-sangue" class="btn btn-danger">Encontrar Hemocentro</a>
            </div>
        </div>
    </section>

    <!-- Grid Principal -->
    <div class="container">
        <div class="donation-grid">
            <!-- Doação Financeira -->
            <section id="doacao-financeira" class="donation-card">
                <h2><i class="fas fa-heart"></i> Doação para Pacientes com Câncer</h2>
                <p>Suas doações ajudam a fornecer tratamento, medicamentos e apoio para pacientes oncológicos.</p>
                
                <div class="impact-stats">
                    <div class="impact-stat">
                        <div class="impact-number">2.500+</div>
                        <div class="impact-text">Pacientes atendidos</div>
                    </div>
                    <div class="impact-stat">
                        <div class="impact-number">R$ 1,2M</div>
                        <div class="impact-text">Em tratamentos custeados</div>
                    </div>
                    <div class="impact-stat">
                        <div class="impact-number">89%</div>
                        <div class="impact-text">Diretamente para pacientes</div>
                    </div>
                    <div class="impact-stat">
                        <div class="impact-number">24/7</div>
                        <div class="impact-text">Suporte contínuo</div>
                    </div>
                </div>

                <h3>Escolha o valor da doação</h3>
                <div class="donation-amounts">
                    <div class="amount-option" data-amount="30">
                        <div class="amount-value">R$ 30</div>
                        <small>Cobre 1 dia de medicação</small>
                    </div>
                    <div class="amount-option" data-amount="50">
                        <div class="amount-value">R$ 50</div>
                        <small>Alimentação hospitalar</small>
                    </div>
                    <div class="amount-option" data-amount="100">
                        <div class="amount-value">R$ 100</div>
                        <small>Exames básicos</small>
                    </div>
                    <div class="amount-option" data-amount="250">
                        <div class="amount-value">R$ 250</div>
                        <small>Sessão de quimioterapia</small>
                    </div>
                    <div class="amount-option" data-amount="500">
                        <div class="amount-value">R$ 500</div>
                        <small>Tratamento especializado</small>
                    </div>
                    <div class="amount-option" data-amount="1000">
                        <div class="amount-value">R$ 1.000</div>
                        <small>Cirurgia emergencial</small>
                    </div>
                </div>

                <div class="custom-amount">
                    <input type="number" placeholder="Ou digite outro valor (R$)" min="10" id="custom-amount">
                </div>

                <h3>Forma de pagamento</h3>
                <div class="payment-methods">
                    <div class="payment-method" data-method="pix">
                        <i class="fas fa-qrcode"></i>
                        <div>PIX</div>
                        <small>Instantâneo</small>
                    </div>
                    <div class="payment-method" data-method="credit">
                        <i class="fas fa-credit-card"></i>
                        <div>Cartão de Crédito</div>
                        <small>Até 12x</small>
                    </div>
                    <div class="payment-method" data-method="debit">
                        <i class="fas fa-credit-card"></i>
                        <div>Cartão de Débito</div>
                        <small>À vista</small>
                    </div>
                    <div class="payment-method" data-method="boleto">
                        <i class="fas fa-barcode"></i>
                        <div>Boleto</div>
                        <small>Até 3 dias</small>
                    </div>
                </div>

                <button class="btn btn-primary" style="width: 100%; margin-top: 30px;">
                    <i class="fas fa-gift"></i> Fazer Doação
                </button>
            </section>

            <!-- Doação de Sangue -->
            <section id="doacao-sangue" class="blood-donation-card">
                <h2><i class="fas fa-tint"></i> Doe Sangue em Presidente Prudente</h2>
                <p>Encontre os hemocentros mais próximos e ajude a salvar vidas</p>
                
                <div class="blood-info">
                    <h4><i class="fas fa-exclamation-triangle"></i> Situação Atual dos Bancos de Sangue</h4>
                    <p>Os estoques estão em nível crítico. Sua doação é urgente!</p>
                </div>

                <h3>Hemocentros em Presidente Prudente</h3>
                <div class="donation-locations">
                    <!-- Hemocentro 1 -->
                    <div class="location-card">
                        <div class="location-name">Hemocentro Regional de Presidente Prudente</div>
                        <div class="location-address">
                            <i class="fas fa-map-marker-alt"></i> Av. Cel. Marcondes, 1234 - Centro
                        </div>
                        <div class="location-details">
                            <div class="location-hours">
                                <i class="fas fa-clock"></i> Seg-Sex: 7h-18h | Sáb: 7h-12h
                            </div>
                            <div class="location-phone">
                                <i class="fas fa-phone"></i> (18) 3221-5000
                            </div>
                        </div>
                    </div>

                    <!-- Hemocentro 2 -->
                    <div class="location-card">
                        <div class="location-name">Hospital Regional</div>
                        <div class="location-address">
                            <i class="fas fa-map-marker-alt"></i> Rua dos Médicos, 567 - Vila Formosa
                        </div>
                        <div class="location-details">
                            <div class="location-hours">
                                <i class="fas fa-clock"></i> Ter-Dom: 8h-17h
                            </div>
                            <div class="location-phone">
                                <i class="fas fa-phone"></i> (18) 3221-6000
                            </div>
                        </div>
                    </div>

                    <!-- Hemocentro 3 -->
                    <div class="location-card">
                        <div class="location-name">Santa Casa de Misericórdia</div>
                        <div class="location-address">
                            <i class="fas fa-map-marker-alt"></i> Praça da Bandeira, 789 - Centro
                        </div>
                        <div class="location-details">
                            <div class="location-hours">
                                <i class="fas fa-clock"></i> Seg-Sáb: 7h30-16h30
                            </div>
                            <div class="location-phone">
                                <i class="fas fa-phone"></i> (18) 3221-7000
                            </div>
                        </div>
                    </div>

                    <!-- Hemocentro 4 -->
                    <div class="location-card">
                        <div class="location-name">Hospital Universitário</div>
                        <div class="location-address">
                            <i class="fas fa-map-marker-alt"></i> Campus Universitário, s/n - Jardim das Rosas
                        </div>
                        <div class="location-details">
                            <div class="location-hours">
                                <i class="fas fa-clock"></i> Qua-Sex: 8h-16h
                            </div>
                            <div class="location-phone">
                                <i class="fas fa-phone"></i> (18) 3221-8000
                            </div>
                        </div>
                    </div>
                </div>

                <h3>Tipos Sanguíneos Mais Necessários</h3>
                <div class="blood-types">
                    <div class="blood-type urgent">O-</div>
                    <div class="blood-type urgent">O+</div>
                    <div class="blood-type">A-</div>
                    <div class="blood-type">A+</div>
                    <div class="blood-type">B-</div>
                    <div class="blood-type">B+</div>
                    <div class="blood-type">AB-</div>
                    <div class="blood-type">AB+</div>
                </div>

                <h3>Requisitos para Doação</h3>
                <div class="requirements-list">
                    <div class="requirement-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Ter entre 16 e 69 anos (menores de 18 precisam de autorização)</span>
                    </div>
                    <div class="requirement-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Pesar mais de 50kg</span>
                    </div>
                    <div class="requirement-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Estar alimentado e bem hidratado</span>
                    </div>
                    <div class="requirement-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Documento oficial com foto</span>
                    </div>
                    <div class="requirement-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Intervalo mínimo de 2 meses entre doações (homens)</span>
                    </div>
                    <div class="requirement-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Intervalo mínimo de 3 meses entre doações (mulheres)</span>
                    </div>
                </div>

                <button class="btn btn-danger" style="width: 100%; margin-top: 20px;">
                    <i class="fas fa-calendar-alt"></i> Agendar Doação de Sangue
                </button>
            </section>
        </div>
    </div>

    <!-- Testemunhos -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">Histórias que Inspiram</h2>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Graças às doações, pude completar meu tratamento contra o câncer. Cada contribuição faz diferença!"
                    </div>
                    <div class="testimonial-author">- Maria Silva, 42 anos</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Doei sangue pela primeira vez e foi uma experiência incrível. Saber que posso salvar vidas é gratificante."
                    </div>
                    <div class="testimonial-author">- João Santos, 28 anos</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Minha filha recebeu transfusões durante o tratamento. Agora nossa família doa regularmente."
                    </div>
                    <div class="testimonial-author">- Ana Costa, 35 anos</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">Perguntas Frequentes</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        Como minha doação será utilizada?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Sua doação é direcionada para medicamentos, tratamentos, transporte de pacientes e custos hospitalares de pessoas com câncer que não têm condições financeiras.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        Posso doar sangue se tive COVID-19?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Sim, após 30 dias da completa recuperação dos sintomas você já pode doar sangue normalmente.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        A doação financeira é dedutível do imposto de renda?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Sim, fornecemos recibo para dedução no imposto de renda para pessoas físicas e jurídicas.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        Quanto tempo leva para doar sangue?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Todo o processo dura cerca de 40 minutos, sendo a coleta em si aproximadamente 15 minutos.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script>
        // Seleção de valor de doação
        document.querySelectorAll('.amount-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.amount-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
                document.getElementById('custom-amount').value = this.getAttribute('data-amount');
            });
        });

        // Seleção de método de pagamento
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });

        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const item = this.parentElement;
                item.classList.toggle('active');
                
                // Fecha outros itens
                document.querySelectorAll('.faq-item').forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
            });
        });

        // Validação do valor customizado
        document.getElementById('custom-amount').addEventListener('input', function() {
            if (this.value < 10) {
                this.value = 10;
            }
            
            // Remove seleção de valores pré-definidos
            document.querySelectorAll('.amount-option').forEach(opt => {
                opt.classList.remove('selected');
            });
        });

        // Smooth scroll para as seções
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Efeito de digitação no hero
        const heroText = document.querySelector('.donation-hero h1');
        const text = heroText.textContent;
        heroText.textContent = '';
        
        let i = 0;
        function typeWriter() {
            if (i < text.length) {
                heroText.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        }
        
        // Inicia a animação quando a página carrega
        window.addEventListener('load', typeWriter);
    </script>
</body>
</html>