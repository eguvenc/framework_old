
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

    <title>Üyelik Girişi</title>

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
        <h2 class="text-muted"><a href="/examples/">Üye Girişi</a></h2>
      </div>

      <div class="content description">

        <?php echo $this->flash->output() ?>

        <section>
        <?php
        if ($results = $this->form->getResults()) {
            foreach ($results->messages as $message) {
                echo $this->form->getMessages($message);
            }
        }
        ?>
        </section>

        <section>
            <form style="width: 300px;" role="form" action="/examples/membership/login/index" method="POST">
              
              <div class="form-group <?php echo $this->form->getErrorClass('email') ?>">
                <?php echo $this->form->getErrorLabel('email') ?>
                <input type="email" name="email" value="<?php echo $this->form->getValue('email') ?>" class="form-control" id="email" placeholder="Email">
              </div>

              <div class="form-group <?php echo $this->form->getErrorClass('password') ?>">
                <?php echo $this->form->getErrorLabel('password') ?>
                <input type="password" name="password" class="form-control" id="pwd" placeholder="Password">
              </div>

              <div>
                <div class="checkbox">
                    <label><input type="checkbox" name="rememberMe" value="1" <?php echo $this->form->setCheckbox('rememberMe', 1) ?> id="rememberMe"> Remember Me</label>
                </div>
              </div>
            <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </section>

        <hr />

        <section>
            <h4>$this->form->outputArray()</h4>
            <pre><?php echo strip_tags(print_r($this->form->outputArray(), true)); ?>
            </pre>

            <h4>$this->form->getError('email')</h4>
            <pre><?php echo $this->form->getError('email') ?></pre>

            <h4>$this->form->getValidationErrors()</h4>
            <pre><?php echo $this->form->getValidationErrors() ?></pre>
        </section>    

      </div>

      <!--
      <footer class="footer">

      </footer>
      -->

    </div> <!-- /container -->


  </body>
</html>