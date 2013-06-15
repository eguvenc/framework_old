<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Welcome to Validation Model !</title>

<?php echo css('welcome.css') ?>
<?php echo js('jquery-min.js') ?>

<style type="text/css">
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
</style>
</head>
    
<body>
<h1>Welcome to Obullo Validation Model !</h1> 

<div style="padding: 10px 10px 10px 0;"><?php echo anchor('/welcome/start', 'Validation Model'); ?> | <?php echo anchor('/welcome/start/ajax_example', 'Validation Model ( Ajax )'); ?></div>

<?php echo form_msg($user, $msg = '', '<div class="notification error">', '</div>'); ?>

<?php if(sess_flash('notice') != '') { ?>
<div class="notification success"><?php echo sess_flash('notice') ?></div>
<?php } ?>

<div>
<?php echo form_open('/welcome/start/do_post', array('method' => 'POST', 'class' => 'no-ajax')); ?>
<table width="100%">
    
    <tr>
        <td style="width:20%;"><?php echo form_label('Email'); ?></td>
        <td>
        <?php echo form_error('user_email', '<div class="input-error">', '</div>'); ?>
        <?php echo form_input('user_email', set_value('user_email'), " id='email' ");?>
        </td>
    </tr>
    
    <tr>
        <td><?php echo form_label('Password'); ?></td>
        <td>
        <?php echo form_error('user_password', '<div class="input-error">', '</div>'); ?>
        <?php echo form_password('user_password', '', " id='password' ");?>
        </td>
    </tr>
    
    <tr>
        <td><?php echo form_label('Confirm'); ?></td>
        <td>
        <?php echo form_error('user_confirm_password', '<div class="input-error">', '</div>'); ?>
        <?php echo form_password('user_confirm_password', '', " id='confirm' ");?>
        </td>
    </tr>
    
    <tr>
        <td></td>
        <td><?php echo form_submit('do_post', 'Do Post');?><font size="1"> * Please do post with blank fields and see the errors.</font></td>
    </tr>
    
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
        <td><b>Code</b></td>
        <td>
        <pre>
        loader::model('user', false);  // Include user model

        $user = new User();
        $user->usr_password = i_get_post('usr_password');
        $user->usr_email    = i_get_post('usr_email');

        $data['user'] = $user;

        view('view_form', $data, false);</pre>
        </td>
    </tr>
    
    
    <tr>
        <td colspan="2">Test Results</td>
    </tr>
    <tr>
        <td><b>form_error('usr_username');</b></td>
        <td><pre><?php echo form_error('usr_username'); ?></pre></td>
    </tr>
    
    <tr>
        <td><b>validation_errors();</b></td>
        <td><pre><?php echo validation_errors(' | ', ' | '); ?></pre></td>
    </tr>
    
    <?php if(is_object($user)) { ?>
    
    <tr>
        <td><b>print_r($user->errors());</b></td>
        <td><pre><?php print_r($user->errors()); ?></pre></td>
    </tr>
    
    <tr>
        <td><b>print_r($user->errors);</b></td>
        <td><pre><?php print_r($user->errors); ?></pre></td>
    </tr>
    
    <tr>
        <td><b>print_r($user->values());</b></td>
        <td><pre><?php print_r($user->values()); ?></pre></td>
    </tr>
    
    <tr>
        <td><b>print_r($user->values);</b></td>
        <td><pre><?php print_r($user->values); ?></pre></td>
    </tr>
    
    <?php } ?>
        
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    
</table>
     
<?php echo form_close(); ?>
</div>

<?php echo '<br />'; ?>
<?php echo view('../footer') ?>
</body>
</html>