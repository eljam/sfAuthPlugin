<?php

class sfAuthUserRequestPasswordValidator extends sfValidatorBase
{
    public function configure($options = array(), $messages = array())
    {
    }
    
    public function doClean($values)
    {
        if (!isset($values['email'])) {
            throw sfException('Error');
        }
        
        $user = Doctrine::getTable('sfAuthUser')->findOneByEmail($values['email']);
        
        if ($user) {
            if ($user->getResetHash() && time() < ($user->getResetHashCreatedAt() + 86400)) {
                throw new sfValidatorError($this, 'You have requested a password less than 24 hours ago');
            }
            
            $user->setResetHash(sfAuthUtil::getHashedPasswordBySaltAndString(time(), uniqid()));
            $user->setResetHashCreatedAt(time());
            $user->save();
            
            return array_merge($values, array('user' => $user));
        }
        
        //Throw user cant be found
        throw new sfValidatorError($this, 'User not found');
    }
}