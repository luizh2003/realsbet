<?php 
//print_r($_POST);
include "../conexao.php";

$sqlcomissao="select * from comissao_afiliado where id_afi='$_POST[id_afi]' ";
$querycomissao=mysqli_query($conn, $sqlcomissao);
$comissao=mysqli_fetch_assoc($querycomissao);
?>

<!-- Body Content Wrapper -->
<form id="myForm" action="script_cadastro_comissao.php" method="post" onsubmit="validaCamposComissao()">
<input type="hidden" name="id_afi" value="<?php echo $_POST['id_afi'];?>" />
<?php
if(empty($_POST['id_comissao'])) echo '<input type="hidden" name="cadcomissao" value="sim" />';	
else echo '<input type="hidden" name="id_comissao" value="'.$_POST['id_comissao'].'" />';
?>
<div class="ms-panel">
    <div class="ms-panel-header ms-panel-custome">
        <h6>Dados da Comissão</h6>
        <!--<a href="../doctor-list.html" class="ms-text-primary">Doctors List</a>-->
    </div>
    
    <div class="ms-panel-body"> 
        <?php if(!empty($_POST['id_comissao'])) echo '<h2 class="mb-4">Alteração de Dados da Comissão</h2>';?>
        <div class="form-row">
           	<div class="col-md-6 mb-2">
                <label for="validationCustom0001">Valor da Comissão*</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="val_comiss" name="valor_comissao" value="" placeholder="Digite o valor da comissão" required>
                </div>
            </div>
            
           	<div class="col-md-6 mb-2">
                <label for="validationCustom0001">Data*</label>
                <div class="input-group">
                    <input type="date" class="form-control" name="data_comissao" value="" required>
                </div>
            </div>
          
            <div class="col-md-12 mb-12">
                 <button class="btn btn-primary d-inline w-20" name="submitButton" type="submit">Adicionar Comissão</button>
            </div> 
        </div>
    </div>  
</div>   
</form>

<?php
/*aqui e a listagem no modal de comissoes desse afiliado*/
$sqlcomissaoList="select * from comissao_afiliado 
inner join afiliados on comissao_afiliado.id_afi=afiliados.id_afi
where comissao_afiliado.id_afi='$_POST[id_afi]' and comissao_afiliado.valido='s' ";
$querycomissaoList=mysqli_query($conn, $sqlcomissaoList);
//print $sqlaulaList;
if(mysqli_num_rows($querycomissaoList)>0)
{
?>
    <div class="table-responsive">
        <table class="table thead-primary">
            <thead>
                <tr>   
                  <th scope="col">Nome do Afiliado</th>
                  <th scope="col" class="text-center">Valor</th>
                  <th scope="col" class="text-center">Data</th>
                  <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <?php
			$totalComissao = 0; // Inicializa o total como zero
            foreach($querycomissaoList as $comissaoList)
            {
				$totalComissao += $comissaoList['valor_comissao']; // Soma o valor ao total
            ?>
                <tr>
                    <td><?php echo $comissaoList['nome'];?></td>
                    <td class="text-center"><?php echo number_format($comissaoList['valor_comissao'], 2, ',', '.'); ?></td>
                    <td class="text-center"><?php echo date('d/m/Y', strtotime($comissaoList['data_comissao']));?></td>
                    <td class="text-center">
                    <a href="#" id="<?php echo $comissaoList['id_comissao'];?>" data-user-id="<?php echo $_POST['id_afi'];?>" class="ExcluirComissao"><i class="far fa-trash-alt ms-text-danger"></i></a>
                    </td>
                </tr>
            <?php
            }
			$corClasse = $totalComissao < 0 ? 'red' : 'green'; // Define a classe com base no valor
            ?>
            <tr>
            	<td><strong>Total de Comissão:</strong></td>
            	<td align="center" style="color: <?php echo $corClasse; ?>;"><?php echo "R$ ".number_format($totalComissao, 2, ',', '.');?></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
<?php
}
else echo "<div class='alert alert-danger text-center' role='alert'>Nenhum registro encontrado!</div>";
?>

<script>
function validaCamposComissao() {
	const valorComissao = document.getElementById('val_comiss').value;
	// Verifica se o valor da comissão é igual a 0
	if (parseFloat(valorComissao.replace('.', '').replace(',', '.')) === 0) {
		alert('O valor da comissão não pode ser zero!');
		event.preventDefault(); // Impede o envio do formulário
		return false;
	}
		
	var submitButton = document.querySelector('button[name="submitButton"]'); 
	// Modifica o texto e desativa o botão se o formulário for válido 
	submitButton.innerHTML = 'Enviando...';
	submitButton.style.pointerEvents = 'none'; // Desativa a interação com o botão   	
}
</script>
           
<script>
$(document).ready(function(){
	$(document).on('click', '.ExcluirComissao', function(e) {
		e.preventDefault(); // Impede o comportamento padrão do link
		var id_comissao = $(this).attr("id");
		var id_afi = $(this).data("user-id");
		// Cria um formulário HTML dinamicamente
		var form = $('<form action="script_cadastro_comissao.php" method="post"></form>');
		// Adiciona os campos ao formulário
		form.append('<input type="hidden" name="excluir_comissao" value="sim">');
		form.append('<input type="hidden" name="id_comissao" value="' + id_comissao + '">');
		form.append('<input type="hidden" name="id_afi" value="' + id_afi + '">');
		$('body').append(form);
		form.submit();
	});
	
	// Seleciona todos os inputs relevantes
	const inputs = document.querySelectorAll('input[name="valor_comissao"]');
	inputs.forEach(function(input) {
		IMask(input, {
			mask: 'num',
			blocks: {
				num: {
					mask: Number,
					thousandsSeparator: '.',
					radix: ',',
					scale: 2,
					signed: true, // Permite números positivos e negativos
					padFractionalZeros: true
				}
			}
		});
	});
});
</script>