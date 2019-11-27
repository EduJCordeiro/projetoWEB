<?php
//pagina de menu

include_once('conexao.php');

session_start();


if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))
{
	unset($_SESSION['usuario']);
	unset($_SESSION['senha']);
	header('location:login');
}

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");

while($consult= mysqli_fetch_array($consulta)){
	$areaUsuario      = $consult['area'];
	$idUsuario        = $consult['idUsuario'];
	$usuarioPrincipal = $consult['usuario'];
	$senhaPrincipal   = $consult['senha'];
	$idEmpresa        = $consult['idEmpresa'];
}

?>

<head>
	<meta charset="UTF-8">
	<link href="assets/css/menu-nav.css" type="text/css" rel="stylesheet">
	<link href="assets/css/submenu.css" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<script type="text/javascript">

	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});

	// Função para excluir cadastro
	function excluirUsuario(id){
		var myCallback = function(choice){
			if(choice){
				jQuery.ajax({
					type: "POST",
					url: "excluir-usuarios",
					data: {
						idUsuario: id,
					},
					success: function( data )
					{
						window.location.reload()
					}
				});
			}
		}
		notif_confirm(({
			'message': 'Confirmar a exclusão do usuário?',
			'textaccept': 'Sim',
			'textcancel': 'Não',
			'fullscreen': true,
			'callback': myCallback
		}))
	}

	function hidelModal() {
		$(".modal-backdrop").remove();
		$("#adcionarUsuario").modal('hide');
	}

</script>

</head>
<div class="nav">
	<?php if($activeMenu == 1){ ?>
		<div class="menu-top menu-top-active 	<?php  /* define a class do menu somente para um menu */  if($areaUsuario == "Manutenção"){ ?> menu-left-1 <?php } else { ?> menu-left <?php } ?> "><a class="link-div" href="relatorio"><span class="far fa-file-alt"></span>&nbsp Controle de máquinas</a></div>
	<?php } else{ ?>
		<div class="menu-top menu-left"><a class="link-div" href="controle-maquinas"><span class="far fa-file-alt"></span>&nbsp Controle de máquinas</a></div>
	<?php }
	/* Nao mostrar o menu cadastro para manutencao */ if($areaUsuario == "Manutenção"){ }else{?>
	<?php  if($activeMenu == 2){?>
		<div class="menu-top menu-top-active"><a class="link-div" href="maquinas"><span class="fas fa-clipboard"></span>&nbsp Cadastros</a></div>
	<?php } else{ ?>
		<div class="menu-top"><a class="link-div" href="maquinas"><span class="fas fa-clipboard"></span>&nbsp Cadastros</a></div>
	<?php }  }?>
	<div class="menu-cfg "><ul class="menu clearfix">
		<li>
			<div class="cfgs">
				<a class="link-menu"><span class="far fa-user-circle left spin-cfg"></span><div class="infosEN"><p class="empresa"><?php echo $areaUsuario ?></p><span class="nome"><?php echo $usuarioPrincipal ?></span></div><span class="fas fa-caret-down right"></span></a>
			</div>

			<ul class="sub-menu clearfix">
				<?php if($areaUsuario == "Admin"){ ?>
					<li><a class="link-sub" data-toggle="modal" data-target="#adcionarUsuario" href=""><span class="fas fa-user-cog"></span>&nbsp Usuários</a></li>
				<?php } ?>
				<li><a class="link-sub" data-toggle="modal" data-target="#configuracoes" href=""><span class="fas fa-cog"></span>&nbsp Configurações</a></li>
				<li><a class="link-sub" href="sair"><span class="	fas fa-sign-out-alt"></span>&nbsp Sair</a></li>
			</ul>
		</li>
	</div>
