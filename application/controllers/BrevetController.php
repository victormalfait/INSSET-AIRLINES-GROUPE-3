<?php
class BrevetController extends Zend_Controller_Action
{
	public function indexAction() 
	{	
		
	}
	public function brevetpilotAction()
	{
		// creation du formulaire
		$monform = new Zend_Form;
		
		//paramétres du formulaire
		$monform->setMethod('post');
		$monform->setAction('/index/#');
		$monform->setAttrib('id', 'monformulaire');
		
		//creation élément formulaire
		$ePilot = new Zend_Form_Element_Select('listpilot');
		$ePilot->setLabel('Liste des pilotes : ');
		$ePilot->setMultiOptions(array('1' => 'Jean Charle', '2' => 'Amstrong Frederic'));
		
		$eBrevet = new Zend_Form_Element_Select('listbrevet');
		$eBrevet->setLabel('Liste des brevet du pilote: ');
		$eBrevet->setMultiOptions(array('1' => 'A380', '2' => 'airbus'));
		
		
		
		
		
		$monform->addElement($ePilot);
		$monform->addElement($eBrevet);
		
		echo $monform;
		
	}
	
	
	
	
	
	
	
}