
<div id="sidebar">
<?php 
if(isset($auth)) : ?>

         <div id="sidepaneluser">
            <div class="sidebarheader">
                <div id="block"></div>
                <div id="tags"><?php echo $username; ?></div>
            </div>

            <div class="tags_panel">
                <?php echo $this->url->anchor('post/create', 'Create New Post') ?> <br>
                <?php echo $this->url->anchor('post/manage', 'Manage Posts') ?> <br>
                <?php echo $this->url->anchor('comment/display', 'Approve Comments') ?>

                <span class="approve_comments">
                    ( <?php echo $total_comments; ?> )
                </span> <br>
                
                <?php echo $this->url->anchor('membership/logout', 'Logout') ?> <br>
            </div>
         </div>

    <?php 
endif; ?>

     <div id="sidepaneluser">
        
        <div class="sidebarheader">
            <div id="block"></div>
            <div id="tags">Tags</div>
        </div>

        <div class="tags_a">
            <?php echo $this->url->anchor('tag/blog', 'blog') ?>
            <?php echo $this->url->anchor('tag/test', 'test') ?>
        </div>
     </div>
</div>