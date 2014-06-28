<html>
	<head>
		<title><?php echo $title ?></title>

		<?php echo $this->html->css('style.css') ?>
		<meta charset="utf-8">
	</head>

<body>
		<?php echo $header ?>
		<div id="clear"></div>
		<div id="containerbox">
			 
			<div id="content" class="x mt">
				<div id="navigation">
					<?php echo $this->url->anchor('/home', 'Home') ?></a> » <b> About </b>
				</div>
				<h1>About</h1>
				<div id="abouttext">
					<p>This is the "about" page for my blog site.</p>
				</div>
				<div id="clear"></div><div id="blockbottom"> </div>
			</div>

			<?php echo $sidebar ?>
			<?php echo $footer ?>

		</div>
</body>

</html>