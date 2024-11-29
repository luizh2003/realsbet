<?php
include "../menu.php";
$sqlafiliado="select * from afiliados where id_afi='$_POST[id_afi]' ";
$queryafiliado=mysqli_query($conn, $sqlafiliado);
$afiliado=mysqli_fetch_assoc($queryafiliado);
?>

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-xl-12 col-md-12">
            <form id="myForm" action="script_cadastroaf.php" method="post">
            <?php
			if(empty($afiliado['id_afi'])) echo '<input type="hidden" name="cadafi" value="sim" />';	
			else echo '<input type="hidden" name="id_afi" value="'.$afiliado['id_afi'].'" />';
			?>
			<div class="ms-panel">
            	<div class="ms-panel-header ms-panel-custome">
               	 	<h6>Dados do Afiliado</h6>
                	<!--<a href="../doctor-list.html" class="ms-text-primary">Doctors List</a>-->
            	</div>
				
                <div class="ms-panel-body"> 
                	<?php if(!empty($afiliado['id_afi'])) echo '<h2 class="mb-4">Alteração de Dados do Afiliado</h2>';?>
                    <div class="form-row">	
                       <div class="col-md-6 mb-2">
                            <label for="validationCustom0001">Nome*</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nome" placeholder="Digite o nome do afiliado" 
                                value="<?php if(!empty($afiliado['id_afi'])) echo $afiliado['nome'];?>" required>
                            </div>
                        </div>  
   
                        <div class="col-md-6 mb-2">
                    		<label for="validationCustom0001">CPF*</label>
                    		<div class="input-group">
                    		<input type="text" class="form-control" name="cpf" placeholder="Digite o CPF" 
                            value="<?php if(!empty($afiliado['id_afi'])) echo $afiliado['cpf'];?>" required>
                    		</div>
                    	</div>

                    	<div class="col-md-6 mb-2">
                    		<label for="validationCustom0001">E-mail*</label>
                    		<div class="input-group">
                    		<input type="email" class="form-control" name="email" placeholder="Digite o e-mail"  
                            value="<?php if(!empty($afiliado['id_afi'])) echo $afiliado['email'];?>" required>
                    		</div>
                    	</div>
                        
                        <div class="col-md-6 mb-2">
                    		<label for="validationCustom0001">Data de Nascimento*</label>
                    		<div class="input-group">
                    		<input type="date" class="form-control" name="data_nasc" 
                            value="<?php if(!empty($afiliado['id_afi'])) echo $afiliado['data_nasc'];?>" required>
                    		</div>
                    	</div>
                        
                        <div class="col-md-6 mb-2">
                    		<label for="validationCustom0001">Telefone*</label>
                    		<div class="input-group">
                    		<input type="text" class="form-control" name="telefone" 
                            value="<?php if(!empty($afiliado['id_afi'])) echo $afiliado['telefone'];?>" placeholder="Digite seu número" required>
                    		</div>
                    	</div>
                                 
                        <div class="col-md-3 mb-3">
                            <label for="emailvaga">Estado</label>
                            <div class="input-group">
                                <select class="form-control" name="estado" required="required" id="estadoselect" onchange="cidadeschange()">
                                  <option value="" disabled selected>Selecionar</option>
                                  <?php
                                  $sqlestado="select * from estado ";
                                  $queryestado=mysqli_query($conn, $sqlestado);
                                  while($estado=mysqli_fetch_assoc($queryestado))
                                  {   
								  	$selected = ($estado['id'] == $afiliado['estado']) ? 'selected' : '';
                                  ?>
                                    <option value="<?php echo $estado['id'];?>" <?php echo $selected; ?>>
									<?php echo $estado['uf']." - ".$estado['nome'];?></option>
                                  <?php
                                  }
                                  ?>
                                </select>  
                            </div>
                        </div> 
                        
                        <div class="col-md-3 mb-3">
                            <label for="emailvaga">Cidade</label>
                            <div class="input-group">
                                <select class="selectCidades form-control" name="cidade" required="required" id="cidadeselect">
                                   <option value="" disabled selected>Selecione o Estado Primeiro</option>
                                    <?php
									// Carregar as cidades do estado selecionado
									if(!empty($afiliado['id_afi'])){
										$sqlcidades = "SELECT * FROM cidade WHERE uf = '$afiliado[estado]' ";
										$querycidades = mysqli_query($conn, $sqlcidades);
										while($cidade = mysqli_fetch_assoc($querycidades)) 
										{
											//Verifica se esta cidade está selecionada
											$selected = ($cidade['id'] == $afiliado['cidade']) ? 'selected' : '';
										?>
                                            <option value="<?php echo $cidade['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo $cidade['nome']; ?>
                                            </option>
										<?php
										}
									}
									?>
                                </select>
                            </div>
                        </div>     
                        
                        <div class="col-md-6 mb-6">
                    		<button class="btn btn-primary d-inline w-20" name="submitButton" type="submit">
							<?php if(empty($afiliado['id_afi'])) echo 'Cadastrar'; 
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
function cidadeschange() {
    var estadoId = $('#estadoselect').val(); // Pega o ID do estado selecionado
	var cidadeSelecionada = $('#cidadeselect').val(); // Pega a cidade selecionada (se houver)
    if (estadoId) {
        $.ajax({
            url: 'buscar_cidades.php', // Rota para buscar_cidades.php
            type: 'POST',
            data: { 
                estado_id: estadoId,
                cidade_selecionada: cidadeSelecionada // Inclui a cidade selecionada
            },
            success: function (data) {
                var cidades = JSON.parse(data); // Converte a resposta JSON para um objeto
                var options = '<option value="" disabled selected>Selecionar Cidade</option>';
                // Cria as opções para o select de cidades
                $.each(cidades, function (index, cidade) {
                    options += '<option value="' + cidade.id + '"' + 
                               (cidade.selected ? ' selected' : '') + '>' + 
                               cidade.nome + '</option>';
                });

                $('#cidadeselect').html(options); // Preenche o select de cidades
            },
            error: function () {
                alert("Erro ao buscar cidades. Tente novamente.");
            }
        });
    } else {
        $('#cidadeselect').html('<option value="" disabled>Selecione o Estado Primeiro</option>'); // Limpa as cidades
    }
}
</script>

<script>
document.getElementById('myForm').addEventListener('submit', function() {
	// Seleciona o formulário
    const form = document.getElementById('myForm'); // Substitua 'form' pelo seletor do seu formulário, se necessário.
    // Adiciona o evento de validação no envio do formulário
	const cpfValue = document.querySelector('input[name="cpf"]'); 
	if(cpfValue.value.length !== 14) {
		event.preventDefault(); // Impede o envio do formulário
		alert('Por favor, preencha o CPF corretamente antes de enviar.');
		return false;
	}

    var submitButton = document.querySelector('button[name="submitButton"]'); 
    submitButton.innerHTML = 'Enviando...';
	submitButton.style.pointerEvents = 'none'; // Desativa a interação com o botão
});

// Espera o DOM estar completamente carregado antes de aplicar a máscara
document.addEventListener('DOMContentLoaded', function() {
	// Aplica a máscara para cpf
	IMask(document.querySelector('input[name="cpf"]'), {
		mask: '000.000.000-00'
	});
	
	IMask(document.querySelector('input[name="telefone"]'), {
		mask: '(00)00000-0000'
	});	
});
</script>

<script>
    // Inicia o Select2
    $(document).ready(function() {
        $('.selectCidades').select2();
    });
</script>
