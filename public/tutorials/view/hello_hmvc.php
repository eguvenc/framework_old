<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            
            <?php echo $this->html->css('welcome.css') ?>

        <title>Hmvc Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hmvc Tutorial</h1>
 
<pre>$this->request->get('tutorials/hello_dummy/test/1/2/3'));
$this->request->get('tutorials/hello_dummy/test/4/5/6'));</pre>

        <p><?php echo $response_a ?></p>
        <p><?php echo $response_b ?></p>
        
        <?php 
            echo $footer;
        ?>
        <section>
            <p>&nbsp;</p>
        </section>
        
    </body>
    
</html>