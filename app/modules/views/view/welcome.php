
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

    <title>Hoşgeldiniz</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/jumbotron-narrow.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <div class="welcome">

          <div class="header clearfix text-center">
            <p><?php echo $this->url->anchor('/', '<img src="'.$this->url->asset('images/logo.png').'">') ?></p>

            <p>PHP 5.4 ve üzeri sürümler için php çatısı. Kolay ve hızlı yazılım geliştirme.</p>
          </div>

        <p class="lead">Hoşgeldiniz, eğer Obullo çatısını kullanmaya yeni başlıyorsanız aşağıdaki bağlantıyı takip edin.</p>
        <p><a class="btn btn-lg btn-default" href="/examples/" role="button">Başlarken</a></p>

      </div>


      <footer class="footer">
        <p>&copy; 2016 Obullo.</p>
      </footer>

    </div> <!-- /container -->

  </body>
</html>


