<?php
include "../menu.php";

if($_POST['caduser']=="sim" || !empty($_POST['id_user']) && empty($_POST['alt_status']))
{
	// Capturar e validar os campos com filter_input
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $senha_confirma = filter_input(INPUT_POST, 'senha_confirma', FILTER_SANITIZE_STRING);
	//Criptografando a senha antes de salvar no banco
	$senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
	
	//aqui verifica para nao deixar cadastrar mais de um email para o usuario no bd
	$sqluser = "SELECT * FROM usuarios WHERE email='$email' AND id_user!='$_POST[id_user]' AND valido='s' ";
	$queryuser=mysqli_query($conn, $sqluser);
	$countuser=mysqli_num_rows($queryuser);
	if($countuser>0)
	{
		echo '<script>
		// Usando SweetAlert para exibir uma mensagem de erro e redirecionar
		Swal.fire({
			title: "Atencao!",
			text: "Já existe um usuario com esse email.",
			icon: "error",
			confirmButtonText: "OK",
			allowOutsideClick: false
		}).then((result) => {
			// Verifica se o botão "OK" foi clicado
			if (result.isConfirmed) {
				// Redireciona para outra página
				window.location.replace("cadastro.php");
			}
		});
		</script>';
		exit();
	}
	
	//caso o usuario tente bular o js do front
	if($senha!=$senha_confirma) {
		echo '<script>
		// Usando SweetAlert para exibir uma mensagem de erro e redirecionar
		Swal.fire({
			title: "Atencao!",
			text: "a senha precisar ser iguais",
			icon: "error",
			confirmButtonText: "OK",
			allowOutsideClick: false
		}).then((result) => {
			// Verifica se o botão "OK" foi clicado
			if (result.isConfirmed) {
				// Redireciona para outra página
				window.location.replace("cadastro.php");
			}
		});
		</script>';
		exit();
	}
	
    // Verificar se todos os campos estão preenchidos caso o user tente bular no front
    if($nome && $email && $senha && $senha_confirma) 
	{
		//aqui faz insert e update baseado no que vem de la simplificado
		if(empty($_POST['id_user'])) $userbd="insert into usuarios set ";
		else $userbd="update usuarios set ";
		$userbd.="
		nome='$nome',
		email='$email', 
		senha='$senha_criptografada', 
		senha_confirma='$senha_criptografada' 
		";
		if(!empty($_POST['id_user'])) $userbd.=" where id_user='$_POST[id_user]' ";
		$queryuserbd=mysqli_query($conn, $userbd);
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
				window.location.replace("cadastro.php");
			}
		});
		</script>';
		exit();
	}
	header("Location:listar.php");
}

//aqui invalida ou valida o user
if($_POST['alt_status']=="sim")
{
	$validouserbd="update usuarios set valido='$_POST[valido]' where id_user='$_POST[id_user]' ";	
	$queryvalidouserbd=mysqli_query($conn, $validouserbd);
	header("Location:listar.php");
}
?>
