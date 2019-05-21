<?php

use Zend\Validate\EmailAddress;
class Application_Form_Authentication extends Zend_Form
{
    public function init()
    {
        $this->setName('authentication');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
            ->setRequired(true)
            ->setAttrib('placeholder', 'Email')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->addValidator('EmailAddress');
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setRequired(true)
            ->setAttrib('placeholder', 'Password')
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $email, $password, $submit));
    }
}
