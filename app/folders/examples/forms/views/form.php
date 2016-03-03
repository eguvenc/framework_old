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

    <title>Form</title>

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
            <li role="presentation"><a href="/examples/forms">Formlar</a></li>
            <li role="presentation"><a href="/examples">Örnekler</a></li>
            <li role="presentation"><a href="#">Dökümentasyon</a></li>
            <li role="presentation"><a href="/tests">Testler</a></li>
          </ul>
        </nav>
        
        <h2 class="text-muted"><a href="/examples/">Form</a></h2>
      </div>

      <div class="content">

        <?php echo $this->form->getMessageString() ?>

         <form role="form" action="/examples/forms/form" method="POST">
          
          <div class="form-group <?php echo $this->form->getErrorClass('email') ?>">
            <?php echo $this->form->getErrorLabel('email') ?>
            <input type="text" name="email" value="<?php echo $this->form->getValue('email') ?>" class="form-control" id="email" placeholder="Email">
          </div>
          
          <div class="form-group <?php echo $this->form->getErrorClass('password') ?>">
            <?php echo $this->form->getErrorLabel('password') ?>
            <input type="password" name="password" class="form-control" id="pwd" placeholder="Password">
          </div>

          <div class="form-group <?php echo $this->form->getErrorClass('confirm_password') ?>">
            <?php echo $this->form->getErrorLabel('confirm_password') ?>
            <input type="password" name="confirm_password" class="form-control" id="pwd" placeholder="Confirm Password">
          </div>

          <label>What do you like to do ?</label>

          <div class="<?php echo $this->form->getErrorClass('hobbies[]') ?>">
            <div class="checkbox">
              <label><input type="checkbox" value="1" <?php echo $this->form->setCheckbox('hobbies[]', 1) ?>  name="hobbies[]" />Dancing</label>
              <label><input type="checkbox" value="2" <?php echo $this->form->setCheckbox('hobbies[]', 2) ?>  name="hobbies[]" />Biking</label>
              <label><input type="checkbox" value="3" <?php echo $this->form->setCheckbox('hobbies[]', 3) ?>  name="hobbies[]" />Yoga</label>
              <label><input type="checkbox" value="4" <?php echo $this->form->setCheckbox('hobbies[]', 4) ?> name="hobbies[]" />Fitness</label>
            </div>
          </div>

          <label>What are your favorite colors ?</label>

          <div class="<?php echo $this->form->getErrorClass('options[]') ?>">
              <div class="checkbox">
                <label><input type="checkbox" name="options[color][]" value="red" <?php echo $this->form->setCheckbox('options[]', 'red') ?> />Red</label>
                <label><input type="checkbox" name="options[color][]" value="blue" <?php echo $this->form->setCheckbox('options[]', 'blue') ?> />Blue</label>
                <label><input type="checkbox" name="options[color][]" value="green" <?php echo $this->form->setCheckbox('options[]', 'green') ?>  />Green</label>
              </div>
          </div>

          <div class="<?php echo $this->form->getErrorClass('agreement') ?>">
            <div class="checkbox">
                <label><input type="checkbox" name="agreement" value="1" <?php echo $this->form->setCheckbox('agreement', 1) ?> id="agreement"> I agree terms and conditions</label>
            </div>
          </div>

          <button type="submit" class="btn btn-default">Submit</button>
        </form>

        <hr />

        <section>
            <h4>$this->form->getOutputArray()</h4>
            <pre><?php echo strip_tags(print_r($this->form->getOutputArray(), true)); ?>
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