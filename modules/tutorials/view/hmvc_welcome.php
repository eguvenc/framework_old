<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo Html::css('welcome.css') ?>
        <title>Hmvc Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo Url::anchor('/', Html::img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hmvc Tutorial</h1>
 
<pre>$response_a = Request::get('tutorials/hmvc_welcome/test/1/2/3');
$response_b = Request::get('tutorials/hmvc_welcome/test/4/5/6');

echo echo $response_a;
echo echo $response_b;</pre>

        <p><?php echo $response_a ?></p>
        <p><?php echo $response_b ?></p>
        
        <?php 
            echo tpl('footer', false) 
        ?>
        <section>
            <p>&nbsp;</p>
        </section>
        
    </body>
    
</html>