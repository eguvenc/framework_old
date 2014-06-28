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
 			<section>
				<?php echo $this->form->getNotice(); ?>
			</section>
			<div id="content">
				
				<div id="navigation">
					<?php echo $this->url->anchor('/home', 'Home') ?> » <b> Contact</b>
				</div>

				<h1 class="h1">Contact</h1>

				<div id="createpost">
					<i>Fields with * are required.</i>
					<p></p>
					<?php echo $this->form->open('/contact/index', array('method' => 'POST', " id='createform' ")) ?>
			                <table>
			                    <tr>
			                        <td style="width:15%;"><?php echo $this->form->label('Name') ?></td>
			                        <td><?php 
echo $this->form->error('contact_name');
echo $this->form->input('contact_name', $this->form->setValue('contact_name'), " ");?><span class="color_red">*</span></td>
			                    </tr>
			                    <tr>
			                        <td><?php echo $this->form->label('Email', 'email') ?></td>
			                        <td><?php 
echo $this->form->error('contact_email');
echo $this->form->input('contact_email', $this->form->setValue('contact_email'), " ");?><span class="color_red">*</span></td>
			                    </tr>
			                    <tr>
			                        <td><?php echo $this->form->label('Subject', 'subject') ?></td>
			                        <td><?php 
echo $this->form->error('contact_subject');
echo $this->form->input('contact_subject', $this->form->setValue('contact_subject'), " ");?><span class="color_red">*</span></td>
			                    </tr>

			                    <tr>
			                        <td><?php echo $this->form->label('Body', 'body') ?></td>
			                        <td><?php 
echo $this->form->error('contact_body');
echo $this->form->textarea('contact_body', $this->form->setValue('contact_body'), ' size="50" style="width:50%" ');?><span class="color_red">*</span></td>
			                    </tr>
			                    <tr>
			                    	<td></td>
			                        <td><?php echo $this->form->submit('dopost', 'Do Post') ?></td>
			                    </tr>
			                    <tr>
			                        <td colspan="2">&nbsp;</td>
			                    </tr>
			                </table>
					<?php echo $this->form->close() ?>
				</div>
			</div>

			<?php echo $sidebar ?>
			<?php echo $footer ?>
		</div>
</body>

</html>