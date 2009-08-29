<?php
/**
 * A User validator that will sign a user in
 */
class sfAuthUserLoginValidator extends sfValidatorBase
{
    /**
     * Must be here
     *
     * @param array $options
     * @param array $messages
     */
    public function configure($options = array(), $messages = array())
    {
    }
    
    /**
     * Checks form values and logs the user in
     *
     * @param array $values
     * @throw sfValidatorErrorSchama
     * @return array
     */
    public function doClean($values)
    {
        $username       = isset($values['username']) ? $values['username'] : '';
        $password       = isset($values['password']) ? $values['password'] : '';
        $remember_me    = isset($values['remember_me']) ? (bool) $values['remember_me'] : false;
        
        $table          = Doctrine::getTable('sfAuthUser'); 
        $field          = ucfirst(sfConfig::get('app_sf_auth_plugin_username_field'));
        $sfUser         = sfContext::getInstance()->getUser();
        
        if ($username && $user = call_user_func_array(array($table, 'findOneBy' . $field), $username)) {
            if ($sfUser->isPasswordValid($user, $password)) {             
                if ($user->getIsActive()) {
                    $sfUser->login($user, $remember_me);
                    return array_merge($values, array('user' => $user));
                }
                
                //User is not activated
                throw new sfValidatorErrorSchema($this, array('username' => new sfValidatorError($this, 'not activated')));
            }
        }
        
        //Password or user is invalid
        throw new sfValidatorErrorSchema($this, array('username' => new sfValidatorError($this, 'invalid')));
    }
}

?>