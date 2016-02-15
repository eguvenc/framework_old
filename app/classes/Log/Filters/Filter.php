<?php

namespace Log\Filters;

use Obullo\Container\ParamsAwareTrait;
use Obullo\Container\ParamsAwareInterface;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

/**
 * Filter
 * 
 * @copyright 2009-2016 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Filter implements ImmutableContainerAwareInterface, ParamsAwareInterface
{
    use ImmutableContainerAwareTrait, ParamsAwareTrait;

    /**
     * Handle record array
     * 
     * @param array $record unformatted record data
     * 
     * @return array|null
     */
    public function method(array $record)
    {

    }
}