<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Paciente</title>
  <style>
    * { 
        box-sizing: border-box; 
        margin: 0; 
        padding: 0; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body { 
        background: linear-gradient(135deg, #e8f0fe 0%, #d0e1ff 100%); 
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 750px;
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

    @media (max-width: 600px) {
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

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        background-color: #fff;
    }

    textarea { 
        resize: vertical; 
        min-height: 80px; 
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

    .radio-group { 
        display: flex; 
        gap: 15px; 
        align-items: center; 
        margin-top: 5px;
    }
    
    .radio-group label {
        font-weight: normal;
        display: inline;
        margin-right: 15px;
    }
    
    #campo-deficiencia { 
        display: none; 
        margin-top: 10px;
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
  </style>
</head>
<body>
  <div class="container">
    <img src="img/MedClickDeLadinho.png" alt="Logo" class="logo">
    <h2>Cadastro de Paciente</h2>

    <form action="cadrastrar.php" method="POST" enctype="multipart/form-data">

      <!-- Nome + CPF -->
      <div class="grid-2">
        <div class="form-group">
          <label for="nome" class="required">Nome Completo</label>
          <input type="text" id="nome" name="nome" required>
        </div>
        <div class="form-group">
          <label for="cpf" class="required">CPF</label>
          <input type="text" id="cpf" name="cpf" required>
          <p class="form-note">Somente números</p>
        </div>
      </div>

      <!-- Email + Telefone -->
      <div class="grid-2">
        <div class="form-group">
          <label for="email" class="required">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="telefone" class="required">Telefone</label>
          <input type="tel" id="telefone" name="telefone" required>
        </div>
      </div>

      <!-- Sexo + Nascimento -->
      <div class="grid-2">
        <div class="form-group">
          <label for="sexo" class="required">Sexo</label>
          <select id="sexo" name="sexo" required>
            <option value="">Selecione</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Outro">Outro</option>
          </select>
        </div>
        <div class="form-group">
          <label for="data_nascimento" class="required">Data de Nascimento</label>
          <input type="date" id="data_nascimento" name="data_nascimento" required>
        </div>
      </div>

      <!-- Endereço -->
      <div class="form-group">
        <label for="endereco" class="required">Endereço</label>
        <input type="text" id="endereco" name="endereco" required>
      </div>

      <!-- Imagem -->
      <div class="form-group">
        <label for="imagem">Foto de Perfil</label>
        <input type="file" id="imagem" name="imagem" accept="image/*">
        <p class="form-note">Formatos: JPG, PNG (opcional)</p>
        <div id="preview-container">
          <canvas id="preview-canvas" width="200" height="200"></canvas><br>
          <button type="button" onclick="rotateImage()">Girar Imagem</button>
        </div>
        <input type="hidden" id="imagem_base64" name="imagem_base64">
      </div>

      <!-- Deficiência -->
      <div class="form-group">
        <label class="required">Você possui alguma deficiência?</label>
        <div class="radio-group">
          <input type="radio" id="deficiencia_sim" name="possui_deficiencia" value="Sim" onclick="toggleDeficiencia(this.value)" required>
          <label for="deficiencia_sim">Sim</label>
          <input type="radio" id="deficiencia_nao" name="possui_deficiencia" value="Não" onclick="toggleDeficiencia(this.value)">
          <label for="deficiencia_nao">Não</label>
        </div>
      </div>

      <div id="campo-deficiencia" class="form-group">
        <label for="deficiencia">Qual deficiência?</label>
        <textarea id="deficiencia" name="deficiencia" placeholder="Descreva aqui..."></textarea>
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

      <button type="submit">Cadastrar</button>
    </form>
  </div>

  <!-- Scripts -->
  <script>
    function toggleDeficiencia(value) {
      document.getElementById('campo-deficiencia').style.display = value === 'Sim' ? 'block' : 'none';
    }

    function toggleSenha() {
      const input = document.getElementById('senha');
      const toggleBtn = input.nextElementSibling;
      input.type = input.type === 'password' ? 'text' : 'password';
      toggleBtn.textContent = input.type === 'password' ? '👁️' : '🔒';
    }

    let currentImage = null, rotation = 0;
    document.getElementById('imagem').addEventListener('change', function (event) {
      const file = event.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const img = new Image();
        img.onload = () => {
          currentImage = img; rotation = 0; drawImage();
          document.getElementById('preview-container').style.display = 'block';
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    });

    function drawImage() {
      const canvas = document.getElementById('preview-canvas');
      const ctx = canvas.getContext('2d');
      const size = canvas.width;
      ctx.clearRect(0,0,size,size);
      ctx.save();
      ctx.translate(size/2, size/2);
      ctx.rotate(rotation * Math.PI / 180);
      const scale = Math.min(size/currentImage.width, size/currentImage.height);
      const w = currentImage.width * scale;
      const h = currentImage.height * scale;
      ctx.drawImage(currentImage, -w/2, -h/2, w, h);
      ctx.restore();
      document.getElementById('imagem_base64').value = canvas.toDataURL('image/jpeg');
    }

    function rotateImage() {
      rotation = (rotation + 90) % 360;
      drawImage();
    }
  </script>
</body>
</html>