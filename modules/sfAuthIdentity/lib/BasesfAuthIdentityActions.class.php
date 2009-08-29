<?php

/**
 * Base actions for the sfAuthPlugin sfAuthIdentity module.
 * 
 * @package     sfAuthPlugin
 * @subpackage  sfAuthIdentity
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BasesfAuthIdentityActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    { 
        $this->identities = Doctrine::getTable('sfAuthIdentity')->getAllByUser($this->getUser());
    }
    
    public function executeNew(sfWebRequest $request)
    {
        //debug to see how the identities urls look like
        if ($request->isMethod('post') && $request->hasParameter('token')) {
            $identity = sfAuthIdentity::createByTokenAndUser($request->getParameter('token'), $this->getUser());
            
            if (is_object($identity)) {
                $this->getUser()->setFlash('success', $this->getContext()->getI18N()->__('Identity added to your profile'));
                return $this->redirect('sfAuthIdentity/index');
            }
            
            $this->getUser()->setFlash('error', $this->getContext()->getI18N()->__($identity));
        }
        
        $this->redirect('sfAuthIdentity/index');
    }
    
    public function executeDelete(sfWebRequest $request)
    {
        //Have id params
        $this->forward404Unless($request->hasParameter('id'));
        
        //Try to delete
        $ok = sfAuthIdentity::deleteByIdAndUser($request->getParameter('id'), $this->getUser());
        
        //404 if failed
        $this->forward404Unless($ok);
        
        //flash and redirect
        $this->getUser()->setFlash('success', $this->getContext()->getI18N()->__('The identity was deleted'));
        $this->redirect('sfAuthIdentity/index');
    }
}
