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
            
            <section><?php echo $this->form->getNotice() ?></section>
            
            <div id="content">
                <div id="navigation">
                    <?php echo $this->url->anchor('/home', 'Home') ?> » <b> Manage Posts </b>
                </div>

                <h1 class="h1 left">Manage Posts </h1>
                <div id="clear"></div>

                <div id="manageposts">

                <?php echo $this->form->open('post/manage', array('method' => 'GET')) ?>
                <table>
                        <th class="table_head x">Title</th>
                        <th class="table_head">Status</th>
                        <th class="table_head x">Create Time</th>
                        <th class="table_head"> </th>
                        <tr>
                            <td>
                            <?php echo $this->form->input('post_title', $this->form->setValue('post_title'), " onkeypress='keyPress();' ")  ?>
                            </td>
                            <td>
                            <?php echo $this->form->dropdown('post_status', '@get.private/posts/getstatuslist)', $this->form->setValue('post_status'), ' onchange="submitPage();" '); ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
<?php 
if(count($posts) > 0) : ?>

    <?php 
    $i = 0;
    foreach ($posts as $post) :
        $i++;
        $mod = ($i%2) == 0 ? 'datacolor1' : 'datacolor2';
        $post = (object)$post;
                     ?>
            <tr id="<?php echo $mod ?>">
                <td><?php echo $post->post_title ?></td>
                <td><?php echo $post->post_status ?></td>
                <td><?php echo $post->post_creation_date ?></td>
                
                <td class="options">
                <?php echo $this->url->anchor('post/preview/'.$post->post_id, $this->html->img('view.png')) ?>
                <?php echo $this->url->anchor('post/update/'.$post->post_id, $this->html->img('update.png')) ?>
                <?php echo $this->url->anchor('post/delete/'.$post->post_id, $this->html->img('delete.png'), ' onclick="return confirm(\'Are you sure from this action ?\');"  ') ?>
                </td>
            </tr>
    <?php 
    endforeach;
else:
?> 
    <tr>
        <td colspan="4">Record not found</td>
    </tr>
    <?php 
endif;
?>
                </table>
                <?php echo $this->form->close() ?>
                </div>
            </div>

            <?php echo $sidebar ?>
            <?php echo $footer ?>

        </div>
<script type="text/javascript">
function keyPress(e){
    if(e.keyCode == 13){
        submitPage();
    }
}
function submitPage(){
    document.forms[0].submit();
}
</script>

</body>
</html>