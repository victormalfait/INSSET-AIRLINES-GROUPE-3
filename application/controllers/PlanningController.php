<?php

class PlanningController extends Zend_Controller_Action
{
	public function indexAction(){
		$tableVol = new TVols;
		$vol = $tableVol->fetchAll();
		$this->view->vol = $vol;
	}
}