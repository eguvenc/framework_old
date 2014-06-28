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

            <div id="content">
                <div id="navigation">
                    <?php echo $this->url->anchor('/home', 'Home') ?> » <b> Post </b>
                </div>

                <div id="post">
                    <div id="title"><h2><?php echo $post->post_title ?> ( Preview )</h2></div>
                    <div id="author"><small>posted by <?php echo $post->user_username ?> on <?php echo $this->date_format->getDate("%F %d,%Y", strtotime($post->post_creation_date)) ?></small></div>
                    
                    <div id="postcontent">
                        <?php echo $post->post_content ?>
                    </div>
                    <div id="postnav">

                        <b>Tags:</b> <?php echo $this->tag_cloud->getHtml(explode(',', $post->post_tags), false) ?>
                        <br><br>

<?php if ( ! empty($post->post_modification_date)) : ?>
                Last Updated On
                <?php echo $post->user_username?> on <?php echo $this->date_format->getDate("%F %d,%Y", strtotime($post->post_modification_date)) ?>

    <?php 
endif;
?>
                    </div>
                </div>

                    <div id="commentcontainer">
                        <h2><?php echo count($comments) ?> comments</h2>
<?php 
$i = 0;
foreach ($comments as $comment) :
    $i++;
    $comment = (object)$comment; ?>
    
    <div id="postcomment">
        <div id="sender"><a href="#"><?php echo $comment->comment_name ?></a> says:</div>
        <div id="commentlink"><a href="#">#<?php echo $i?></a></div>
        <div id="clear"></div>
        <div id="detail"><?php echo $this->date_format->getDate("%F %d,%Y %H:%i:%s", strtotime($comment->comment_creation_date)) ?></div>
        <div id="commentext"><?php echo $comment->comment_body ?></div>
    </div>

    <?php 
endforeach; ?>
                    </div>            
                <div id="blockbottom"> </div>
            </div>

            <?php echo $sidebar ?>
            <?php echo $footer ?>

        </div>
</body>
</html>