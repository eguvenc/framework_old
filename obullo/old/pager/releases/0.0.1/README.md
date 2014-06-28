## Pager Class

The Pager class <b>derived</b> from <b>PEAR Pager Class</b>, Pager is a class to page an array of data. It is taken as input and it is paged according to various parameters.it has more options, and it is customizable, either dynamically or via stored preferences.

### Initializing the Class

------

```php
new Pager;
$this->pager->method();
```

Once loaded, the Pager object will be available using: <kbd>$this->pager->method();</kbd>

Pager Class has two modes called <kbd>"Jumping"</kbd> and <kbd>"Sliding"</kbd>.

### Sliding Mode

------

```php
[1]  «  1  |  2  |  3  |  4  |  5  »  [6]
```

This simple example will page the array data, giving back pages with 8 data per page, and links to the previous two / next two pages:

```php
$params = array(
    'mode'         => 'Sliding',  // Jumping
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'GET',
    'query_string' => false,
    'current_page' => $this->uri->segment(3),
    'base_url'     => '/framework/index.php/welcome/start/index/',
);

// just dummy data
$params['item_data']  = range(1, 1000);

new Pager($params);

$data  = $this->pager->getPageData();
$links = $this->pager->getLinks();

// $links is an ordered + associative array with 'back'/'pages'/'next'/'first'/'last'/'all' links.
// NB: $links['all'] is the same as $this->pager->links;

echo $links['all'];

echo '<hr />';

// Show data for current page:
echo 'PAGED DATA: '; print_r($data);

echo '<hr />';

// Results from methods:
echo 'getCurrentPage()...: '; var_dump($this->pager->getCurrentPage());
echo 'getNextPage()......: '; var_dump($this->pager->getNextPage());
echo 'getPrevPage()......: '; var_dump($this->pager->getPrevPage());
echo 'numItems()..........: '; var_dump($this->pager->numItems());
echo 'numPages()..........: '; var_dump($this->pager->numPages());
echo 'isFirstPage()......: '; var_dump($this->pager->isFirstPage());
echo 'isLastPage().......: '; var_dump($this->pager->isLastPage());
echo 'isLastPageEnd()...: '; var_dump($this->pager->isLastPageEnd());
echo '$this->pager->range........: '; var_dump($this->pager->range);
```

If want to use simple pager you can use framework urls you should set <kbd>query_string = false</kbd> and you must set <kbd>'current_page'</kbd> parameter to your page segment ( $this->uri->segment(3) ) like this.

```php
$params = array(
    'mode'         => 'Sliding',  // Jumping
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'GET',
    'query_string' => false,
    'current_page' => $this->uri->segment(3),
    'base_url'     => '/framework/index.php/welcome/start/index/',
);
```

The page number 5 will be your segment 3.

```php
         segment                      0       1     2    3
http://localhost/framework/index.php/welcome/start/index/5
```

### Jumping Mode

------

Change mode parameter as <kbd>Jumping</kbd> and increase <kbd>Delta</kbd> parameter.

```php
[1]  << Back 1  2  3  4  5  Next >>  [33]
```

#### $this->pager->getLinks();

getLinks() function also will give you <b>link rel</b> = "" tags, You can get link rel style tags using <b>$links['link_tags'] or $link['link_tags_raw']</b>

