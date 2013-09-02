<html>
<head>
<title>404 Page Not Found</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php 
// Important: Don't change the 404 page css names (body, content, h1) 
// Some browsers does not support different css names.        
?>

<style type="text/css">
body {
background-color:    #fff;
margin:              0px;
font-size:           14px;
font-family :        Arial,Verdana, Sans-serif;
color:               #666;
}

#content  {
border:             none;
background-color:   #fff;
padding:            20px 20px 12px 20px;
}

h1 {
font-weight:        bold;
font-size:          20px;
color:              #333;
margin:             0 0 8px 0;
}
</style>

</head>
<body>
<div id="content">
    <h1><?php echo $heading ?></h1> The url <?php echo $message ?> you requested was not found.
</div>
</body>
</html>