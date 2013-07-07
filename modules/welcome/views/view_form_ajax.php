<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Welcome to ODM ( Object Data Model )</title>

<?php echo css('welcome.css') ?>
<?php echo js('jquery-min.js') ?>
<?php
$js_files = array(  
        'form/jquery.livequery.js',
        'form/underscore.js',
        'form/underscore_addons.js',
        'form/notification.js',
        'form/form.js',
        'form/form_settings.js',
      );
foreach($js_files as $jsfile)
{
    echo js($jsfile); //  JQuery Obullo Form Plugin 
}
?>
<style type="text/css">
.input-error { color: #DF4545; }
.notification {max-width: 800px;border:1px solid;margin-left:0px;margin-bottom:10px;padding:15px;text-align:center;font-weight:bold;font-size: 16px;}
.notification h1 { margin-bottom: 5px; }
.notification.success {border-color: #E6DB55;background-color: #FFFFE0;line-height: 24px;color: #000;background-position:0px 1px}
.notification.error {border-color: #DF4545;background-color: #FFEBE8;color: #000;background-position:0 -7055px;}
.loading { font-size: 10px; color:blue; padding: 5px;}
</style>
<?php
##  OBULLO JQUERY FORM VALIDATION CLASS ATTRIBUTES ##
//  
// no-top-msg:    Hide the main jquery form error msg which is located at the top.
// no-ajax:       If you don't want to ajax post use this atttribute for native posts.
// hide-form:     Form plugin will hide the form area if form submit success !
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
        <td><div class="loading_div" style="float:left;"><?php echo form_submit('do_post', 'Do Post', "");?></div></td>
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
    
<p>Using php <b>json_encode($user->errors);</b> function you can send ajax response in json format, we do it with form_json helper because of write less coding.</p>
<br />
<?php echo view('../footer') ?>
</body>
</html>