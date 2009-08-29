<?php

class sfAuthUserActivateValidator extends sfValidatorBase
{
    public function configure($options = array(), $messages = array())
    {
    }
    
    public function doClean($value)
    {
        $user = Doctrine::getTable('sfAuthUser')->findOneByActivateHash($value);
        
        if ($user) {
            if ($user->getIsActive()) {
                //Throw user already activated
                throw new sfValidatorError($this, 'already activated');
            }
            
            //Save active state
            $user->setIsActive(1);
            $user->save();
            
            //Login
            sfContext::getInstance()->getUser()->login($user);
            
            return $value;
        }
        
        //Throw user not found
        throw new sfValidatorError($this, 'activation hash invalid');
    }

}