</div>
<?php if($activeMenu == 2){ ?>
	<div class="submenu-new">
		<?php if($activeSub == 1){ ?>
			<div class="sub-active submenu-top submenu-left-2"><a class="sublink-div" href="maquinas"><span class="fas fa-warehouse"></span>&nbsp Máquinas</a></div>
		<?php } else{ ?>
			<div class="submenu-top submenu-left-2"><a class="sublink-div" href="maquinas"><span class="fas fa-warehouse"></span>&nbsp Máquinas</a></div>
		<?php } if($activeSub == 2){?>
			<div class="sub-active  submenu-top"><a class="sublink-div-active" href="transportadora-status"><span class="fas fa-truck"></span>&nbsp Transportadora e Status</a></div>
		<?php } else{ ?>
			<div class="submenu-top"><a class="sublink-div-active" href="transportadora-status"><span class="fas fa-truck"></span>&nbsp Transportadora e Status</a></div>
		<?php }?>
	</div>
<?php } ?>
<!-- MODAL PARA ADICIONAR USUARIOS-->
<div class="container-modal">
	<div class="topppp modal fade" id="adcionarUsuario" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-add modal-scroll-media">
				<div class="modal-header">
					<h4 class="modal-title-modelo">Adicionar usuários</h4>
				</div>
				<form name="adicionar-usuarios" method="post" action="adicionar-usuarios">
					<input type="hidden" value="<?php echo $idUsuario ?>" name="idUsuario">
					<div class="modal-body">
						<div class="input-modal-modelo tm">
							<p>Usuário:</p>
							<input class="form-control-modelo" type="text" autocomplete="off" placeholder="Usuário *" name="usuarioAdiciona" required>
						</div>
						<div class="input-modal-modelo-b tm">
							<p>Senha:</p>
							<input class="form-control-modelo" type="password" autocomplete="new-password" placeholder="Senha *" name="senhaAdiciona" required>
						</div>
						<div class="input-modal-modelo-b tm">
							<p>Área:</p>
							<select class="form-control-modelo" name="areaAdiciona">
								<option value="Comercial">
									Comercial
								</option>
								<option value="Manutenção">
									Manutenção
								</option>

							</select>
						</div>
					</div>

					<div class="modal-footer modal-footer-espaco">
						<button type="submit" class="btn btn-primary">Adicionar</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
					</div>
				</form>


				<div class="modal-users">
					<div class="users">
						<p class="title-users"> Usuários cadastrados </p>
						<div class="user-title">
							<div class="user-ttl user-info">
								<p class="txt-info">Usuário</p>
							</div>
							<div class="user-ttl user-info">
								<p class="txt-info">Área</p>
							</div>
							<div class="user-ttl user-icon">
							</div>
							<div class="user-ttl user-icon border-user">
							</div>
						</div>

						<div class="scroll-box-user">
							<div class="box-user">

								<?php $resultado = $mysqli->query("SELECT * FROM login WHERE idEmpresa = '$idEmpresa'");

								while($reg_cadastro= mysqli_fetch_array($resultado)){

									$codigoUsuario    = $reg_cadastro['idUsuario'];
									$usuario          = $reg_cadastro['usuario'];
									$senha						= $reg_cadastro['senha'];
									$area             = $reg_cadastro['area'];

									?>

									<div class="user">
										<div class="user-info user-ib">
											<p class="txt-info"><?php echo $usuario ?></p>
										</div>
										<div class="user-info user-ib">
											<p class="txt-info"><?php echo $area ?></p>
										</div>
										<a href="" data-toggle="modal" data-target="#myModalEditarUsuarios<?php echo $codigoUsuario ?>" onclick="hidelModal();">
											<div class="user-icon user-ib user-edit" data-toggle="tooltip" data-placement="top" title="Editar">
												<span class="fas fa-pencil-alt"></span>
											</div>
										</a>
										<div class="user-icon user-ib user-trash border-user" data-dismiss="modal" onclick="excluirUsuario(<?php echo $codigoUsuario ?>)" data-toggle="tooltip" data-placement="top" title="Excluir">
											<span class="far fa-trash-alt"></span>
										</div>
									</div>

								<?php  } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$resultado1 = $mysqli->query("SELECT * FROM login WHERE idEmpresa = '$idEmpresa'");
while($reg_cadastro= mysqli_fetch_array($resultado1)){

	$codigoUsuario1    = $reg_cadastro['idUsuario'];
	$usuario1          = $reg_cadastro['usuario'];
	$senha1						= $reg_cadastro['senha'];
	$area1             = $reg_cadastro['area'];

	?>
	<!-- MODAL PARA EDITAR USUARIOS -->
	<div class="modal fade" id="myModalEditarUsuarios<?php echo $codigoUsuario1 ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-tr">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"></button>
					<h4 class="modal-title title-modal" style="text-align: center">Editar usuário</h4>
				</div>
				<form name="editar-usuarios" method="post" action="editar-usuarios">
					<input type="hidden" value="<?php echo $codigoUsuario1 ?>" name="idUsuario">
					<div class="modal-body">
						<div class="input-modal-modelo tm">
							<p>Usuário:</p>
							<input class="form-control-modelo" type="text" value="<?php echo $usuario1 ?>" autocomplete="off" placeholder="Usuário *" name="usuarioEdita" required>
						</div>
						<div class="input-modal-modelo-b tm">
							<p>Senha:</p>
							<input class="form-control-modelo" type="password" value="<?php echo $senha1 ?>" autocomplete="new-password" placeholder="Senha *" name="senhaEdita" required>
						</div>
						<div class="input-modal-modelo-b tm">
							<p>Área:</p>
							<select class="form-control-modelo" name="areaEdita">
								<?php if($area1 == "Comercial"){?>
									<option value="Comercial">Comercial</option>
									<option value="Manutenção">Manutenção</option>
								<?php }else{ ?>
									<option value="Manutenção">Manutenção</option>
									<option value="Comercial">Comercial</option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="modal-footer modal-footer-espaco">
						<button type="submit" class="btn btn-primary">Editar</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php } ?>

<!-- MODAL PARA ALTERAR DADOS DE LOGIN-->
<div class="container-modal">
	<div class="modal fade" id="configuracoes" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-modelo">
				<div class="modal-header">
					<h4 class="modal-title-modelo">Alterar informações</h4>
				</div>
				<form name="form-alterar-dados" method="post" action="form-alterar-dados">
					<input type="hidden" value="<?php echo $idUsuario ?>" name="idUsuario">
					<div class="modal-body">
						<div class="input-modal-modelo tm">
							<p>Usuário:</p>
							<input class="form-control-modelo" type="text" autocomplete="off" placeholder="Usuário *" value="<?php echo $usuarioPrincipal ?>" name="usuarioAltera" required>
						</div>
						<div class="input-modal-modelo-b tm">
							<p>Senha:</p>
							<input class="form-control-modelo" type="password" autocomplete="new-password" placeholder="Senha *" value="<?php echo $senhaPrincipal ?>" name="senhaAltera" required>
						</div>

					</div>
					<div class="modal-footer modal-footer-espaco-alt">
						<button type="submit" class="btn btn-primary">Alterar</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