```php
$links = $this->pager->getLinks();
print_r($links);

Array
(
    [0] => <a href="/framework/index.php?d=...&page=2" title="previous page"><< Back</a>
    [1] => 3<a href="/framework/index.php?d=...&page=4" title="page 4">4</a> 
    [2] => <a href="/framework/index.php?d=...&page=4" title="next page">Next >></a>

    [3] => <a href="/framework/index.php?d=...&page=1" title="first page">[1]</a> 
    [4] => <a href="/framework/index.php?d=...&page=6" title="last page">[6]</a>
    [5] => <a href="/framework/index.php?d=...&page=1" title="first page">[1]</a> <a href="/framework/index.php?d=...&page=2" title="previous page"><< Back</a> 3 <a href="/framework/index.php?d=...&page=4" title="page 4">4</a>  <a href="/framework/index.php?d=...&page=4" title="next page">Next >></a> <a href="/framework/index.php?d=...&page=6" title="last page">[6]</a>

    [6] => <link rel="first" href="/framework/index.php?d=...&page=1" title="first page">
<link rel="previous" href="/framework/index.php?d=...&page=2" title="previous page">
<link rel="next" href="/framework/index.php?d=...&page=4" title="next page">
<link rel="last" href="/framework/index.php?d=...&page=6" title="last page">

    [back] => <a href="/framework/index.php?d=...&page=2" title="previous page"><< Back</a>
    [pages] => 3 <a href="/framework/index.php?d=...&page=4" title="page 4">4</a> 

    [next] => <a href="/framework/index.php?d=...&page=4" title="next page">Next >></a>
    [first] => <a href="/framework/index.php?d=...&page=1" title="first page">[1]</a> 
    [last] => <a href="/framework/index.php?d=...&page=6" title="last page">[6]</a>
    [all] => <a href="/framework/index.php?d=...&page=1" title="first page">[1]</a> <a href="/framework/index.php?d=...&page=2" title="previous page"><< Back</a> 3 <a href="/framework/index.php?d=...&page=4" title="page 4">4</a>  <a href="/framework/index.php?d=...&page=4" title="next page">Next >></a> <a href="/framework/index.php?d=...&page=6" title="last page">[6]</a>

[link_tags] => <link rel="first" href="/framework/index.php?d=...&page=1" title="first page">
<link rel="previous" href="/framework/index.php?d=...&page=2" title="previous page">
<link rel="next" href="/framework/index.php?d=...&page=4" title="next page">
<link rel="last" href="/framework/index.php?d=...&page=6" title="last page">

    [link_tags_raw] => Array
        (
            [first] => Array
                (
                    [url] => /framework/index.php?d=...&page=1
                    [title] => first page
                )

            [prev] => Array
                (
                    [url] => /framework/index.php?d=...&page=2
                    [title] => previous page
                )

            [next] => Array
                (
                    [url] => /framework/index.php?d=...&page=4
                    [title] => next page
                )

            [last] => Array
                (
                    [url] => /framework/index.php?d=...&page=6
                    [title] => last page
                )

        )

)
```

**Note:** If you intend to use *rel links* you should set http_method param to GET, because *$links['link_tags']* or *$link['link_tags_raw']* variables gives result just with Http GET method.

### Paging Database Query Results

------

```php
$query = $this->db->query('SELECT * FROM articles');
$numRows = $query->count();

$params = array(
    'mode'         => 'sliding',  // jumping 
    'per_page'     => 5,
    'delta'        => 2,
    'http_method'  => 'GET',    
    'url_var'      => 'page',
    'query_string' => false,      // If false use framework URLs
    'current_page' => $this->uri->segment(3),
    'base_url'     => '/framework/index.php/welcome/start/index/',
    'total_items'  => $numRows,
);

new Pager($params);
 
list($from, $to) = $this->pager->getOffsetByPage();
 
echo 'from:'.$from.'<br />';
echo 'to:'.$to.'<br />';
 
$this->db->get('articles', $params['per_page'], $from - 1);
$data = $this->db->getResultArray();
 
echo $this->pager->links;

echo '<hr />';

print_r($data);
```

### Http GET Example

------

If you prefer to Http GET methods, you must set these parameters <kbd>http_method = GET , query_string = true</kbd> and add your extra variables to <kbd>extra_vars</kbd>.

```php
$params = array(
    'mode'         => 'jumping',  // sliding
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'GET',     // set http_method to GET
    'url_var'      => 'page',    // provide your PAGE query string
    'query_string' => true,      // set query string to true
    'base_url'     => '/framework/index.php',  // Change your base url
    'total_items'  => $numRows,
    'extra_vars'   => array('d'=>'welcome','c'=>'start', 'm' => 'index'),
); 
```

Above the settings produce this url. 

```php
http://localhost/framework/index.php?d=welcome&c=start&m=index&page=3
```

**Note** If you get some errors be sure your *$config['enable_query_strings'] = true;* parameter setted to true in <dfn>app/config/config.php</dfn> file.

### Http POST Example

------

If you prefer to Http POST methods, just change http_method and set query_string to false.

```php
$params = array(
    'mode'         => 'jumping',  // jumping
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'POST',     // set http_method to POST
    'query_string' => false,      // set query string to false
    'base_url'     => '/framework/index.php/welcome/start/index',  // Change your base url
    'total_items'  => $numRows,
);
```

When you use POST method Pager render_link function will produce javascript onclick function for each html a tags. So code output of links shown as below.

```php
<a href="javascript:void(0);" onclick="
var form = document.createElement("form");
var input = ""; 
form.action = "/framework/index.php/welcome/start/index"; 
form.method = "POST";

input = document.createElement("input"); 
input.type = "hidden"; 
input.name = "set_per_page"; 
input.value = "5"; 
form.appendChild(input); 

input = document.createElement("input"); 
input.type = "hidden"; 
input.name = "page"; 
input.value = "1"; 
form.appendChild(input); 
document.getElementsByTagName("body")[0].appendChild(form);

form.submit(); 
return false;">Page Number</a>
```

