<?php


class sfAuthUserResetPasswordValidator extends sfValidatorBase
{
    public function configure($options = array(), $messages = array())
    {
    }
    
    public function doClean($values)
    {
        if (!isset($values['password'], $values['reset_hash'])) {
            throw new sfException('Error');
        }
        
        $user = Doctrine::getTable('sfAuthUser')->findOneByResetHash($values['reset_hash']);
        if ($user) {
            $user->setPassword($values['password']);
            $user->setResetHash(null);
            $user->setResetHashCreatedAt(null);
            $user->save();
            
            return array_merge($values, array('user' => $user));
        }
        
        throw new sfValidatorError($this, 'Hash did not match any users');
    }
}