<?php

// pagina de login

  include('conexao.php');

  // Script para verificar credenciais do login

  $erro = array();

  if(!isset($_POST['usuario'])){
    $_POST['usuario'] = '';
  }
  if(!isset($_POST['senha'])){
    $_POST['senha'] = '';
  }

  $usuario = $_POST['usuario'];
  $password = $_POST['senha'];

  $usuario = preg_replace('/[^[:alnum:]_]/', '',$usuario);

  if(isset($_POST['enviar'])){

  $sql_login = $mysqli->query("SELECT * FROM login WHERE Binary usuario = '$usuario' AND Binary senha = '$password'");
  $entra_login = mysqli_num_rows($sql_login);

      if($entra_login > 0){
        // session_start inicia a sessão
        session_start();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['senha'] = $password;

        $_SESSION['busca'] = 0;
        $_SESSION['modelo'] = '';
        $_SESSION['fabricante'] = '';
        $_SESSION['apelido'] = '';
        $_SESSION['patrimonio'] = '';

        $ip = $_SERVER['REMOTE_ADDR'];

        $horaAtual = date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil


        header('location:controle-maquinas');

      }else{
        ?>
          <script>
          // Função para mostrar um alert na tela
          window.onload = function not1(){
                notif({
              msg: "Dados incorretos!",
              type: "error",
              bgcolor: "#FF5A5A",
              color: "#FFF"
            });
          }
          </script>
          <?php
        }
    }

?>
<html lang="pt-br">
 <head>
   <title>Login</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" href="http://www.maqbusca.com.br/wp-content/themes/maqbusca/favicon.ico" type="image/x-icon" />
    <link href="assets/css/login.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="notifIt/js/notifIt.js"></script>
    <link rel="stylesheet" type="text/css" href="notifIt/css/notifIt.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="assets/bootstrap/js/bootstrap.js"></script>
  </head>
  <body>
    <div class="geral">
      <div class="logo">
        <img src="assets/images/login.png" alt="Logo" height="60px" width="auto">
      </div>
      <div class="texto">
        <h4>Login</h4>
      </div>
      <div class="login">
        <form method="POST" action="">
          <div class="control-form">
            <p>Usuário:</p>
            <input type="text" autocomplete="off" name="usuario" id="usuario" required />
          </div>
          <div class="control-form control-bottom">
            <p>Senha:</p>
            <input type="password" name="senha" id="senha" required />
          </div>
              <div class="bto">
          <button id="entrar" type="submit" class="btn-senha" name="enviar" value="enviar">Entrar</button>
              </div>
        </form>
      </div>
    </div>
  </body>
</html>