### Html Widgets

------

You have two HTML widgets called <kbd>Per Page Select Box</kbd> and <kbd>Page Select Box</kbd>

#### getPerPageSelectBox($start = integer, $end = integer, $step = integer, $show_all_data = false, $extra_params = array() );

This function will produce html select menu to set_per_page parameter like this

```php
[1]  << Back 1  2  3  4  5  Next >>  [33]  
Per Page
```

### Per Page Select Box Example

```php
<?php
Class Pager_Example extends Controller {
    
    function __construct()
    {   
        parent::__construct();
        
        new Db();
    }           
    
    public function index()
    {                  
        setVar('title', 'Pager Test !');
        
        $query = $this->db->query('SELECT * FROM articles');
        $numRows = $query->count();
        
        $perPage = ($this->get->post('set_per_page')) ? $this->get->post('set_per_page') : '5';
        
        $params = array(
            'mode'         => 'sliding',  // jumping
            'per_page'     => $perPage,
            'delta'        => 2,
            'http_method'  => 'GET',    
            'url_var'      => 'page',
            'query_string' => true,      // If false use Obullo style URLs
            'base_url'     => '/framework/index.php',
            'total_items'  => $numRows,
            'extra_vars'   => array('d'=>'welcome','c'=>'start', 'm' => 'index', 'set_per_page' => $perPage),
        );
        
        new Pager($params);
         
        list($from, $to) = $this->pager->getOffsetByPage();
        $this->db->get('articles', $params['per_page'], $from - 1);
        
        setVar('rows', $this->db->getResult());
        setVar('params', $params);
        setVar('links', $this->pager->getLinks());
        setVar('select_box', $this->pager->getPerPageSelectBox(5, 50, 5, false));

        view('pagertest');
    }
       
}
/* End of file start.php */
```

and <kbd>pagertest.php</kbd> file should be like this 

```php
<h1><?php echo getVar('title'); ?></h1> 
<?php
$params = getVar('params');
$links  = getVar('links');
$rows   = getVar('rows');
$select_box = getVar('select_box');
?>
<p><?php print 'PAGED DATA: <br />'; var_dump($rows); ?></p>

<?php
$hiddens = array(
'd' => 'tests',
'c' => 'start',
'm' => 'index',
);

echo Form::open('', array('method' => $params['http_method']), $hiddens);

echo '<br />'.$links['all'].'    Per Page '.$select_box.' ';

echo Form::submit('_send', 'Send', "");
echo Form::close();

?>
```

and URL for this example 

```php
http://localhost/framework/index.php?d=welcome&c=start&m=index
```

 If you want to use <kbd>onchange = ""</kbd> for selectbox instead SEND button you must provide <kbd>auto_submit</kbd> as extra parameter. Remove <b>form_helper</b> functions from the testpager view and add extra parameter to get_per_page_select_box in start controller. 

```php
$this->pager->getPerPageSelectBox(5, 50, 5, false, array('auto_submit' => true));
```

#### getPageSelectBox($extra_params = array() );

------

This function will produce html select menu to page parameter like this

```php
[1]  «    »  [33]   Per Page
```

### Page Select Box Example ( POST Method )

```php
<?php
Class Pager_Example extends Controller {
    
    function __construct()
    {   
        parent::__construct();
        
        new Db;
        new Get;
    }           
    
    public function index()
    {                  
        setVar('title', 'Pager Test !');
        
        $query   = $this->db->query('SELECT * FROM articles');
        $numRows = $this->db->getCount();
        
        $perPage = ($this->get->post('set_per_page') != '') ? $this->get->post('set_per_page') : '5';
        
        $params = array(
            'mode'         => 'sliding',  // jumping
            'per_page'     => $perPage,
            'delta'        => 2,
            'http_method'  => 'POST',    
            'query_string' => false,
            'base_url'     => '/framework/index.php/welcome/start/index',
            'total_items'  => $numRows,
            'extra_vars'   => array('set_per_page' => $perPage),
        );
        
        new Pager($params);
         
        list($from, $to) = $this->pager->getOffsetByPage();
         
        $this->db->get('articles', $params['per_page'], $from - 1);
        
        setVar('rows', $this->db->getResult());
        setVar('params', $params);
        setVar('links', $this->pager->getLinks());
        
        setVar('page_select_box', $this->pager->getPageSelectBox(array('auto_submit' => true)));  // auto submit
        setVar('per_page_select_box', $this->pager->getPerPageSelectBox(5, 50, 5, false, array('auto_submit' => true)));
        
        view('pagertest');
    }
    
}
```

