<html>
    <head>
<title>
<?php echo $title ?>
</title>

        <?php echo $this->html->css('style.css') ?>
        <meta charset="utf-8">
    </head>

<body>
        <?php echo $header ?>

        <div id="clear"></div>
        <div id="containerbox">
            
            <section>
                <?php echo $this->form->getNotice() ?>
            </section>

            <div id="content">
                <div id="navigation">
                    <?php echo $this->url->anchor('/home', 'Home') ?> » <b> Approve Comments </b>
                </div>

                <h1 class="h1 left">Comments </h1>
                <div id="clear"></div>

                <div id="approve_comments">
<?php
if (count($comments) > 0) :
    foreach ($comments as $row) :
        $row = (object)$row; // convert to object ?>

            <div id="postcomment">  
                <div id="sender"><?php echo $row->comment_name ?> store says on <?php echo $this->url->anchor('/post/preview/'.$row->post_id, $row->post_title); ?> </div>
                <div id="commentlink"><a href="#">#<?php echo $row->comment_id?></a></div>
                <small class="pending left"><?php  echo ($row->comment_status == 0) ? 'Pending approval |' : ' '; ?>
                <?php 

                $approve = ($row->comment_status == 1) ? $this->url->anchor('/comment/update/'.$row->comment_id.'/unapprove', 'Unapprove') : $this->url->anchor('/comment/update/'.$row->comment_id.'/approve', 'Approve');

                echo $approve;
                ?>
                | <?php echo $this->url->anchor('/comment/delete/'.$row->comment_id, 'Delete') ?>
                </small>
                <div id="detail_left"><?php echo $this->date_format->getDate("%F %d,%Y", strtotime($row->comment_creation_date)) ?></div>
                <div id="clear"></div>
                <div id="commentext"><?php echo $row->comment_body ?></div>
            </div>  
    <?php
    endforeach;
endif;
?>
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