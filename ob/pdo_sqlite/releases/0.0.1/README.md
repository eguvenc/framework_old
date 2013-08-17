

$params = array(
    'mode'         => 'Sliding',  // Jumping
    'per_page'     => 8,
    'delta'        => 2,
    'http_method'  => 'GET',
    'query_string' => false,
    'current_page' => $this->uri->segment(2),
    'base_url'     => '/welcome/index/',
);

// just dummy data
$params['item_data']  = range(1, 1000);

$pagerClass = new Pager();
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
echo 'getCurrentPage()...: '; var_dump($pager->getCurrentPage());
echo 'getNextPage()......: '; var_dump($pager->getNextPage());
echo 'getPrevPage()......: '; var_dump($pager->getPrevPage());
echo 'numItems()..........: '; var_dump($pager->numItems());
echo 'numPages()..........: '; var_dump($pager->numPages());
echo 'isFirstPage()......: '; var_dump($pager->isFirstPage());
echo 'isLastPage().......: '; var_dump($pager->isLastPage());
echo 'isLastPageEnd()...: '; var_dump($pager->isLastPageEnd());
echo '$pager->range........: '; var_dump($pager->range);
   