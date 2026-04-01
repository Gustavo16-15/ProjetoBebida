<?php
// Configurações de conexão (as mesmas do seu outro arquivo)
$host = "db"; $dbname = "app_db"; $user = "root"; $pass = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    // Recebe o ID do pedido que o JS quer verificar
    $id_pedido = $_GET['id'] ?? 0;

    $sql = "SELECT status_pagamento FROM pedidos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_pedido]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        echo json_encode([
            'pago' => ($resultado['status_pagamento'] === 'pago'),
            'status' => $resultado['status_pagamento']
        ]);
    } else {
        echo json_encode(['pago' => false, 'erro' => 'Pedido não encontrado']);
    }

} catch (Exception $e) {
    echo json_encode(['pago' => false, 'erro' => $e->getMessage()]);
}