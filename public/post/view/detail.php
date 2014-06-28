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
            <section><?php echo $this->form->getMessage() ?></section>

            <div id="content">
                <div id="navigation">
                    <?php echo $this->url->anchor('/home', 'Home') ?> » <b> Post </b>
                </div>

                <div id="post">
                    <div id="title"><h2><?php echo $post->post_title ?></h2></div>
                    <div id="author"><small>posted by <?php echo $post->user_username ?> on <?php echo $this->date_format->getDate("%F %d,%Y", strtotime($post->post_creation_date)) ?></small></div>
                    
                    <div id="postcontent"><?php echo $post->post_content ?></div>
                    <div id="postnav">

                        <b>Tags:</b> <?php echo $this->tag_cloud->getHtml(explode(',', $post->post_tags), false) ?>
                        <br><br>

<?php
if ( ! empty($post->post_modification_date)) : ?>
    Last Updated On
    <?php echo $post->user_username ?> on <?php echo $this->date_format->getDate("%F %d,%Y", strtotime($post->post_modification_date)) ?>

    <?php 
endif; ?>
                    </div>
                </div>
                <div id="commentcontainer">
                        <h2><?php echo count($comments) ?> comments</h2>
<?php 
$i = 0;
foreach($comments as $comment) : 
        $i++; 
        $comment = (object)$comment;
    ?> 
        <div id="postcomment">
            <div id="sender"><a href="#"><?php echo $comment->comment_name ?></a> says:</div>
            <div id="commentlink"><a href="#">#<?php echo $i?></a></div>
            <div id="clear"></div>
            <div id="detail"><?php echo $this->date_format->getDate("%F %d,%Y %H:%i:%s", strtotime($comment->comment_creation_date)) ?></div>
            <div id="commentext"><?php echo $comment->comment_body ?></div>
        </div>
    <?php 
endforeach; 
?>
            <h3>Leave a Comment</h3>
            <i class="required-t">Fields with * are required.</i>

            <?php echo $this->form->open('post/detail/'.$post->post_id, array('method' => 'POST', 'id' => 'commentform')) ?>
            <?php echo $this->form->hidden('comment_post_id', $post->post_id) ?>
            <table width="100%">

                <tr>
                    <td style="width:15%;"><?php echo $this->form->label('Name', 'name') ?><span class="color_red">*</span></td>
                    <td><?php 
                        echo $this->form->error('comment_name');
                        echo $this->form->input('comment_name', $this->form->setValue('comment_name'), " id='comment_name' ");
                        ?></td>
                </tr>

                <tr>
                    <td><?php echo $this->form->label('Email', 'email') ?><span class="color_red">*</span></td>
                    <td><?php 
                        echo $this->form->error('comment_email');
                        echo $this->form->input('comment_email', $this->form->setValue('comment_email'), " id='comment_email' ");
                        ?></td>
                </tr>

                <tr>
                    <td><?php echo $this->form->label('Website', 'website') ?><span class="color_red">*</span></td>
                    <td><?php 
                        echo $this->form->error('comment_website');
                        echo $this->form->input('comment_website', $this->form->setValue('comment_website'), " id='comment_website' ");
                        ?></td>
                </tr>

                <tr>
                    <td><?php echo $this->form->label('Comment', 'comment') ?><span class="color_red">*</span></td>
                    <td><?php 
                        echo $this->form->error('comment_body');
                        echo $this->form->textarea('comment_body', $this->form->setValue('comment_body'), ' style="width:50%" ');
                        ?></td>
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
                <div id="blockbottom"> </div>
            </div>

            <?php echo $sidebar ?>
            <?php echo $footer ?>

        </div>
</body>
</html>