<?php

class sfAuthSigninForm extends sfForm
{
    public function configure()
    {
        $this->setWidgets(array(
            'username'      => new sfWidgetFormInput(),
            'password'      => new sfWidgetFormInputPassword(),
            'remember_me'   => new sfWidgetFormInputCheckbox(),
        ));
        
        $this->setValidators(array(
            'username'      => new sfValidatorDoctrineChoice(array(
                'required'      => true,
                'model'         => 'sfAuthUser',
                'column'        => sfConfig::get('app_sf_auth_plugin_username_field', 'email'),
            )),
            'password'      => new sfValidatorString(array('required' => true)),
            'remember_me'   => new sfValidatorBoolean(),
        ));
        
        $this->validatorSchema->setPostValidator(new sfAuthUserLoginValidator());
        
        $this->widgetSchema->setNameFormat('sf_auth_signin[%s]');
    }
}

?>