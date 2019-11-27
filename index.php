<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>

        <?php
            $url = (isset($_GET['url'])) ? $_GET['url']:'home.php';
            $url = array_filter(explode('/',$url));

            $file = $url[0].'.php';

            if(is_file($file)){
                include $file;
            }else{
                include 'index.html';
            }

            // script para tirar o .php da url e redirecionar para a pagina inicial
        ?>

    </body>
</html>
