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
                    'tpl.elementDiv' => '<div class="col-sm-12" %s>
                    <label class="label-control col-sm-3">%s</label>
                        <div class="form-group col-sm-9">
                            %s
                        </div>
                    </div>',
                    'tpl.descriptionDiv' => '<div class="col-sm-12" style="margin-top: -10px;margin-left: 3px">
                    <label class="label-control col-sm-3"></label>
                        <div class="form-group col-sm-9">
                            %s
                        </div>
                    </div>',
                    'tpl.groupDescriptionDiv' => '<div class="col-sm-12" style="margin-top: -10px;margin-left: 3px">
                    <label class="label-control col-sm-3"></label>
                        <div class="form-group col-sm-9">
                            %s
                        </div>
                    </div>',
                    'tpl.groupElementDiv' => array(
                        'groupedDiv' => '<div class="col-sm-12" %s>
                            <div class="col-sm-3"></div>
                            <div id="%s_inputError" class="col-sm-9"></div>
                            <label class="label-control col-sm-3">%s</label>
                            %s
                        </div>',
                        // We replace groupedDiv's last "%s" with parentDiv value after that foreach process.
                        'parentDiv' => '##label##
                                <div class="form-group col-sm-%d">%s</div>',
                        // The label field is not empty, we are replace the parenDiv => ##label##
                        'parentLabel' => '<label class="label-control col-sm-1" for="">%s</label>'
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