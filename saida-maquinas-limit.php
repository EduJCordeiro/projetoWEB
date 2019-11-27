<html>
<head>
	<title>Relatórios</title>
	<meta charset="UTF-8">
	<script>
	$(".modal-backdrop").click(function(){
		$("div").removeClass("modal-backdrop");
		comeca();
	});
	</script>
</head>
<?php
// pagina de saida de maquinas com limite

error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
include_once ('conexao.php');
session_start();

if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))
{
	unset($_SESSION['usuario']);
	unset($_SESSION['senha']);
	header('location:index.html');
}


date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");

while($consult= mysqli_fetch_array($consulta)){
	$idEmpresa   = $consult['idEmpresa'];
	$areaUsuario = $consult['area'];
}
if($_SESSION['busca'] == 1){
	$resultado =  $mysqli->query("SELECT * FROM saidamaquina WHERE status LIKE '%".$_SESSION['status']."%' AND nomeMaquina LIKE '%".$_SESSION['modelo']."%' AND nomeMaquina LIKE '%".$_SESSION['fabricante']."%' AND nomeMaquina LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa' ORDER BY dataSaida, horaSaida ASC LIMIT 0,5");
}else{
	$resultado = $mysqli->query("SELECT * FROM saidamaquina WHERE idEmpresa = '$idEmpresa' ORDER BY dataSaida, horaSaida ASC LIMIT 0,5");
}

if($_SESSION['busca'] == 1){
	$consultaTotalSaida =  $mysqli->query("SELECT * FROM saidamaquina WHERE status LIKE '%".$_SESSION['status']."%' AND nomeMaquina LIKE '%".$_SESSION['modelo']."%' AND nomeMaquina LIKE '%".$_SESSION['fabricante']."%' AND nomeMaquina LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa' ORDER BY dataSaida, horaSaida ASC");
}else{
	$consultaTotalSaida =  $mysqli->query("SELECT * FROM saidamaquina WHERE idEmpresa = '$idEmpresa'");
}

$TotalSaida = mysqli_num_rows($consultaTotalSaida);

if($TotalSaida == 0){
	$TotalSaida = "";
}

$_SESSION['TotalSaida'] = $TotalSaida;

$verifica = mysqli_num_rows($resultado); ?>
<div class="box-main">
	<p class="title-relatorio"><span class="fas fa-sign-out-alt icon-box"></span>Saída de máquinas <span class="total-box"><?php echo $TotalSaida  ?></span></p>
