
$pagerClass = new Pager();

$params = array(
    'mode'         => 'Sliding',  // Jumping
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'GET',
    'query_string' => false,
    'current_page' => $this->uri->segment(2),
    'base_url'     => '/welcome/index/',
);

$params['item_data']  = range(1, 1000); // just dummy data

$pager = $pagerClass->init($params);

$data  = $pager->getPageData();
$links = $pager->get_links();

// $links is an ordered + associative array with 'back'/'pages'/'next'/'first'/'last'/'all' links.
// NB: $links['all'] is the same as $pager->links;

echo $links['all'];

echo '<hr />';

// Show data for current page:
echo 'PAGED DATA: '; print_r($data);

echo '<hr />';

// Results from methods:
echo 'get_current_page()...: '; var_dump($pager->get_current_page());
echo 'get_next_page()......: '; var_dump($pager->get_next_page());
echo 'get_prev_page()......: '; var_dump($pager->get_prev_page());
echo 'num_items()..........: '; var_dump($pager->num_items());
echo 'num_pages()..........: '; var_dump($pager->num_pages());
echo 'is_first_page()......: '; var_dump($pager->is_first_page());
echo 'is_last_page().......: '; var_dump($pager->is_last_page());
echo 'is_last_page_end()...: '; var_dump($pager->is_last_page_end());
echo '$pager->range........: '; var_dump($pager->range);
   