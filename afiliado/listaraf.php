<?php 
include_once "../menu.php";
//lista todos os users
$sqlafiliado="select * from afiliados where valido in('s','n') ";
if(!empty($_POST['filtroAfiliado'])) $sqlafiliado.="and id_afi='$_POST[filtroAfiliado]'";
$queryafiliado=mysqli_query($conn, $sqlafiliado);
?>

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="ms-panel">
                <div class="ms-panel-header ms-panel-custome">
                    <h6>Visualizar Afiliado</h6>
                    <!--<a href="../doctor-list.html" class="ms-text-primary">Doctors List</a>-->
                </div>
    
                <div class="ms-panel-body">  
                	<?php
					if(mysqli_num_rows($queryafiliado)>0)
					{
					?>
                    	<form action="" method="post">
                    	<div class="form-row">
                        	<div class="col-md-6 mb-2">
                                <label for="validationCustom0001">Filtragem de Afiliado*</label>
                                <div class="input-group">
                                   	<select name="filtroAfiliado" class="selectAfi form-control" onchange="this.form.submit();">
                                    	<option value=""><?php if(empty($_POST['filtroAfiliado'])) echo 'Selecione'; else echo 'Sem Filtro';?></option>
                                        <?php
										$sqlafiliadoFiltro="select * from afiliados where valido in('s','n') ";
										$queryafiliadoFiltro=mysqli_query($conn, $sqlafiliadoFiltro);
										while($afiliadosFiltro=mysqli_fetch_assoc($queryafiliadoFiltro))
										{
										?>
                                        	<option value="<?php echo $afiliadosFiltro['id_afi'];?>" 
											<?php if($afiliadosFiltro['id_afi']==$_POST['filtroAfiliado']) echo "selected";?>>
											<?php echo $afiliadosFiltro['nome']." - ".$afiliadosFiltro['cpf'];?></option>
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
                                    <th scope="col">CPF</th>
                                    <th scope="col">Data de nascimento</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Celular</th>
                                    <th scope="col">Cidade</th>  
                                    <th scope="col">Status</th> 
                                    <th scope="col">Comissão</th> 
                                    <th scope="col">Ações</th>  
                                 </tr>
                            </thead> 
                            <?php
                            foreach($queryafiliado as $afiliado)
                            {
								//pegar o nome da uf e da cidade
								$sqlcidades="SELECT estado.uf AS nomeuf, cidade.nome AS nomecidade
								FROM estado
								inner join cidade on estado.id=cidade.uf
								where estado.id='$afiliado[estado]' and cidade.id='$afiliado[cidade]' ";
								$querycidades=mysqli_query($conn, $sqlcidades);
								$cidades=mysqli_fetch_assoc($querycidades);
                            ?>
                                <tr class="text-center">
                                    <td><?php echo $afiliado['nome'];?></td>
                                    <td><?php echo $afiliado['cpf'];?></td>
                                    <td><?php echo date('d/m/Y', strtotime($afiliado['data_nasc']));?></td>
                                    <td><?php echo $afiliado['email'];?></td>
                                    <td><?php echo $afiliado['telefone'];?></td>
                                    <td><?php echo $cidades['nomeuf']." - ".$cidades['nomecidade'];?></td>
                                    <td>
                                    <select name="valido" class="form-control valido" style="width:130px;" id="<?php echo $afiliado['id_afi'];?>">
                                    	<option value="" disabled selected>Selecione</option>
                                    	<option value="s" <?php if($afiliado['valido']=="s") echo "selected";?>>Ativo</option>
                                    	<option value="n" <?php if($afiliado['valido']=="n") echo "selected";?>>Inativo</option>
                                    </select>
                                    </td>
                                    <td>
                                    <?php if($afiliado['valido']=='s') { ?>
                                        <a href="#" id="<?php echo $afiliado['id_afi'];?>" class="comissao">
                                        <i class="fas fa-dollar-sign" style="color: green;"></i>
                                        </a>
                                    <?php } else echo '<i class="fas fa-exclamation-circle text-danger"></i>'; ?>
                                    </td>
                                    <td>
                                    <a href="#" id="<?php echo $afiliado['id_afi'];?>" class="editarAfi"><i class="fas fa-pencil-alt ms-text-primary"></i></a>
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

<!-- Modal específico para cada comissao -->
<div class="modal fade" id="Modalcomissao" tabindex="-1" role="dialog">
<div class="modal-dialog modal-lg">
<div class="modal-content">
    <div class="modal-header ms-modal-header-radius-0">
        <h4 class="modal-title text-white">Assinatura Digital</h4>
        <button type="button" class="close text-white" data-dismiss="modal">x</button>
    </div>
    
    <div class="modal-body p-0 text-left">
        <div class="col-xl-12 col-md-12">
            <div class="ms-panel ms-panel-bshadow-none">
                <div class="ms-panel-body">
                    <span id="visucomissao"></span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<script>
//Quando o documento estiver pronto, execute a função
$(document).ready(function() {
    // Adiciona o evento de clique
    $(document).on('click', '.comissao', function() {
		var id_afi;
		<?php if(empty($_GET['modalcomissao'])) { ?>
			id_afi = $(this).attr('id');
		<?php } else { ?>
			id_afi = '<?php echo $_GET['modalcomissao'];?>';
		<?php } ?>

      	var dados = {
        	id_afi: id_afi
    	};
		$.post('modal_comissao.php', dados, function(retorna) {
        	// Carregar o conteúdo para o usuário
       	 	$("#visucomissao").html(retorna);
        	$('#Modalcomissao').modal('show'); 
    	});
    });
	
	// Atualiza o modal quando ele é fechado
    $('#Modalcomissao').on('hidden.bs.modal', function () {
	   // Você pode fazer um reload ou atualizar o conteúdo aqui
	   window.location.reload(); // Recarrega a página
	   // Se você quiser atualizar os dados, pode refazer a chamada AJAX aqui ou simplesmente deixar vazio
    });
	
	const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('modalcomissao') === '<?php echo $_GET['modalcomissao'];?>') {
       $('.comissao').click();
	   	// Remove o parâmetro da URL e o sinal de interrogação
		urlParams.delete('modalcomissao');
		const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
		window.history.replaceState({}, '', newUrl);
	}
});
</script>

<script>
$(document).ready(function(){
	$(document).on('click', '.editarAfi', function(e) {
		e.preventDefault(); // Impede o comportamento padrão do link
		var id_afi = $(this).attr("id");
		// Cria um formulário HTML dinamicamente
		var form = $('<form action="cadastroaf.php" method="post"></form>');
		// Adiciona os campos ao formulário
		form.append('<input type="hidden" name="id_afi" value="' + id_afi + '">');
		$('body').append(form);
		form.submit();
	});
	
	$(document).on('change', '.valido', function () {
		var statusSelecionado = $(this).val(); // Obtém o valor selecionado
    	var id_afi = $(this).attr('id'); // ID do usuário vindo do atributo "id"
		// Cria e envia o formulário dinamicamente
		var form = $('<form action="script_cadastroaf.php" method="post"></form>');
		form.append('<input type="hidden" name="alt_status" value="sim">');
		form.append('<input type="hidden" name="id_afi" value="' + id_afi + '">');
		form.append('<input type="hidden" name="valido" value="' + statusSelecionado + '">');
		$('body').append(form);
		form.submit();
	});
});	
</script>

<script>
    // Inicia o Select2
    $(document).ready(function() {
        $('.selectAfi').select2();
    });
</script>