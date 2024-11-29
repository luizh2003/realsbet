<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "realsbet";
$conn = mysqli_connect($servername, $username, $password, $dbname);

$estadoId = $_POST['estado_id'];
$cidadeSelecionada = isset($_POST['cidade_selecionada']) ? $_POST['cidade_selecionada'] : null; // Cidade selecionada

// Consulta as cidades do estado
$sqlcidades = "SELECT * FROM cidade WHERE uf = '$estadoId'";
$querycidades = mysqli_query($conn, $sqlcidades);
$cidades = [];
while($cidade = mysqli_fetch_assoc($querycidades)) {
    $cidades[] = [
        'id' => $cidade['id'], 
        'nome' => $cidade['nome'], 
        'selected' => ($cidade['id'] == $cidadeSelecionada) // Verifica se esta cidade está selecionada
    ];
}
// Retorna o JSON com as cidades
echo json_encode($cidades);
?>
