<?php

class sfAuthUserAutoLoginFilter extends sfFilter
{
    public function execute($filterChain)
    {
        if ($this->isFirstCall()) {
            $context = sfContext::getInstance();
            
            if (!$context->getUser()->isAuthenticated()) {
                $request        = $context->getRequest();
                $cookie_name    = sfConfig::get('app_sf_auth_plugin_cookie_name');
                
                if ($request->getCookie($cookie_name)) {
                    if ($user = Doctrine::getTable('sfAuthUser')->findOneByRememberMeHash($request->getCookie($cookie_name))) {
                        $context->getUser()->login($user);
                    }
                }
            }
        }
        
        $filterChain->execute();
    }
}

?>