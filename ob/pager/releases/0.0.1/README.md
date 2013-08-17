## Pager Class

------

Obullo's Pager class <b>derived</b> from <b>PEAR Pager Class</b>, Pager is a class to page an array of data. It is taken as input and it is paged according to various parameters.it has more options, and it is customizable, either dynamically or via stored preferences.

### Initializing the Class

------

```php
new Pager();
```

Once the library is loaded it will be ready for use. The image library object you will use to call all functions is: <dfn>$this->pager->method()</dfn>

### Quick Access To Library

------


Also using lib(); function you can grab the instance of Obullo libraries.

```php
$pager = lib('ob/pager');

$pager->init();
```

Pager Class has two modes called <dfn>"Jumping"</dfn> and <dfn>"Sliding"</dfn>.

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
    'query_string' => FALSE,
    'current_page' => $this->uri->segment(3),
    'base_url'     => '/obullo/index.php/welcome/start/index/',
);


// just dummy data
$params['item_data']  = range(1, 1000);

$data  = $pager->getPageData();
$pager = lib('ob/pager')->init($params);
$links = $pager->getLinks();

// $links is an ordered + associative array with 'back'/'pages'/'next'/'first'/'last'/'all' links.
// NB: $links['all'] is the same as $pager->links;

echo $links['all'];

echo '<hr />';

// Show data for current page:
echo 'PAGED DATA: '; print_r($data);

echo '<hr />';

// Results from methods:
echo 'getCurrentPage()...: '; var_dump($pager->getCurrentPage());
echo 'getNextPage()......: '; var_dump($pager->getNextPage());
echo 'getPrevPage()......: '; var_dump($pager->getPrevPage());
echo 'numItems()..........: '; var_dump($pager->numItems());
echo 'numPages()..........: '; var_dump($pager->numPages());
echo 'isFirstPage()......: '; var_dump($pager->isFirstPage());
echo 'isLastPage().......: '; var_dump($pager->isLastPage());
echo 'isLastPageEnd()...: '; var_dump($pager->isLastPageEnd());
echo '$pager->range........: '; var_dump($pager->range);
```

If want to use simple pager you can use Obullo Style urls you should set <samp>query_string = FALSE</samp> and you must set <samp>'current_page'</samp> parameter to your page segment ( $this->uri->segment(3) ) like this.

```php
$params = array(
    'mode'         => 'Sliding',  // Jumping
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'GET',
    'query_string' => FALSE,
    'current_page' => $this->uri->segment(3),
    'base_url'     => '/obullo/index.php/welcome/start/index/',
);
```

The page number 5 will be your segment 3.

```php
         segment                     0       1     2    3
http://localhost/obullo/index.php/welcome/start/index/5
```

### Jumping Mode

------

Change mode parameter as <samp>Jumping</samp> and increase <samp>Delta</samp> parameter.

```php
[1]  << Back 1  2  3  4  5  Next >>  [33]
```

### $pager->getLinks();

------

getLinks() function also will give you <b>link rel</b> = "" tags, You can get link rel style tags using <b>$links['link_tags'] or $link['link_tags_raw']</b>

```php
$links = $pager->getLinks();
print_r($links);

