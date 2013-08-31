<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo html\css('welcome.css') ?>
        <title>Obullo</title>
    </head>
    <body>
        <header>
            <a href="http://www.obullo.com/"><?php echo html\img('logo.png', ' alt="Obullo" ') ?></a>
        </header>
        <h1>Welcome to Obullo!</h1>
        <p>
            Congratulations! Your Obullo is running. If this is your first time using Obullo, start with this <?php echo url\anchor('#tutorials', '"Hello World" Tutorial') ?>.
        </p>

        <section>
            <h2>Get Started</h2>
            <ol>
                <li>The app code is in <code>index.php</code></li>
                <li>Add / Update your packages using package.json file</li>
                <li>Run <code>obm update</code> from your console</li>
            </ol>
        </section>

        <section>
            <h2>Update your package.json</h2>
            <p>
                If a new packages available, the package manager ( Obm ) will upgrage it using your package.json. If you need a stable a version remove asterisk ( * ) and set the package version to a specific number. ( e.g. auth: "2.0" )
<pre>
{
"dependencies": {
"obullo": "*",
"auth" : "*"
}
}</pre>                
            </p>

            <h2>Submit your packages</h2>
            <p>
                To find more obullo packages please go <?php echo url\anchor('http://obm.obullo.com', 'Obm Packages') ?>. if you want to add your package click here <?php echo url\anchor('http://obm.obullo.com/submit-package', 'Submit Package') ?>.
            </p>
        </section>
        
        <a name="tutorials"></a>
        <section>
            <h2>Tutorials</h2>
            <ol>
                <li><?php echo url\anchor('/welcome/task', 'Hello World Tutorial') ?></li>
                <li><?php echo url\anchor('/tutorials/form', 'Odm Tutorial') ?></li>
                <li><?php echo url\anchor('/welcome/task', 'Task Tutorial') ?></li>
                <li><?php echo url\anchor('/welcome/hmvc', 'Hmvc Tutorial') ?></li>
            </ol>
        </section>

        <?php echo vi\views('footer', false) ?>
        
        <section>
                <input type="submit" name="button1" value="test1"/>
                <input type="submit" name="button2" value="test2"/>
        </section>
        
    </body>
</html>