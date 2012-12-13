<?php

class RessourcehumaineController extends Zend_Controller_Action
{

	public function indexAction()
	{

	}

	public function editerAction()
	{
		// on charge le model
        $tablePilote = new TPilote;

        // on envoi a la vue tout les pilote
        $this->view->pilotes = $tablePilote->fetchAll();
	}

	public function supprimerAction()
	{
		
	}
}