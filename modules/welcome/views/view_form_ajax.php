<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Welcome to ODM ( Object Data Model )</title>
<?php echo css('welcome.css') ?>

<style type="text/css">
.input-error { color: #DF4545; }
.notification {max-width: 800px;border:1px solid;margin-left:0px;margin-bottom:10px;padding:15px;text-align:center;font-weight:bold;font-size: 16px;}
.notification h1 { margin-bottom: 5px; }
.notification.success {border-color: #E6DB55;background-color: #FFFFE0;line-height: 24px;color: #000;background-position:0px 1px}
.notification.error {border-color: #DF4545;background-color: #FFEBE8;color: #000;background-position:0 -7055px;}
.loading { font-size: 10px; color:blue; padding: 5px;}
</style>

<?php echo js('jquery') ?> 
<?php echo js('form_json') ?> 
<?php echo js('underscore') ?>

<?php
// Form Json Dynamic Attributes  
// 
// no-top-msg:    Hide the top error message.
// no-ajax:       Use this attribute for native posts.
// hide-form:     Hide the form area if form submit success.
?>
<h1>Welcome to ODM ( Object Data Model )</h1> 
<div style="padding: 10px 10px 10px 0;"><?php echo anchor('/welcome/start', 'Odm Form'); ?> | <?php echo anchor('/welcome/start/ajax_example', 'Odm Form ( Ajax )'); ?></div>

<div>
<?php echo form_open('/welcome/start/do_post.json', array('method' => 'POST', 'class' => 'hide-form'));?>
<table width="100%">
    <tr>
        <td style="width:20%;"><?php echo form_label('Email'); ?></td>
        <td><?php echo form_input('user_email', '', " id='email' ");?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Password'); ?></td>
        <td><?php echo form_password('user_password', '', " id='password' ");?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Confirm'); ?></td>
        <td><?php echo form_password('user_confirm_password', '', " id='confirm' ");?></td>
    </tr>
    <tr>
        <td></td>
        <td><div class="loading-element" style="float:left;"><?php echo form_submit('do_post', 'Do Post', "");?></div></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>
<?php echo form_close(); ?>
</div>
    
<p>* Using php <b>json_encode($user->errors);</b> function you can send ajax response in json format however we do it with form_json helper because of write less coding.</p>
<br />
<?php echo view('../footer') ?>
</body>
</html>