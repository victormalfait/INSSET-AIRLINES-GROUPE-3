<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	
    }

    public function indexAction()
    {
    	$redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoUrl("connexion/index");
    }


}

