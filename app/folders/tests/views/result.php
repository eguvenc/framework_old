
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- <link rel="icon" href="favicon.ico"> -->

    <title>Sonuç</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/welcome.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation"><a href="/examples">Örnekler</a></li>
            <li role="presentation"><a href="#">Dökümentasyon</a></li>
          </ul>
        </nav>
        
        <?php
        $ancestor = $this->router->getAncestor();
  			$folder = $this->router->getFolder();
  			$class  = $this->router->getClass();
  			$method = $this->router->getMethod();
        ?>
        <h2 class="text-muted"><a href="/<?php echo $ancestor?>/<?php echo $folder ?>/<?php echo lcfirst($class) ?>"><?php echo lcfirst($class) ?>-><?php echo $method ?>()</a></h2>
      </div>

      <div class="tests">
        <?php echo $results ?>
        <?php 
        if (isset($dump)) {
            if (is_array($dump)) {
                echo "<pre>".print_r($dump, true)."</pre>";
            } else {
                echo "<pre>".var_export($dump, true)."</pre>";
            }
        }
        ?>
      </div>

      <!--
      <footer class="footer">

      </footer>
      -->

    </div> <!-- /container -->

  </body>
</html>