Array
(
    [0] => <a href="/obullo/index.php?d=...&page=2" title="previous page"><< Back</a>
    [1] => 3<a href="/obullo/index.php?d=...&page=4" title="page 4">4</a> 
    [2] => <a href="/obullo/index.php?d=...&page=4" title="next page">Next >></a>

    [3] => <a href="/obullo/index.php?d=...&page=1" title="first page">[1]</a> 
    [4] => <a href="/obullo/index.php?d=...&page=6" title="last page">[6]</a>
    [5] => <a href="/obullo/index.php?d=...&page=1" title="first page">[1]</a> <a href="/obullo/index.php?d=...&page=2" title="previous page"><< Back</a> 3 <a href="/obullo/index.php?d=...&page=4" title="page 4">4</a>  <a href="/obullo/index.php?d=...&page=4" title="next page">Next >></a> <a href="/obullo/index.php?d=...&page=6" title="last page">[6]</a>

    [6] => <link rel="first" href="/obullo/index.php?d=...&page=1" title="first page">
<link rel="previous" href="/obullo/index.php?d=...&page=2" title="previous page">
<link rel="next" href="/obullo/index.php?d=...&page=4" title="next page">
<link rel="last" href="/obullo/index.php?d=...&page=6" title="last page">

    [back] => <a href="/obullo/index.php?d=...&page=2" title="previous page"><< Back</a>
    [pages] => 3 <a href="/obullo/index.php?d=...&page=4" title="page 4">4</a> 

    [next] => <a href="/obullo/index.php?d=...&page=4" title="next page">Next >></a>
    [first] => <a href="/obullo/index.php?d=...&page=1" title="first page">[1]</a> 
    [last] => <a href="/obullo/index.php?d=...&page=6" title="last page">[6]</a>
    [all] => <a href="/obullo/index.php?d=...&page=1" title="first page">[1]</a> <a href="/obullo/index.php?d=...&page=2" title="previous page"><< Back</a> 3 <a href="/obullo/index.php?d=...&page=4" title="page 4">4</a>  <a href="/obullo/index.php?d=...&page=4" title="next page">Next >></a> <a href="/obullo/index.php?d=...&page=6" title="last page">[6]</a>

[link_tags] => <link rel="first" href="/obullo/index.php?d=...&page=1" title="first page">
<link rel="previous" href="/obullo/index.php?d=...&page=2" title="previous page">
<link rel="next" href="/obullo/index.php?d=...&page=4" title="next page">
<link rel="last" href="/obullo/index.php?d=...&page=6" title="last page">

    [link_tags_raw] => Array
        (
            [first] => Array
                (
                    [url] => /obullo/index.php?d=...&page=1
                    [title] => first page
                )

            [prev] => Array
                (
                    [url] => /obullo/index.php?d=...&page=2
                    [title] => previous page
                )

            [next] => Array
                (
                    [url] => /obullo/index.php?d=...&page=4
                    [title] => next page
                )

            [last] => Array
                (
                    [url] => /obullo/index.php?d=...&page=6
                    [title] => last page
                )

        )

)
```

**Note:**If you intend to use *rel links* you should set http_method param to GET for this because of *$links['link_tags']* or *$link['link_tags_raw']* variables gives result just with Http GET method.

### Paging Database Query Results

------

```php
$query = $this->db->query('SELECT * FROM articles');
$num_rows = $query->num_rows();

$params = array(
    'mode'         => 'sliding',  // jumping 
    'per_page'     => 5,
    'delta'        => 2,
    'http_method'  => 'GET',    
    'url_var'      => 'page',
    'query_string' => FALSE,      // If FALSE use Obullo style URLs
    'current_page' => $this->uri->segment(3),
    'base_url'     => '/obullo/index.php/welcome/start/index/',
    'total_items'  => $num_rows,
);

$pager = lib('ob/pager')->init($params);
 
list($from, $to) = $pager->get_offset_by_page();
 
echo 'from:'.$from.'<br />';
echo 'to:'.$to.'<br />';
 
$this->db->get('articles', $params['per_page'], $from - 1);
$data = $this->db->result_array();
 
echo $pager->links;

echo '<hr />';

