<?php

namespace ServiceProvider;

use Obullo\Container\ServiceProvider\AbstractServiceProvider;

class Captcha extends AbstractServiceProvider
{
    /**
     * The provides array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        'captcha'
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();
        $params    = $this->getConfiguration('captcha')->getParams();

        $captcha = $container->share('captcha', 'Obullo\Captcha\Provider\Image')
            ->withArgument($container->get('url'))
            ->withArgument($container->get('request'))
            ->withArgument($container->get('session'))
            ->withArgument($container->get('logger'))
            ->withArgument($params);

        // $captcha->withMethodCall('setTranslator', $container->get('translator'));

        $captcha->withMethodCall(
            'setImageAttributes',
            [
                [
                    'src'   =>  '/captcha/index/',
                    'style' => 'display:block;float:left;margin: 0px 10px 10px 0px;',
                    'id'    => 'captcha_image',
                    'class' => ''
                ]
            ]
        );
        $captcha->withMethodCall(
            'setInputAttributes',
            [
                [
                    'type'  => 'text',
                    'name'  => 'captcha_answer',
                    'class' => 'form-control',
                    'id'    => 'captcha_answer',      
                    'placeholder' => 'Security Code'
                ]
            ]
        );
        $captcha->withMethodCall(
            'setRefreshButton',
            [
                '<button type="button" style="margin-top:10px;" onclick="refreshCaptcha(this.form);" class="btn btn-default" aria-label="Left Align">
                  <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
                  Refresh
                </button>'
            ]
        );
        $captcha->withMethodCall('setCharset', [$container->get('config')->get('config')['locale']['charset']]);  // UTF-8
        $captcha->withMethodCall('setBackground', ['secure']);  // none
        $captcha->withMethodCall('setPool', ['alpha']);  // numbers, random
        $captcha->withMethodCall('setChar', [5]);
        $captcha->withMethodCall('setFont', [['Almontew','Anglican','Heras']]);
        $captcha->withMethodCall('setFontSize', [20]);
        $captcha->withMethodCall('setHeight', [36]);
        $captcha->withMethodCall('setWave', [false]);
        $captcha->withMethodCall('setColor', ['red', 'black']);  // blue , green, yellow, cyan
        $captcha->withMethodCall('setTrueColor', [false]);
        $captcha->withMethodCall('setNoiseColor', ['red']);
    }
}