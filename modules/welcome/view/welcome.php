<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo Html::css('welcome.css') ?>
        <title>Obullo</title>
    </head>
    <body>
        <header>
            <?php echo Url::anchor('/', Html::img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        <h1>Welcome to Obullo !</h1>
        <p>
            Congratulations! Your Obullo is running. If this is your first time using Obullo, start with this <?php echo Url::anchor('#tutorials', '"Hello World" Tutorial') ?>.
        </p>

        <section>
            <h2>Get Started</h2>
            <ol>
                <li>Go to <?php echo Url::anchor('http://obm.obullo.com', 'http://obm.obullo.com') ?> and find packages for your Obullo</li>
                <li>Update your packages using your <strong>package.json</strong> file</li>
                <li>Run <code>obm update</code> from your console to make your packages up to date.</li>
            </ol>
        </section>

        <section>
            <h2>Update your package.json</h2>
            <p>
                If new packages available, the package manager <strong>( Obm )</strong> will upgrade packages using your package.json. If you need a previous stable version remove the asterisk ( * ) and set it to a specific number. ( e.g. auth: "0.0.3" )
<pre>
{
    "dependencies": {
        "obullo": "*",
        "acl" : "*"
        "auth" : "0.0.3"
    }
}</pre>                
            </p>

            <h2>Submit your packages</h2>
            <p>
                If you want to add your package please click here <?php echo Url::anchor('http://obullo.com/submit-package', 'Submit Package') ?>.
            </p>
        </section>
        
        <a name="tutorials"></a>
        <section>
            <h2>Tutorials</h2>
            <ol>
                <li><?php echo Url::anchor('/tutorials/hello_world', 'Hello World') ?></li>
                <li><?php echo Url::anchor('/tutorials/form_html', 'Odm Tutorial') ?></li>
                <li><?php echo Url::anchor('/tutorials/task_welcome', 'Task Tutorial') ?></li>
                <li><?php echo Url::anchor('/tutorials/hmvc_welcome', 'Hmvc Tutorial') ?></li>
            </ol>
        </section>

        <?php 
            echo tpl('footer', false) 
        ?>
        
        <section>
            <p>&nbsp;</p>
        </section>
        
    </body>
</html>