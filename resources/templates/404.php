<?php
if (defined('STDIN')) {
	echo Obullo\Cli\Console::fail("404 Page Not Found");
	return;
}
?>
<html>
<head>
<title>404 Page Not Found</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php 
// Important: Don't edit the css tags (body, content, h1) 
// Some browsers doesn't support different css namings.        
?>
<style type="text/css">
body {
background-color:   #fff;
margin:             0px;
font-size:          14px;
font-family :       Arial,Verdana, Sans-serif;
color:              #666;
}

#content  {
border:             none;
background-color:   #fff;
padding:            20px 20px 12px 20px;
}

#content p {
font-size:          18px;
}

h1 {
color:              #BF342A;
margin-top:         0px;
font-weight:        normal;
}
</style>
</head>
<body>

<div id="content">
    <h1>Page Not Found</h1>
    <p>The page you are looking for could not be found.</p>
</div>

</body>
</html>