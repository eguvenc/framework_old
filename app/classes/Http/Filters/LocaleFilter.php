<?php

namespace Http\Filters;

use Obullo\Container\Container;
use Obullo\Application\Addons\LocaleRewriteTrait;

class LocaleFilter
{
    use LocaleRewriteTrait;

    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        $this->rewrite();
    }
}

// END LocaleFilter class

/* End of file LocaleFilter.php */
/* Location: .Http/Filters/LocaleFilter.php */