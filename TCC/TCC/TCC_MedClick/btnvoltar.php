<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Incluir Font Awesome se não estiver incluído -->
     
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  .btn-voltar-minimal {
    position: fixed;
    top: 100px;
    left: 20px;
    z-index: 1000;
    display: inline-flex;
    align-items: center;
    padding: 10px 16px;
    background: rgba(11, 0, 51, 0.9);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
    cursor: pointer;
    backdrop-filter: blur(10px);
  }
  
  .btn-voltar-minimal:hover {
    background: rgba(26, 115, 232, 0.9);
    transform: translateX(-3px);
  }
  
  .btn-voltar-minimal i {
    margin-right: 6px;
    font-size: 14px;
  }
  
  /* Responsividade */
  @media (max-width: 768px) {
    .btn-voltar-minimal {
      top: 90px;
      left: 15px;
      padding: 8px 12px;
      font-size: 12px;
    }
  }
</style>
</head>
<body>
<a href="painel_gerente.php" class="btn-voltar-minimal">
  <i class="fas fa-arrow-left"></i>
  Voltar
</a>


</body>
</html>
