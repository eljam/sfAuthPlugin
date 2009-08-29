<?php

class sfAuthSecurityUser extends sfBasicSecurityUser
{
    /**
     * @var sfAuthUser
     */
    private $user = null;
    
    public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
    {
        parent::initialize($dispatcher, $storage, $options);
    
        if (!$this->isAuthenticated()) {
            // remove user if timeout
            $this->getAttributeHolder()->removeNamespace('sfGuardSecurityUser');
            $this->user = null;
        }
    }
    
    public function getReferer($default)
    {
        $referer = $this->getAttribute('referer', $default);
        $this->getAttributeHolder()->remove('referer');
    
        return $referer;
    }
    
    public function setReferer($referer)
    {
        if (!$this->hasAttribute('referer')) {
            $this->setAttribute('referer', $referer);
        }
    }

    
    public function getAuthUser()
    {
        if (!$this->user && $id = $this->getAttribute('user_id', null, 'sfAuthUser')) {
            $this->user = Doctrine::getTable('sfAuthUser')->find($id);
            if (!$this->user) {
                $this->logout();
                throw new sfException('The user does not exist in the database anymore.');
            }
        }
        
        return $this->user;
    }
    
    public function login(sfAuthUser $user, $remember = false)
    {
        $context        = sfContext::getInstance();
        $sfUser         = $context->getUser();
        $request        = $context->getRequest();
        $response       = $context->getResponse();
        $credentials    = $user->getAllCredentials();
        
        $sfUser->setAttribute('user_id', $user->getId(), 'sfAuthUser');
        $sfUser->setAuthenticated(true);
        $sfUser->clearCredentials();
        $sfUser->addCredentials($user->getAllCredentials());
        
        if ($remember) {
            //generate a hash for the remember_me_hash column
            $user->setRememberMeHash(sfAuthUtil::getHashedPasswordBySaltAndString($user->getUsername(), 'cookie'));
            
            //Configuration vars
            $expire = sfConfig::get('app_sf_auth_plugin_cookie_expire', 1209600) + time();
            $cookie_name = sfConfig::get('app_sf_auth_plugin_cookie_name', 'sfAuthUserCookie');
            
            //if the cookie already exists destroy it.
            if ($request->getCookie($cookie_name)) {
                $response->setCookie($cookie_name, '', time() - 3600);
            }
            
            $response->setCookie($cookie_name, $user->getRememberMeHash(), $expire);
            $user->save();
        }
        
        //Event dispatcher the user have logged in... Useful for setting Culture
        //etc when a user have logged in that may recide in another table
        $this->dispatcher->notify(new sfEvent($this, 'sf_auth.login'));
    }
    
    public function getRPXObjectByTokenAndLogin($token)
    {
        $rpx        = new RPX();
        $response   = $rpx->call('auth_info', array('token' => $token));
        
        if ($response->stat == 'ok') {
            $identity = Doctrine::getTable('sfAuthIdentity')->findOneByUrl($response->profile->identifier);
            if ($identity) {
                $this->login($identity->getUser());
            }
        }
        
        return $response;
    }
    
    public function isPasswordValid(sfAuthUser $user, $password)
    {        
        if ($user->getPassword() === sfAuthUtil::getHashedPasswordBySaltAndString($user->getSalt(), $password)) {
            return true;
        }
        
        //if we havent returned yet use the event dispatcher to notify until an event returns true
        $event = $this->dispatcher->notifyUntil(new sfEvent($this, 'sf_auth.is_password_valid'));
        
        if ($event->isProcessed()) {
            return $event->getReturnValue();
        }
        
        return false;
    }
    
    public function logout() {
        $this->getAttributeHolder()->removeNamespace('sfGuardSecurityUser');
        $this->clearCredentials();
        $this->setAuthenticated(false);
        
        $cookie_name = sfConfig::get('app_sf_auth_plugin_cookie_name', 'sfAuthUserCookie');
        sfContext::getInstance()->getResponse()->setCookie($cookie_name, '', time() - 3600);
    }
    
    public function isSuperAdmin()
    {
        return $this->getAuthUser() ? $this->getAuthUser()->getIsSuperAdmin() : false;
    }
    
    public function isAnonymous()
    {
        return (bool) !$this->isAuthenticated();
    }
    
    public function hasCredential($credential, $useAnd = true)
    {
        if (empty($credential)) return true;
        if ($this->isSuperAdmin()) return true;
        
        return parent::hasCredential($credential, $useAnd);
    }
    
    public function __toString()
    {
        return $this->getAuthUser()->__toString();
    }
    
    public function __call($method, $arguments)
    {
        if ($this->getAuthUser()) {
            return call_user_func_array(array($this->getAuthUser(), $method), $arguments);
        }
    }
}

?>