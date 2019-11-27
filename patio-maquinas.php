<html>
<head>
	<title>Relatórios</title>
	<meta charset="UTF-8">
</head>
<body>
	<?php
	// pagina de maquinas no patio sem limite

	// Recuperando informações

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
	$teste = $_SESSION['teste'];
	if($_SESSION['busca'] == 1){
		if($areaUsuario == 'Manutenção'){
			$resultado = $mysqli->query("SELECT * FROM patiomaquinas WHERE status LIKE '%".$_SESSION['status']."%' AND nomeMaquina LIKE '%".$_SESSION['modelo']."%' AND nomeMaquina LIKE '%".$_SESSION['fabricante']."%' AND nomeMaquina LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa' ORDER BY disponivel ASC, idMaquina ASC");
		}else{
			$resultado = $mysqli->query("SELECT * FROM patiomaquinas WHERE status LIKE '%".$_SESSION['status']."%' AND nomeMaquina LIKE '%".$_SESSION['modelo']."%' AND nomeMaquina LIKE '%".$_SESSION['fabricante']."%' AND nomeMaquina LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa' ORDER BY disponivel DESC, idMaquina ASC");
		}
	}else{
		if($areaUsuario == 'Manutenção'){
			$resultado = $mysqli->query("SELECT * FROM patiomaquinas WHERE idEmpresa = '$idEmpresa' ORDER BY disponivel ASC, idMaquina ASC");
		}else{
			$resultado = $mysqli->query("SELECT * FROM patiomaquinas WHERE idEmpresa = '$idEmpresa' ORDER BY disponivel DESC, idMaquina ASC");
		}
	}
	if($_SESSION['busca'] == 1){
		$consultaTotalPatio = $mysqli->query("SELECT * FROM patiomaquinas WHERE status LIKE '%".$_SESSION['status']."%' AND nomeMaquina LIKE '%".$_SESSION['modelo']."%' AND nomeMaquina LIKE '%".$_SESSION['fabricante']."%' AND nomeMaquina LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa'");
	}else{
		$consultaTotalPatio = $mysqli->query("SELECT * FROM patiomaquinas WHERE idEmpresa = '$idEmpresa'");
	}

	$TotalPatio = mysqli_num_rows($consultaTotalPatio);

	$contadorPatioDisponivel = $mysqli->query("SELECT * FROM patiomaquinas WHERE disponivel = '1' AND idEmpresa = '$idEmpresa'");

	$countDisponivel = mysqli_num_rows($contadorPatioDisponivel);

	$contadorPatioManutencao = $mysqli->query("SELECT * FROM patiomaquinas WHERE disponivel = '0' AND idEmpresa = '$idEmpresa'");

	$countManutencao = mysqli_num_rows($contadorPatioManutencao);

	if($TotalPatio == 0){
		$TotalPatio = "";
	}
	?>
	<div class="box-main" style="margin-bottom: 20px;">
		<p class="title-relatorio"><span class="fas fa-map-marker-alt"></span> Máquinas no pátio<span class="total-box"><?php echo $TotalPatio ?></span></p>
		<div class="nrTotalSep">
			<p class="totalMaqSep totalCom">Disponível: <?php echo $countDisponivel ?></p>
			<p class="totalMaqSep totalMan">Manutenção: <?php echo $countManutencao ?></p>
		</div>
	</div>

		<?php
		$verifica = mysqli_num_rows($resultado);
		if($verifica == 0){ ?>
			<div class="txt-vazio">
				<?php	if($_SESSION['busca'] == 1){ ?>
					<p style="text-align:center;">Nenhuma máquina na busca</p>
				<?php	}else{ ?>
					<p style="text-align:center;">Nenhuma máquina no pátio</p>
				<?php } ?>
			</div>
			<?php
		}else{

			while($reg_cadastro= mysqli_fetch_array($resultado)){

				$codigoPatioMaquina   = $reg_cadastro['idPatioMaquinas'];
				$idPatioMaquina       = $reg_cadastro['idMaquina'];
				$nomePatioMaquina     = $reg_cadastro['nomeMaquina'];
				$statusPatio          = $reg_cadastro['status'];
				$statusComercial      = $reg_cadastro['status2'];
				$disponivel			  = $reg_cadastro['disponivel'];
				$dtRetorno			  = $reg_cadastro['dtRetorno'];
				$dtDisponivel		  = $reg_cadastro['dtDisponivel'];

				$resultadoStatus = $mysqli->query("SELECT * FROM status WHERE idStatus = '$statusPatio' AND idEmpresa = '$idEmpresa'");


				while($reg_cadastro= mysqli_fetch_array($resultadoStatus)){
					$NomeStatusPatio           = $reg_cadastro['nome'];
					$AreaStatusPatio           = $reg_cadastro['area'];
				}

				$resultadoStatus1 = $mysqli->query("SELECT * FROM status WHERE idStatus = '$statusComercial' AND idEmpresa = '$idEmpresa'");


				while($reg_cadastro= mysqli_fetch_array($resultadoStatus1)){
					$NomeStatusPatioComercial = $reg_cadastro['nome'];
				}

				$consultaSaida = $mysqli->query("SELECT * FROM saidamaquina WHERE idEmpresa = '$idEmpresa' AND idMaquina = '$idPatioMaquina'");
				$verificaSaida = mysqli_num_rows($consultaSaida);

				// converte as datas para o formato timestamp
				$dataAtualConverte = strtotime($dataAtual); 
				$dtRetornoConverte = strtotime($dtRetorno);
				// verifica a diferença em segundos entre as duas datas e divide pelo número de segundos que um dia possui
				if($dtRetorno != "0000-00-00"){
					$contadorRetorno = ($dataAtualConverte - $dtRetornoConverte) /86400;
				}

				// converte as datas para o formato timestamp
				$dtDisponivelConverte = strtotime($dtDisponivel);
				// verifica a diferença em segundos entre as duas datas e divide pelo número de segundos que um dia possui
				if($dtDisponivelConverte != "0000-00-00"){
					$contadorDisponivel = ($dataAtualConverte - $dtDisponivelConverte) /86400;
				}
				// ==========================================================================================================================================
				?>

				<div id="frases" class="box-info-patio-maq">
					<?php if($disponivel == "1"){?>
							<div data-toggle="tooltip" style="background: #22B14C;" data-placement="top" title="Patrimônio | Modelo | Fabricante"  class="box-title">
							<?php }else{?>
								<div data-toggle="tooltip" style="background: #F28E00;" data-placement="top" title="Patrimônio | Modelo | Fabricante"  class="box-title">
								<?php } ?>
							<p class="title-patio"><?php echo $nomePatioMaquina ?></p>
						</div>
						<?php if($statusComercial != ""){ ?>
							<div class="status-patio-2">
								<p class="title-patio"><?php echo $NomeStatusPatio ?></p>
							</div>
							<div class="status-patio-2">
								<p class="title-patio-2"><?php echo $NomeStatusPatioComercial ?></p>
							</div>
						<?php } else { ?>
							<div class="status-patio">
								<p class="title-patio"><?php echo $NomeStatusPatio ?></p>
							</div>
						<?php } if($disponivel != "1"){	?>
							<?php if($verificaSaida == "1"){ ?>
								<div class="btn-patio-r">
									<p class="patio-btn-ag">Agendado</p>
								</div>

								<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatio<?php echo $codigoPatioMaquina ?>"><div class="btn-patio-l">
									<p class="patio-btn">Definir status</p>
								</div></a>

							<?php } elseif($areaUsuario == "Manutenção"){ ?>
								<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatio<?php echo $codigoPatioMaquina ?>"><div class="btn-patio">
									<p class="patio-btn">Definir status</p>
								</div></a>

							<?php } else { ?>


								<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatioAgenda<?php echo $codigoPatioMaquina ?>"><div class="btn-patio-r">
									<p class="patio-btn">Agendar</p>
								</div></a>

								<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatio<?php echo $codigoPatioMaquina ?>"><div class="btn-patio-l">
									<p class="patio-btn">Definir status</p>
								</div></a>
							<?php } }else { ?>

								<?php if($verificaSaida == "1"){ ?>
									<div class="btn-patio-r">
										<p class="patio-btn-ag">Agendado</p>
									</div>

									<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatio<?php echo $codigoPatioMaquina ?>"><div class="btn-patio-l">
										<p class="patio-btn">Definir status</p>
									</div></a>

								<?php } elseif($areaUsuario == "Manutenção"){ ?>
									<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatio<?php echo $codigoPatioMaquina ?>"><div class="btn-patio">
										<p class="patio-btn">Definir status</p>
									</div></a>

								<?php } else { ?>

									<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatioAgenda<?php echo $codigoPatioMaquina ?>"><div class="btn-patio-r">
										<p class="patio-btn">Agendar</p>
									</div></a>

									<a href="" onclick="paraTudo();" data-toggle="modal" data-target="#myModalPatio<?php echo $codigoPatioMaquina ?>"><div class="btn-patio-l">
										<p class="patio-btn">Definir status</p>
									</div></a>


								<?php } } ?>
								<div class="totalHoras">
								<?php 
									if($disponivel == '1'){ 
										if($contadorDisponivel >= '0'){
											if($dtDisponivel != '0000-00-00'){ ?>
												<p class="txtHoras">Está disponível há <?php echo $contadorDisponivel; if($contadorDisponivel == "1"){ ?> dia <?php } else { ?> dias <?php } ?></p>
											<?php } 
										} 
									}else{ 
										if($dtRetorno != '0000-00-00'){
											if($contadorRetorno >= '0'){ ?>
											<p class="txtHoras">Está em manutenção há <?php echo $contadorRetorno; if($contadorRetorno == "1"){ ?> dia <?php } else { ?> dias <?php } ?></p>
											<?php } 
										} 
									} ?>
								</div>
							</div>
						</div>
						<!-- MODAL PARA EDITAR SAIDA DE MAQUINA -->
						<div class="modal fade" data-backdrop="static" id="myModalPatio<?php echo $codigoPatioMaquina ?>" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content modal-mps">
									<div class="modal-header">
										<button type="button" class="close" onclick="comecaTudo();" data-dismiss="modal"></button>
										<h4 class="modal-title title-modal" style="text-align: center"><?php echo $nomePatioMaquina ?></h4>
									</div>
									<form name="definir-status" method="post" action="definir-status">
										<input type="hidden" value="<?php echo $codigoPatioMaquina ?>" name="codigoPatioMaquinaDefinir">
										<input type="hidden" value="<?php echo $idPatioMaquina ?>" name="idPatioMaquina">
										<input type="hidden" value="<?php echo $nomePatioMaquina ?>" name="nomePatioMaquina">
										<div class="modal-body modal-body-tr">
											<div class="control-form">
												<p>Status:</p>
												<select class="text-form-select st" name="statusDefinir" required>
													<option value="">
														Selecione um status *
													</option>
													<?php
													if($areaUsuario == "Manutenção"){
														$query = $mysqli->query("SELECT * FROM status WHERE area = '$areaUsuario'");
													}else if($areaUsuario == "Comercial"){ ?>
														<option value="Sem status">
															Sem status
														</option>
														<?php
														$query = $mysqli->query("SELECT * FROM status WHERE area = '$areaUsuario'");
													}else{
														$query = $mysqli->query("SELECT * FROM status");
													}

													while($reg = $query->fetch_array()) {
														if($reg['idStatus'] == "1" || $reg['idStatus'] == "3" || $reg['idStatus'] == "5" ) {

														}else{?>

															<option value="<?php echo $reg['idStatus']; ?>">
																<?php echo $reg['nome']; ?>
															</option>
														<?php } }?>
													</select>
												</div>
											</div>
											<div class="modal-footer" style="margin-top: 50px;">
												<button type="submit" class="btn btn-primary">Enviar</button>
												<button type="button" class="btn btn-default" onclick="comecaTudo();" data-dismiss="modal">Voltar</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<!-- MODAL PARA AGENDAR SAIDA DE MAQUINA -->
							<div class="modal fade" data-backdrop="static" id="myModalPatioAgenda<?php echo $codigoPatioMaquina ?>" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content modal-mp">
										<div class="modal-header">
											<button type="button" class="close" onclick="comecaTudo();" data-dismiss="modal"></button>
											<h4 class="modal-title title-modal" style="text-align: center"><?php echo $nomePatioMaquina ?></h4>
										</div>
										<form name="agendar-saida" method="post" action="agendar-saida">
											<input type="hidden" value="<?php echo $idPatioMaquina ?>" name="idPatioMaquinaDefinir">
											<input type="hidden" value="<?php echo $codigoPatioMaquina ?>" name="codigoPatioMaquinaDefinir">
											<input type="hidden" value="<?php echo $nomePatioMaquina ?>" name="nomePatioMaquinaDefinir">
											<input type="hidden" value="<?php echo $statusPatio ?>" name="statusHist">
											<div class="modal-body modal-body-tr">
												<div class="control-form" style="margin-top: 15px;">
													<div class="control-form-modal-top control-form-modal">
														<p>Nome da transportadora:</p>
														<select class="text-form-tr" name="nomeTransportadora" required>
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
														<input class="text-form-tr" type="text" onkeypress="mascaraData( this, event ); return onlynumber();" autocomplete="off" placeholder="Data de saída *" value="<?php echo $dataSaida ?>" name="dataSaida" maxlength="10" required>
													</div>
													<div class="control-form-modal-bottom control-form-modal">
														<p>Hora da saída:</p>
														<input class="text-form-tr" type="text" autocomplete="off" placeholder="Hora de saída *" onkeypress="mascaraHora( this, event ); return onlynumber();" value="<?php echo $horaSaida ?>" name="horaSaida" maxlength="5" required>
													</div>
													<div class="control-form-modal-bottom control-form-modal">
														<p>Observações:</p>
														<textarea style="resize: none; height: 80px;" class="text-form-tr" autocomplete="off" placeholder="Observações" value="<?php echo $horaSaida ?>" name="observacoes" />
														</div>
													</div>

												</div>
												<div class="modal-footer" style="margin-top: 320px;">
													<button type="submit" class="btn btn-primary">Enviar</button>
													<button type="button" class="btn btn-default" onclick="comecaTudo();" data-dismiss="modal">Voltar</button>
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
