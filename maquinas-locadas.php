<html>
<head>
	<title>Relatórios</title>
	<meta charset="UTF-8">

</head>
<body>
<?php

// pagina de maquinas locadas sem limite

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
$dataAtual = date('Y-m-d');

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");

while($consult= mysqli_fetch_array($consulta)){
	$idEmpresa   = $consult['idEmpresa'];
	$areaUsuario = $consult['area'];
}
if($_SESSION['busca'] == 1){
	$resultadoML = $mysqli->query("SELECT * FROM maquinas WHERE fase LIKE '%".$_SESSION['status']."%' AND modelo LIKE '%".$_SESSION['modelo']."%' AND fabricante LIKE '%".$_SESSION['fabricante']."%' AND patrimonio LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa' AND fase = '3' AND excluido = '0' ORDER BY futuro DESC, idMaquina ASC");
}else{
	$resultadoML = $mysqli->query("SELECT * FROM maquinas WHERE idEmpresa = '$idEmpresa' AND fase = '3' AND excluido = '0' ORDER BY futuro DESC, idMaquina ASC");
}

if($_SESSION['busca'] == 1){
	$consultaTotalLocada = $mysqli->query("SELECT * FROM maquinas WHERE fase LIKE '%".$_SESSION['status']."%' AND modelo LIKE '%".$_SESSION['modelo']."%' AND fabricante LIKE '%".$_SESSION['fabricante']."%' AND patrimonio LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa' AND fase = '3'");
}else{
	$consultaTotalLocada = $mysqli->query("SELECT * FROM maquinas WHERE idEmpresa = '$idEmpresa' AND fase = '3' AND excluido = '0'");
}

$TotalLocada = mysqli_num_rows($consultaTotalLocada);

if($TotalLocada == 0){
	$TotalLocada = "";
}
?>
<span class="teste"></span>
<div class="box-main">
	<p class="title-relatorio"><span class="fas fa-map-marker-alt"></span> Máquinas Locadas <span class="total-box"><?php echo $TotalLocada;?></span></p>
