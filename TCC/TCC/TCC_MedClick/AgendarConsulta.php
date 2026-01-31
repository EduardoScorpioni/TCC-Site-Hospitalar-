<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['email'])) {
    header("Location:login1.php");
    exit();
}

// Dados do paciente e especialidades
$email = $_SESSION['email'];
$stmt = $pdo->prepare("SELECT id, nome, cpf FROM pacientes WHERE email = ?");
$stmt->execute([$email]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);
$especialidades = $pdo->query("SELECT id, nome FROM especialidades ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// ================= AJAX interno =================
if (isset($_GET['acao'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        // 1) Buscar médicos por especialidade
        if ($_GET['acao'] === 'medicos' && !empty($_GET['especialidade_id'])) {
            $esp = (int)$_GET['especialidade_id'];
            $sql = "SELECT m.id, m.nome, m.especialidade_id, l.nome AS local_consulta, l.endereco
                    FROM medicos m
                    LEFT JOIN locais_consulta l ON m.local_consulta_id = l.id
                    WHERE m.especialidade_id = ?
                    ORDER BY m.nome";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$esp]);
            $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $medicos]);
            exit;
        }

        // 2) Buscar datas com horários disponíveis
        if ($_GET['acao'] === 'datas' && !empty($_GET['medico_id'])) {
            $med = (int)$_GET['medico_id'];

            $sql = "
                SELECT DISTINCT DATE(a.data) AS data
                FROM agenda a
                WHERE a.medico_id = ?
                  AND a.disponivel = 1
                  AND a.data >= CURDATE()
                ORDER BY a.data ASC
                LIMIT 30
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$med]);
            $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $datas]);
            exit;
        }

        // 3) Buscar horas livres para uma data
        if ($_GET['acao'] === 'horas' && !empty($_GET['medico_id']) && !empty($_GET['data'])) {
            $med = (int)$_GET['medico_id'];
            $data = $_GET['data'];

            $sql = "
                SELECT a.id, TIME_FORMAT(a.hora, '%H:%i') AS hora
                FROM agenda a
                WHERE a.medico_id = ?
                  AND a.data = ?
                  AND a.disponivel = 1
                  AND NOT EXISTS (
                    SELECT 1 FROM consultas c 
                    WHERE c.medico_id = a.medico_id 
                    AND c.data = a.data 
                    AND c.hora = a.hora 
                    AND c.status IN ('Agendada', 'Realizada')
                  )
                ORDER BY a.hora ASC
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$med, $data]);
            $horas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $horas]);
            exit;
        }

        echo json_encode(['success' => false, 'error' => 'Ação não reconhecida']);
        exit;
    } catch (Exception $e) {
        error_log("Erro no AJAX: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Erro interno do servidor']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Agendar Consultas - MedClick</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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
            background-color: #f8fafc;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            background-color: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 40px;
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
        }

        h1 {
            text-align: center;
            color: var(--russian-violet);
            margin-bottom: 30px;
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 15px;
        }

        h1:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-accent);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--russian-violet);
        }

        input[type="text"], select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        input[type="text"]:focus, select:focus {
            outline: none;
            border-color: var(--slate-blue);
            box-shadow: 0 0 0 3px rgba(112, 93, 188, 0.2);
        }

        input[type="text"]:disabled {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .local-box {
            padding: 16px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid var(--teal);
            display: none;
        }

        .local-box strong {
            color: var(--teal);
        }

        .info-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--kelly-green);
        }

        .info-card h3 {
            color: var(--russian-violet);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card h3 i {
            color: var(--slate-blue);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--russian-violet);
        }

        .info-value {
            color: #64748b;
        }

        button {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 16px;
            font-size: 1.1rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: var(--shadow-md);
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        button:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step-indicator:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--white);
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #94a3b8;
            position: relative;
            z-index: 2;
        }

        .step.active {
            background: var(--slate-blue);
            border-color: var(--slate-blue);
            color: var(--white);
        }

        .step.completed {
            background: var(--kelly-green);
            border-color: var(--kelly-green);
            color: var(--white);
        }

        .step-label {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 8px;
            font-size: 0.8rem;
            color: #64748b;
            white-space: nowrap;
        }

        .success-message {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left: 4px solid var(--kelly-green);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: none;
        }

        .error-message {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #ef4444;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: none;
        }

        .debug {
            font-size: 0.9rem;
            color: #666;
            margin-top: 20px;
            padding: 15px;
            background: #f1f5f9;
            border-radius: 8px;
            display: none;
        }

        .no-availability {
            padding: 20px;
            text-align: center;
            background: #fef3c7;
            border-radius: 10px;
            margin: 15px 0;
            border-left: 4px solid #f59e0b;
        }

        .no-availability i {
            font-size: 2rem;
            color: #f59e0b;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .step-label {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 1.6rem;
            }
            
            input[type="text"], select {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main>
<div class="container">
    <h1><i class="fas fa-calendar-check"></i> Agendar Consulta</h1>
    
    <!-- Mensagens de sucesso/erro -->
    <?php if (isset($_SESSION['sucesso_agendamento'])): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i> 
            <strong>Sucesso!</strong>
            <p><?= $_SESSION['sucesso_agendamento'] ?></p>
        </div>
        <?php unset($_SESSION['sucesso_agendamento']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['erro_agendamento'])): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> 
            <strong>Erro!</strong>
            <p><?= $_SESSION['erro_agendamento'] ?></p>
        </div>
        <?php unset($_SESSION['erro_agendamento']); ?>
    <?php endif; ?>
    
    <div class="step-indicator">
        <div class="step active" id="step1">1</div>
        <div class="step" id="step2">2</div>
        <div class="step" id="step3">3</div>
        <div class="step" id="step4">4</div>
    </div>
    
    <div class="info-card">
        <h3><i class="fas fa-user"></i> Seus Dados</h3>
        <div class="info-item">
            <span class="info-label">Nome:</span>
            <span class="info-value"><?= htmlspecialchars($paciente['nome']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">CPF:</span>
            <span class="info-value"><?= htmlspecialchars($paciente['cpf']) ?></span>
        </div>
    </div>

    <form id="formAgendar" action="salvar_consulta.php" method="post">
        <input type="hidden" name="paciente_id" value="<?= (int)$paciente['id'] ?>">
        <input type="hidden" id="hidden_especialidade_id" name="especialidade_id" value="">

        <div class="form-group">
            <label for="especialidade_select"><i class="fas fa-stethoscope"></i> Tipo de Consulta</label>
            <select id="especialidade_select" name="especialidade_select" required>
                <option value="">Selecione o Tipo de Consulta</option>
                <?php foreach($especialidades as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="medico_id"><i class="fas fa-user-md"></i> Médico</label>
            <select id="medico_id" name="medico_id" required disabled>
                <option value="">Selecione a especialidade primeiro</option>
            </select>
        </div>

        <div id="local_display" class="local-box"></div>

        <div id="datas-horas"></div>

        <button type="submit" id="submit-btn" disabled>
            <i class="fas fa-calendar-plus"></i> Confirmar Agendamento
        </button>
    </form>

    <div class="debug" id="debugOutput"></div>
</div>
</main>
<?php include 'footer.php'; ?>

<script>
// helper para mostrar debug
function debug(msg) {
    console.log(msg); // agora só aparece no console do navegador (F12)
}

// Atualizar indicador de etapas
function updateStepIndicator(step) {
    const steps = document.querySelectorAll('.step');
    steps.forEach((s, i) => {
        if (i + 1 < step) {
            s.classList.add('completed');
            s.classList.remove('active');
        } else if (i + 1 === step) {
            s.classList.add('active');
            s.classList.remove('completed');
        } else {
            s.classList.remove('active', 'completed');
        }
    });
}

// Inicializar com a primeira etapa
updateStepIndicator(1);

// Função para fazer requisições AJAX
async function fetchData(url) {
    try {
        debug('Fetching: ' + url);
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error('Erro HTTP: ' + response.status);
        }
        
        const data = await response.json();
        debug('Resposta: ' + JSON.stringify(data));
        
        if (!data.success) {
            throw new Error(data.error || 'Erro desconhecido');
        }
        
        return data.data;
    } catch (error) {
        console.error('Erro na requisição:', error);
        showError('Erro ao carregar dados. Tente novamente.');
        throw error;
    }
}

// Mostrar mensagem de erro
function showError(message) {
    alert('Erro: ' + message);
    debug('ERRO: ' + message);
}

// 1) Carrega médicos ao escolher especialidade
document.getElementById('especialidade_select').addEventListener('change', async function () {
    const espId = this.value;
    if (!espId) return;
    
    // Atualiza o campo hidden com a especialidade selecionada
    document.getElementById('hidden_especialidade_id').value = espId;
    
    updateStepIndicator(2);
    
    const medSel = document.getElementById('medico_id');
    medSel.innerHTML = '<option value="">Carregando médicos...</option>';
    medSel.disabled = true;
    
    document.getElementById('datas-horas').innerHTML = '';
    document.getElementById('local_display').style.display = 'none';
    document.getElementById('submit-btn').disabled = true;

    try {
        const medicos = await fetchData(`AgendarConsulta.php?acao=medicos&especialidade_id=${encodeURIComponent(espId)}`);
        
        medSel.innerHTML = '<option value="">Selecione o Médico</option>';
        if (!medicos || medicos.length === 0) {
            medSel.innerHTML = '<option value="">Nenhum médico disponível</option>';
            medSel.disabled = true;
            document.getElementById('datas-horas').innerHTML = `
                <div class="no-availability">
                    <i class="fas fa-calendar-times"></i>
                    <p>Não há médicos disponíveis para esta especialidade no momento.</p>
                </div>
            `;
            return;
        }
        
        medicos.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.nome;
            opt.dataset.local = m.local_consulta || 'Local não definido';
            opt.dataset.endereco = m.endereco || '';
            opt.dataset.especialidade = m.especialidade_id || '';
            medSel.appendChild(opt);
        });
        medSel.disabled = false;
    } catch (error) {
        medSel.innerHTML = '<option value="">Erro ao carregar</option>';
        debug('Erro ao carregar médicos: ' + error.message);
    }
});

