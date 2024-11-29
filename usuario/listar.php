<?php 
include_once "../menu.php";
//lista todos os users
$sqlusuario="select * from usuarios where valido in('s','n') ";
if(!empty($_POST['filtroUsuario'])) $sqlusuario.="and id_user='$_POST[filtroUsuario]'";
$queryusuario=mysqli_query($conn, $sqlusuario);
?>

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="ms-panel">
                <div class="ms-panel-header ms-panel-custome">
                    <h6>Visualizar Usuário</h6>
                    <!--<a href="../doctor-list.html" class="ms-text-primary">Doctors List</a>-->
                </div>
    
                <div class="ms-panel-body">  
                	<?php
					if(mysqli_num_rows($queryusuario)>0)
					{
					?>
                    	<form action="" method="post">
                    	<div class="form-row">
                        	<div class="col-md-6 mb-2">
                                <label for="validationCustom0001">Filtragem de Usuário*</label>
                                <div class="input-group">
                                   	<select name="filtroUsuario" class="selectUser form-control" onchange="this.form.submit();">
                                    	<option value=""><?php if(empty($_POST['filtroUsuario'])) echo 'Selecione'; else echo 'Sem Filtro';?></option>
                                        <?php
										$sqlusuarioFiltro="select * from usuarios where valido in('s','n') ";
										$queryusuarioFiltro=mysqli_query($conn, $sqlusuarioFiltro);
										while($usuarioFiltro=mysqli_fetch_assoc($queryusuarioFiltro))
										{
										?>
                                        	<option value="<?php echo $usuarioFiltro['id_user'];?>" 
											<?php if($usuarioFiltro['id_user']==$_POST['filtroUsuario']) echo "selected";?>>
											<?php echo $usuarioFiltro['nome']." - ".$usuarioFiltro['email'];?></option>
                                        <?php
										}
										?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        </form>
                        
                        <div class="table-responsive">
                        <table class="table table-striped thead-primary">
                            <thead>
                                 <tr class="text-center">
                                 	<th scope="col">Nome</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Ações</th>  
                                 </tr>
                            </thead> 
                            <?php
                            foreach($queryusuario as $usuario)
                            {
                            ?>
                                <tr class="text-center">
                                    <td><?php echo $usuario['nome'];?></td>
                                    <td><?php echo $usuario['email'];?></td>
                                    <td align="center">
                                   	<select name="valido" class="form-control valido" style="width:130px;" id="<?php echo $usuario['id_user'];?>">
                                    	<option value="" disabled selected>Selecione</option>
                                    	<option value="s" <?php if($usuario['valido']=="s") echo "selected";?>>Ativo</option>
                                    	<option value="n" <?php if($usuario['valido']=="n") echo "selected";?>>Inativo</option>
                                    </select>
                                    </td>
                                    <td class="">
                                    <a href="#" id="<?php echo $usuario['id_user'];?>" class="editarUser"><i class="fas fa-pencil-alt ms-text-primary"></i></a>
                                    </td> 
                                </tr> 
                            <?php
                            }
                            ?>           
                        </table>
                 		</div>
                 	<?php
					}else echo "<div class='alert alert-danger text-center' role='alert'>Nenhum registro encontrado!</div>";
					?>
                </div>
     		</div>
     	</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$(document).on('click', '.editarUser', function(e) {
		e.preventDefault(); // Impede o comportamento padrão do link
		var id_user = $(this).attr("id");
		// Cria um formulário HTML dinamicamente
		var form = $('<form action="cadastro.php" method="post"></form>');
		// Adiciona os campos ao formulário
		form.append('<input type="hidden" name="id_user" value="' + id_user + '">');
		$('body').append(form);
		form.submit();
	});
	
	$(document).on('change', '.valido', function () {
		var statusSelecionado = $(this).val(); // Obtém o valor selecionado
    	var id_user = $(this).attr('id'); // ID do usuário vindo do atributo "id"
		// Cria e envia o formulário dinamicamente
		var form = $('<form action="script_cadastro.php" method="post"></form>');
		form.append('<input type="hidden" name="alt_status" value="sim">');
		form.append('<input type="hidden" name="id_user" value="' + id_user + '">');
		form.append('<input type="hidden" name="valido" value="' + statusSelecionado + '">');
		$('body').append(form);
		form.submit();
	});
});	
</script>

<script>
    // Inicia o Select2
    $(document).ready(function() {
        $('.selectUser').select2();
    });
</script>