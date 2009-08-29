<?php

class sfAuthUserResetPasswordUserForm extends BaseFormDoctrine
{
    public function configure()
    {
        $this->setWidgets(array(
            'password'              => new sfWidgetFormInputPassword(),
            'password_confirmation' => new sfWidgetFormInputPassword(),
        ));
        
        $this->setValidators(array(
            'password'              => new sfValidatorString(array('required' => true)),
            'password_confirmation' => new sfValidatorString(array('required' => true)),
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password_confirmation', '==', 'password'));
        $this->widgetSchema->setNameFormat('sf_auth_reset_password[%s]');
    }
    
    public function getModelName()
    {
        return 'sfAuthUser';
    }
}