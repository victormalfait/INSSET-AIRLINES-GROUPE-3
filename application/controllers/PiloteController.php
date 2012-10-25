<?php
class PiloteController extends Zend_Controller_Action
{
	
	public function indexAction()
	{
	
	}
	public function ajoutpiloteAction()
	{
		// creation du formulaire
		$ajoutpilote = new Zend_Form;
		
		//paramétre du formulaire
		$ajoutpilote->setMethod('post');
		$ajoutpilote->setAction('/index/#');
		$ajoutpilote->setAttrib('id', 'formulairepilote');
		
		//creation élément formulaire
		$ePilot = new Zend_Form_Element_Text('pilot');
		$ePilot->setLabel('ajouter un pilote: ');
		$eSubmit= new Zend_Form_Element_Submit('ajouter');
		//$ePilot->setMultiOptions(array('1' => 'Jean Charle', '2' => 'Amstrong Frederic'));
		
		$ajoutpilote->addElement($ePilot);
		$ajoutpilote->addElement($eSubmit);
		
		
		echo $ajoutpilote;
		
		
	}
	
	
	
	
	
	
	
		
	
	
}


?>