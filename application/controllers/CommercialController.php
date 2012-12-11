<?php

class CommercialController extends Zend_Controller_Action
{

	public function indexAction()
    {
    	// on charge le formulaire FCommercial
    	$formCommercial = new FCommercial;

    	// on l'envoi à la vu
    	$this->view->formCommercial = $formCommercial;
    }

    public function catalogueAction ()
    {
    	
    }

}