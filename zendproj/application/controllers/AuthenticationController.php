<?php
use Zend\Auth\Adapter\DbTable;
use Model\Base\Authentication;
class AuthenticationController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
    }

    public function loginAction() {
        $form = new Application_Form_Authentication();
        $form->submit->setLabel('Login');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $email = $form->getValue('email');
                $password = $form->getValue('password');
                $con = Doctrine_Manager::getInstance()->connection();
                $authentication = new Model_Authentication();
                $authentication = $authentication->checkAuthentication($email, $password);
               if($authentication != false) {
                  Zend_Session::start();
                  $userDetails = new Zend_Session_Namespace('userDetails');
                  $userDetails->email =$email;
                  $userDetails->password = $password;
                  $this->_redirect('index');
               } else {
                    $this->_redirect('authentication/login');
              }
            }
        }
    }

    public function registerAction() {
        $form = new Application_Form_Authentication();
        $form->submit->setLabel('Register');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $email = $form->getValue('email');
                $password = $form->getValue('password');
                $con = Doctrine_Manager::getInstance()->connection();
                $res = $con->execute("Insert Into authentication ( email, password) values ( '$email', '$password');", array(1));
                $this->_helper->redirector('login');
            } else {
            $form->populate($formData);
            }
        }
    }
}
