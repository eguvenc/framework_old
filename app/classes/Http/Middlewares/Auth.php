<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Authentication\Middleware\UniqueSessionTrait;
;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Container\ContainerInterface as Container;
use Obullo\Authentication\User\UserInterface as User;

class Auth implements MiddlewareInterface, ContainerAwareInterface
{
    use UniqueSessionTrait;

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
        if ($this->user->identity->check()) {
    
            $this->killSessions();  // Terminate multiple logins
            
            // $this->user->activity->set('last', time());

        }
        $err = null;

        return $next($request, $response, $err);
    }
}