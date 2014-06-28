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
                    <?php echo $this->url->anchor('/home', 'Home') ?> » <b> Create Post </b>
                </div>

                <section><?php echo $this->form->getMessage() ?></section>
                <section><?php echo $this->form->getNotice() ?></section>

                <h1 class="h1">Create Post </h1>

                <div id="createpost">
                    <i>Fields with * are required.</i>
                    <p></p>
                    <?php echo $this->form->open('/post/create', array('method' => 'POST', " id='createform' ")) ?>
                            <table>
                                <tr>
                                    <td style="width:15%;"><?php echo $this->form->label('Title') ?></td>
                                    <td>
                                    <?php 
                                    echo $this->form->error('post_title');
                                    echo $this->form->input('post_title', $this->form->setValue('post_title'), " ");
                                    ?>,<span class="color_red">*</span></td>
                                </tr>

                                <tr>
                                    <td><?php echo $this->form->label('Content') ?></td>
                                    <td><?php 
                                        echo $this->form->error('post_content');
                                        echo $this->form->textarea('post_content', $this->form->setValue('post_content'), ' cols="50" rows="10" width:"50%" ')
                                    ?><span class="color_red">*</span></td>
                                </tr>

                                <tr>
                                    <td><?php echo $this->form->label('Tags') ?></td>
                                    <td>
                                    <?php 
                                    echo $this->form->error('post_tags');
                                    echo $this->form->input('post_tags', $this->form->setValue('post_tags'), " ")
                                    ?><span class="color_red">*</span>
                                    <p class="cp">Please separate different tags with commas.</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?php echo $this->form->label('Status', 'status') ?></td>
                                    <td>
                                    <?php 
                                    echo $this->form->error('post_status');
                                    echo $this->form->dropdown('post_status', '@get(private/posts/getstatuslist)', $this->form->setValue('post_status'), " ")
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