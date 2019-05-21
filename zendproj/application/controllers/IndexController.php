<?php

class IndexController extends Zend_Controller_Action
{
    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $storage = new Zend_Session_Namespace('userDetails');
        $data = $storage->email;
        if(!$data) {
            $this->_redirect('authentication/login');
        }
        $con = Doctrine_Manager::getInstance()->connection();
        $res = $con->execute("SELECT * FROM albums;", array(1));
        $retorno = $res->fetchAll();
        $form = new Application_Form_Album();
    }

    public function addAction() {
        $storage = new Zend_Session_Namespace('userDetails');
        $data = $storage->email;
        if(!$data) {
            $this->_redirect('authentication/login');
        }
        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('We did something in the last request');
        $form = new Application_Form_Album();
        $form->submit->setLabel('Add');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $artist = $form->getValue('artist');
                $title = $form->getValue('title');
                $novel = $form->getValue('novel');
                $multiCheckbox = $form->getValue('multiCheckbox');
                $multiCheckbox = implode(", ", $multiCheckbox);
                $selectlang = $form->getValue('selectlang');
                $multiselect = $form->getValue('multiselect');
                $multiselect = implode(", ", $multiselect);
                $image = $form->getValue('profile_pic');
                if(file_exists(APPLICATION_PATH .'/../public/upload/'.$image)) {
                    $form->profile_pic->addFilter('Rename', APPLICATION_PATH .'/../public/upload/'.md5(uniqid(time(), true)).$image);
                }
                $form->profile_pic->receive();
                $image = $form->getValue('profile_pic');
                $albums = new Model_Albums();
                $albums->addAlbum($artist, $title, $novel, $multiCheckbox, $selectlang, $multiselect, $image);
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function editAction() {
        $storage = new Zend_Session_Namespace('userDetails');
        $data = $storage->email;
        if(!$data){ 
            $this->_redirect('authentication/login');
        }
        $id = $this->_getParam('id', 0);
        $form = new Application_Form_Album();
        $form->submit->setLabel('Save');
        $form->profile_pic->setLabel('Replace Image');
        $this->view->form = $form;
        $con = Doctrine_Manager::getInstance()->connection();
        $res = $con->execute("SELECT * FROM albums where id=$id;", array(1));
        $retorno = $res->fetchAll();
        $retorno = $retorno[0];
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {   
                $data = $form->getValues(); 
                $id = (int)$form->getValue('id');
                $artist = $form->getValue('artist'); 
                $title = $form->getValue('title');
                $novel = $form->getValue('novel');
                $multiCheckbox = $form->getValue('multiCheckbox');
                $selectlang = $form->getValue('selectlang');
                $multiselect = $form->getValue('multiselect');
                $image = $form->getValue('profile_pic');
                if(file_exists(APPLICATION_PATH .'/../public/upload/'.$image)) {
                    $form->profile_pic->addFilter('Rename', APPLICATION_PATH .'/../public/upload/'.md5(uniqid(time(), true)).$image);
                }
                $form->profile_pic->receive();
                $image = $form->getValue('profile_pic');
                $data['image'] = $image;
                $data['multiCheckbox'] = implode(', ', $multiCheckbox);
                $data['multiselect'] = implode(', ',$multiselect);
                if($data['image'] != '') {
                    $con = Doctrine_Manager::getInstance()->connection();
                    $res = $con->execute("UPDATE albums SET artist = '".$data['artist']."',title =  '".$data['title']."'  ,novel =  '".$data['novel']."',multiCheckbox =  '".$data['multiCheckbox']."',selectlang =  '".$data['selectlang']."',multiselect =  '".$data['multiselect']."',image =  '".$data['image']."' WHERE id=".$data['id'], array(1));
                } else {
                    $res = $con->execute("UPDATE albums SET artist = '".$data['artist']."',title =  '".$data['title']."'  ,novel =  '".$data['novel']."',multiCheckbox =  '".$data['multiCheckbox']."',selectlang =  '".$data['selectlang']."',multiselect =  '".$data['multiselect']."' WHERE id=".$data['id'], array(1));
                }
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $id = $this->_getParam('id', 0);
                if ($id > 0) {
                    $albums = new Model_Albums();
                    $data = $albums->getAlbum($id);
                    $form = new Application_Form_Album($data);
                    $this->view->form = $form;
                    $form->submit->setLabel('Save');
                    $data['multiCheckbox'] = explode(', ',$data['multiCheckbox']);
                    $data['multiselect'] = explode(', ',$data['multiselect']);
                    $form->populate($data);
                }
            }
        }
    }

    public function deleteAction() {
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');
                $albums = new Model_Albums();
                $albums->deleteAlbum($id);
            }
            $this->_helper->redirector('index');
        } else {
            $id = $this->_getParam('id', 0);
            $albums = new Model_Albums();
            $this->view->album = $albums->getAlbum($id);
        }
    }

    public function logoutAction() {   
        $userDetails = new Zend_Session_Namespace('userDetails');
        $userDetails->unsetAll();
        $this->_redirect('authentication/login');
    }
}