// 2) Ao selecionar médico: exibe local e carrega datas
document.getElementById('medico_id').addEventListener('change', async function () {
    const medId = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const local = selectedOption?.dataset?.local || '';
    const endereco = selectedOption?.dataset?.endereco || '';
    const divLocal = document.getElementById('local_display');

    if (local) {
        let localHtml = `<strong>Local da Consulta:</strong> ${escapeHtml(local)}`;
        if (endereco) {
            localHtml += `<br><strong>Endereço:</strong> ${escapeHtml(endereco)}`;
        }
        divLocal.innerHTML = localHtml;
        divLocal.style.display = 'block';
    } else {
        divLocal.innerHTML = `<strong>Local da Consulta:</strong> A definir`;
        divLocal.style.display = 'block';
    }

    if (!medId) {
        document.getElementById('datas-horas').innerHTML = '';
        document.getElementById('submit-btn').disabled = true;
        return;
    }
    
    updateStepIndicator(3);
    
    document.getElementById('datas-horas').innerHTML = `
        <div class="form-group">
            <label>Data</label>
            <select disabled>
                <option>Carregando datas disponíveis...</option>
            </select>
        </div>
    `;

    try {
        const datas = await fetchData(`AgendarConsulta.php?acao=datas&medico_id=${encodeURIComponent(medId)}`);
        
        const div = document.getElementById('datas-horas');
        if (!datas || datas.length === 0) {
            div.innerHTML = `
                <div class="no-availability">
                    <i class="fas fa-calendar-times"></i>
                    <p>Não há datas disponíveis para este médico no momento.</p>
                </div>
            `;
            document.getElementById('submit-btn').disabled = true;
            return;
        }
        
        let html = '<div class="form-group">';
        html += '<label><i class="fas fa-calendar-day"></i> Data da Consulta</label>';
        html += '<select id="data_agenda" name="data_agenda" required>';
        html += '<option value="">Selecione a Data</option>';
        datas.forEach(d => {
            html += `<option value="${d.data}">${formatDateBR(d.data)}</option>`;
        });
        html += '</select></div><div id="horarios"></div>';
        div.innerHTML = html;

        document.getElementById('data_agenda').addEventListener('change', async function () {
            const dataSelecionada = this.value;
            if (!dataSelecionada) {
                document.getElementById('horarios').innerHTML = '';
                document.getElementById('submit-btn').disabled = true;
                return;
            }
            
            updateStepIndicator(4);
            
            document.getElementById('horarios').innerHTML = `
                <div class="form-group">
                    <label>Horário</label>
                    <select disabled>
                        <option>Carregando horários disponíveis...</option>
                    </select>
                </div>
            `;

            try {
                const horas = await fetchData(`AgendarConsulta.php?acao=horas&medico_id=${encodeURIComponent(medId)}&data=${encodeURIComponent(dataSelecionada)}`);
                
                const divHoras = document.getElementById('horarios');
                if (!horas || horas.length === 0) {
                    divHoras.innerHTML = `
                        <div class="no-availability">
                            <i class="fas fa-clock"></i>
                            <p>Não há horários disponíveis para esta data.</p>
                        </div>
                    `;
                    document.getElementById('submit-btn').disabled = true;
                    return;
                }
                
                let hhtml = '<div class="form-group">';
                hhtml += '<label><i class="fas fa-clock"></i> Horário</label>';
                hhtml += '<select id="agenda_id" name="agenda_id" required>';
                hhtml += '<option value="">Selecione o Horário</option>';
                horas.forEach(h => {
                    hhtml += `<option value="${h.id}">${h.hora}</option>`;
                });
                hhtml += '</select></div>';
                divHoras.innerHTML = hhtml;
                
                document.getElementById('agenda_id').addEventListener('change', function() {
                    document.getElementById('submit-btn').disabled = !this.value;
                });
            } catch (error) {
                console.error('Erro ao carregar horas', error);
                divHoras.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Erro ao carregar horários. Tente novamente.</p>
                    </div>
                `;
                debug('Erro ao carregar horas: ' + error.message);
            }
        });
    } catch (error) {
        console.error('Erro ao carregar datas', error);
        document.getElementById('datas-horas').innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Erro ao carregar datas. Tente novamente.</p>
            </div>
        `;
        debug('Erro ao carregar datas: ' + error.message);
    }
});

// Prevenir envio do formulário até que esteja completo
document.getElementById('formAgendar').addEventListener('submit', function(e) {
    const agendaId = document.getElementById('agenda_id');
    if (!agendaId || !agendaId.value) {
        e.preventDefault();
        alert('Por favor, selecione um horário para a consulta.');
    }
});

// util
function formatDateBR(ymd) {
    if (!ymd) return ymd;
    const parts = ymd.split('-');
    if (parts.length !== 3) return ymd;
    
    const date = new Date(parts[0], parts[1] - 1, parts[2]);
    return date.toLocaleDateString('pt-BR', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

function escapeHtml(s) {
    if (!s) return '';
    return s.toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>
</body>
</html>