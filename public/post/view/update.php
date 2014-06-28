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
                    <?php echo $this->url->anchor('/home', 'Home') ?> » <b> Update Post </b>
                </div>

                <h1 class="h1">Update Post </h1>

                <div id="createpost">
                    <i>Fields with * are required.</i>
                    <p></p>
                    <?php echo $this->form->open('/post/update/'.$post_id, array('method' => 'POST', " id='createform' ")) ?>
                            <table>
                                <tr>
                                    <td style="width:15%;"><?php echo $this->form->label('Title') ?></td>
                                    <td><?php 
                                        echo $this->form->error('post_title');
                                        echo $this->form->input('post_title', $row, " ");
                                        ?><span class="color_red">*</span></td>
                                </tr>

                                <tr>
                                    <td><?php echo $this->form->label('Content') ?></td>
                                    <td><?php 
                                        echo $this->form->error('post_content');
                                        echo $this->form->textarea('post_content', $row, ' rows="15" cols="80" size="50" style="width:50%" ');
                                        ?><span class="color_red">*</span></td>
                                </tr>

                                <tr>
                                    <td><?php echo $this->form->label('Tags') ?></td>
                                    <td>
                                    <?php 
                                        echo $this->form->error('post_tags');
                                        echo $this->form->input('post_tags', $row, " ");
                                        ?><span class="color_red">*</span>
                                    
                                    <p class="cp">Please separate different tags with commas.</p>

                                    </td>
                                </tr>

                                <tr>
                                    <td><?php echo $this->form->label('Status') ?></td>
                                    <td>
                                    <?php 
                                        echo $this->form->error('post_status');
                                        echo $this->form->dropdown('post_status', '@get(private/posts/getstatuslist)', $row, " ");
                                        ?><span class="color_red">*</span>
                                    </td>
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