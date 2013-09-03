<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo html\css('welcome.css') ?>
        <title>Hello World</title>
    </head>

    <body>
        <header>
            <?php echo url\anchor('/', html\img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hello World</h1>

        <section>
            <p><?php echo vi\getVar('var') ?></p>
        </section> 
        
        <?php echo vi\views('footer', false) // get the common [views] from /mods/view(s) folder.  ?>
        
        <section>
            <p>&nbsp;</p>
        </section>
        
    </body>
</html>