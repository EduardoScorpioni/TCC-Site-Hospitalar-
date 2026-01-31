<?php
session_start();
require 'conexao.php';

// Buscar farmácias cadastradas
$stmt = $pdo->query("SELECT * FROM farmacias ORDER BY nome ASC");
$farmacias = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Buscar cidades únicas sem duplicar
$stmtCidades = $pdo->query("SELECT DISTINCT cidade FROM farmacias ORDER BY cidade ASC");
$cidades = $stmtCidades->fetchAll(PDO::FETCH_COLUMN);


// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login1.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <title>Farmácias - MedClick</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    main {
        padding: 40px 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .page-title {
        font-size: 2.5rem;
        color: var(--russian-violet);
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }

    .page-title:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: var(--gradient-accent);
        border-radius: 2px;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .controls-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 500px;
        min-width: 250px;
    }

    .search-box input {
        width: 100%;
        padding: 14px 45px 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: var(--white);
        box-shadow: var(--shadow-sm);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--slate-blue);
        box-shadow: 0 0 0 3px rgba(112, 93, 188, 0.2);
    }

    .search-box i {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--slate-blue);
    }

    .filter-controls {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        background-color: var(--white);
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--slate-blue);
    }

    .view-toggle {
        display: flex;
        background: var(--white);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .view-btn {
        padding: 10px 15px;
        border: none;
        background: var(--white);
        cursor: pointer;
        transition: all 0.3s ease;
        color: #64748b;
    }

    .view-btn.active {
        background: var(--slate-blue);
        color: var(--white);
    }

    .results-info {
        margin-bottom: 20px;
        color: #64748b;
        font-size: 0.95rem;
    }

    /* Layout de Grade */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    /* Layout de Lista */
    .cards-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .cards-list .card {
        flex-direction: row;
        height: auto;
    }

    .cards-list .card img {
        width: 250px;
        height: 200px;
        border-radius: 10px 0 0 10px;
    }

    .cards-list .card-content {
        flex: 1;
        padding: 25px;
    }

    /* Card de Farmácia */
    .card {
        background-color: var(--white);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-content {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 1.3rem;
        color: var(--russian-violet);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .card-address {
        color: #64748b;
        margin-bottom: 15px;
        flex: 1;
    }

    .card-details {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
        color: #64748b;
    }

    .detail-item i {
        color: var(--slate-blue);
    }

    .card-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 8px;
    }

    .badge-open {
        background-color: #dcfce7;
        color: #166534;
    }

    .badge-closed {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .badge-24h {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .card-actions {
        display: flex;
        gap: 12px;
        margin-top: auto;
    }

    .action-btn {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: var(--gradient-primary);
        color: var(--white);
    }

    .btn-secondary {
        background: var(--white);
        color: var(--slate-blue);
        border: 2px solid var(--slate-blue);
    }

    .btn-whatsapp {
        background: #25D366;
        color: var(--white);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .no-results {
        text-align: center;
        padding: 60px 20px;
        grid-column: 1 / -1;
    }

    .no-results i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .no-results h3 {
        color: #64748b;
        margin-bottom: 10px;
    }

    .no-results p {
        color: #94a3b8;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 40px;
    }

    .pagination-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        background: var(--white);
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pagination-btn.active {
        background: var(--slate-blue);
        color: var(--white);
        border-color: var(--slate-blue);
    }

    .pagination-btn:hover:not(.active) {
        border-color: var(--slate-blue);
        color: var(--slate-blue);
    }

    .map-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .map-content {
        background: var(--white);
        border-radius: 15px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow: hidden;
        position: relative;
    }

    .map-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--white);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: var(--shadow-md);
    }

    .map-iframe {
        width: 100%;
        height: 500px;
        border: none;
    }

    @media (max-width: 768px) {
        .controls-row {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .filter-controls {
            justify-content: center;
        }
        
        .cards-list .card {
            flex-direction: column;
        }
        
        .cards-list .card img {
            width: 100%;
            border-radius: 10px 10px 0 0;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .card-actions {
            flex-direction: column;
        }
    }

    @media (max-width: 576px) {
        main {
            padding: 20px 15px;
        }
        
        .cards-grid {
            grid-template-columns: 1fr;
        }
        
        .page-title {
            font-size: 1.8rem;
        }
        
        .filter-select {
            width: 100%;
        }
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <div class="page-header">
    <h1 class="page-title">Farmácias Parceiras</h1>
    <p class="page-subtitle">Encontre a farmácia mais próxima de você e aproveite descontos exclusivos</p>
  </div>

  <div class="controls-row">
    <div class="search-box">
      <input type="text" id="search" placeholder="Buscar farmácia por nome, endereço ou medicamento...">
      <i class="fas fa-search"></i>
    </div>
    
    <div class="filter-controls">
 <select class="filter-select" id="filter-city">
  <option value="">Todas as cidades</option>
  <?php foreach ($cidades as $cidade): ?>
    <option value="<?= strtolower(str_replace(' ', '-', trim($cidade))) ?>">
      <?= htmlspecialchars($cidade) ?>
    </option>
  <?php endforeach; ?>
</select>

      <select class="filter-select" id="filter-status">
        <option value="">Todas</option>
        <option value="open">Abertas agora</option>
        <option value="24h">24 horas</option>
      </select>
      
      <div class="view-toggle">
        <button class="view-btn active" id="view-grid"><i class="fas fa-th"></i></button>
        <button class="view-btn" id="view-list"><i class="fas fa-list"></i></button>
      </div>
    </div>
  </div>
  
  <div class="results-info" id="results-info">
    Mostrando <span id="results-count">9</span> de 9 farmácias
  </div>

 <div id="cards-container" class="cards-grid">
  <?php if (!empty($farmacias)): ?>
    <?php foreach ($farmacias as $farmacia): ?>
      <?php
        // Determinar status
        $status = "open";
        $badgeClass = "badge-open";
        $badgeText = "Aberta agora";

        if ($farmacia['abertura'] === "00:00:00" && $farmacia['fechamento'] === "23:59:59") {
            $status = "24h";
            $badgeClass = "badge-24h";
            $badgeText = "24 horas";
        } else {
            $horaAtual = date("H:i:s");
            if ($horaAtual < $farmacia['abertura'] || $horaAtual > $farmacia['fechamento']) {
                $status = "closed";
                $badgeClass = "badge-closed";
                $badgeText = "Fechada";
            }
        }

        // Caminho da imagem
        $img = !empty($farmacia['imagem']) 
    ? "img/farmacias/" . $farmacia['imagem'] 
    : "img/default_farmacia.jpg";

      ?>
      <div class="card" 
           data-city="<?= strtolower(str_replace(' ', '-', $farmacia['cidade'])) ?>" 
           data-status="<?= $status ?>">
           
        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($farmacia['nome']) ?>">
        
        <div class="card-content">
          <h3 class="card-title"><?= htmlspecialchars($farmacia['nome']) ?></h3>
          <p class="card-address">
            <?= htmlspecialchars($farmacia['endereco']) ?> - 
            <?= htmlspecialchars($farmacia['cidade']) ?> - 
            <?= htmlspecialchars($farmacia['estado']) ?>
          </p>
          
          <div class="card-details">
            <span class="detail-item"><i class="fas fa-phone"></i> <?= htmlspecialchars($farmacia['telefone']) ?></span>
            <span class="detail-item"><i class="fas fa-clock"></i> 
              <?= substr($farmacia['abertura'],0,5) ?> - <?= substr($farmacia['fechamento'],0,5) ?>
            </span>
          </div>
          
          <div class="card-badge <?= $badgeClass ?>"><?= $badgeText ?></div>
          
          <div class="card-actions">
            <a href="https://www.google.com/maps?q=<?= urlencode($farmacia['endereco'].' '.$farmacia['cidade'].' '.$farmacia['estado']) ?>" 
               target="_blank" class="action-btn btn-primary">
              <i class="fas fa-map-marker-alt"></i> Como chegar
            </a>
            <a href="https://wa.me/55<?= preg_replace('/\D/', '', $farmacia['telefone']) ?>" 
               target="_blank" class="action-btn btn-whatsapp">
              <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="no-results">
      <i class="fas fa-store"></i>
      <h3>Nenhuma farmácia cadastrada</h3>
      <p>Volte mais tarde.</p>
    </div>
  <?php endif; ?>
</div>


  <div class="pagination">
    <button class="pagination-btn active">1</button>
    <button class="pagination-btn">2</button>
    <button class="pagination-btn">3</button>
  </div>
</main>

<!-- Modal para visualização do mapa -->
<div class="map-modal" id="mapModal">
  <div class="map-content">
    <div class="map-close" onclick="closeMapModal()">
      <i class="fas fa-times"></i>
    </div>
    <iframe class="map-iframe" id="mapIframe" src="" frameborder="0"></iframe>
  </div>
</div>

<script>
// Filtro de farmácias
function filtrarFarmacias() {
  const termo = document.getElementById('search').value.toLowerCase();
  const cidade = document.getElementById('filter-city').value;
  const status = document.getElementById('filter-status').value;
  const cards = document.querySelectorAll('.card');
  
  let visibleCount = 0;
  
  cards.forEach(card => {
    const nome = card.querySelector('.card-title').innerText.toLowerCase();
    const endereco = card.querySelector('.card-address').innerText.toLowerCase();
    const cardCidade = card.getAttribute('data-city');
    const cardStatus = card.getAttribute('data-status');
    
    const termoMatch = nome.includes(termo) || endereco.includes(termo);
    const cidadeMatch = !cidade || cardCidade === cidade;
    const statusMatch = !status || cardStatus === status;
    
    const exibir = termoMatch && cidadeMatch && statusMatch;
    card.style.display = exibir ? 'flex' : 'none';
    
    if (exibir) visibleCount++;
  });
  
  // Atualizar contador de resultados
  document.getElementById('results-count').textContent = visibleCount;
  
  // Mostrar mensagem se não houver resultados
  const noResults = document.getElementById('no-results');
  if (visibleCount === 0) {
    if (!noResults) {
      const container = document.getElementById('cards-container');
      const noResultsDiv = document.createElement('div');
      noResultsDiv.id = 'no-results';
      noResultsDiv.className = 'no-results';
      noResultsDiv.innerHTML = `
        <i class="fas fa-search"></i>
        <h3>Nenhuma farmácia encontrada</h3>
        <p>Tente ajustar os filtros ou termos de busca.</p>
      `;
      container.appendChild(noResultsDiv);
    }
  } else if (noResults) {
    noResults.remove();
  }
}

// Alternar entre visualização em grade e lista
document.getElementById('view-grid').addEventListener('click', function() {
  const container = document.getElementById('cards-container');
  container.className = 'cards-grid';
  this.classList.add('active');
  document.getElementById('view-list').classList.remove('active');
});

document.getElementById('view-list').addEventListener('click', function() {
  const container = document.getElementById('cards-container');
  container.className = 'cards-list';
  this.classList.add('active');
  document.getElementById('view-grid').classList.remove('active');
});

// Modal do mapa
function showMapModal(nome, endereco) {
  const modal = document.getElementById('mapModal');
  const iframe = document.getElementById('mapIframe');
  const enderecoCodificado = encodeURIComponent(endereco);
  iframe.src = `https://maps.google.com/maps?q=${enderecoCodificado}&z=15&output=embed`;
  modal.style.display = 'flex';
}

function closeMapModal() {
  const modal = document.getElementById('mapModal');
  const iframe = document.getElementById('mapIframe');
  modal.style.display = 'none';
  iframe.src = '';
}

// Fechar modal clicando fora do conteúdo
document.getElementById('mapModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeMapModal();
  }
});

// Event listeners para os filtros
document.getElementById('search').addEventListener('input', filtrarFarmacias);
document.getElementById('filter-city').addEventListener('change', filtrarFarmacias);
document.getElementById('filter-status').addEventListener('change', filtrarFarmacias);

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
  filtrarFarmacias();
  
  // Simular status de abertura (apenas para demonstração)
  const cards = document.querySelectorAll('.card');
  const now = new Date();
  const currentHour = now.getHours();
  
  cards.forEach(card => {
    const status = card.getAttribute('data-status');
    if (status === 'open') {
      // Para farmácias "abertas", verificar se está dentro do horário comercial
      if (currentHour < 8 || currentHour >= 22) {
        card.setAttribute('data-status', 'closed');
        const badge = card.querySelector('.card-badge');
        if (badge) {
          badge.className = 'card-badge badge-closed';
          badge.textContent = 'Fechada';
        }
      }
    }
  });
});
</script>
<?php include 'footer.php'; ?>
</body>
</html>