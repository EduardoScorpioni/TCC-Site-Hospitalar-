<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username  = "root";
$password  = "";     // Ajuste conforme sua senha
$dbname    = "medclick";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Coletar dados do formulário
$nome            = $_POST['nome'];
$cpf             = $_POST['cpf'];
$email           = $_POST['email'];
$endereco        = $_POST['endereco'];
$data_nascimento = $_POST['data_nascimento'];
$sexo            = $_POST['sexo'];
$telefone        = $_POST['telefone'];
$possui_deficiencia = $_POST['possui_deficiencia'];
$deficiencia     = isset($_POST['deficiencia']) ? $_POST['deficiencia'] : NULL;
$senha           = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// Tratar upload de imagem
if (isset($_FILES["Imagem"]) && $_FILES["Imagem"]["error"] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . "/img/";
    // Garante que a pasta exista
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $filename = basename($_FILES["Imagem"]["name"]);
    $targetPath = $uploadDir . $filename;
    if (move_uploaded_file($_FILES["Imagem"]["tmp_name"], $targetPath)) {
        // Salva o caminho relativo para o banco
        $imagem = "img/" . $filename;
        echo "Imagem enviada com sucesso.<br>";
    } else {
        echo "Erro ao mover o arquivo de imagem.<br>";
        $imagem = "";
    }
} else {
    // Se nenhum arquivo foi enviado, deixa em vazio
    $imagem = "";
}

// Verifica se já existe CPF ou E‐mail
$verifica_sql  = "SELECT id FROM pacientes WHERE cpf = ? OR email = ?";
$verifica_stmt = $conn->prepare($verifica_sql);
if (!$verifica_stmt) {
    die("Erro na preparação de verificação: " . $conn->error);
}
$verifica_stmt->bind_param("ss", $cpf, $email);
$verifica_stmt->execute();
$verifica_result = $verifica_stmt->get_result();
if ($verifica_result->num_rows > 0) {
    echo "Erro: CPF ou E-mail já cadastrados!";
    $verifica_stmt->close();
    $conn->close();
    exit;
}
$verifica_stmt->close();

// Agora, INSERÇÃO com 11 placeholders
$sql = "INSERT INTO pacientes 
    (nome, cpf, email, endereco, data_nascimento, sexo, telefone, possui_deficiencia, deficiencia, senha, imagem)
 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Falha ao preparar a query de inserção: " . $conn->error);
}

// bind_param com 11 “s”
$stmt->bind_param(
    "sssssssssss",
    $nome,
    $cpf,
    $email,
    $endereco,
    $data_nascimento,
    $sexo,
    $telefone,
    $possui_deficiencia,
    $deficiencia,
    $senha,
    $imagem
);

if ($stmt->execute()) {
    // Redireciona para login em caso de sucesso
    header("Location: login1.php?sucesso=1");
    exit;
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
