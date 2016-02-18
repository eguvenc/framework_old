
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

    <title>Captcha Form</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/welcome.css" rel="stylesheet">

    <script type="text/javascript">
    function refreshCaptcha(myform){
        var captcha = myform.captcha_image;
        if (captcha) {
            myform.captcha_image.src="/captcha/index/'" + Math.random();
            myform.captcha_answer.value = '';
        }
    }
    </script>
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
            <li role="presentation"><a href="/examples/forms">Formlar</a></li>
            <li role="presentation"><a href="/examples">Örnekler</a></li>
            <li role="presentation"><a href="#">Dökümentasyon</a></li>
          </ul>
        </nav>
        
        <h2 class="text-muted"><a href="/examples/">Captcha Form</a></h2>
      </div>

      <div class="content">

        <?php echo $this->form->getMessages() ?>

         <form style="width: 300px;" role="form" action="/examples/captcha" method="POST">

              <div class="form-group <?php echo $this->form->getErrorClass('email') ?>">
                <?php echo $this->form->getErrorLabel('email') ?>
                <input type="email" name="email" value="<?php echo $this->form->getValue('email') ?>" class="form-control" id="email" placeholder="Email">
              </div>

              <div class="form-group <?php echo $this->form->getErrorClass('captcha_answer') ?>">
                <?php echo $this->form->getErrorLabel('captcha_answer') ?>
                <?php echo $this->captcha->printHtml() ?>
                <?php echo $this->captcha->printRefreshButton() ?>
              </div>

              <button type="submit" class="btn btn-default">Submit</button>
        </form>

        <section>
            <h4>$this->form->outputArray()</h4>
            <pre><?php echo strip_tags(print_r($this->form->outputArray(), true)); ?>
            </pre>

            <h4>$this->form->getError('captcha_answer')</h4>
            <pre><?php echo $this->form->getError('captcha_answer') ?></pre>

            <h4>$this->form->getValue('captcha_answer')</h4>
            <pre><?php echo $this->form->getValue('captcha_answer') ?></pre>
        </section> 

      </div>

      <!--
      <footer class="footer">

      </footer>
      -->

    </div> <!-- /container -->

  </body>
</html>