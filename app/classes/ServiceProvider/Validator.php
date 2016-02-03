<?php

namespace ServiceProvider;

use Obullo\Container\ServiceProvider\AbstractServiceProvider;

class Validator extends AbstractServiceProvider
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
        'validator'
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

        $container->share('validator', 'Obullo\Validator\Validator')
            ->withArgument($container)
            ->withArgument($container->get('config'))
            ->withArgument($container->get('request'))
            ->withArgument($container->get('translator'))
            ->withArgument($container->get('logger'))
            ->withArgument(
                [
                    'rules' => [
                    
                        'alpha' => 'Obullo\Validator\Rules\Alpha',
                        'alphadash' => 'Obullo\Validator\Rules\AlphaDash',
                        'alnum' => 'Obullo\Validator\Rules\Alnum',
                        'alnumdash' => 'Obullo\Validator\Rules\AlnumDash',
                        'csrf' => 'Obullo\Validator\Rules\Csrf',
                        'captcha' => 'Obullo\Validator\Rules\Captcha',
                        'recaptcha' => 'Obullo\Validator\Rules\ReCaptcha',
                        'date' => 'Obullo\Validator\Rules\Date',
                        'email' => 'Obullo\Validator\Rules\Email',
                        'exact' => 'Obullo\Validator\Rules\Exact',
                        'iban' => 'Obullo\Validator\Rules\Iban',
                        'isbool' => 'Obullo\Validator\Rules\IsBool',
                        'isdecimal' => 'Obullo\Validator\Rules\IsDecimal',
                        'isjson' => 'Obullo\Validator\Rules\IsJson',
                        'isnumeric' => 'Obullo\Validator\Rules\IsNumeric',
                        'matches' => 'Obullo\Validator\Rules\Matches',
                        'max' => 'Obullo\Validator\Rules\Max',
                        'md5' => 'Obullo\Validator\Rules\Md5',
                        'min' => 'Obullo\Validator\Rules\Min',
                        'required' => 'Obullo\Validator\Rules\Required',
                        'trim' => 'Obullo\Validator\Rules\Trim'
                    ]
                ]
            );
    }
}