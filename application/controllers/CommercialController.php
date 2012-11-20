<?php

class CommercialController extends Zend_Controller_Action
{
	public function indexAction()
    {
    	$formCommercial = new FCommercial;
    	$this->view->formCommercial = $formCommercial;
    }
}