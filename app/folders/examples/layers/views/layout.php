
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

    <title>Şemalar</title>

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
        <h2 class="text-muted"><a href="/examples/">Şemalar</a></h2>
      </div>

<pre>
class Layout extends Controller
{
    use \View\Base;

    public function index()
    {   
        $this->view->load('layout');
    }
}
</pre>

      <pre>echo $header</pre>

      <header>
        <pre><?php echo htmlentities($header) ?></pre>
      </header>

      <pre>echo $footer</pre>

      <footer class="footer" style="padding-top:0;border:none;">
        <pre><?php echo htmlentities($footer) ?></pre>
      </footer>


    </div> <!-- /container -->
  </body>
</html>