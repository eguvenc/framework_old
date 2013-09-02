## Vi Helper

Obullo has a Vi helper that is always active and it declared in your [Autoload](/docs/advanced/auto-loading) file.

### Function Reference

------

#### vi\view('filename', $string = true, $data = array());

Load view file from local directory e.g. /welcome

#### vi\setVar('key', $val = '');

Set variables to your layouts

#### vi\getVar('key');

Get view variable for all views

#### vi\setArray('key', $val = '');

Set array type variables to your view files

#### vi\getArray('key');

Get array variables

**Tip:**About loading and creating view files, you can find more examples in [views](/docs/general/views) section.

### Loading Views from common views folder

------

Obullo has a general /views folder which is located in <dfn>/modules</dfn> directory.Sometimes you may want to put common views to this folder which is like <b>header</b> and <b>footer</b> parts etc ..

```php
<?php 
Class Home extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }                               

    public function index()
    {
        vi\setVar('title', 'Welcome to Mysite !');
        
        vi\views('home', false);
    }
}

//  End of file home.php
// Location: .modules/home/controller/home.php // 
```

```php
Location: .modules/home/view/home.php

<?php echo vi\views('header'); ?>

<div class="row" id="wrapper">
<div class="radius" id="content"> 
  
        <ul class="tabs-content">
            <li class="active" id="simple1Tab">
                <p>Video ..</p>
            </li>
            <li id="simple2Tab">This is simple tab 2's content. Now you see it!</li>
        </ul>

        <div class="row">
            <div class="twelve columns">
                <p><?php echo anchor('/signup', 'Join', ' class="button" ') ?></p>
            </div>
        </div>
    </div>
      
</div>
</div>

<?php echo vi\views('footer'); ?>
```

```php
// Location: .modules/views/header.php

<div class="row">
    <div class="twelve columns">
        
        <div class="row">
            <div class="three columns">
                <a href="/"><div style="background-color:#f2f2f2;width: 220px;height: 60px;">Logo</div></a>
            </div>
            
            <?php if($this->auth->check()) { ?>
            
            <div class="five columns">
                <p><?php echo anchor('/settings', 'Settings')?>  <?php echo $this->auth->data('user_email') ?> <?php echo anchor('/logout', 'Logout'); ?></p>
            </div>
            
            <?php } ?>
            
        </div>
    </div>
```

### Subfolders

------

You can create unlimited subfolders.

```php
echo vi\views('subfolder/sub/filename');
```