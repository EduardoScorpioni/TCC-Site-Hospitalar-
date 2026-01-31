<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'gerente') {
    header("Location: login1.php?erro=acesso");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="shortcut icon" href="ico/Med-Click_1.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Farmácia</title>
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

    label { 
        font-weight: 600; 
        margin-bottom: 8px; 
        display: block; 
        color: #444;
        font-size: 15px;
    }

    input, textarea {
        padding: 14px; 
        border: 1px solid #ddd; 
        border-radius: 8px; 
        font-size: 15px; 
        width: 100%;
        transition: all 0.3s ease;
        background-color: #f9fafc;
    }

    input:focus, textarea:focus {
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
    
    #preview-container img { 
        border-radius: 10px; 
        border: 3px solid #1a73e8; 
        margin-bottom: 15px; 
        max-width: 100%;
        height: auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .required::after {
        content: '*';
        color: #e53935;
        margin-left: 4px;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="img/MedClickDeLadinho.png" class="logo" alt="Logo">

    <h2>Cadastro de Farmácia</h2>

    <form action="salvar_farmacia.php" method="POST" enctype="multipart/form-data">

  <div class="form-group">
    <label for="nome" class="required">Nome da Farmácia</label>
    <input type="text" id="nome" name="nome" required>
  </div>

  <div class="form-group">
    <label for="endereco" class="required">Endereço</label>
    <input type="text" id="endereco" name="endereco" required>
  </div>

  <div class="grid-2">
    <div class="form-group">
      <label for="cidade" class="required">Cidade</label>
      <input type="text" id="cidade" name="cidade" required>
    </div>
    <div class="form-group">
      <label for="estado" class="required">Estado</label>
      <input type="text" id="estado" name="estado" maxlength="2" placeholder="UF" required>
      <p class="form-note">Exemplo: SP, RJ, MG...</p>
    </div>
  </div>

  <div class="grid-2">
    <div class="form-group">
      <label for="telefone" class="required">Telefone</label>
      <input type="text" id="telefone" name="telefone" required placeholder="(xx) xxxxx-xxxx">
    </div>
  </div>

  <!-- Campos de horário -->
  <div id="campos_horario" class="grid-2">
    <div class="form-group">
      <label for="abertura" class="required">Hora de Abertura</label>
      <input type="time" id="abertura" class="form-control" name="abertura" >
    </div>

    <div class="form-group">
      <label for="fechamento" class="required">Hora de Fechamento</label>
      <input type="time" id="fechamento" class="form-control" name="fechamento" >
    </div>
     <div class="form-group">
      <label>
        <input type="checkbox" id="farmacia_24h" name="farmacia_24h" value="1" onchange="toggleHorarios()"> Farmácia 24 horas
      </label>
    </div>
  </div>
     


  <div class="form-group">
    <label for="imagem">Imagem (logo ou fachada)</label>
    <input type="file" id="imagem" name="imagem" accept="image/*" onchange="previewImage(event)">
    
    <div id="preview-container">
      <img id="preview" alt="Pré-visualização da imagem">
    </div>
  </div>

  <button type="submit">Cadastrar Farmácia</button>
</form>

  </div>

  <script>
    function previewImage(event) {
      const file = event.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const preview = document.getElementById('preview');
        preview.src = e.target.result;
        document.getElementById('preview-container').style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
    <script>
function previewImage(event) {
  const file = event.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const preview = document.getElementById('preview');
    preview.src = e.target.result;
    document.getElementById('preview-container').style.display = 'block';
  };
  reader.readAsDataURL(file);
}

function toggleHorarios() {
  const is24h = document.getElementById('farmacia_24h').checked;
  document.getElementById('abertura').disabled = is24h;
  document.getElementById('fechamento').disabled = is24h;
}
</script>

  </script>
</body>
</html>
