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
        $action = ($this->request->get('action')) ? $this->request->get('action') : 'logout';

        switch ($action) {            

        case 'destroyMe':
            $this->user->identity->destroy();  // Destroy all the identity cache and logout.
            $this->flash->info('Identity cache destroyed and you have succesfully logged out');
            break;

        case 'forgetMe':
            $this->user->identity->logout();   // Mark user as logged out but identity cache still in memory.
            $this->user->identity->forgetMe(); // Remove rember me cookie.
            $this->flash->info('Remember me cookie removed and you have succesfully logged out');
            break;

        case 'logout':
            $this->user->identity->logout();  // Mark user as logged out but identity cache still in memory.
                                              // Next login will perform without no database query.
            $this->flash->info('You have succesfully logged out');
            break;
        }
        
        return $this->response->redirect('/examples/membership/login/index');
    }
}