<html>
<head>
	<title>Relatórios</title>
	<meta charset="UTF-8">
</head>
<?php

// pagina de maquinas retornando sem limite

error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
include_once ('conexao.php');
session_start();

if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))
{
	unset($_SESSION['usuario']);
	unset($_SESSION['senha']);
	header('location:login');
}


date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil
$dataAtual = date('d/m/Y');

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");

while($consult= mysqli_fetch_array($consulta)){
	$idEmpresa   = $consult['idEmpresa'];
	$areaUsuario = $consult['area'];
}

if($_SESSION['busca'] == 1){
	$resultadoPM = $mysqli->query("SELECT * FROM retornomaquinas WHERE nomeMaquina LIKE '%".$_SESSION['modelo']."%' AND nomeMaquina LIKE '%".$_SESSION['fabricante']."%' AND nomeMaquina LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa' ORDER BY dataRetorno, horaRetorno ASC");
}else{
	$resultadoPM = $mysqli->query("SELECT * FROM retornomaquinas WHERE idEmpresa = '$idEmpresa' ORDER BY dataRetorno, horaRetorno ASC");
}

if($_SESSION['busca'] == 1){
	$consultaTotalRetorno = $mysqli->query("SELECT * FROM retornomaquinas WHERE nomeMaquina LIKE '%".$_SESSION['modelo']."%' AND nomeMaquina LIKE '%".$_SESSION['fabricante']."%' AND nomeMaquina LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa' ORDER BY dataRetorno, horaRetorno ASC");
}else{
	$consultaTotalRetorno =  $mysqli->query("SELECT * FROM retornomaquinas WHERE idEmpresa = '$idEmpresa'");
}

$TotalRetorno = mysqli_num_rows($consultaTotalRetorno);

if($TotalRetorno == 0){
	$TotalRetorno = "";
}
?>
<div class="box-main">
	<p class="title-relatorio"><span class="fas fa-undo-alt icon-box"></span>Máquinas Retornando <span class="total-box"><?php echo $TotalRetorno; ?></span></p>
