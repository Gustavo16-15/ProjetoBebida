<?php 
$host = 'localhost';
$db = 'app_db';
$user ='root';
$pass = "";

try {
$pdo = new PDO ("mysql:host=$host;dbname=$db;", $user, $pass);


// $id_produto = 1;
$nome= $_POST ['nome'];
$valor = $_POST ['valor'];
$soma = $_POST ['soma'];

$sql= "INSERT INTO CARRINHO (nome,valor,soma)
    VALUES(:nome,:valor,:soma)";
    $stmt = $pdo-> prepare($sql);


    $stmt-> execute([
    ':nome'=> $nome,
    ':valor'=> $valor,
    ':soma'=> $soma
    ]);

     echo "<script>
        alert('Produto comprado com sucesso!');
        window.location.href = 'index.php';
      </script>";

}catch (PDOException $e){
 echo "Erro ao conectar".
 $e->getMessage();
}
?>