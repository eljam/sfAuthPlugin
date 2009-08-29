<?php

class sfAuthRouting
{
    static public function attach(sfEvent $event)
    {
        $routing = $event->getSubject();
        
        //Login
        $routing->prependRoute('sf_auth_login', new sfRoute('/login', array(
            'module' => 'sfAuthUser',
            'action' => 'login',
        )));
        
        //Logout
        $routing->prependRoute('sf_auth_logout', new sfRoute('/logout', array(
            'module' => 'sfAuthUser',
            'action' => 'logout',
        )));
        
        //New
        $routing->prependRoute('sf_auth_new', new sfRoute('/register', array(
            'module' => 'sfAuthUser',
            'action' => 'new',
        )));
        
        //activate
        $routing->prependRoute('sf_auth_activate', new sfRoute('/activate', array(
            'module' => 'sfAuthUser',
            'action' => 'activate',
        )));
        
        //reset password
        $routing->prependRoute('sf_auth_reset_password', new sfRoute('/reset_password', array(
            'module' => 'sfAuthUser',
            'action' => 'reset_password',
        )));
        
        //request password
        $routing->prependRoute('sf_auth_request_password', new sfRoute('/request_password', array(
            'module' => 'sfAuthUser',
            'action' => 'request_password',
        )));
    }

}

?>