<?php

/**
 * Base actions for the sfAuthPlugin user module.
 * 
 * @package     sfAuthPlugin
 * @subpackage  user
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BasesfAuthUserActions extends sfActions
{
    /**
     * Renders a form that makes it possible for the user to login
     * @param sfWebRequest $request
     */
    public function executeNew(sfWebRequest $request)
    {
        $form_name = (string) sfConfig::get('app_sf_auth_plugin_form_register');
        $this->form = new $form_name;
        
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('sf_auth_user'));
            
            if ($this->form->isValid() && $this->form->save()) {
                //Event so the user can choose their own way to do activation.
                $this->dispatcher->notify(new sfEvent($this, 'sf_auth.new', array('sfAuthUser' => $this->form->getObject())));
                
                $this->redirect('@homepage');
            }
        }
    }
    
    /**
     * Renders a form that makes it possible for the user to login
     * @param sfWebRequest $request
     */
    public function executeLogin(sfWebRequest $request)
    {
        //set the referrer used when loggin in.
        $this->getUser()->setReferer($this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer());
        
        $this->form = new sfAuthSigninForm();
        
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('sf_auth_signin'));
            
            if ($this->form->isValid()) {
                $this->getUser()->setFlash('success', $this->getContext()->getI18N()->__('Welcome back :)'));
                
                $referer = $this->getUser()->getReferer($request->getReferer());
                $this->redirectUnless(empty($referer), $referer);
                $this->redirect('@homepage');   
            }
        }
    }
    
    public function executeRpx(sfWebRequest $request)
    {
        if ($request->isMethod('post') && $request->hasParameter('token')) {
            $rpx = $this->getUser()->getRPXObjectByTokenAndLogin($request->getParameter('token'));
            
            //Login failed and the user isnt a user, redirect to the create page
            //and call the event dispatcher
            if (!$this->getUser()->isAuthenticated()) {
                //Notify that the user couldnt login by rpx and let the developer decide what to do.
                $event = $this->dispatcher->notify(new sfEvent($this, 'sf_auth.rpx_no_user', array('rpx' => $rpx, 'request' => $request)));
                
                //Default behavior if the user wasnt logged in by rpx
                if (!$event->isProcessed()) {
                    //No event processed setFlash and redirect
                    $this->getUser()->setFlash('notice', 'No user found. Please create a user and add your identity');
                    $this->redirect('sfAuthUser/new');
                }
            }
            
            $referer = $this->getUser()->getReferer();
            $this->redirectUnless(empty($referer), $referer);
            $this->redirect('@homepage');
        }
        
        //This shouldnt be visible to the end user so always redirect
        $this->redirect('sfAuthUser/login');
    }
    
    /**
     * Signs a user out and redirects back to the frontpage
     * @param sfWebRequest $request
     */
    public function executeLogout(sfWebRequest $request)
    {
        $sfAuthUser = $this->getUser()->getAuthUser();
        $this->getUser()->logout();
        
        //Event dispatch here so the developer can set a user to offline or something
        $this->dispatcher->notify(new sfEvent($this, 'sf_auth.logout', array('sfAuthUser' => $sfAuthUser)));
        
        $this->redirect('@homepage');
    }
    
    public function executeRequest_password(sfWebRequest $request)
    {
        $this->form = new sfAuthUserRequestPasswordForm();
        
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('sf_auth_request_password'));
            
            if ($this->form->isValid()) {
                //The form is valid a hash have been generated now send email
                $this->dispatcher->notify(new sfEvent($this, 'sf_auth.request_password', array('sfAuthUser' => $this->form->getObject())));
                $this->redirect('@homepage');
            }
        }
    }
    
    public function executeReset_password(sfWebRequest $request)
    {
        $this->form = new sfAuthUserResetPasswordForm();
        if ($this->getUser()->isAuthenticated()) {
            $this->form = new sfAuthUserResetPasswordUserForm($this->getUser()->getAuthUser());
        }
        
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $this->form->bind($request->getParameter('sf_auth_reset_password'));
            
            if ($this->form->isValid() && $this->form->save()) {
                //The password would have been saved redirect based on user is logged in or not
                if (!$this->getUser()->isAuthenticated()) {
                    $this->getUser()->login($this->form->getValue('user'));
                    $this->redirect('@homepage');
                }
                
                $this->redirect('sfAuthUser/reset_password');
            }
        }
    }
    
    public function executeActivate(sfWebRequest $request)
    {
        $this->form = new sfAuthUserActivateForm();
        
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('sf_auth_user_activate'));
            
            if ($this->form->isValid()) {
                //User is activated and now logged ind
                $this->redirect('@homepage');
            }
        }
    }
}
