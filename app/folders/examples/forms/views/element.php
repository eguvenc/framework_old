
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

    <title>Form Element</title>

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
          </ul>
        </nav>
        
        <h2 class="text-muted"><a href="/examples/">Form Element</a></h2>
      </div>

      <div class="content">

        <?php echo $this->form->getMessageString() ?>
        
        <?php $element = $this->form->getElement() ?>

          <?php echo $element->form('/examples/forms/element', ' method="POST" role="form" ') ?>

          <div class="form-group <?php echo $this->form->getErrorClass('name') ?>">
            <?php
                echo $this->form->getErrorLabel('name');
                echo $element->input('name', $this->form->getValue('name'), ' class="form-control" id="name" placeholder="Name" ');
            ?>
          </div>

          <div class="form-group <?php echo $this->form->getErrorClass('email') ?>">
            <?php
              echo $this->form->getErrorLabel('email');
              echo $element->input('email', $this->form->getValue('email'), ' class="form-control" id="email" placeholder="Email" ');
            ?>
          </div>

          <div class="form-group <?php echo $this->form->getErrorClass('message') ?>">
            <?php
              echo $this->form->getErrorLabel('message');
              $data = array(
                  'name'      => 'message',
                  'id'        => 'message',
                  'value'     => '',
                  'maxlength' => '800',
                  'rows'      => '8',
                  'cols'      => '50',
                  'class'     => 'form-control',
                  'placeholder' => 'Your Message',
              );
              echo $element->textarea($data);
            ?>
          </div>

          <div class="<?php echo $this->form->getErrorClass('subscribe') ?>">
            <div class="checkbox">
              <label for="subscribe"><?php echo $element->checkbox('subscribe', 1, $this->form->setCheckbox('subscribe', 1), ' id="subscribe" ')?>Subscribe to newsletter</label>
            </div>
          </div>

        <div class="form-group <?php echo $this->form->getErrorClass('communicate') ?>">
          <p>How we communicate with you ?</p>
          <div class="radio">
          <label for="communicate1"><?php echo $element->radio('communicate', 'email', $this->form->setRadio('communicate', 'email'), ' id="communicate1" ') ?>&nbsp;By Email</label>
          <label for="communicate2"><?php echo $element->radio('communicate', 'phone', $this->form->setRadio('communicate', 'phone'), ' id="communicate2" '); ?>&nbsp;By Phone</label>
          </div>
        </div>

        <div class="form-group <?php echo $this->form->getErrorClass('hear') ?>">
          <p>Where did you hear about us ?</p>
          <?php
            echo $this->form->getErrorLabel('hear');
            $options = array(
                '' => '',
                'newspapers'  => 'Newspapers',
                'google'  => 'Google',
                'tv'  => 'Tv',
                'radio'  => 'Radio',
                'friend'  => 'A friend',
            );
            echo $element->dropdown('hear', $options, $this->form->getValue('hear'), ' class="form-control" ');
          ?>
        </div>

        <?php echo $element->submit('submitForm', "Submit", ' class="btn btn-default" '); ?>
        <?php echo $element->formClose() ?>

        <hr />

        <section>
            <h4>$this->form->getOutputArray()</h4>
            <pre><?php echo strip_tags(print_r($this->form->getOutputArray(), true)); ?>
            </pre>

            <h4>$this->form->getError('name')</h4>
            <pre><?php echo $this->form->getError('name') ?></pre>

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