<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo html\css('welcome.css') ?>
        <title>Obullo</title>
    </head>
    <body>
        <header>
            <?php echo url\anchor('/', html\img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        <h1>Welcome to Obullo !</h1>
        <p>
            Congratulations! Your Obullo is running. If this is your first time using Obullo, start with this <?php echo url\anchor('#tutorials', '"Hello World" Tutorial') ?>.
        </p>

        <section>
            <h2>Get Started</h2>
            <ol>
                <li>Search and find packages for your Obullo</li>
                <li>Update your packages using <strong>package.json</strong> file</li>
                <li>Run <code>obm update</code> from your console, now your packages always up to date.</li>
            </ol>
        </section>

        <section>
            <h2>Update your package.json</h2>
            <p>
                If a new packages available, the package manager <strong>( Obm )</strong> will upgrage it using your package.json. If you need a stable a version remove asterisk ( * ) and set the package version to a specific number. ( e.g. auth: "2.0" )
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
                <li><?php echo url\anchor('/tutorials/hello_world', 'Hello World') ?></li>
                <li><?php echo url\anchor('/tutorials/form', 'Odm Tutorial') ?></li>
                <li><?php echo url\anchor('/tutorials/task', 'Task Tutorial') ?></li>
                <li><?php echo url\anchor('/tutorials/hmvc_welcome', 'Hmvc Tutorial') ?></li>
            </ol>
        </section>

        <?php echo vi\views('footer', false) ?>
        
        <section>
            <p>&nbsp;</p>
        </section>
        
    </body>
</html>