print_r($data);
```

### Http GET Examples

------

If you prefer to Http GET methods, you must set these parameters <samp>http_method = GET , query_string = TRUE</samp> and add your extra variables to <samp>extra_vars</samp>.

```php
$params = array(
    'mode'         => 'jumping',  // sliding
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'GET',     // set http_method to GET
    'url_var'      => 'page',    // provide your PAGE query string
    'query_string' => TRUE,      // set query string to TRUE
    'base_url'     => '/obullo/index.php',  // Change your base url
    'total_items'  => $num_rows,
    'extra_vars'   => array('d'=>'welcome','c'=>'start', 'm' => 'index'),
); 
```

Above the settings produce this url. 

```php
http://localhost/obullo/index.php?d=welcome&c=start&m=index&page=3
```

**Note** If you get some errors be sure your *$config['enable_query_strings'] = TRUE;* parameter setted to TRUE in <dfn>app/config/config.php</dfn> file.

### Http POST Examples

------

If you prefer to Http POST methods, just change http_method and set query_string to FALSE.

```php
$params = array(
    'mode'         => 'jumping',  // jumping
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'POST',     // set http_method to POST
    'query_string' => FALSE,      // set query string to FALSE
    'base_url'     => '/obullo/index.php/welcome/start/index',  // Change your base url
    'total_items'  => $num_rows,
);
```

When you use POST method Pager render_link function will produce javascript onclick function for each html a tags. So code output of links shown as below.

```php
<a href="javascript:void(0);" onclick="
var form = document.createElement("form");
var input = ""; 
form.action = "/obullo/index.php/welcome/start/index"; 
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
return FALSE;">Page Number</a>
```

### Html Widgets

------

You have two HTML widgets called <samp>Per Page Select Box</samp> and <samp>Page Select Box</samp>

#### getPerPageSelectBox($start = integer, $end = integer, $step = integer, $show_all_data = FALSE, $extra_params = array() );

------

This function will produce html select menu to set_per_page parameter like this

```php
[1]  << Back 1  2  3  4  5  Next >>  [33]  
Per Page
```

### Per Page Select Box Example

```php
<?php
Class Start extends Controller {
    
    function __construct()
    {   
        parent::__construct();
        
        loader::database();
        loader::helper('ob/form');
    }           
    
    public function index()
    {                  
        view_var('title', 'Pager Test !');
        
        $query = $this->db->query('SELECT * FROM articles');
        $num_rows = $query->num_rows();
        
        $per_page = (i_get_post('set_per_page')) ? i_get_post('set_per_page') : '5';
        
        $params = array(
            'mode'         => 'sliding',  // jumping
            'per_page'     => $per_page,
            'delta'        => 2,
            'http_method'  => 'GET',    
            'url_var'      => 'page',
            'query_string' => TRUE,      // If FALSE use Obullo style URLs
            'base_url'     => '/obullo/index.php',
            'total_items'  => $num_rows,
            'extra_vars'   => array('d'=>'welcome','c'=>'start', 'm' => 'index', 'set_per_page' => $per_page),
        );
        
        $pager = lib('ob/pager')->init($params);
         
        list($from, $to) = $pager->get_offset_by_page();
         
        $this->db->get('articles', $params['per_page'], $from - 1);
        $data['rows'] = $this->db->result();
        
        $data['params'] = $params;
        $data['links']  = $pager->getLinks();
        $data['per_page_select_box']  = $pager->getPerPageSelectBox(5, 50, 5, FALSE);

        view('view_pager_test', $data, FALSE);
    }
       
}
/* End of file start.php */
```

and <samp>view_pager_test.php</samp> file should be like this 

```php
<h1><? echo view_var('title'); ?></h1> 

<p><? print 'PAGED DATA: <br />'; var_dump($rows); ?></p>

<?php

$hiddens = array(
'd' => 'tests',
'c' => 'start',
'm' => 'index',
);

echo form_open('', array('method' => $params['http_method']), $hiddens);

echo '<br />'.$links['all'].'    Per Page '.$per_page_select_box.' ';

echo form_submit('_send', 'Send', "");
echo form_close();

?>
```

and URL for this example 

```php
http://localhost/obullo/index.php?d=welcome&c=start&m=index
```

 If you want to use <samp>onchange = ""</samp> for selectbox instead SEND button you must provide <samp>auto_submit</samp> as extra parameter. Remove <b>form_helper</b> functions from the view_test_pager and add extra parameter to get_per_page_select_box in start controller. 

```php
$pager->getPerPageSelectBox(5, 50, 5, FALSE, array('auto_submit' => TRUE));
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
Class Start extends Controller {
    
    function __construct()
    {   
        parent::__construct();
        
        loader::database();
        loader::helper('ob/form');
    }           
    
    public function index()
    {                  
        view_var('title', 'Pager Test !');
        
        $query = $this->db->query('SELECT * FROM articles');
        $num_rows = $query->num_rows();
        
        $per_page = (i_get_post('set_per_page') != '') ? i_get_post('set_per_page') : '5';
        
        $params = array(
            'mode'         => 'sliding',  // jumping
            'per_page'     => $per_page,
            'delta'        => 2,
            'http_method'  => 'POST',    
            'query_string' => FALSE,
            'base_url'     => '/obullo/index.php/welcome/start/index',
            'total_items'  => $num_rows,
            'extra_vars'   => array('set_per_page' => $per_page),
        );
        
        $pager = lib('ob/pager')->init($params);
         
        list($from, $to) = $pager->get_offset_by_page();
         
        $this->db->get('articles', $params['per_page'], $from - 1);
        $data['rows'] = $this->db->result();
        
        $data['params'] = $params;
        $data['links']  = $pager->getLinks();
        
        $data['page_select_box']      = $pager->getPageSelectBox(array('auto_submit' => TRUE));  // auto submit
        $data['per_page_select_box']  = $pager->getPerPageSelectBox(5, 50, 5, FALSE, array('auto_submit' => TRUE));
        
        view('view_pager_test', $data, FALSE); 
    }
    
}
```

and <samp>view_pager_test.php</samp> file should be like this 

```php
<h1><?php echo view_var('title'); ?></h1>

