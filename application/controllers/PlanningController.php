<?php

class PlanningController extends Zend_Controller_Action
{
	public function indexAction(){
		$tableVol = new TVols;
		$vol = $tableVol->fetchAll();
		$this->view->vol = $vol;
	}

	public function menuplanningAction(){

	}

	public function plannificationAction(){
		$tableDestination = new TDestination;
		$destinationRequest = $tableDestination->select()->where('plannification = 0');
		$destination = $tableDestination->fetchAll($destinationRequest);

		$this->view->destination =$destination;
	}

	public function plannifierAction(){
		$id_destination = $this->_getParam('id_destination');
		$form = new FPlannifier;

		$this->view->id_destination = $id_destination;
		$this->view->formPlannifier = $form;
		$form->setIdDestination($id_destination);
		$form->init();

		$tableDestination = new TDestination;
		$destination = $tableDestination->find($id_destination)->current();
		$this->view->destination = $destination;

		if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();
            // var_dump( $formVol->isValid($formData));

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
            	if($form->getValue('pilote') != -1 && $form->getValue('copilote') != -1 && $form->getValue('avion') != -1){

	            	$tableVol = new TVols;
	            	$row = $tableVol->createRow();                

	                $row->id_pilote        = $form->getValue('pilote');
	                $row->id_copilote      = $form->getValue('copilote');
	                $row->immatriculation  = $form->getValue('avion');
	                $row->id_destination   = $id_destination;
	                $row->remarque         = 'RAS';

	                //sauvegarde de la requete
	                $result = $row->save();
	    
	                // RAZ du formulaire
	                $form->reset();

	                $redirector = $this->_helper->getHelper('Redirector');
	                $redirector->gotoUrl('planning/index');
	            }else{
	            	echo 'choisissez un avion, un pilote et un copilote pour ce vol';
	            }
            }
        }

	}

}