</div>
	<?php
	$verifica = mysqli_num_rows($resultadoML);
	if($verifica == 0){ ?>
		<div class="txt-vazio">
			<?php
			if($_SESSION['busca'] == 1){ ?>
				<p style="text-align:center;">Nenhuma máquina na busca</p>
			<?php	}else{ ?>
				<p style="text-align:center;">Nenhuma máquina em locação</p>
			<?php } ?>
		</div>
		<?php
	}else{
		while($reg_cadastro= mysqli_fetch_array($resultadoML)){

			$codigoMaquinaLocada       = $reg_cadastro['idMaquina'];
			$modeloMaquinaLocada       = $reg_cadastro['modelo'];
			$fabricanteMaquinaLocada   = $reg_cadastro['fabricante'];
			$patrimonioMaquinaLocada   = $reg_cadastro['patrimonio'];
			$statusLocada              = $reg_cadastro['fase'];
			$dtLocacao                 = $reg_cadastro['dtLocacao'];
			$futuro					   = $reg_cadastro['futuro'];

			$nomeMaquinaLocada = $patrimonioMaquinaLocada." | ".$modeloMaquinaLocada." | ".$fabricanteMaquinaLocada;

			$dataAtualConverte = strtotime($dataAtual);
			$dtLocacaoConverte = strtotime($dtLocacao);
			// verifica a diferença em segundos entre as duas datas e divide pelo número de segundos que um dia possui
			if($dtLocacaoConverte != "0000-00-00"){
				$contadorLocacao = ($dataAtualConverte - $dtLocacaoConverte) /86400;
			}
			// ==========================================================================================================================================
			?>

			<div class="box-info-patio-maq">
				<div data-toggle="tooltip" data-placement="top" title="Patrimônio | Modelo | Fabricante" <?php if($futuro == '1'){ ?>class="box-title" style="background: #007CD4;" <?php }else { ?>class="box-title" <?php } ?> class="box-title">
					<p class="title-patio"><?php echo $nomeMaquinaLocada ?></p>
				</div>
				<div class="status-patio">
					<p class="title-patio">Máquina Locada</p>
				</div>
				<?php if($futuro == '1'){ ?>
					<p style="text-align:center;">Saída futura agendada</p>
				<?php }else { ?>
					<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalRetorno<?php echo $codigoMaquinaLocada ?>"><div class="btn-locada-left">
						<p class="locada-btn-left">Agendar retorno</p>
					</div></a>
					<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatioAgenda<?php echo $codigoMaquinaLocada ?>"><div class="btn-locada">
						<p class="locada-btn">Agendar saída</p>
					</div></a>
				<?php } ?>
				<div class="totalHoras">
				<?php if($contadorLocacao >= '0'){
						 if($dtLocacao != '0000-00-00'){ ?>
							 <p class="txtHoras">Está locada há <?php echo $contadorLocacao; if($contadorLocacao == "1"){ ?> dia <?php } else { ?> dias <?php } ?></p>
						 <?php } 
					  } ?>
				</div>
			</div>
			<!-- MODAL PARA EDITAR SAIDA DE MAQUINA -->
			<div class="modal fade" data-backdrop="static" id="myModalRetorno<?php echo $codigoMaquinaLocada ?>" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-mp">
						<div class="modal-header">
							<button type="button" class="close" onblur="comecaTudo();" data-dismiss="modal"></button>
							<h4 class="modal-title title-modal" style="text-align: center"><?php echo $nomeMaquinaLocada ?></h4>
						</div>
						<form name="agendar-retorno" method="post" action="agendar-retorno">
							<input type="hidden" value="<?php echo $codigoMaquinaLocada ?>" name="idMaquina">
							<input type="hidden" value="<?php echo $nomeMaquinaLocada ?>" name="nomeMaquina">
							<div style="text-align: center" class="modal-body modal-body-tr">
								<div class="control-form">
									<div class="control-form-modal-bottom control-form-modal">
										<p>Data de retorno:</p>
										<input autocomplete="off" class="text-form-tr" type="text" onkeypress="mascaraData( this, event ); return onlynumber();" placeholder="Data de retorno *"  name="dataRetorno" maxlength="10" required>
									</div>
									<div class="control-form-modal-bottom control-form-modal">
										<p>Hora do retorno:</p>
										<input autocomplete="off" class="text-form-tr" type="text" placeholder="Hora do retorno *" onkeypress="mascaraHora( this, event ); return onlynumber();" name="horaRetorno" maxlength="5" required>
									</div>
								</div>

							</div>
							<div class="modal-footer" style="margin-top: 140px;">
								<button type="submit" class="btn btn-primary">Enviar</button>
								<button type="button" onblur="comecaTudo();" data-dismiss="modal" class="btn btn-default btn-submit-locada">Voltar</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- MODAL PARA AGENDAR SAIDA DE MAQUINA -->
			<div class="modal fade" data-backdrop="static" id="myModalPatioAgenda<?php echo $codigoMaquinaLocada ?>" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-mp">
						<div class="modal-header">
							<button type="button" class="close" onblur="comecaTudo();" data-dismiss="modal"></button>
							<h4 class="modal-title title-modal" style="text-align: center"><?php echo $nomeMaquinaLocada ?></h4>
						</div>
						<form name="agendamento-futuro" method="post" action="agendamento-futuro">
							<input type="hidden" value="<?php echo $codigoMaquinaLocada ?>" name="idMaquina">
							<input type="hidden" value="<?php echo $nomeMaquinaLocada ?>" name="nomeMaquina">
							<div class="modal-body modal-body-tr">
								<div class="control-form" style="margin-top: 15px;">
									<div class="control-form-modal-top control-form-modal">
										<p>Nome da transportadora:</p>
										<select class="text-form-tr" name="nomeTransportadora" required>
											<?php
											$query = $mysqli->query("SELECT * FROM transportadora");

											$queryTransportadora = $mysqli->query("SELECT * FROM transportadora");

											$TotalTransportadora = mysqli_num_rows($queryTransportadora);

											if($TotalTransportadora == 0){ ?>
												<option value="">
													Sem transportadora
												</option>
											<?php }else{
												while($reg = $query->fetch_array()) { ?>
													<option value="<?php echo $reg['nomeTransportadora']; ?>">
														<?php echo $reg['nomeTransportadora']; ?>
													</option>
												<?php }} ?>
											</select>
										</div>
										<div class="control-form-modal-bottom control-form-modal">
											<p>Data de saída:</p>
											<input class="text-form-tr" type="text" onkeypress="mascaraData( this, event ); return onlynumber();" autocomplete="off" placeholder="Data de saída *" value="<?php echo $dataSaida ?>" name="dataSaida" maxlength="10" required>
										</div>
										<div class="control-form-modal-bottom control-form-modal">
											<p>Hora da saída:</p>
											<input class="text-form-tr" type="text" autocomplete="off" placeholder="Hora de saída *" onkeypress="mascaraHora( this, event ); return onlynumber();" value="<?php echo $horaSaida ?>" name="horaSaida" maxlength="5" required>
										</div>
										<div class="control-form-modal-bottom control-form-modal">
											<p>Observações:</p>
											<textarea style="resize: none; height: 80px;" class="text-form-tr" autocomplete="off" placeholder="Observações*" name="observacoes">Agendamento futuro</textarea>
										</div>
									</div>

								</div>
								<div class="modal-footer" style="margin-top: 320px;">
									<button type="submit" class="btn btn-primary">Enviar</button>
									<button type="button" class="btn btn-default btn-submit-locada" onblur="comecaTudo();" data-dismiss="modal">Voltar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<?php
				// ==========================================================================================================================================
			}
		}

		?>

</body>
</html>
