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
		$this->view->id_destination = $id_destination;
		$tableDestination = new TDestination;
		$destination = $tableDestination->find($id_destination)->current();
		$this->view->destination = $destination;
	}

}