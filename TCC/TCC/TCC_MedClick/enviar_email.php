<?php
// Arquivo: enviar_email.php

// Incluir a biblioteca PHPMailer (certifique-se de ter instalado via Composer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ajuste o caminho conforme necessário

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $assunto = $_POST['assunto'];
    $mensagem = $_POST['mensagem'];
    
    // Validação básica
    if (empty($nome) || empty($email) || empty($assunto) || empty($mensagem)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, insira um e-mail válido.']);
        exit;
    }
    
    try {
        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        
        // Configurações do servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.mail.outlook.com'; // Ou seu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'eduscorpioni@outlook.com'; // Seu e-mail
        $mail->Password = 'Edu155151'; // Sua senha
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Remetente e destinatário
        $mail->setFrom('eduscorpioni@outlook.com', 'MedClick');
        $mail->addAddress('contato@medclick.com.br'); // E-mail da empresa
        $mail->addReplyTo($email, $nome);
        
        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Contato via Site - ' . $assunto;
        
        $mail->Body = "
        <h2>Novo contato via site MedClick</h2>
        <p><strong>Nome:</strong> $nome</p>
        <p><strong>E-mail:</strong> $email</p>
        <p><strong>Telefone:</strong> " . ($telefone ? $telefone : 'Não informado') . "</p>
        <p><strong>Assunto:</strong> $assunto</p>
        <p><strong>Mensagem:</strong></p>
        <p>" . nl2br($mensagem) . "</p>
        ";
        
        $mail->AltBody = "
        Novo contato via site MedClick
        Nome: $nome
        E-mail: $email
        Telefone: " . ($telefone ? $telefone : 'Não informado') . "
        Assunto: $assunto
        Mensagem: $mensagem
        ";
        
        // Enviar e-mail
        if ($mail->send()) {
            echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao enviar mensagem. Tente novamente.']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Erro ao enviar mensagem: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>