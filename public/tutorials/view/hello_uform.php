<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo Html::css('welcome.css') ?>
        <title>Odm Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo Url::anchor('/', Html::img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Odm Tutorial</h1>
        <h2><?php echo Url::anchor('tutorials/hello_ajax', 'Ajax Tutorial') ?></h2>

        <section>
            <?php
                if(Get::post('dopost'))
                {
                    echo $this->user->messages('errorMessage');
                }
            ?>
        </section>

        <section>
            
        </section>
        
        <section>

            <? echo $uform   ?>   

        </section> 
        
        <section>
            <p>Total memory usage <?php echo round(memory_get_usage()/1024/1024, 2).' MB' ?></p>
        </section>
    </body>
    
</html>