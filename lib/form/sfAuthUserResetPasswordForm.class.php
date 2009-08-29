<?php

class sfAuthUserResetPasswordForm extends sfForm
{
    public function configure()
    {
        $this->setWidgets(array(
            'reset_hash'            => new sfWidgetFormInput(),
            'password'              => new sfWidgetFormInputPassword(),
            'password_confirmation' => new sfWidgetFormInputPassword(),
        ));
        
        $this->setValidators(array(
            'reset_hash'            => new sfValidatorString(array('required' => true)),
            'password'              => new sfValidatorString(array('required' => true)),
            'password_confirmation' => new sfValidatorString(array('required' => true)),
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
            new sfValidatorSchemaCompare('password_confirmation', '==', 'password'),
            new sfAuthUserResetPasswordValidator(),
        )));
        
        $this->widgetSchema->setNameFormat('sf_auth_reset_password[%s]');
    }
        
    public function save()
    {
        return (bool) $this->isValid();
    }
}