and <kbd>pagertest.php</kbd> file should be like this 

```php
<h1><?php echo getVar('title'); ?></h1>

<?php
$params = getVar('params');
$links  = getVar('links');
$rows   = getVar('rows');
$page_select_box = getVar('page_select_box');
$per_page_select_box = getVar('per_page_select_box');
?>

<p><?php print 'PAGED DATA: <br />'; var_dump($rows); ?></p>

<br />

<?php

echo $links['first']. $links['back'].'   '.$page_select_box.'   '.$links['next'].'   '.$links['last'];
echo '   Per Page    '.$per_page_select_box.'   ';

?>
```

### Html Widgets Extra Parameters

------

Additionaly you can set <kbd>option_text</kbd> parameter to customize select menu texts. Change your codes like this

```php
$pageOptions    = array('auto_submit' => true, 'option_text' => 'Go to page %d');
$perPageOptions = array('auto_submit' => true, 'option_text' => 'Show %d data');

setVar('page_select_box', $this->pager->getPageSelectBox($pageOptions));
setVar('per_page_select_box', $this->pager->getPerPageSelectBox(5, 50, 5, false, $perPageOptions));
```

The last changes will give you below the output.

```php
 [1]  «    »  [33]   Per Page
```

### Customizing Pager Data Links

------

<table>
    <thead>
    <tr>
      <th>Parameters</th><th>Description</th></thead>
    <tbody>
        <tr>
            <td>alt_first, alt_prev, alt_next, alt_last, alt_page</td>
            <td>Specify alternative text to display for the first, last, next and previous page numbers. It's generally a good idea to include these keys so that users with older browsers can still see the navigation links.</td>
        <tr>
            <td>separator, space_before_separator, space_after_separator</td>
            <td>Specifies the symbol to use between page links, while the spaces_before_separator and spaces_after_separator keys specify the space before and after each separator. These keys are useful to control the space between each page link.</td>
        </tr>
        <tr>
            <td>cur_page_link_class, link_class</td>
            <td>Specifies the name of the CSS class used to style each page link.The cur_page_link_class specifies selected page css.</td>
        </tr>
        <tr>
            <td>first_page_text, last_page_text</td>
            <td>Specify the text that should appear instead of the first and last page numbers.</td>
        </tr>
        <tr>
            <td>prev_img, next_img</td>
            <td>Specify the text that should appear for the next and previous page links. HTML code can also be used in place of plain text for all these; this means that you can use img tags</td>
        </tr>
        <tr>
            <td>cur_page_span_pre, cur_page_span_post</td>
            <td>String texts before and after current page link</td>
        </tr>
        <tr>
            <td>first_page_pre = '[' , first_page_post = ']'</td>
            <td>String texts before and after first page link default "[" "]"</td>
        </tr>
        <tr>
            <td>last_page_pre = '[' , last_page_post = ']'</td>
            <td>String texts before and after last page link default "[" "]"</td>
        </tr>
    </tbody>
</table>

### Example Configuration

```php
/*
$params = array(
    'mode'         => 'jumping',  // sliding
    'per_page'     => $perPage,
    'delta'        => 2,
    'http_method'  => 'POST',    
    'query_string' => false,
    'base_url'     => '/framework/index.php/tests/start/index',
    'total_items'  => $numRows,
    'extra_vars'   => array('set_per_page' => $perPage),
    
     // Customizations
    'alt_first'  => 'First page',
    'alt_prev'   => 'Previous page',
    'alt_next'   => 'Next page',
    'alt_last'   => 'Last page',
    'alt_page'   => 'Page',

    'separator'  => '',
    'space_before_separator' => 2,
    'spaces_after_separator'  => 2,

    'cur_page_link_class'  => 'current', // css customization
    'link_class' => 'myPager'           // css customization

    'first_page_text' => '« First Page',
    'last_page_text'  => '» Last Page',

    'prev_img'   => '‹ Prev Page',   // you can use <img src = '' /> tags.
    'next_img'   => '› Next Page',
);
*/
```

Add this codes to your css file. 

```php
.myPager {
border: 1px solid #000;
padding: 4px;
background-color: #F4F6FF;
}
.current {
border: 1px solid #000;
padding: 4px;
background-color: #ccc;    
} 
```

After that custom parameters output will look like this 

```php
[« First Page] ‹ Prev Page 1 2 › Next Page [» Last Page]
```

Special thanks to [Lorenzo Alberton](http://www.alberton.info/) who originally developed The PEAR pager library.