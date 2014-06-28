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
            <h1 class="post_tag">Post Tagged With <?php echo $this->uri->getSegment(1)?></h1>

<?php 
if(count($posts) > 0) :
    foreach ($posts as $row) :?>

        <div id="post">     
            <div id="title"><h2><?php echo $this->url->anchor('/post/detail/'.$row['post_id'], $row['post_title']) ?></h2></div>
            <div id="author"><small>posted by <?php echo $row['user_username'] ?> on <?php echo $this->date_format->getDate("%F %d,%Y", strtotime($row['post_creation_date'])) ?></small></div>
            <div id="postcontent">
                <?php echo $row['post_content'] ?>
            </div>
            <div id="postnav">
                <b>Tags:</b> <?php echo $this->tag_cloud->getHtml(explode(',', $row['post_tags']), false) ?><br><br>
            <?php echo $this->url->anchor('/post/detail/'.$row['post_id'], 'Comments ('.$row['total_comment'].')') ?>
                 | Last Updated On <?php echo $this->date_format->getDate("%F %d,%Y %H:%i", strtotime($row['post_modification_date'])) ?>
            </div>
        </div>

    <?php 
    endforeach;
endif;
?>
            </div>

            <?php echo $sidebar ?>
            <?php echo $footer ?>

        </div>
</body>

</html>