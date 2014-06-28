<?php

namespace Service\Jelly;

use Service\ServiceInterface;
use Obullo\Jelly\Form as JellyForm;

/**
 * Cache Service
 *
 * @category  Service
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/container
 */
Class Form implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register($c)
    {
        $c['jelly/form'] = function () use ($c) {
            return new JellyForm(
                $c, 
                array(
                    'db.form_tablename'    => 'forms',
                    'db.group_tablename'   => 'form_groups',
                    'db.option_tablename'  => 'form_options',
                    'db.element_tablename' => 'form_elements',
                    'tpl.elementDiv' => '<div class="form-group">
                    <label class="col-sm-2 control-label">%s</label>
                        <div class="col-sm-8">
                            %s
                        </div>
                    </div>',
                    'tpl.groupElementDiv' => array(
                        'groupedDiv' => '<div class="form-group">
                            <label class="col-sm-2 control-label">%s</label>
                            %s
                        </div>', // We replace groupedDiv's "%s"  with parentDiv value after that foreach process.
                        'parentDiv' => '<div class="col-sm-2">%s</div>'
                    ),
                    'ajax.function' => 'submitAjax(%s)'
                )
            );
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Cache.php */