<p><? print 'PAGED DATA: <br />'; var_dump($rows); ?></p>

<br />

<?php

echo $links['first']. $links['back'].'   '.$page_select_box.'   '.$links['next'].'   '.$links['last'];
echo '   Per Page    '.$per_page_select_box.'   ';

?>
```

### Html Widgets Extra Parameters

------

Additionaly you can set <samp>option_text</samp> parameter to customize select menu texts. Change your codes like this

```php
$page_options     = array('auto_submit' => TRUE, 'option_text' => 'Go to page %d');
$per_page_options = array('auto_submit' => TRUE, 'option_text' => 'Show %d data');

$data['page_select_box']      = $pager->getPageSelectBox($page_options);
$data['per_page_select_box']  = $pager->getPerPageSelectBox(5, 50, 5, FALSE, $per_page_options); 
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
      <th>Parameters</th><th>Description</th></thead><tbody><tr>
<td>alt_first, alt_prev, alt_next, alt_last, alt_page</td><td>Specify alternate text to display for the first, last, next and previous page numbers. It's generally a good idea to include these keys so that users with older browsers can still see the navigation links.</td></tr>
<tr>
<td>separator, space_before_separator, space_after_separator</td><td>Specifies the symbol to use between page links, while the spaces_before_separator and spaces_after_separator keys specify the space before and after each separator. These keys are useful to control the space between each page link.</td>/tr>
<tr>
<td>cur_page_link_class, link_class</td><td>Specifies the name of the CSS class used to style each page link. cur_page_link_class specifies selected page css.</td>/tr>
<tr>
<td>first_page_text, last_page_text</td><td>Specify the text that should appear instead of the first and last page numbers.</td>/tr>
<tr>
<td>prev_img, next_img</td><td>	Specify the text that should appear for the next and previous page links. HTML code can also be used in place of plain text for all these; this means that you can use img tags</td>/tr>
<tr>
<td>cur_page_span_pre, cur_page_span_post</td><td>String texts before and after current page link</td>/tr>
<tr>
<td>first_page_pre = '[' , first_page_post = ']'</td><td>String texts before and after first page link default "[" "]"</td>/tr>
<tr>
<td>last_page_pre = '[' , last_page_post = ']'</td><td>String texts before and after last page link default "[" "]"</td>/tr></table>

```php
$params = array(
    'mode'         => 'jumping',  // sliding
    'per_page'     => $per_page,
    'delta'        => 2,
    'http_method'  => 'POST',    
    'query_string' => FALSE,
    'base_url'     => '/obullo/index.php/tests/start/index',
    'total_items'  => $num_rows,
    'extra_vars'   => array('set_per_page' => $per_page),
    
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
    'link_class' => 'my_pager'           // css customization

    'first_page_text' => '« First Page',
    'last_page_text'  => '» Last Page',

    'prev_img'   => '‹ Prev Page',   // you can use <img src = '' /> tags.
    'next_img'   => '› Next Page',
); 
```

Add this css codes to your view or css file. .my_pager {

```php
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

Special thanks to [Lorenzo Alberton](http://www.alberton.info/) who originally developed by PEAR pager library.
