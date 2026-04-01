<?php
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

    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    if (!$dados) {
        throw new Exception("Dados inválidos recebidos.");
    }

    $itens = json_encode($dados['carrinho'], JSON_UNESCAPED_UNICODE);

    $endereco = filter_var(
        $dados['endereco'] ?? '',
        FILTER_SANITIZE_SPECIAL_CHARS
    );

    $obs = filter_var(
        $dados['observacoes'] ?? '',
        FILTER_SANITIZE_SPECIAL_CHARS
    );

    $pagamento = filter_var(
        $dados['pagamento'] ?? '',
        FILTER_SANITIZE_SPECIAL_CHARS
    );

    $gorjeta = 0;
    if (!empty($dados['gorjeta'])) {
        $gorjeta = (float) trim(
            str_replace(['R$', '.', ','], ['', '', '.'], $dados['gorjeta'])
        );
    }

    $total = (float) trim(
        str_replace(['R$', '.', ','], ['', '', '.'], $dados['total'])
    );

    $sql = "INSERT INTO pedidos
            (itens, endereco, observacoes, gorjeta, pagamento, total)
            VALUES
            (:itens, :endereco, :obs, :gorjeta, :pagamento, :total)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':itens' => $itens,
        ':endereco' => $endereco,
        ':obs' => $obs,
        ':gorjeta' => $gorjeta,
        ':pagamento' => $pagamento,
        ':total' => $total
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Pedido salvo com sucesso!',
        'id_pedido'=> $pdo -> lastInsertId() // linha nova
    ]);

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}
?>