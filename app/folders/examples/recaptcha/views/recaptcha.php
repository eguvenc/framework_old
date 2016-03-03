
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

    <?php echo $this->recaptcha->printJs() ?>

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
            <li role="presentation"><a href="/tests">Testler</a></li>
          </ul>
        </nav>
        
        <h2 class="text-muted"><a href="/examples/">ReCaptcha Form</a></h2>
      </div>

      <div class="content">

        <?php echo $this->form->getMessageString() ?>

         <form style="width: 300px;" role="form" action="/examples/recaptcha" method="POST">

              <div class="form-group <?php echo $this->form->getErrorClass('email') ?>">
                <?php echo $this->form->getErrorLabel('email') ?>
                <input type="email" name="email" value="<?php echo $this->form->getValue('email') ?>" class="form-control" id="email" placeholder="Email">
              </div>

              <div class="form-group <?php echo $this->form->getErrorClass($this->recaptcha->getInputName()) ?>">
                <?php echo $this->form->getErrorLabel($this->recaptcha->getInputName()) ?>
                <?php echo $this->recaptcha->printHtml() ?>
              </div>

              <button type="submit" class="btn btn-default">Submit</button>
        </form>

        <section>
            <h4>$this->form->getOutputArray()</h4>
            <pre><?php echo strip_tags(print_r($this->form->getOutputArray(), true)); ?>
            </pre>

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