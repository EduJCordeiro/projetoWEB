<?php

// pagina de controle de maquinas
error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
include_once ('conexao.php'); //faz conexao com o banco
session_start();

if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))  // validação de usuario e senha
{
	unset($_SESSION['usuario']);
	unset($_SESSION['senha']);
	header('location:login'); // redireciona para a tela de login
}


date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil
$dataAtual = date('d/m/Y');

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'"); // select para consulta de dados 

while($consult= mysqli_fetch_array($consulta)){ // while para pegar os dados da consulta
	$idEmpresa   = $consult['idEmpresa']; // define variavel para do dado recebido pela consulta
	$areaUsuario = $consult['area']; // define variavel para do dado recebido pela consulta
}

$activeMenu = "1";
$activeSub = "1";

?>
<html>
<head>
	<title>Controle de máquinas</title>
	<meta charset="UTF-8">
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="icon" href="http://www.maqbusca.com.br/wp-content/themes/maqbusca/favicon.ico" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<link href="assets/css/cadastro.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="assets/notifIt/js/notifIt.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/notifIt/css/notifIt.css">
	<script type="text/javascript" src="assets/js/mask.js"></script>
	<script src="assets/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript">

	function onlynumber(evt) { // script para somente numeros
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
	$(document).ready(function(){ // script de tooltip utilizando freamework
		$('[data-toggle="tooltip"]').tooltip();
	});

	// Função para mostrar um alert na tela
	function not(){  // script de notificacao utilizando freamework
		notif({
			msg: "<?php echo $_SESSION['mensagem']?>",
			type: "success",
			bgcolor: "#00B55E",
			color: "#FFF"
		});
	}
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
	function cancelarSaida(id){
		var myCallback = function(choice){
			if(choice){
				jQuery.ajax({
					type: "POST",
					url: "cancelar-saida.php",
					data: {
						idMaquinaConfirma: id,
					},
					success: function( data )
					{
						window.location.reload()
					}
				});
			}
		}

		notif_confirm(({
			'message': 'Confirmar a saída da máquina?',
			'textaccept': 'Sim',
			'textcancel': 'Não',
			'fullscreen': true,
			'callback': myCallback
		}))
	}

	jQuery(document).ready(function(){ // Função para busca de maquina
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

	$(function(){ // Função para busca de modelo
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

	var xx = 0;
	function limitSM(){ // script para colocar o gif de load para carregar mais maquinas
		document.getElementById("limitSM").innerHTML = "<div class=\"div-loader\"><img class=\"loader\" src=\"assets/images/loader.gif\"></div>";
		limitSM = '1';
	}
	function limitPM(){ // script para colocar o gif de load para carregar mais maquinas
		document.getElementById("limitPM").innerHTML = "<div class=\"div-loader\"><img class=\"loader\" src=\"assets/images/loader.gif\"></div>";
		limitPM = '1';
	}
	function limitMR(){ // script para colocar o gif de load para carregar mais maquinas
		document.getElementById("limitMR").innerHTML = "<div class=\"div-loader\"><img class=\"loader\" src=\"assets/images/loader.gif\"></div>";
		limitMR = '1';
	}
	function limitML(){ // script para colocar o gif de load para carregar mais maquinas
		document.getElementById("limitML").innerHTML = "<div class=\"div-loader\"><img class=\"loader\" src=\"assets/images/loader.gif\"></div>";
		limitML = '1';
	}

	function paraTudo() { // script para pausar o carregamento automatico de todas as colunas
		para();
		paraP();
		paraPA();
		paraPAA();
	}
	function comecaTudo() { // script para começar o carregamento automatico de todas as colunas
		comeca();
		comecaP();
		comecaPM();
		comecaPMPA();
	}

	// ==================

	function comeca() { // script para atualizar a coluna do patio
		refreshpatio();
	}
	function para() {
		clearTimeout(timeOutVar); // script para resetar o timeout
	}

	$(document).ready( function(){ // script para verificar qual a variavel e carregar a pagina de limit ou todas e carregar a coluna
		if(limitPM == '1'){
		$('#patio').load('patio-maquinas.php');
	}else{
		$('#patio').load('patio-maquinas-limit.php');
	}
		refreshpatio();
	});

	function refreshpatio(){ // script para verificar qual a variavel e carregar a pagina de limit ou todas e carregar a coluna
		timeOutVar = setTimeout( function() {
			if(limitPM == '1'){
			$('#patio').load('patio-maquinas.php');
		}else{
			$('#patio').load('patio-maquinas-limit.php');
		}
			refreshpatio();
		}, 6000);
	}

	// ==================

	function comecaP(){ // script para atualizar a coluna da saida
		refreshsaida();
	}
	function paraP(){
		clearTimeout(timeOutVarSaida); // script para resetar o timeout
	}

	$(document).ready( function(){// script para verificar qual a variavel e carregar a pagina de limit ou todas e carregar a coluna
		if(limitSM == '1'){
		$('#saida').load('saida-maquinas.php');
		}else{
		$('#saida').load('saida-maquinas-limit.php');
		}
		refreshsaida();
	});

	function refreshsaida(){ // script para verificar qual a variavel e carregar a pagina de limit ou todas e carregar a coluna
		timeOutVarSaida = setTimeout( function() {
			if(limitSM == '1'){
			$('#saida').load('saida-maquinas.php');
			}else{
			$('#saida').load('saida-maquinas-limit.php');
			}
			refreshsaida();
		}, 6000);
	}

	// ==================

	function comecaPM(){ // script para atualizar a coluna da saida
		refreshlocada();
	}
	function paraPA(){
		clearTimeout(timeOutVarLocada);// script para resetar o timeout 
	}

	$(document).ready( function(){ // script para verificar qual a variavel e carregar a pagina de limit ou todas e carregar a coluna
		if(limitML == '1'){
	$('#locada').load('maquinas-locadas.php');
		}else{
	$('#locada').load('maquinas-locadas-limit.php');
		}
		refreshlocada();
	});

	function refreshlocada(){ // script para verificar qual a variavel e carregar a pagina de limit ou todas e carregar a coluna
		timeOutVarLocada = setTimeout( function() {
			if(limitML == '1'){
		$('#locada').load('maquinas-locadas.php');
			}else{
		$('#locada').load('maquinas-locadas-limit.php');
			}
			refreshlocada();
		}, 6000);
	}
	// ==================
	function comecaPMPA(){ // script para atualizar a coluna da saida
		refreshretornando();
	}
	function paraPAA(){
		clearTimeout(timeOutVarRetornando); // script para resetar o timeout 
	}

	$(document).ready( function(){ // script para verificar qual a variavel e carregar a pagina de limit ou todas e carregar a coluna
		if(limitMR == '1'){
	$('#retornando').load('maquinas-retornando.php');
		}else{
	$('#retornando').load('maquinas-retornando-limit.php');
		}
		refreshretornando();
	});

	function refreshretornando(){ // script para verificar qual a variavel e carregar a pagina de limit ou todas e carregar a coluna
		timeOutVarRetornando = setTimeout( function() {
			if(limitMR == '1'){
		$('#retornando').load('maquinas-retornando.php');
			}else{
		$('#retornando').load('maquinas-retornando-limit.php');
			}
			refreshretornando();
		}, 6000);
	}

	// ==================

	$(function() { //onload
   setEvent();
});

$(document).on('mousemove', function() { //mouse move
    if (timeout !== null) {
        clearTimeout(timeout); //clear no timer
    }
    setEvent(); //seta ele novamente para caso aja inatividade faça o evento
});

function setEvent(){
    timeout = setTimeout(function() {
        window.location.reload();
    },15 * 60 * 1000);
}

</script>
</head>
<?php if($_SESSION['notify'] == 1){	?>
	<body onload="not()">
		<?php $_SESSION['notify'] = 0; }else {	?>
			<body> <?php } ?>
				<div class="nav"><?php include_once ('menu-nav.php'); ?></div>
				<div class="page">
				<div class="titleCM"><h1>Controle de máquinas</h1></div>
					<div class="scroll-boxes">
						<div class="boxes">
							<div class="search-rela">
								<?php
								if($_SESSION['busca'] == 0){ ?>
									<a class="text-busca" href="" data-toggle="modal" data-target="#myModalBusca"><button class="btn-busca" type="button"><span class="fas fa-search"></span>&nbsp Busca avançada</button></a>
								<?php }else{ ?>
									<a class="text-busca" href="cancela-busca.php"><button class="btn-cancela-busca" type="button"><span class="fas fa-times"></span>&nbsp Cancelar busca</button></a>
								<?php } ?>
							</div>

							<?php if($areaUsuario == 'Manutenção'){ // se a area for manutenção ele muda o style  ?>
								<style>

								.search-rela{
									width: 100%;
									height: 40px;
									float: left;
									text-align: right;
									padding-right: 50px;
									margin-bottom: 10px;
								}
								.b2, .b3{
									margin-left: 105px;
								}
								.b1{
									width: 480px;
								}
								.b2{
									width: 300px;
								}
								.b3{
									width: 300px;
								}

								.boxes{
									width:1290px;
									height: 820px;
									margin-left: auto;
									margin-right: auto;
									background: #fff;
								}


								@media only screen and (max-width: 1330px){
									.boxes{
										width:1190px;
									}
									.b2, .b3{
										margin-left: 55px;
									}


								}
								@media only screen and (max-width: 1230px){
									.boxes{
										width:1090px;
									}
									.b2, .b3{
										margin-left: 5px;
									}

								}


								@media only screen and (max-width: 1130px){
									.boxes{
										margin-top: 0px;
									}
									.search-rela{
										text-align: left;
										padding-left: 50px;
										margin-top: 10px;
										margin-bottom: 5px;
									}
									.scroll-boxes{
										border: 1px solid #ebebeb;
										width:98%;
										height: 820px;
										margin-left: auto;
										margin-right: auto;
										overflow-x: scroll;
										overflow-y: hidden;
										background: #ebebeb;
										border-radius: 3px;
									}
									.scroll-boxes::-webkit-scrollbar {
										height: 15px;
									}

									.scroll-boxes::-webkit-scrollbar-thumb {
										-webkit-border-radius: 10px;
										border-radius: 3px;
										background: #CFCFCF;
										-webkit-box-shadow: inset 0 0 1px rgba(0,0,0,0.5);
										border:1px solid #9E9E9E;
									}


								}
								.dtr{
									margin-left: 55px;
								}
								</style>
							<?php }else {  ?>

								<style>
								.search-rela{
									width: 100%;
									height: 40px;
									float: left;
									text-align: right;
									padding-right: 50px;
									margin-top: 10px;
									margin-bottom: 5px;
								}
								.dtr{
									margin-left: 35px;
								}
								.b2, .b3, .b4{
									margin-left: 10px;
								}
								.b1{
									width: 480px;
								}
								.b2, .b4{
									width: 260px;
								}
								.b3{
									width: 260px;
								}

								.boxes{
									width:1290px;
									height: 820px;
									margin-left: auto;
									margin-right: auto;
									background: #fff;
								}



								@media only screen and (max-width: 1330px){
									.boxes{
										margin-top: 0px;
									}
									.search-rela{
										text-align: left;
										padding-left: 50px;
									}
									.scroll-boxes{
										border: 1px solid #ebebeb;
										width:98%;
										height: 820px;
										margin-left: auto;
										margin-right: auto;
										overflow-x: scroll;
										overflow-y: hidden;
										background: #ebebeb;
										border-radius: 3px;
									}
									.scroll-boxes::-webkit-scrollbar {
										height: 15px;
									}

									.scroll-boxes::-webkit-scrollbar-thumb {
										-webkit-border-radius: 10px;
										border-radius: 3px;
										background: #CFCFCF;
										-webkit-box-shadow: inset 0 0 1px rgba(0,0,0,0.5);
										border:1px solid #9E9E9E;
									}


								}
								</style>

							<?php	}	?>

							<div id="saida" class="box b1 scroll-box"></div> <!-- mostra a coluna de saida -->
							<div id="patio" class="box b2 scroll-box"></div> <!-- mostra a coluna de patio -->
							<div id="retornando" class="box b3 scroll-box"></div> <!-- mostra a coluna de retornando -->
							<?php if($areaUsuario == 'Manutenção'){ ?>
							<?php }else{ ?>
								<div id="locada" class="box b4 scroll-box"></div> <!-- mostra a coluna de locadas -->
							<?php	} ?>
						</div>
					</div>
				</div>
				<?php include('footer.php'); ?> <!-- faz include do footer-->
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
											<input class="text-form" type="text" autocomplete="off" placeholder="Apelido" name="apelidoBusca" id="apelidoBusca">
										</div>
										<div id="fabricanteBusca-div" class="control-form">
											<p>Fabricante:</p>
											<input class="text-form" type="text" autocomplete="off" placeholder="Fabricante" name="fabricanteBusca">
										</div>
									</div>
									<div class="form-bottom">
										<div class="control-form">
											<p>Status:</p>
											<select class="text-form-select st" id="statusBusca" name="statusBusca">
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
			</body>
			</html>
