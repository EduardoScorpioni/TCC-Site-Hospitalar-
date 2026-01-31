<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Médico</title>
  <style>
    * { 
        box-sizing: border-box; 
        margin: 0; 
        padding: 0; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body { 
        background: linear-gradient(135deg, #e8f4f8 0%, #c9e6f2 100%); 
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        width: 700px;
        max-width: 95%;
        padding: 40px;
        position: relative;
        overflow: hidden;
        margin: 20px 0;
    }

    .container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: linear-gradient(90deg, #1a73e8, #0B0033);
    }

    .logo { 
        display: block;
        margin: 0 auto 25px;
        width: 80px;
        height: auto;
    }

    h2 { 
        text-align: center; 
        margin-bottom: 30px; 
        color: #1a73e8;
        font-size: 28px;
        font-weight: 600;
    }

    form { 
        display: flex; 
        flex-direction: column; 
        gap: 20px; 
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 650px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 5px;
    }

    label { 
        font-weight: 600; 
        margin-bottom: 8px; 
        display: block; 
        color: #444;
        font-size: 15px;
    }

    input, select, textarea {
        padding: 14px; 
        border: 1px solid #ddd; 
        border-radius: 8px; 
        font-size: 15px; 
        width: 100%;
        transition: all 0.3s ease;
        background-color: #f9fafc;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        background-color: #fff;
    }

    button {
        padding: 16px; 
        background: #1a73e8; 
        color: white; 
        border: none;
        border-radius: 8px; 
        font-size: 17px; 
        cursor: pointer; 
        margin-top: 15px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: background 0.3s ease;
        box-shadow: 0 4px 6px rgba(26, 115, 232, 0.2);
    }
    
    button:hover { 
        background: #0f5cc3; 
        box-shadow: 0 6px 8px rgba(26, 115, 232, 0.3);
    }

    #preview-container { 
        display: none; 
        margin-top: 20px; 
        text-align: center; 
        padding: 15px;
        background: #f9fafc;
        border-radius: 10px;
    }
    
    #preview-container canvas { 
        border-radius: 50%; 
        border: 3px solid #1a73e8; 
        margin-bottom: 15px; 
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    #preview-container button {
        margin-top: 0;
        padding: 10px 18px;
        font-size: 15px;
    }

    .senha-container { 
        position: relative; 
    }
    
    .senha-container span { 
        position: absolute; 
        right: 15px; 
        top: 50%; 
        transform: translateY(-50%);
        cursor: pointer; 
        color: #777;
        font-size: 18px;
    }
    
    .required::after {
        content: '*';
        color: #e53935;
        margin-left: 4px;
    }
    
    .form-note {
        font-size: 13px;
        color: #777;
        margin-top: 5px;
    }
    
    .search-box {
        position: relative;
        margin-bottom: 8px;
    }
    
    .search-box::before {
        content: '🔍';
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
    }
    
    .search-box input {
        padding-left: 35px;
    }
    
    select option {
        padding: 8px;
    }
    
    select option:hover {
        background-color: #f0f7ff;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="img/MedClickDeLadinho.png" class="logo" alt="Logo">

    <h2>Cadastro de Médico</h2>

    <form action="cadastrar_medico.php" method="POST" enctype="multipart/form-data">

      <!-- Nome e CRM lado a lado -->
      <div class="grid-2">
        <div class="form-group">
          <label for="nome" class="required">Nome do Médico</label>
          <input type="text" id="nome" name="nome" required placeholder="Sem prefixo Dr./Dra.">
        </div>

        <div class="form-group">
          <label for="crm" class="required">CRM</label>
          <input type="text" id="crm" name="crm" required>
          <p class="form-note">Número de registro profissional</p>
        </div>
      </div>

      <!-- Especialidade e Local lado a lado -->
      <div class="grid-2">
        <div class="form-group">
          <label for="especialidade" class="required">Especialidade</label>
          <select id="especialidade" name="especialidade_id" required>
            <option value="">Selecione</option>
            <?php foreach($especialidades as $esp): ?>
              <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="buscarLocal" class="required">Buscar Local de Consulta</label>
          <div class="search-box">
            <input type="text" id="buscarLocal" placeholder="Digite para buscar hospital/local..." oninput="filtrarLocais()">
          </div>
          
          <label for="local_consulta_id" class="required">Local de Consulta</label>
          <select id="local_consulta_id" name="local_consulta_id" required>
            <option value="">Selecione um local</option>
            <?php foreach ($locais as $local): ?>
              <option value="<?= $local['id'] ?>"><?= htmlspecialchars($local['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Foto de Perfil -->
      <div class="form-group">
        <label for="imagem">Foto de Perfil</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" onchange="handleImage(event)">
        <p class="form-note">Formatos: JPG, PNG (opcional)</p>
        
        <div id="preview-container">
          <canvas id="preview-canvas" width="200" height="200"></canvas><br>
          <button type="button" onclick="rotateImage()">Girar Imagem</button>
        </div>

        <input type="hidden" id="imagem_base64" name="imagem_base64">
      </div>

      <!-- Senha -->
      <div class="form-group">
        <label for="senha" class="required">Senha</label>
        <div class="senha-container">
          <input type="password" id="senha" name="senha" required>
          <span onclick="toggleSenha()">👁️</span>
        </div>
        <p class="form-note">Mínimo de 8 caracteres</p>
      </div>

      <!-- Botão -->
      <button type="submit">Cadastrar Médico</button>
    </form>
  </div>

  <script>
    function toggleSenha() {
      const pwd = document.getElementById('senha');
      const toggleBtn = pwd.nextElementSibling;
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
      toggleBtn.textContent = pwd.type === 'password' ? '👁️' : '🔒';
    }

    let currentImage = null, rotation = 0;

    function handleImage(event) {
      const file = event.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = e => {
        const img = new Image();
        img.onload = () => {
          currentImage = img;
          rotation = 0;
          drawImage();
          document.getElementById('preview-container').style.display = 'block';
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }

    function drawImage() {
      const canvas = document.getElementById('preview-canvas');
      const ctx = canvas.getContext('2d');
      const size = canvas.width;

      ctx.clearRect(0, 0, size, size);
      ctx.save();
      ctx.translate(size/2, size/2);
      ctx.rotate(rotation * Math.PI / 180);

      const scale = Math.min(size/currentImage.width, size/currentImage.height);
      ctx.drawImage(
        currentImage,
        -currentImage.width*scale/2,
        -currentImage.height*scale/2,
        currentImage.width*scale,
        currentImage.height*scale
      );

      ctx.restore();
      document.getElementById('imagem_base64').value = canvas.toDataURL('image/jpeg');
    }

    function rotateImage() {
      rotation = (rotation + 90) % 360;
      drawImage();
    }

    // Filtro de locais de consulta
    function filtrarLocais() {
      const filtro = document.getElementById('buscarLocal').value.toLowerCase();
      const opcoes = document.getElementById('local_consulta_id').options;

      for (let i = 0; i < opcoes.length; i++) {
        const texto = opcoes[i].textContent.toLowerCase();
        opcoes[i].style.display = texto.includes(filtro) ? '' : 'none';
      }
    }
  </script>
</body>
</html>