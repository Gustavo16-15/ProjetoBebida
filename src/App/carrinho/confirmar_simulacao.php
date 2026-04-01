<?php
// Configurações de conexão (use as mesmas do seu projeto)
$host = "db"; 
$dbname = "app_db"; 
$user = "root"; 
$pass = "root";

header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Recebe o ID do pedido enviado pelo JavaScript (setTimeout)
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    if (!isset($dados['id_pedido'])) {
        throw new Exception("ID do pedido não fornecido para simulação.");
    }

    $id = $dados['id_pedido'];

    // 2. Executa a "Mágica": Muda o status de 'pendente' para 'pago'
    // IMPORTANTE: O nome da coluna deve ser o mesmo que você criou no banco
    $sql = "UPDATE pedidos SET status_pagamento = 'pago' WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // 3. Responde ao JavaScript que a simulação deu certo
    echo json_encode([
        'sucesso' => true, 
        'mensagem' => 'Simulação de pagamento concluída no banco!'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false, 
        'erro' => $e->getMessage()
    ]);
}
?>