<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Welcome to Obullo !</title>
<?php echo css('welcome.css') ?>
</head>
<body>
    
<h1>Welcome to Obullo !</h1> 

<div id="main">
    <div class="fieldset"> 
          <div class="fieldbox"> 
               <h3 class="legend">modules / welcome</h3> 
               <div class="inner">
               
                    <p>If you would like to edit <b>Welcome Module</b> you'll find files located at</p>
                    
                    <?php echo br(); ?>
                    
                    <code><b>modules / </b><samp>views</samp> / footer.php <kbd>( View Footer )</kbd></code> 
                    
                    <code><b>modules / welcome / </b><samp>views</samp> / view_welcome.php <kbd>( View )</kbd></code>
                    
                    <code><b>modules / welcome / </b><samp>controllers</samp> / welcome.php <kbd>( Controller )</kbd></code>
                   
                    <?php echo br();  ?>
                    
                    <div class="test"><?php echo anchor('/welcome/hmvc', 'Try to New HMVC Feature'); ?></div>
                    <div class="test"><?php echo anchor('/welcome/start', 'Try to New Validation Model'); ?></div>
                    <div class="test"><?php echo anchor('/welcome/task', 'Try to New Task Feature'); ?></div>
                    <div class="test"><?php echo anchor('/backend', 'Try to New Sub Modules'); ?></div>
                    
                    <?php echo br(2);  ?>
                    
                    <p><b>Note:</b> If you are new to Obullo, you should start by 
                reading the <a href="http://obullo.com/user_guide/en/1.0.1/index.html">User Guide</a>.</p>
           
              </div>
          </div> 
    </div> 

<?php echo view('../footer') ?>

</div> 

<?php echo br() ?>

</body>
</html>
