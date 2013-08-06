<?php 
namespace Ob;
new html\start();
new url\start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Welcome to Obullo !</title>
<meta charset="utf-8"></meta>
<?php echo html\css('welcome.css') ?>
</head>
<body>
    
<h1>Welcome to Obullo !</h1> 

<div id="main">
    <div class="fieldset"> 
          <div class="fieldbox"> 
               <h3 class="legend">modules / welcome</h3> 
               <div class="inner">
               
                    <p>If you would like to edit <b>Welcome Module</b> you'll find files located at</p>
                    <br />
                    
                    <code><b>modules / </b><samp>views</samp> / footer.php <kbd>( Footer )</kbd></code> 
                    
                    <code><b>modules / welcome / </b><samp>views</samp> / welcome.php <kbd>( View )</kbd></code>
                    
                    <code><b>modules / welcome / </b><samp>controllers</samp> / welcome.php <kbd>( Controller )</kbd></code>
                   
                    <br />
                    <div class="test"><?php echo url\anchor('/welcome/hmvc', 'Try to New HMVC Feature'); ?></div>
                    <div class="test"><?php echo url\anchor('/welcome/start', 'Try to New Odm'); ?></div>
                    <div class="test"><?php echo url\anchor('/welcome/task', 'Try to New Task Feature'); ?></div>
                    <br />
                    
                    <p><b>Note:</b> If you are new to Obullo, you should start by 
                reading the <a href="http://obullo.com/user_guide/en/1.0.1/index.html">User Guide</a>.</p>
           
              </div>
          </div> 
    </div> 

<?php echo vi\views('footer') ?>

</div> 

<br />

</body>
</html>
