<?php
include "../menu.php";

if($_POST['cadafi']=="sim" || !empty($_POST['id_afi']) && empty($_POST['alt_status']))
{
	// Capturar e validar os campos com filter_input
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_EMAIL);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $data_nasc = filter_input(INPUT_POST, 'data_nasc', FILTER_SANITIZE_STRING);
	$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
	$estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
	$cidade = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING);
	
	//aqui verifica para nao deixar cadastrar mais de um email para o usuario no bd
	$sqluser = "SELECT * FROM afiliados WHERE (cpf='$cpf' OR email='$email') AND id_afi!='$_POST[id_afi]' AND valido='s' ";
	$queryuser=mysqli_query($conn, $sqluser);
	$countuser=mysqli_num_rows($queryuser);
	if($countuser>0)
	{
		echo '<script>
		// Usando SweetAlert para exibir uma mensagem de erro e redirecionar
		Swal.fire({
			title: "Atencao!",
			text: "Já existe um afiliado com esse cpf ou esse email.",
			icon: "error",
			confirmButtonText: "OK",
			allowOutsideClick: false
		}).then((result) => {
			// Verifica se o botão "OK" foi clicado
			if (result.isConfirmed) {
				// Redireciona para outra página
				window.location.replace("cadastroaf.php");
			}
		});
		</script>';
		exit();
	}
    // Verificar se todos os campos estão preenchidos caso o user tente bular no front
    if($nome && $cpf && $email && $data_nasc && $telefone && $estado && $cidade) 
	{
		//aqui faz insert e update baseado no que vem de la simplificado
		if(empty($_POST['id_afi'])) $afiliadobd="insert into afiliados set ";
		else $afiliadobd="update afiliados set ";
		$afiliadobd.="
		nome='$nome',
		cpf='$cpf',
		email='$email',
		data_nasc='$data_nasc',
		telefone='$telefone',
		estado='$estado',
		cidade='$cidade'
		";
		if(!empty($_POST['id_afi'])) $afiliadobd.=" where id_afi='$_POST[id_afi]' ";
		$queryuserbd=mysqli_query($conn, $afiliadobd);
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
				window.location.replace("cadastroaf.php");
			}
		});
		</script>';
		exit();
	}
	header("Location:listaraf.php");
}

//aqui invalida ou valida o user
if($_POST['alt_status']=="sim")
{
	$validoafiliadobd="update afiliados set valido='$_POST[valido]' where id_afi='$_POST[id_afi]' ";	
	$queryvalidoafiliadobd=mysqli_query($conn, $validoafiliadobd);
	header("Location:listaraf.php");
}
?>
