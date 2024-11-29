<?php
include "../menu.php";

if($_POST['cadcomissao']=="sim" && empty($_POST['excluir_comissao']))
{
	// Função para remover formatação
	function removerFormatacao($valor) {
		// Remove pontos e substitui vírgula por ponto
		$valor = str_replace('.', '', $valor); // Remove os pontos
		$valor = str_replace(',', '.', $valor); // Troca a vírgula por ponto
		return (float)$valor; // Retorna como número
	}
	// Capturar e validar os campos com filter_input
    $valor_comissao = removerFormatacao(filter_input(INPUT_POST, 'valor_comissao', FILTER_SANITIZE_STRING));
    $data_comissao = filter_input(INPUT_POST, 'data_comissao', FILTER_SANITIZE_EMAIL);	
    // Verificar se todos os campos estão preenchidos caso o user tente bular no front
    if($valor_comissao && $data_comissao) 
	{
		//aqui faz insert e update baseado no que vem de la simplificado
		$comissbd="insert into comissao_afiliado set
		id_afi='$_POST[id_afi]',
		data_comissao='$data_comissao',
		valor_comissao='$valor_comissao'
		";
		$querycomissbd=mysqli_query($conn, $comissbd);
	}
	else{
		echo '<script>
		// Usando SweetAlert para exibir uma mensagem de erro e redirecionar
		Swal.fire({
			title: "Atencao!",
			text: "Confira os campos",
			icon: "error",
			confirmButtonText: "OK",
			allowOutsideClick: false
		}).then((result) => {
			// Verifica se o botão "OK" foi clicado
			if (result.isConfirmed) {
				// Redireciona para outra página
				window.location.replace("listaraf.php?modalcomissao='.$_POST['id_afi'].'");
			}
		});
		</script>';
		exit();
	}
	header("Location:listaraf.php?modalcomissao=".$_POST['id_afi']."");
}

//aqui invalida ou valida o user
if($_POST['excluir_comissao']=="sim")
{
	$validocomissaobd="update comissao_afiliado set valido='n' where id_comissao='$_POST[id_comissao]' ";	
	$querycomissaobd=mysqli_query($conn, $validocomissaobd);
	header("Location:listaraf.php?modalcomissao=".$_POST['id_afi']."");
}
?>
