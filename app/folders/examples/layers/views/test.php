
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

    <title>Arayüz Katmanları</title>

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
        <?php echo $this->view->get('examples::navigation') ?>
        <h2 class="text-muted"><a href="/examples/">Arayüz Katmanları</a></h2>
      </div>

      <div class="content">
    <pre>
$a = $this->layer->get('examples/layers/dummy/index/1/2/3');
$b = $this->layer->get('welcome/dummy/index/4/5/6');
$c = $this->layer->get('examples/layers/dummy/index/7/8/9');</pre>

            <section><p>&nbsp;</p></section>

            <section>
                <?php echo $a ?>
                <?php echo $b ?>
                <?php echo $c ?>
            </section>


      </div>

      <!--
      <footer class="footer">

      </footer>
      -->
      
    </div> <!-- /container -->

  </body>
</html>
