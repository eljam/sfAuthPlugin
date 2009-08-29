<?php

class sfAuthUserActivateForm extends sfForm
{
    public function configure()
    {
        $this->setWidgets(array(
            'activate_hash' => new sfWidgetFormInput(),
        ));
        
        $this->setValidators(array(
            'activate_hash' => new sfValidatorAnd(array(
                new sfValidatorString(array('required' => true)),
                new sfAuthUserActivateValidator(),
            )),
        ));
        
        $this->widgetSchema->setNameFormat('sf_auth_user_activate[%s]');
    }
}