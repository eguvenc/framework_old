<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo html\css('welcome.css') ?>
        <title>Hmvc Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo url\anchor('/', html\img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hmvc Tutorial</h1>
 
<pre>new request\start();

$response_a = request\get('tutorials/hmvc_welcome/test/1/2/3');
$response_b = request\get('tutorials/hmvc_welcome/test/4/5/6');

echo vi\getVar('response_a');
echo vi\getVar('response_b');</pre>

        <p><?php echo vi\getVar('response_a') ?></p>
        <p><?php echo vi\getVar('response_b') ?></p>
        
        <?php echo vi\views('footer', false) // get the common [views] from /mods/view(s) folder.  ?>
        
        <section>
            <p>&nbsp;</p>
        </section>
        
    </body>
    
</html>