<?php

class LogistiqueController extends Zend_Controller_Action
{
	public function indexAction(){
		$tableVol = new TVols;
		$vols = $tableVol->fetchAll();
	}
}