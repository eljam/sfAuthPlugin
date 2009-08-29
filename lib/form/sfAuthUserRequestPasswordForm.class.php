<?php

class sfAuthUserRequestPasswordForm extends sfForm
{
    public function configure()
    {       
        $this->setWidgets(array(
            'email' => new sfWidgetFormInput(),
        ));
        
        $this->setValidators(array(
            'email' => new sfValidatorString(array('required' => true)),
        ));
        
        $this->validatorSchema->setPostValidator(new sfAuthUserRequestPasswordValidator());
        
        $this->widgetSchema->setNameFormat('sf_auth_request_password[%s]');
    }
}