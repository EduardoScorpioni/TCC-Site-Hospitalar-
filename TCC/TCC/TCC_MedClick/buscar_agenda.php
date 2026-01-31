<?php
session_start();
require_once 'conexao.php'; // Inclua seu arquivo de conexão com o banco de dados
$id_medico = $_SESSION['id_medico']; // Ajuste conforme o nome da variável de sessão do médico logado

// Obtém a data por parâmetro (GET) ou usa a data atual
$data_pesquisa = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

// Consulta a agenda do médico para a data especificada, marcando ocupado e mostrando paciente
$sql = "SELECT a.hora AS horario, 
               a.disponivel, 
               c.id AS id_consulta,
               p.nome AS paciente
        FROM agenda a
        LEFT JOIN consultas c 
            ON c.id_medico = a.medico_id 
           AND c.data_consulta = a.data 
           AND c.hora_consulta = a.hora
           AND c.status = 'Agendada'
        LEFT JOIN pacientes p 
            ON c.id_paciente = p.id
        WHERE a.medico_id = ? AND a.data = ?
        ORDER BY a.hora";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $id_medico, $data_pesquisa);
$stmt->execute();
$result = $stmt->get_result();
?>

<table>
  <thead>
    <tr>
      <th>Horário</th>
      <th>Status</th>
      <th>Paciente</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['horario']); ?></td>
        <td><?= $row['id_consulta'] ? 'Ocupado' : 'Livre'; ?></td>
        <td><?= $row['paciente'] ? htmlspecialchars($row['paciente']) : '-'; ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
