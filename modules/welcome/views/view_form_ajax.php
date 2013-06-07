<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Welcome to Validation Model !</title>

<?php echo css('welcome.css') ?>
<?php echo js('jquery-min.js') ?>
<?php echo plugin('form'); //  JQuery Obullo Form Plugin ?>

<style type="text/css">
.button {
    padding: 5px 10px;
    display: inline;
    background: #777 url('<?php echo public_url('/images/button.png', true) ?>') repeat-x bottom;
    border: none;
    color: #fff;
    cursor: pointer;
    font-weight: normal;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    text-shadow: 1px 1px #666;
    text-decoration: none;
    }
.button:hover {
    background-position: 0 center;
    }
.button:active {
    background-position: 0 top;
    position: relative;
    top: 1px;
    padding: 6px 10px 4px;
    }
.button.white { font-size: 90%; background-color: #fff; color: #003399; text-shadow: 1px 1px #fff; border: 1px solid #ACACAC; }
.button.disabled { background: #F2F1F0; color: #ccc; text-shadow: 1px 1px #fff; border: 1px solid #ACACAC; }
label { font-weight:bold; }
.input-error { color: #DF4545; }
.notification {
  max-width: 800px;
  border:1px solid;
  margin-left: 0px;
  margin-bottom: 10px;
  padding: 15px;
  text-align:center;
  font-weight:bold;
  font-size: 16px;
}
.notification h1 { margin-bottom: 5px; }
.notification.success {
  border-color: #E6DB55;
  background-color: #FFFFE0;
  line-height: 24px;
  color: #000;
  background-position:0px 1px
}
.notification.error {
  border-color: #DF4545;
  background-color: #FFEBE8;
  color: #000;
  background-position:0 -7055px;
}
.loading { font-size: 10px; color:blue; padding: 5px;}
</style>

<?php
    ##  OBULLO JQUERY FORM VALIDATION CLASS ATTRIBUTES ##
    //  
    // no-top-msg:    Hide the main jquery form error msg which is located at the top.
    // no-ajax:       If you don't want to ajax post use this atttribute for native posts.
    // hide-form:     Form plugin will hide the form area if form submit success !
?>
<h1>Welcome to Obullo Validation Model !</h1> 

<div style="padding: 10px 10px 10px 0;"><?php echo anchor('/welcome/start', 'Validation Model (No Ajax)'); ?> | <?php echo anchor('/welcome/start/ajax_example', 'Validation Model with Ajax'); ?></div>

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
        <td><div class="loading_div" style="float:left;"><?php echo form_submit('do_post', 'Do Post', " class='button white' ");?></div></td>
    </tr>
    
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
        <td><b>Code</b></td>
        <td>
            <pre>
            loader::model('user', false);

            $user = new User();
            $user->user_password = i_get_post('user_password');
            $user->user_email    = i_get_post('user_email');

            if($user->save())
            {
                echo form_send_success($user);
            } 
            else
            {
                echo form_send_error($user);
            }
            </pre>
        </td>
    </tr>
    
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    
</table>
<?php echo form_close(); ?>
</div>

<p>
    Using php <b>json_encode($user->errors);</b> function you can send response in json format, we do it with obullo form_send helper because of write less coding.<br />
        Please look at the <b>form_send</b> helper functions you will understand well how does it work. Helper file is located <b>obullo/helpers/form_send.php</b>
</p>

<?php echo br(); ?>
<?php echo view('../footer') ?>

</body>
</html>