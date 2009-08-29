<?php

class sfAuthUserRegisterForm extends BasesfAuthUserForm
{
    public function configure()
    {
        unset(
            $this['id'],
            $this['groups_list'],
            $this['credentials_list'],
            $this['is_active'],
            $this['is_super_admin'],
            $this['reset_hash'],
            $this['reset_hash_created_at'],
            $this['remember_me_hash'],
            $this['salt'],
            $this['activate_hash']
        );
        
        //Email
        $this->validatorSchema['email'] = new sfValidatorEmail(array('required' => true));
        
        //Password
        $this->widgetSchema['password_confirmation'] = new sfWidgetFormInputPassword();
        $this->validatorSchema['password_confirmation'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema->moveField('password_confirmation', sfWidgetFormSchema::AFTER, 'password');
        
        $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
        $this->validatorSchema['password'] = new sfValidatorString(array('required' => true));
        
        //Email
        $this->widgetSchema['email_confirmation'] = new sfWidgetFormInput();
        $this->validatorSchema['email_confirmation'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema->moveField('email_confirmation', sfWidgetFormSchema::AFTER, 'email');
        
        //Uniqueness
        $this->validatorSchema['username'] = new sfValidatorAnd(array(
            new sfValidatorString(array('required' => true)),
            new sfValidatorDoctrineUnique(array(
                'required' => true,
                'model' => 'sfAuthUser',
                'column' => 'username',
            )),
        ));
        
        $this->validatorSchema['email'] = new sfValidatorAnd(array(
            new sfValidatorString(array('required' => true)),
            new sfValidatorDoctrineUnique(array(
                'required' => true,
                'model' => 'sfAuthUser',
                'column' => 'email',
            )),
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
            new sfValidatorSchemaCompare('password_confirmation', '==', 'password'),
            new sfValidatorSchemaCompare('email_confirmation', '==', 'email'),
        )));
    }
}