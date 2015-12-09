<html>
<head>
<title>Error</title>
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
<?php
if(isset($header)) {
	echo '<h1>'.$header.'</h1>';
} else {
	echo '<h1>Error</h1>';
}
?>
<p><?php echo $error ?></p>
</div>
</body>
</html>