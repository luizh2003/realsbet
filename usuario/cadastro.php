<?php
include "../menu.php";
$sqlusuario="select * from usuarios where id_user='$_POST[id_user]' ";
$queryusuario=mysqli_query($conn, $sqlusuario);
$usuario=mysqli_fetch_assoc($queryusuario);
?>

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-xl-12 col-md-12">
            <form id="myForm" action="script_cadastro.php" method="post">
            <?php
			if(empty($usuario['id_user'])) echo '<input type="hidden" name="caduser" value="sim" />';	
			else echo '<input type="hidden" name="id_user" value="'.$usuario['id_user'].'" />';
			?>
			<div class="ms-panel">
            	<div class="ms-panel-header ms-panel-custome">
               	 	<h6>Dados do Usuário</h6>
                	<!--<a href="../doctor-list.html" class="ms-text-primary">Doctors List</a>-->
            	</div>
				
                <div class="ms-panel-body"> 
                	<?php if(!empty($usuario['id_user'])) echo '<h2 class="mb-4">Alteração de Dados do Usuário</h2>';?>
                    <div class="form-row">	
                       <div class="col-md-6 mb-2">
                            <label for="validationCustom0001">Nome*</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nome" placeholder="Digite o nome do usuário" 
                                value="<?php if(!empty($usuario['id_user'])) echo $usuario['nome'];?>" required>
                            </div>
                        </div>  

                    	<div class="col-md-6 mb-2">
                    		<label for="validationCustom0001">E-mail*</label>
                    		<div class="input-group">
                    		<input type="email" class="form-control" name="email" placeholder="Digite o e-mail"  
                            value="<?php if(!empty($usuario['id_user'])) echo $usuario['email'];?>" required>
                    		</div>
                    	</div>
                        
                        <div class="col-md-6 mb-2">
                    		<label for="validationCustom0001">Senha*</label>
                    		<div class="input-group">
                    		<input type="password" class="form-control" name="senha" placeholder="Digite a senha" 
                            value="<?php if(!empty($usuario['id_user'])) echo 'noalter';?>"
                            required>
                    		</div>
                    	</div>
                        
                        <div class="col-md-6 mb-2">
                    		<label for="validationCustom0001">Confirme sua senha*</label>
                    		<div class="input-group">
                    		<input type="password" class="form-control" name="senha_confirma" placeholder="Confirme a senha"  
                            value="<?php if(!empty($usuario['id_user'])) echo 'noalter';?>"
                            required>
                    		</div>
                    	</div>
                        
                        <div class="col-md-6 mb-2">
                    		<button class="btn btn-primary d-inline w-20" name="submitButton" type="submit">
							<?php if(empty($usuario['id_user'])) echo 'Cadastrar'; 
							else echo 'Alterar';?></button>   
                        </div>
					</div>
				</div>
 			</div>
  			</form>
		</div>
	</div>
</div>

<script>
document.getElementById('myForm').addEventListener('submit', function() {
    var submitButton = document.querySelector('button[name="submitButton"]'); 
    submitButton.innerHTML = 'Enviando...';
	submitButton.style.pointerEvents = 'none'; // Desativa a interação com o botão
});
</script>