</div>
	<?php
	$verifica = mysqli_num_rows($resultadoPM);
	if($verifica == 0){ ?>
		<div class="txt-vazio">
			<?php	if($_SESSION['busca'] == 1){ ?>
				<p style="text-align:center;">Nenhuma máquina na busca</p>
			<?php	}else{ ?>
				<p style="text-align:center;">Nenhuma máquina retornando</p>
			<?php } ?>
		</div>
		<?php
	}else{

		while($reg_cadastro= mysqli_fetch_array($resultadoPM)){

			$codigoRetornoMaquinas    = $reg_cadastro['idRetornoMaquinas'];
			$idRetornoMaquina         = $reg_cadastro['idMaquina'];
			$nomeRetornoMaquina       = $reg_cadastro['nomeMaquina'];
			$dataRetorno              = $reg_cadastro['dataRetorno'];
			$horaRetorno              = $reg_cadastro['horaRetorno'];

			$dataRetorno = implode("/",array_reverse(explode("-",$dataRetorno)));

			// ==========================================================================================================================================
			?>

			<div class="box-info-patio">
				<div data-toggle="tooltip" data-placement="top" title="Patrimônio - Modelo - Fabricante"  class="box-title">
					<p class="title-retornando"><?php echo $nomeRetornoMaquina ?></p>
				</div>
				<div class="data-hora-retorno dtr">
					<div data-toggle="tooltip" data-placement="top" title="Data do retorno"  class="data-retorno">
						<p class="txt-data"><?php echo $dataRetorno ?> </p>
					</div>
					<div data-toggle="tooltip" data-placement="bottom" title="Hora do retorno"  class="hora-retorno">
						<p class="txt-data"><?php echo $horaRetorno ?> </p>
					</div>
				</div>
				<div class="box-btns-retorno">
					<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalConfirmaRetorno<?php echo $codigoRetornoMaquinas ?>"><div data-toggle="tooltip" data-placement="top" title="Confirmar retorno" class="btn-conf option-retorno">
						<span class="fas fa-check"></span>
					</div></a>
					<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalEditarRetorno<?php echo $codigoRetornoMaquinas ?>"><div data-toggle="tooltip" data-placement="top" title="Editar retorno" class="btn-edit option-retorno">
						<span class="fas fa-pencil-alt"></span>
					</div></a>
					<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalCancelaRetorno<?php echo $codigoRetornoMaquinas ?>"><div data-toggle="tooltip" data-placement="top" title="Cancelar retorno" class="btn-cancel option-retorno">
						<span class="fas fa-times"></span>
					</div></a>
				</div>
			</div>

			<!-- MODAL PARA CONFIRMAR RETORNO  -->
			<div class="modal fade" data-backdrop="static" data-backdrop="static" id="myModalConfirmaRetorno<?php echo $codigoRetornoMaquinas ?>" role="dialog">
				<div class="modal-dialog">
					<div class="modal-tr">
						<form name="confirma-retorno" method="post" action="confirma-retorno">
							<input type="hidden" value="<?php echo $codigoRetornoMaquinas ?>" name="codigoRetorno">
							<input type="hidden" value="<?php echo $idRetornoMaquina ?>" name="idRetornoMaquina">
							<input type="hidden" value="<?php echo $nomeRetornoMaquina ?>" name="nomeRetornoMaquina">
							<div class="notifit_confirm_bg_top" style=""></div>
							<input type="hidden" value="<?php echo $idMaquina ?>" name="idMaquina">
							<div class="notifit_confirm_top" style=""><div class="notifit_confirm_message">Confirmar o retorno?</div><button type="submit" class="notifit_confirm_accept">Sim</button><button  onblur="comecaTudo();" data-dismiss="modal" class="notifit_confirm_cancel">Não</button></div>
						</form>
					</div>
				</div>
			</div>

			<!-- MODAL PARA EDITAR SAIDA DE MAQUINA -->
			<div class="modal fade" data-backdrop="static" data-backdrop="static" id="myModalEditarRetorno<?php echo $codigoRetornoMaquinas ?>" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-mr">
						<div class="modal-header">
							<button type="button" class="close" onblur="comecaTudo();" data-dismiss="modal"></button>
							<h4 class="modal-title title-modal" style="text-align: center"><?php echo $nomeRetornoMaquina ?></h4>
						</div>
						<form name="editar-retorno" method="post" action="editar-retorno">
							<input type="hidden" value="<?php echo $codigoRetornoMaquinas ?>" name="codigoMaquina" required>
							<div class="modal-body modal-body-tr">
								<div class="control-form-modal-top control-form-modal">
									<p>Data de retorno:</p>
									<input class="text-form-tr" type="text" onkeypress="mascaraData( this, event ); return onlynumber();" autocomplete="off" placeholder="Data de retorno *" value="<?php echo $dataRetorno ?>" name="dataRetorno" maxlength="10" required>
								</div>
								<div class="control-form-modal-bottom control-form-modal">
									<p>Hora do retorno:</p>
									<input class="text-form-tr" type="text" autocomplete="off" placeholder="Hora de retorno *" onkeypress="mascaraHora( this, event ); return onlynumber();" value="<?php echo $horaRetorno ?>" name="horaRetorno" maxlength="5" required>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Enviar</button>
								<button type="button" class="btn btn-default btn-submit-retornando" onblur="comecaTudo();" data-dismiss="modal">Voltar</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- MODAL PARA CANCELAR RETORNO -->
			<div class="modal fade" data-backdrop="static" data-backdrop="static" id="myModalCancelaRetorno<?php echo $codigoRetornoMaquinas ?>" role="dialog">
				<div class="modal-dialog">
					<div class="modal-tr">
						<form name="cancelar-retorno" method="post" action="cancelar-retorno">
							<input type="hidden" value="<?php echo $codigoRetornoMaquinas ?>" name="codigoRetorno">
							<input type="hidden" value="<?php echo $idRetornoMaquina ?>" name="idRetornoMaquina">
							<div class="notifit_confirm_bg_top" style=""></div>
							<input type="hidden" value="<?php echo $idMaquina ?>" name="idMaquina">
							<div class="notifit_confirm_top" style=""><div class="notifit_confirm_message">Confirmar o cancelamento do retorno?</div><button type="submit" class="notifit_confirm_accept">Sim</button><button  onblur="comecaTudo();" data-dismiss="modal" class="notifit_confirm_cancel">Não</button></div>
						</form>
					</div>
				</div>
			</div>

			<?php
			// ==========================================================================================================================================
		}
	}

	?>

</head>
</html>
