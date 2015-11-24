<?php

namespace Examples\Membership;

use Obullo\Http\Controller;

class Logout extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->user->identity->logout();      // Don't remove the user identity from cache just logout user. ( We use cached identity if user come back )

        // $this->user->identity->destroy();  // Remove the identity from cache and logout. ( So application needs to do sql query )
        // $this->user->identity->forgetMe(); // Remove rember me cookie from cookie.
        
        $this->flash->info('You succesfully logged out')
            ->response->redirect('/examples/membership/login/index');
    }
}