</div>
	<?php
	if($verifica == 0){ ?>
		<div class="txt-vazio">
			<?php	if($_SESSION['busca'] == 1){ ?>
				<p style="text-align:center;">Nenhuma máquina na busca</p>
			<?php	}else{ ?>
				<p style="text-align:center;">Nenhuma saída de máquina</p>
			<?php } ?>
		</div>
		<?php
	}else{
		while($reg_cadastro= mysqli_fetch_array($resultado)){

			$codigoMaquina     = $reg_cadastro['idSaidaMaquina'];
			$idMaquina         = $reg_cadastro['idMaquina'];
			$nomeMaquina       = $reg_cadastro['nomeMaquina'];
			$dataSaida         = $reg_cadastro['dataSaida'];
			$dataSaidaExplode  = implode("/",array_reverse(explode("-",$dataSaida)));
			$horaSaida         = $reg_cadastro['horaSaida'];
			$status            = $reg_cadastro['status'];
			$observacoes       = $reg_cadastro['observacoes'];
			$transportadora    = $reg_cadastro['transportadora'];
			$futuro            = $reg_cadastro['futuro'];
			$statusHist        = $reg_cadastro['statusHist'];
			$statusHist2       = $reg_cadastro['statusHist2'];

			$resultadoStatus = $mysqli->query("SELECT * FROM status WHERE idStatus = '$status' AND idEmpresa = '$idEmpresa'");
			while($reg_cadastro= mysqli_fetch_array($resultadoStatus)){
				$NomeStatus           = $reg_cadastro['nome'];
			}
			$resultadoTr = $mysqli->query("SELECT * FROM transportadora WHERE nomeTransportadora = '$transportadora' AND idEmpresa = '$idEmpresa'");
			while($reg_cadastro= mysqli_fetch_array($resultadoTr)){
				$telefone           = $reg_cadastro['telefone'];
			}

			$dataAtual = date('Y-m-d');
			$horaAtual = date('H:i');

			$horaAtual30 =  date("H:i",strtotime("$horaAtual + 30 minutes"));   
  
			$alerta = "0";

			if($dataAtual > $dataSaida){
				$alerta = "1";  
			}else{
				if($dataAtual >= $dataSaida){
					if($horaSaida <= $horaAtual30 && $horaSaida >= $horaAtual){  
						$alerta = "2";  
					} else if($horaAtual > $horaSaida) {
						$alerta = "1";  
					}
				}
			}


			if($observacoes == NULL){
				$observacoes = "Sem observações";
			}
			// ==========================================================================================================================================
			?>
			<div class="box-info">

				<div data-toggle="tooltip" data-placement="top" title="Patrimônio | Modelo | Fabricante" <?php if($futuro == '1'){ ?>class="box-title-futuro"  <?php }else { ?>class="box-title" <?php } ?> >
					<p class="title"><?php  echo $nomeMaquina ?></p>
				</div>
				<div class="box-top">

					<div class="data-hora">
						<div data-toggle="tooltip" data-placement="top" title="Data da saída" class="data-saida">
							<p class="txt-data-hora"><?php echo $dataSaidaExplode ?></p>
						</div>
						<?php if($alerta == 2){ ?>
							<div data-toggle="tooltip" style="border:1px solid #BF0000; color:#BF0000;" data-placement="bottom" title="Hora da saída" class="hora-saida ">
								<p class="txt-data-hora"><?php echo $horaSaida ?></p>
							</div>
						<?php } else if($alerta == 1) {?>
							<div data-toggle="tooltip" style="background:#BF0000; color:#FFF; border:1px solid " data-placement="bottom" title="Hora da saída" class="hora-saida pulse-data">
								<p class="txt-data-hora"><?php echo $horaSaida ?></p>
							</div>
						<?php } else {?>
							<div data-toggle="tooltip" data-placement="bottom" title="Hora da saída" class="hora-saida">
								<p class="txt-data-hora"><?php echo $horaSaida ?></p>
							</div>
						<?php }?>
					</div>
					<div class="box-mid">
						<div class="status-box">
							<p class="txt-data-hora"><?php echo $transportadora." | ".$telefone;?></p>
						</div>
						<div class="transportadora-box-scroll">
							<div class="transportadora-box">
								<p class="txt-data-hora"><?php echo nl2br($observacoes) ?></p>
							</div>
						</div>
					</div>

					<div class="box-btns">
						<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalConfirma<?php echo $codigoMaquina ?>"><div data-toggle="tooltip" data-placement="top" title="Confirmar saída" class="btn-conf option">
							<span class="fas fa-check"></span>
						</div></a>
						<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalEditar<?php echo $codigoMaquina ?>"><div data-toggle="tooltip" data-placement="top" title="Editar saída" class="btn-edit option">
							<span class="fas fa-pencil-alt"></span>
						</div></a>
						<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalCancela<?php echo $codigoMaquina ?>"><div data-toggle="tooltip" data-placement="top" title="Cancelar saída" class="btn-cancel option">
							<span class="fas fa-times"></span>
						</div></a>
					</div>


				</div>

			</div>

			<!-- MODAL PARA EDITAR SAIDA DE MAQUINA -->
			<div class="modal fade" data-backdrop="static" id="myModalEditar<?php echo $codigoMaquina ?>" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-tr">
						<div class="modal-header">
							<button type="button" class="close" onblur="comecaTudo();" data-dismiss="modal"></button>
							<h4 class="modal-title title-modal" style="text-align: center"><?php echo $nomeMaquina ?></h4>
						</div>
						<form name="editar-saida" method="post" action="editar-saida">
							<input type="hidden" value="<?php echo $codigoMaquina ?>" name="codigoMaquinaEditar" required>
							<div class="modal-body modal-body-tr">
								<input type="hidden"name="idTransportadoraEditar">
								<div class="control-form-modal-top control-form-modal">
									<p>Nome da transportadora:</p>
									<select class="text-form-tr" name="nomeTransportadoraEditar" required>
										<option value="<?php echo $transportadora ?>">
											<?php echo $transportadora ?>
										</option>
										<?php
										$query = $mysqli->query("SELECT * FROM transportadora");

										while($reg = $query->fetch_array()) { ?>
											<option value="<?php echo $reg['nomeTransportadora']; ?>">
												<?php echo $reg['nomeTransportadora']; ?>
											</option>
										<?php } ?>
									</select>
								</div>
								<div class="control-form-modal-bottom control-form-modal">
									<p>Data de saída:</p>
									<input class="text-form-tr" type="text" onkeypress="mascaraData( this, event ); return onlynumber();" autocomplete="off" placeholder="Data de saída *" value="<?php echo $dataSaidaExplode ?>" name="dataSaidaEditar"  maxlength="10" required>
								</div>
								<div class="control-form-modal-bottom control-form-modal">
									<p>Hora da saída:</p>
									<input class="text-form-tr" type="text" autocomplete="off" placeholder="Hora de saída *" onkeypress="mascaraHora( this, event ); return onlynumber();" value="<?php echo $horaSaida ?>" name="horaSaidaEditar"  maxlength="5" required>
								</div>
								<div class="control-form-modal-bottom control-form-modal">
									<p>Observações:</p>
									<textarea style="resize: none; height: 80px;" class="text-form-tr" autocomplete="off" placeholder="Observações" name="observacoesEditar" ><?php echo $observacoes ?></textarea>
								</div>
							</div>
							<div class="modal-footer" style="margin-top: 70px;">
								<button type="submit" class="btn btn-primary">Editar</button>
								<button type="button" class="btn btn-default btn-submit-saida" onblur="comecaTudo();" data-dismiss="modal">Voltar</button>
							</div>
						</form>
					</div>
				</div>
			</div>

					<!-- MODAL PARA CANCELAR SAIDA -->
					<div class="modal fade" data-backdrop="static" id="myModalCancela<?php echo $codigoMaquina ?>" role="dialog">
						<div class="modal-dialog">
							<div class="modal-tr">
								<form name="excluir-saida" method="post" action="excluir-saida">
									<input type="hidden" value="<?php echo $codigoMaquina ?>" name="codigoMaquinaEditar" required>
									<input type="hidden" value="<?php echo $idMaquina ?>" name="idMaquinaEditar" required>
									<input type="hidden" value="<?php echo $nomeMaquina ?>" name="nomeMaquina" required>
									<input type="hidden" value="<?php echo $statusHist ?>" name="statusHist" required>
									<div class="notifit_confirm_bg_top" style=""></div>
									<input type="hidden" value="<?php echo $idMaquina ?>" name="idMaquinaConfirma" required>
									<div class="notifit_confirm_top" style=""><div class="notifit_confirm_message">Cancelar a saída da máquina?</div><button type="submit" class="notifit_confirm_accept">Sim</button><button onblur="comecaTudo();" data-dismiss="modal" class="notifit_confirm_cancel">Não</button></div>
								</form>
							</div>
						</div>
					</div>

					<!-- MODAL PARA CONFIRMAR SAIDA -->
					<div class="modal fade" data-backdrop="static" id="myModalConfirma<?php echo $codigoMaquina ?>" role="dialog">
						<div class="modal-dialog">
							<div class="modal-tr">
								<form name="confirmar-saida" method="post" action="confirma-saida">
									<input type="hidden" value="<?php echo $codigoMaquina ?>" name="codigoMaquinaConfirma" required>
									<div class="notifit_confirm_bg_top" style=""></div>
									<input type="hidden" value="<?php echo $idMaquina ?>" name="idMaquinaConfirma" required>
									<input type="hidden" value="<?php echo $nomeMaquina ?>" name="nomeMaquina" required>
									<input type="hidden" value="<?php echo $statusHist ?>" name="statusHist" required>
									<div class="notifit_confirm_top" style=""><div class="notifit_confirm_message">Confirmar a saída da máquina?</div><button type="submit" class="notifit_confirm_accept">Sim</button><button onblur="comecaTudo();" data-dismiss="modal" class="notifit_confirm_cancel">Não</button></div>
								</form>
							</div>
						</div>
					</div>
					<?php
					// ==========================================================================================================================================
				}
			}
		if($TotalSaida  > '5'){
			?>
<div id="limitSM"><button id="limitSM" type="submit" onclick="limitSM();" class="btn-carrega">Carregar todas as maquinas</button></div>
<?php } ?>
	</head>
	</html>
