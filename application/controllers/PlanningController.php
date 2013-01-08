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
}