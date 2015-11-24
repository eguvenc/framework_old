<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Container\ContainerInterface as Container;
use Obullo\Authentication\User\UserInterface as User;

class Guest implements MiddlewareInterface, ContainerAwareInterface
{
    protected $user;

    /**
     * Constructor
     * 
     * @param User $user auth user controller
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container object or null
     *
     * @return void
     */
    public function setContainer(Container $container = null)
    {
        $this->c = $container;
    }
    
    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        if ($this->user->identity->guest()) {

            $this->c['flash']->info('Your session has been expired.');

            return $response->redirect('/examples/membership/login/index');
        }
        $err = null;

        return $next($request, $response, $err);
    }
}