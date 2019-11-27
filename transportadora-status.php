<?php

/* ====================================================
== Página de cadastro das transportadoras e dos status,
tambés pode-se visualiza-los e edita-los
===================================================== */

error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include_once ('conexao.php');

session_start();

if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)){
	unset($_SESSION['usuario']);
	unset($_SESSION['senha']);
	header('location:index.html');
}

date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil
$dataAtual = date('d/m/Y');

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");

while($consult= mysqli_fetch_array($consulta)){
	$areaUsuario = $consult['area'];
	$idUsuario   = $consult['idUsuario'];
	$usuario     = $consult['usuario'];
	$senha       = $consult['senha'];
	$idEmpresa   = $consult['idEmpresa'];
}

$activeMenu = "2";
$activeSub = "2";

if($areaUsuario == 'Manutenção'){
	$_SESSION['notify'] = 2;
	header('location:controle-maquinas.php');
}
?>
<html>
<head>
	<title>Transportadora e Status</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="assets/notifIt/js/notifIt.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/notifIt/css/notifIt.css">
	<script type="text/javascript" src="assets/js/mask.js"></script>
	<script src="assets/bootstrap/js/bootstrap.js"></script>
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/cadastro.css" rel="stylesheet">
	<script>
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
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});
	// Função para mostrar um alert na tela
	function not1(){
		notif({
			msg: "<?php echo $_SESSION['mensagem']?>",
			type: "success",
			bgcolor: "#00B55E",
			color: "#FFF"
		});
	}
	function not2(){
		notif({
			msg: "Você não tem permissão",
			type: "success",
			bgcolor: "#FF5A5A",
			color: "#FFF"
		});
	}
	// Funções para enviar formualrio sem trocar de página
	jQuery(document).ready(function(){
		jQuery('#form-transportadora').submit(function(){
			var dados = jQuery( this ).serialize();
			jQuery.ajax({
				type: "POST",
				url: "form-transportadora.php",
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
		jQuery('#form-status').submit(function(){
			var dados = jQuery( this ).serialize();
			jQuery.ajax({
				type: "POST",
				url: "form-status.php",
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
	function excluirStatus(id){
		var myCallback = function(choice){
			if(choice){
				jQuery.ajax({
					type: "POST",
					url: "excluir-status.php",
					data: {
						idStatus: id,
					},
					success: function( data )
					{
						window.location.reload()
					}
				});
			}
		}
		notif_confirm(({
			'message': 'Confirmar a exclusão do status?',
			'textaccept': 'Sim',
			'textcancel': 'Não',
			'fullscreen': true,
			'callback': myCallback
		}))
	}
	// Função para excluir cadastro
	function excluirTransportadora(id){
		var myCallback = function(choice){
			if(choice){
				jQuery.ajax({
					type: "POST",
					url: "excluir-transportadora.php",
					data: {
						idTransportadora: id,
					},
					success: function( data )
					{
						window.location.reload()
					}
				});
			}
		}
		notif_confirm(({
			'message': 'Confirmar a exclusão da transportadora?',
			'textaccept': 'Sim',
			'textcancel': 'Não',
			'fullscreen': true,
			'callback': myCallback
		}))
	}
	</script>
</head>
<?php if($_SESSION['notify'] == 1){	?>
	<body onload="not1()">
		<?php $_SESSION['notify'] = 0; } elseif($_SESSION['notify'] == 2){	?>
			<body onload="not2()">
				<?php $_SESSION['notify'] = 0; }else { ?>
					<body> <?php } ?>
						<div class="nav"><?php include_once ('menu-nav.php'); ?></div>
						<div class="page">
							<div class="transportadoras">


								<p class="title">Transportadoras</p>
								<a class="text-cad" href="" data-toggle="modal" data-target="#NovaTransportadora"><button class="btn-busca" type="button">Nova Transportadora</button></a>
								<div class="t-dados-trs">
									<?php
									$resultadotr = $mysqli->query("SELECT * FROM transportadora WHERE idEmpresa = '$idEmpresa'");

									$verificatr = mysqli_num_rows($resultadotr);
									if($verificatr == 0){ ?>
										<div class="control-form-modal-top control-form-modal">
											<p style="text-align:center;">Nenhuma transportadora cadastrada</p>
										</div>

										<?php
									}else{


										?>
										<div class="t-dados tra">Nome da transportadora</div>
										<div class="t-dados tel">Telefone</div>
										<div class="t-dados-small"></div>
										<div class="t-dados-small tdr"></div>
									</div>

									<div class="scroll-stm">


										<?php



										while($reg_cadastro= mysqli_fetch_array($resultadotr)){
											$codigoTransportadora          =$reg_cadastro['idTransportadora'];
											$nomeTransportadora            =$reg_cadastro['nomeTransportadora'];
											$telefone                      =$reg_cadastro['telefone'];
											?>
											<div class="txt-dados-trs">
												<div class="txt-dados tra"><?php echo $nomeTransportadora ?></div>
												<div class="txt-dados tel"><?php echo $telefone ?></div>
												<a href="" data-toggle="modal" data-target="#myModalEditarTransportadora<?php echo $codigoTransportadora ?>"><div  data-toggle="tooltip" data-placement="top" title="Editar" class="title-dados-small-ee"><span class="fas fa-pencil-alt"></span></div></a>
												<div  data-toggle="tooltip" onclick="excluirTransportadora(<?php echo $codigoTransportadora ?>)" data-placement="top" title="Excluir" class="title-dados-small-tt tdr"><span class="far fa-trash-alt"></span></div>
											</div>

											<!-- MODAL PARA EDITAR TRANSPORTDORA -->
											<div class="modal fade" id="myModalEditarTransportadora<?php echo $codigoTransportadora ?>" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content modal-tr">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal"></button>
															<h4 class="modal-title title-modal" style="text-align: center">Editar transportadora</h4>
														</div>
														<form name="editar-transportadora" method="post" action="editar-transportadora">
															<div class="modal-body modal-body-tr">
																<input type="hidden" value="<?php echo $codigoTransportadora ?>" name="idTransportadoraEditar">
																<div class="control-form-modal-top control-form-modal">
																	<p>Nome da transportadora:</p>
																	<input class="text-form-tr" maxlength="15" type="text" autocomplete="off" placeholder="Nome *" value="<?php echo $nomeTransportadora ?>" name="nomeTransportadoraEditar" required>
																</div>
																<div class="control-form-modal-bottom control-form-modal">
																	<p>Telefone:</p>
																	<input class="text-form-tr" type="tel" onkeypress="return onlynumber();" autocomplete="off" placeholder="Telefone *" value="<?php echo $telefone ?>" name="telefoneTransportadoraEditar" maxlength="15" required>
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
										<?php } }?>
									</div>
								</div>

						
								<!-- MODAL PARA CADASTRO DE TRANSPORTADORAS-->
								<div class="container-modal">
						<div class="modal fade" id="NovaTransportadora" role="dialog">
							<div style="width: 800px" class="modal-dialog">
								<div class="modal-content modal-maquina modal-scroll-media" >
									<div class="modal-header">
										<h4 class="modal-title-modelo">Cadastro de máquinas</h4>
									</div>
									<div class="modal-cadastro">
									<form name="form-transportadora" id="form-transportadora" method="post" action="form-transportadora.php">
										<div class="control-form ntr">
											<p>Nome da transportadora:</p>
											<input class="text-form-tr" maxlength="15" type="text" autocomplete="off" placeholder="Nome *" name="nomeTransportadora" required>
										</div>
										<div class="control-form">
											<p>Telefone:</p>
											<input class="text-form-tr" onkeypress="return onlynumber();" type="tel" autocomplete="off" placeholder="Telefone *" name="telefone" maxlength="15" required>
										</div>
									</form>
								</div>
																<div class="modal-footer">
																	<button type="submit" class="btn btn-primary">Salvar</button>
																	<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
																</div>
							</div>
						</div>
					</div>
						</div>	



								<div class="status">
									<p class="title">Status</p>
									<a class="text-cad" href="" data-toggle="modal" data-target="#NovoStatus"><button class="btn-busca" type="button">Novo Status</button></a>
									<div class="t-dados-trs">
										<?php
										$resultadosts = $mysqli->query("SELECT * FROM status WHERE idEmpresa = '$idEmpresa'");

										$verificasts = mysqli_num_rows($resultadosts);
										if($verificasts == 0){ ?>
											<div class="control-form-modal-top control-form-modal">
												<p style="text-align:center;">Nenhum status cadastrado</p>
											</div>
											<?php
										}else{


											?>
											<div class="t-dados tra">Status</div>
											<div class="t-dados tel">Área</div>
											<div class="t-dados-small"></div>
											<div class="t-dados-small tdr"></div>
										</div>
										<div class="scroll-stm">
											<?php

											while($reg_cadastro= mysqli_fetch_array($resultadosts)){
												$codigoStatus          = $reg_cadastro['idStatus'];
												$nomeStatus            = $reg_cadastro['nome'];
												$area                  = $reg_cadastro['area'];
												?>
												<div class="txt-dados-trs">
													<div class="txt-dados tra"><?php echo $nomeStatus ?></div>
													<div class="txt-dados tel"><?php echo $area ?></div>
													<?php if($codigoStatus <= 5){?>
														<div  data-toggle="tooltip" data-placement="top" title="Bloqueado" class="title-dados-small-tt-block"><span class="fas fa-pencil-alt"></span></div>
														<div  data-toggle="tooltip" data-placement="top" title="Bloqueado" class="title-dados-small-tt-block tdr"><span class="far fa-trash-alt"></span></div>
													<?php }else{ ?>
														<a href="" data-toggle="modal" data-target="#myModalEditarStatus<?php echo $codigoStatus ?>"><div  data-toggle="tooltip" data-placement="top" title="Editar" class="title-dados-small-ee"><span class="fas fa-pencil-alt"></span></div></a>
														<div  data-toggle="tooltip" onclick="excluirStatus(<?php echo $codigoStatus ?>)" data-placement="top" title="Excluir" class="title-dados-small-tt tdr"><span class="far fa-trash-alt"></span></div>
													<?php } ?>
												</div>
												<!-- MODAL PARA EDITAR STATUS -->
												<div class="modal fade" id="myModalEditarStatus<?php echo $codigoStatus ?>" role="dialog">
													<div class="modal-dialog">
														<div class="modal-content modal-menor">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"></button>
																<h4 class="modal-title title-modal" style="text-align: center">Editar status</h4>
															</div>
															<form name="editar-status" method="post" action="editar-status">
																<div class="modal-body">
																	<input  type="hidden" value="<?php echo $codigoStatus ?>" name="idStatusEditar">
																	<div class="control-form-modal-sts control-form-modal">
																		<p>Status:</p>
																		<input class="text-form-tr" type="text" value="<?php echo $nomeStatus ?>" autocomplete="off" placeholder="Status *" name="nomeStatusEditar" required>
																	</div>
																	<div class="radio-form-modal-sts">
																		<div class="btn-npago">
																			<?php if($area == "Comercial"){?>
																				<input type="radio" class="option-input n-pago radio" name="areaStatusEditar" value="Comercial" checked/>
																			<?php }else{?>
																				<input type="radio" class="option-input n-pago radio" name="areaStatusEditar" value="Comercial"/>
																			<?php } ?>
																		</div>
																		<div class="btn-pago">
																			<?php if($area == "Manutenção"){?>
																				<input type="radio" class="option-input pago radio" value="Manutenção" name="areaStatusEditar" checked/>
																			<?php }else{?>
																				<input type="radio" class="option-input pago radio" value="Manutenção" name="areaStatusEditar"/>
																			<?php } ?>
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
											<?php } }?>
										</div>
									</div>

																	<!-- MODAL PARA CADASTRO DE STATUS-->
								<div class="container-modal">
						<div class="modal fade" id="NovoStatus" role="dialog">
							<div style="width: 800px" class="modal-dialog">
								<div class="modal-content modal-maquina modal-scroll-media" >
									<div class="modal-header">
										<h4 class="modal-title-modelo">Cadastro de máquinas</h4>
									</div>
									<div class="modal-cadastro">
									<div class="form-transportadora">
									<form name="form-status" id="form-status" method="post" action="form-status.php">
										<div class="control-form">
											<p>Status:</p>
											<input class="text-form-tr" type="text" autocomplete="off" placeholder="Status *" name="status" required>
										</div>
										<div class="radio-form">
											<div class="btn-npago">
												<input type="radio" class="option-input n-pago radio" name="pago" value="Comercial"/>
											</div>
											<div class="btn-pago">
												<input type="radio" class="option-input pago radio" name="pago" value="Manutenção" required/>
											</div>
										</div>
									</form>
								</div>
								</div>
																<div class="modal-footer">
																	<button type="submit" class="btn btn-primary">Salvar</button>
																	<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
																</div>
							</div>
						</div>
					</div>
						</div>	
								</div>
								<?php include('footer.php'); ?>
							</body>
							</html>
