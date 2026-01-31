<?php
session_start();

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "medclick";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Pega os dados do formulário
$crm   = isset($_POST['crm']) ? trim($_POST['crm']) : '';
$senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

if ($crm === '' || $senha === '') {
    header("Location: login_medico.php?erro=campos");
    exit;
}

$sql  = "SELECT * FROM medicos WHERE crm = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $crm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $medico = $result->fetch_assoc();

    // Verifica senha
    if (password_verify($senha, $medico['senha'])) {
        $_SESSION['tipo']          = 'medico';
        $_SESSION['id']            = $medico['id'];
        $_SESSION['medico_id']     = $medico['id']; // ✅ Linha essencial para processa_liberar_horarios.php
        $_SESSION['usuario']       = $medico['nome'];
        $_SESSION['crm']           = $medico['crm'];
        $_SESSION['especialidade'] = $medico['especialidade_id']; // corrigido: o campo do banco normalmente é "especialidade_id"
        $_SESSION['imagem']        = $medico['imagem'];

        header("Location: pagina_medico.php?login=ok");
        exit;
    } else {
        header("Location: login_medico.php?erro=senha");
        exit;
    }
} else {
    header("Location: login_medico.php?erro=usuario");
    exit;
}
?>
