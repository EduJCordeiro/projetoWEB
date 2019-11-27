
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mini cadastro de Clientes</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('includes/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?= base_url('includes/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('includes/js/jquery.forms/jquery.forms.js') ?>"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    	.container {
    		margin-top: 50px;
    	}
    </style>
    <script>
    
    	/*
    	 * Função que carrega após o DOM estiver carregado.
    	 * Como estou usando o ajaxForm no formulário, é aqui que eu o configuro.
    	 * Basicamente somente digo qual função será chamada quando os dados forem postados com sucesso.
    	 * Se o retorno for igual a 1, então somente recarrego a janela.
    	 */
    	$(function(){
    		$('#formulario_clientes').ajaxForm({
    			success: function(data) {
    				if (data == 1) {
    					
    					//se for sucesso, simplesmente recarrego a página. Aqui você pode usar sua imaginação.
    					document.location.href = document.location.href;
				    	
    				}
    			}
    		});
    	});
    
    	//Aqui eu seto uma variável javascript com o base_url do CodeIgniter, para usar nas funções do post.
    	var base_url = "<?= base_url() ?>";
    	
	    /*
	     *	Esta função serve para preencher os campos do cliente na janela flutuante
	     * usando jSon.  
	     */
    	function carregaDadosClienteJSon(id_cliente){
    		$.post(base_url+'/index.php/clientes/dados_cliente', {
    			id_cliente: id_cliente
    		}, function (data){
    			$('#nome').val(data.nome);
    			$('#email').val(data.email);
    			$('#id_cliente').val(data.id_cliente);//aqui eu seto a o input hidden com o id do cliente, para que a edição funcione. Em cada tela aberta, eu seto o id do cliente. 
    		}, 'json');
    	}
    
    	function janelaEditarCliente(id_cliente){
    		
    		//antes de abrir a janela, preciso carregar os dados do cliente e preencher os campos dentro do modal
    		carregaDadosClienteJSon(id_cliente);
    		
	    	$('#modalEditarCliente').modal('show');
    	}
    	
    </script>
  </head>

  <body>

    <div class="container">

		<div class="panel panel-primary">
		  <div class="panel-heading">
		    <h3 class="panel-title">Meus Clientes Cadastrados</h3>
		  </div>
		  <div class="panel-body">
		    <table class="table">
		    	<tr>
		    		<th>Nome</th>
		    		<th>E-mail</th>
		    		<th>Ação</th>
		    	</tr>
		    	<? foreach ($clientes -> result() as $cliente): ?>
					<tr>
						<td><?= $cliente->nome ?></td>
						<td><?= $cliente->email ?></td>
						<td><a href="javascript:;" onclick="janelaEditarCliente(<?= $cliente->id ?>)">Editar</td>
					</tr>
				<? endforeach; ?>
		  </div>
		</div>		
      
    </div><!-- /.container -->

    
	<div class="modal fade bs-example-modal-lg" id="modalEditarCliente" >
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
	        <h4 class="modal-title">Editar Cliente</h4>
	      </div>
	      <div class="modal-body">
	      	
			<form role="form" method="post" action="<?= base_url('index.php/clientes/salvar')?>" id="formulario_clientes">
			  <div class="form-group">
			    <label for="nome">Nome</label>
			    <input type="text" class="form-control" id="nome" name="nome">
			  </div>
			  <div class="form-group">
			    <label for="email">E-mail</label>
			    <input type="email" class="form-control" id="email" name="email">
			  </div>
			  <input type="hidden" name="id_cliente" id="id_cliente" value="" />
			</form>	    
			    
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="button" class="btn btn-primary" onclick="$('#formulario_clientes').submit()">Salvar Alterações</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->  
	  
  </body>
</html>
