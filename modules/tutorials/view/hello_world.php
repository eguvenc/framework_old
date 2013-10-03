<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Hello World</title>
    </head>

    <body>
        <h1>Hello World</h1>

        <section>
            <p><?php echo getVar('var') ?></p>
        </section> 
            <?php echo views('footer', false) // get the common [views] from /modules/view(s) folder.  ?>
        <section>
            <p>&nbsp;</p>
        </section>
    </body>
</html>