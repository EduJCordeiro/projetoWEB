<?php

/* ==============================================
== Página de cadastro e visualização das Máquinas
============================================== */

error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
include_once ('conexao.php');

session_start();

if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)){
	unset($_SESSION['usuario']);
	unset($_SESSION['senha']);
	header('location:index.html');
}

date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");

$consultaMaq =  $mysqli->query("SELECT * FROM maquinas WHERE excluido = '0' AND idEmpresa = '$idEmpresa'");

$Total = mysqli_num_rows($consultaMaq);


while($consult= mysqli_fetch_array($consulta)){
	$areaUsuario = $consult['area'];
	$idUsuario   = $consult['idUsuario'];
	$usuario     = $consult['usuario'];
	$senha       = $consult['senha'];
	$idEmpresa   = $consult['idEmpresa'];
}

$activeMenu = "2";
$activeSub = "1";

if($areaUsuario == 'Manutenção'){
	$_SESSION['notify'] = 2;
	header('location:controle-maquinas');
}
?>
<html>
<head>
	<title>Cadastro de máquinas</title>
	<meta charset="UTF-8">
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="icon" href="http://www.maqbusca.com.br/wp-content/themes/maqbusca/favicon.ico" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<link href="assets/css/cadastro.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="assets/notifIt/js/notifIt.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/notifIt/css/notifIt.css">
	<script type="text/javascript">

	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});

	// Função para mostrar um alert na tela
	function not(){
		notif({
			msg: "<?php echo $_SESSION['mensagem']?>",
			type: "success",
			bgcolor: "#00B55E",
			color: "#FFF"
		});
	}
	// Funções para enviar formualrio sem trocar de página
	jQuery(document).ready(function(){
		jQuery('#form-modelo').submit(function(){
			var dados = jQuery( this ).serialize();
			jQuery.ajax({
				type: "POST",
				url: "form-modelo.php",
				data: dados,
				success: function( data )
				{
					window.location.reload()
				}
			});
			return false;
		});
	});
	jQuery(document).ready(function(){
		jQuery('#adicionar-usuarios').submit(function(){
			var dados = jQuery( this ).serialize();
			jQuery.ajax({
				type: "POST",
				url: "adicionar-usuarios.php",
				data: dados,
				success: function( data )
				{
					window.location.reload()
				}
			});
			return false;
		});
	});
	jQuery(document).ready(function(){
		jQuery('#form-alterar-dados').submit(function(){
			var dados = jQuery( this ).serialize();
			jQuery.ajax({
				type: "POST",
				url: "form-alterar-dados.php",
				data: dados,
				success: function( data )
				{
					window.location.reload()
				}
			});
			return false;
		});
	});

	jQuery(document).ready(function(){
		jQuery('#busca-maquina').submit(function(){
			var dados = jQuery( this ).serialize();
			jQuery.ajax({
				type: "POST",
				url: "busca-maquina.php",
				data: dados,
				success: function( data )
				{
					window.location.reload()
				}
			});
			return false;
		});
	});
	// Função para excluir cadastro
	function excluirMaquina(id, patrimonio){
		var myCallback = function(choice){
			if(choice){
				jQuery.ajax({
					type: "POST",
					url: "excluir-maquina.php",
					data: {
						id: id,
					},
					success: function( data )
					{
						window.location.reload()
					}
				});
			}
		}
		notif_confirm(({
			'message': 'Confirmar a exclusão da máquina '+ patrimonio +'?',
			'textaccept': 'Sim',
			'textcancel': 'Não',
			'fullscreen': true,
			'callback': myCallback
		}))
	}
	function onlynumber(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		//var regex = /^[0-9.,]+$/;
		var regex = /^[0-9.]+$/;
		if( !regex.test(key) ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	}
	// Função para autocompletar o campo fabricante de acordo com o modelo
	$(function(){
		$('#modelo').change(function(){
			if($('#modelo').val()){
				$.getJSON('autocomplete-fabricante.ajax.php?search=',{modelo: $('#modelo').val(), codigo: 'nocode', ajax: 'true'}, function(j){
					for (var i = 0; i < j.length; i++) {
						document.getElementById("fabricante-div").innerHTML = "<div class=\"control-form-after\"><p>Fabricante:</p><input class=\"text-form\" type=\"text\"  value=\"" + j[i].fabricante+ "\" placeholder=\"Fabricante *\" name=\"fabricante\" id=\"fabricante\" required></div>";
					}
				});
			}
		});
	});
	$(function(){
		$('#modeloBusca').change(function(){
			if($('#modeloBusca').val()){
				$.getJSON('autocomplete-fabricante.ajax.php?search=',{modelo: $('#modeloBusca').val(), codigo: 'nocode', ajax: 'true'}, function(j){
					for (var i = 0; i < j.length; i++) {
						document.getElementById("fabricanteBusca-div").innerHTML = "<div class=\"control-form-after\"><p>Fabricante:</p><input class=\"text-form\" type=\"text\"  value=\"" + j[i].fabricante+ "\" placeholder=\"Fabricante\" name=\"fabricanteBusca\" id=\"fabricanteBusca\" required></div>";
					}
				});
			}
		});
	});

	function hideModalModelo() {
		$(".modal-backdrop").remove();
		$("#NovoModelo").modal('hide');
	}

	// Função para excluir cadastro
	function excluirModelo(id){
		var myCallback = function(choice){
			if(choice){
				jQuery.ajax({
					type: "POST",
					url: "excluir-modelo.php",
					data: {
						idModelo: id,
					},
					success: function( data )
					{
						window.location.reload()
					}
				});
			}
		}
		notif_confirm(({
			'message': 'Confirmar a exclusão do modelo?',
			'textaccept': 'Sim',
			'textcancel': 'Não',
			'fullscreen': true,
			'callback': myCallback
		}))
	}

	$(function(){
		$('#patrimonio').change(function(){
			if($('#patrimonio').val()){
				$.getJSON('noRepeat-patrimonio.php?search=',{patrimonio: $('#patrimonio').val(), ajax: 'true'}, function(j){
					for (var i = 0; i < j.length; i++) {
						if(j[i].patrimonio >= 1){
							document.getElementById("patrimonio").style.borderColor = "red";
							notif({
								msg: "Patrimônio já cadastrado!",
								type: "error",
								bgcolor: "#FF5A5A",
								color: "#FFF"
							});
							$("#btn-submit").attr("disabled", true);
						}else{
							document.getElementById("patrimonio").style.borderColor = "#ccc";
							$("#btn-submit").attr("disabled", false);
						}
					}
				});
			}
		});
	});

	$(function(){
		$('#nomeModelo').change(function(){
			if($('#nomeModelo').val()){
				$.getJSON('noRepeat-modelo.php?search=',{modelo: $('#nomeModelo').val(), ajax: 'true'}, function(j){
					for (var i = 0; i < j.length; i++) {
						if(j[i].modelo >= 1){
							document.getElementById("nomeModelo").style.borderColor = "red";
							notif({
								msg: "Modelo já cadastrado!",
								type: "error",
								bgcolor: "#FF5A5A",
								color: "#FFF"
							});
							$("#btn-submit-modelo").attr("disabled", true);
						}else{
							document.getElementById("nomeModelo").style.borderColor = "#ccc";
							$("#btn-submit-modelo").attr("disabled", false);
						}
					}
				});
			}
		});
	});

	</script>
</head>
<?php if($_SESSION['notify'] == 1){	?>
	<body onload="not()">
		<?php $_SESSION['notify'] = 0; }else {	?>
			<body> <?php } ?>
				<div class="nav"><?php include_once ('menu-nav.php'); ?></div>
				<div class="page">

					<div class="frota">
						<p class="title">Máquinas na frota</p>
						<a class="text-cad" href="" data-toggle="modal" data-target="#NovaMaquina"><button class="btn-busca" type="button">Nova Máquina</button></a>

						<?php $resultadomnf = $mysqli->query("SELECT * FROM maquinas WHERE idEmpresa = '$idEmpresa' AND excluido ='0'");

						$verificamnf = mysqli_num_rows($resultadomnf);
						if($verificamnf == 0){ ?>
							<div class="control-form-modal-top control-form-modal">
								<p style="text-align:center;">Nenhuma máquina cadastrada</p>
							</div>
							<?php
						}else{	?>
							<div class="search">
								<?php
								if($_SESSION['busca'] == 1){
									$consultaTotalMaq =  $mysqli->query("SELECT * FROM maquinas WHERE excluido = '0' AND apelido LIKE '%".$_SESSION['apelido']."%' AND fase LIKE '%".$_SESSION['status']."%' AND modelo LIKE '%".$_SESSION['modelo']."%' AND fabricante LIKE '%".$_SESSION['fabricante']."%'
										AND patrimonio LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa'"); ?>
										<div class="btn-cancela-busca-maq" data-toggle="tooltip" data-placement="top" title="Cancelar busca"><a class="text-busca" href="" ><span class="far fa-times-circle text-busca-cancela"</span></a></div>
											<a class="text-busca" href="" data-toggle="modal" data-target="#myModalBusca"><button class="btn-busca" type="button"><span class="fas fa-search"></span>&nbsp Busca avançada</button></a> Buscando por:
											<?php
											if($_SESSION['patrimonio'] == "" && $_SESSION['apelido'] == "" && $_SESSION['modelo'] == "" && $_SESSION['fabricante'] == "" && $_SESSION['status'] == ""){
												echo "Todos";
											}
											if($_SESSION['patrimonio'] != ""){
												echo $_SESSION['patrimonio'];
												if($_SESSION['apelido'] != "" || $_SESSION['modelo'] != "" || $_SESSION['fabricante'] != "" || $_SESSION['status'] != ""){
													echo " | ";
												}
											}
											if($_SESSION['apelido'] != ""){
												echo $_SESSION['apelido'];
												if($_SESSION['modelo'] != "" || $_SESSION['fabricante'] != "" || $_SESSION['status'] != ""){
													echo " | ";
												}
											}
											if($_SESSION['modelo'] != ""){
												echo $_SESSION['modelo'];
												if($_SESSION['fabricante'] != "" || $_SESSION['status'] != ""){
													echo " | ";
												}
											}
											if($_SESSION['fabricante'] != ""){
												echo $_SESSION['fabricante'];
												if($_SESSION['status'] != ""){
													echo " | ";
												}
											}
											if($_SESSION['status'] != ""){
												$resultadoStatus1 = $mysqli->query("SELECT * FROM status WHERE idStatus = '".$_SESSION['status']."' AND idEmpresa = '$idEmpresa'");
												while($reg_cadastro1 = mysqli_fetch_array($resultadoStatus1)){
													$nomeStatus           = $reg_cadastro1['nome'];
												}
												echo $nomeStatus;
											}
										}else{ ?>
											&nbsp;&nbsp;&nbsp;<a class="text-busca" href="" data-toggle="modal" data-target="#myModalBusca"><button class="btn-busca" type="button"><span class="fas fa-search"></span>&nbsp Busca avançada</button></a>

											<?php
											$consultaTotalMaq =  $mysqli->query("SELECT * FROM maquinas WHERE excluido = '0' AND idEmpresa = '$idEmpresa'");
										}
										$TotalMaq = mysqli_num_rows($consultaTotalMaq);
										?>
										<p class="total">N.º de máquinas: <?php echo $TotalMaq ?></p>
									</div>
									<div class="tdados">
										<div class="title-dados tdsmall">Patrimônio</div>
										<div class="title-dados tdsmall">Apelido</div>
										<div class="title-dados">Modelo</div>
										<div class="title-dados">Fabricante</div>
										<div class="title-dados tddata">Data do cadastro</div>
										<div class="title-dados tdlarge">Status</div>
										<div class="title-dados-small"></div>
										<div class="title-dados-small nb"></div>
									</div>
									<div class="scroll-maq">

										<?php
										if($_SESSION['busca'] == 1){
											$resultado = $mysqli->query("SELECT * FROM maquinas WHERE excluido = '0' AND apelido LIKE '%".$_SESSION['apelido']."%' AND fase LIKE '%".$_SESSION['status']."' AND modelo LIKE '%".$_SESSION['modelo']."%' AND fabricante LIKE '%".$_SESSION['fabricante']."%'
												AND patrimonio LIKE '%".$_SESSION['patrimonio']."%' AND idEmpresa = '$idEmpresa'");
												$_SESSION['busca'] = 0;
												$_SESSION['modelo'] = '';
												$_SESSION['fabricante'] = '';
												$_SESSION['apelido'] = '';
												$_SESSION['patrimonio'] = '';
												$_SESSION['status'] = '';
											}else{
												$resultado = $mysqli->query("SELECT * FROM maquinas WHERE excluido = '0' AND idEmpresa = '$idEmpresa'");
											}
											while($reg_cadastro= mysqli_fetch_array($resultado)){

												$codigoMaquina    = $reg_cadastro['idMaquina'];
												$Apelido          = $reg_cadastro['apelido'];
												$Modelo           = $reg_cadastro['modelo'];
												$Fabricante       = $reg_cadastro['fabricante'];
												$Patrimonio				= $reg_cadastro['patrimonio'];
												$DataCadastro		  = $reg_cadastro['dataCadastro'];
												$Status						= $reg_cadastro['fase'];

												if($Status == 0){
													$NomeStatus	= $Status;
												}

												$DataCadastro = implode("/",array_reverse(explode("-",$DataCadastro)));

												$resultadoStatus = $mysqli->query("SELECT * FROM status WHERE idStatus = '$Status' AND idEmpresa = '$idEmpresa'");
												while($reg_cadastro= mysqli_fetch_array($resultadoStatus)){
													$NomeStatus           = $reg_cadastro['nome'];
												}	?>

												<div class="dados">
													<div class="title-dados tdsmall"><?php echo $Patrimonio ?></div>
													<div class="title-dados tdsmall"><?php echo $Apelido ?></div>
													<div class="title-dados"><?php echo $Modelo ?></div>
													<div class="title-dados"><?php echo $Fabricante ?></div>
													<div class="title-dados tddata"><?php echo $DataCadastro ?></div>
													<div class="title-dados tdlarge"><?php echo $NomeStatus ?></div>
													<a href="" data-toggle="modal" data-target="#myModalEditar<?php echo $codigoMaquina ?>"><div  data-toggle="tooltip" data-placement="top" title="Editar" class="title-dados-small "><span class="fas fa-pencil-alt"></span></div></a>
													<div onclick="excluirMaquina(<?php echo $codigoMaquina ?>, <?php echo $Patrimonio ?>);" data-toggle="tooltip" data-placement="top" title="Excluir" class="title-dados-small-t nb "><span class="far fa-trash-alt"></span></div>
												</div>

												<!-- MODAL PARA EDITAR MAQUINA -->
												<div class="modal fade" id="myModalEditar<?php echo $codigoMaquina ?>" role="dialog">
													<div class="modal-dialog">
														<div class="modal-content modal-menor">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"></button>
																<h4 class="modal-title title-modal" style="text-align: center">Editar máquina <?php echo $Patrimonio ?></h4>
															</div>
															<form name="editar-maquina" method="post" action="editar-maquina">
																<div class="modal-body modal-body-maquina">
																	<input type="hidden" value="<?php echo $codigoMaquina ?>" name="idEditar">
																	<div class="form-top-modal">
																		<div class="control-form">
																			<p>Patrimônio:</p>
																			<input class="text-form" type="text" value="<?php echo $Patrimonio ?>" onkeypress="return onlynumber();" autocomplete="off" placeholder="Patrimônio *" name="patrimonioEditar" required>
																		</div>
																		<div class="control-form form-control-select">
																			<p>Modelo:</p>
																			<input class="text-form" type="text" value="<?php echo $Modelo ?>" autocomplete="off" placeholder="Modelo *" name="modeloEditar" required>
																		</div>
																	</div>
																	<div class="form-middle-modal">
																		<div class="control-form">
																			<p>Apelido:</p>
																			<input class="text-form" type="text" value="<?php echo $Apelido ?>" autocomplete="off" placeholder="Apelido *" name="apelidoEditar" required>
																		</div>
																		<div class="control-form">
																			<p>Fabricante:</p>
																			<input class="text-form" type="text" autocomplete="off" placeholder="Fabricante *" value="<?php echo $Fabricante ?>" name="fabricanteEditar" required>
																		</div>
																	</div>
																	<div class="form-bottom-modal">
																		<div class="control-form form-control-select">
																			<p>Status:</p>
																			<select class="text-form-select st" name="statusEditar" required>
																				<option value="">Selecione um status *</option>
																				<option value="3">Em locação</option>
																				<option value="2">Disponível</option>
																				<option value="4">Checklist</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="modal-footer">
																	<button type="submit" class="btn btn-primary">Editar</button>
																	<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
																</div>
															</form>
														</div>
													</div>
												</div>
												<?php
											} ?>
										</div>
									<?php } ?>
								</div>

					<!-- MODAL PARA CADASTRO DE MAQUINA-->
					<div class="container-modal">
						<div class="modal fade" id="NovaMaquina" role="dialog">
							<div style="width: 800px" class="modal-dialog">
								<div class="modal-content modal-maquina modal-scroll-media" >
									<div class="modal-header">
										<h4 class="modal-title-modelo">Cadastro de máquinas</h4>
									</div>
									<div class="modal-cadastro">
									<form name="form-cadastro-maquina" method="post" action="form-cadastro-maquina">
								<div class="form-top">
									<div class="control-form">
										<p>Apelido:</p>
										<input class="text-form" type="text" autocomplete="off" placeholder="Apelido *" name="apelido" required>
									</div>
									<div class="control-form form-control-select">
										<p>Modelo:</p>
										<select class="text-form-select" name="modelo"  id="modelo" required>
											<option value="">
												Selecione um modelo *
											</option>
											<?php
											$query = $mysqli->query("SELECT * FROM modelo ORDER BY nome ASC");

											while($reg = $query->fetch_array()) { ?>
												<option value="<?php echo $reg['nome']; ?>">
													<?php echo $reg['nome']; ?>
												</option>
											<?php } ?>
										</select>
										<div class="add"><a data-toggle="modal" data-target="#NovoModelo" href=""><span data-toggle="tooltip" data-placement="top" title="Adicionar modelo" class="fas fa-plus"></span></a></div>
									</div>
								</div>
								<div class="form-middle">
									<div class="control-form">
										<p>Patrimônio:</p>
										<input class="text-form" type="text" onkeypress="return onlynumber();" onclick="teste();" autocomplete="off" placeholder="Patrimônio *" id="patrimonio" name="patrimonio" required>
									</div>
									<div id="fabricante-div" class="control-form">
										<p>Fabricante:</p>
										<input class="text-form" type="text" autocomplete="off" placeholder="Fabricante *" name="fabricante"  required>
									</div>
								</div>
								<div class="form-bottom">
									<div class="control-form">
										<p>Status:</p>
										<select class="text-form-select st" name="status" required>
											<option value="">Selecione um status *</option>
											<option value="3">Em locação</option>
											<option value="2">Disponível</option>
											<option value="4">Checklist</option>
										</select>
									</div>
								</div>
								
								</div>
								<div class="modal-footer">
									<button type="submit" id="btn-submit" class="btn btn-primary">Salvar</button>
											<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
										</div>
							</form>
								</div>
							</div>
						</div>
					</div>

					<!-- MODAL PARA ADICIONAR MODELO E FABRICANTE-->
					<div class="container-modal">
						<div class="modal fade" id="NovoModelo" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content modal-modelo modal-scroll-media" >
									<div class="modal-header">
										<h4 class="modal-title-modelo">Adicionar modelo</h4>
									</div>
									<form name="form-modelo" id="form-modelo" method="post" action="form-modelo.php">
										<div class="modal-body">
											<div class="input-modal-modelo tm">
												<p>Modelo:</p>
												<input class="form-control-modelo" type="text" autocomplete="off" placeholder="Nome modelo *" id="nomeModelo" name="nomeModelo" required>
											</div>
											<div class="input-modal-modelo-b tm">
												<p>Fabricante:</p>
												<input class="form-control-modelo" type="text" placeholder="Fabricante *" name="fabricanteModelo" required>
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" id="btn-submit-modelo" class="btn btn-primary">Adicionar</button>
											<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
										</div>
									</form>
									<?php
									$consultaTotalMod =  $mysqli->query("SELECT * FROM modelo WHERE excluido = '0' AND idEmpresa = '$idEmpresa'");

									$TotalMod = mysqli_num_rows($consultaTotalMod);


									if($TotalMod == 0){


									}else {
										?>

										<div class="modelos-cad-b"> </div>
										<div class="modelos-cad">




											<p class="title-modelo">Modelos cadastrados </p><p class="total-modelos">Total: <?php echo $TotalMod; ?> </p>

											<div class="modelo-dados">
												<div class="dado-modelo"><p class="txt-modelo">Modelo</p>
												</div>
												<div class="dado-modelo"><p class="txt-modelo">fabricante</p>
												</div>
												<div class="icon-modelo">
												</div>
												<div class="icon-modelo imb">
												</div>
											</div>
											<div class="scroll-box-modelo">
												<div class="box-modelo">

													<?php 	$resultadoModelo = $mysqli->query("SELECT * FROM modelo WHERE idEmpresa = '$idEmpresa'");
													while($reg_cadastro= mysqli_fetch_array($resultadoModelo)){
														$codigoModelo           = $reg_cadastro['idModelo'];
														$nomeModelo           = $reg_cadastro['nome'];
														$fabricanteModelo           = $reg_cadastro['fabricante'];
														?>


														<div class="modelo-infos">
															<div class="infos-modelo"><p class="txt-modelo-info"><?php echo $nomeModelo; ?></p>
															</div>
															<div class="infos-modelo"><p class="txt-modelo-info"><?php echo $fabricanteModelo; ?></p>
															</div>
															<a href="" data-toggle="modal" data-target="#myModalEditarModelo<?php echo $codigoModelo ?>" onclick="hideModalModelo();">
																<div data-toggle="tooltip" data-placement="top" title="Editar" class="icons-modelo"><span class="fas fa-pencil-alt"></span>
																</div>
															</a>
															<div data-toggle="tooltip" data-placement="top" title="Excluir" data-dismiss="modal" class="icons-modelo-tr" onclick="excluirModelo(<?php echo $codigoModelo ?>)"><span class="far fa-trash-alt"></span>
															</div>
														</div>

													<?php 	}	?>

												</div>
											</div>
										</div>
									<?php 	}	?>
								</div>
							</div>
						</div>
					</div>

								<!-- MODAL DA BUSCA -->
								<div class="modal fade" id="myModalBusca" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content modal-menor">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal"></button>
												<h4 class="modal-title title-modal" style="text-align: center">Busca avançada</h4>
											</div>
											<form name="busca-maquina" id="busca-maquina" method="post" action="busca-maquina.php">
												<div class="modal-body modal-body-consumo">
													<div class="form-top-modal">
														<div class="control-form">
															<p>Patrimônio:</p>
															<input class="text-form" type="text" autocomplete="off" placeholder="Patrimônio" onkeypress="return onlynumber();" name="patrimonioBusca">
														</div>
														<div class="control-form form-control-select">
															<p>Modelo:</p>
															<select class="text-form-select" id="modeloBusca" name="modeloBusca">
																<option value="">
																	Selecione um modelo
																</option>
																<?php
																$query = $mysqli->query("SELECT * FROM modelo WHERE idEmpresa = '$idEmpresa' ORDER BY nome ASC");

																while($reg = $query->fetch_array()) { ?>
																	<option value="<?php echo $reg['nome']; ?>">
																		<?php echo $reg['nome']; ?>
																	</option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="form-middle-modal">
														<div class="control-form">
															<p>Apelido:</p>
															<input class="text-form" type="text" autocomplete="off" placeholder="Apelido" name="apelidoBusca">
														</div>
														<div id="fabricanteBusca-div" class="control-form">
															<p>Fabricante:</p>
															<input class="text-form" type="text" autocomplete="off" placeholder="Fabricante" name="fabricanteBusca">
														</div>
													</div>
													<div class="form-bottom">
														<div class="control-form">
															<p>Status:</p>
															<select class="text-form-select st" name="statusBusca">
																<option value="">
																	Selecione um status *
																</option>
																<?php
																$query = $mysqli->query("SELECT * FROM status");

																while($reg = $query->fetch_array()) { ?>
																	<option value="<?php echo $reg['idStatus']; ?>">
																		<?php echo $reg['nome']; ?>
																	</option>
																<?php } ?>
															</select>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary">Buscar</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
							<?php
							$consultaModelo = $mysqli->query("SELECT * FROM modelo");

							while($reg = $consultaModelo->fetch_array()) { ?>

								<!-- MODAL PARA EDITAR USUARIOS -->
								<div class="modal fade" id="myModalEditarModelo<?php echo $reg['idModelo']; ?>" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content modal-tr">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal"></button>
												<h4 class="modal-title title-modal" style="text-align: center">Editar modelo</h4>
											</div>
											<form name="editar-modelo" method="post" action="editar-modelo">
												<input type="hidden" value="<?php echo $reg['idModelo']; ?>" name="idModeloEditar" />
												<div class="modal-body">
													<div class="input-modal-modelo tm">
														<p>Modelo:</p>
														<input class="form-control-modelo" type="text" value="<?php echo $reg['nome']; ?>" autocomplete="off" placeholder="Nome modelo *" name="nomeModeloEditar" required>
													</div>
													<div class="input-modal-modelo-b tm">
														<p>Fabricante:</p>
														<input class="form-control-modelo" type="text" value="<?php echo $reg['fabricante']; ?>" autocomplete="off" placeholder="Fabricante *" name="fabricanteModeloEditar" required>
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary">Editar</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							<?php }

							include('footer.php'); ?>
						</body>
						</html>
