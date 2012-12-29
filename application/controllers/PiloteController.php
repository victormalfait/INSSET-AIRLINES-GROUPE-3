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

		
		$ajoutpilote->addElement($ePilot);
		$ajoutpilote->addElement($eSubmit);
		
		
		echo $ajoutpilote;
		
		
	}
	
	public function disponibiliterpiloteAction()
	{
		// creation du formulaire
		$edispopilote = new Zend_Form;
		
		//paramétre du formulaire
		$edispopilote->setMethod('post');
		$edispopilote->setAction('/index/#');
		$edispopilote->setAttrib('id', 'disponibilite');
		
		//creation élément formulaire
		
		//voire les pilotes
		$ePilote = new Zend_Form_Element_Select('pilot');
		$ePilote->setLabel('selectionner un pilote');
		$ePilote->setMultiOptions(array('1' => 'Jean Charle', '2' => 'Amstrong Frederic'));
		
		//voire les brevets
		$eBrevet = new Zend_Form_Element_Select('listbrevet');
		$eBrevet->setLabel('Liste des brevet : ');
		$eBrevet->setMultiOptions(array('1' => 'A380', '2' => 'airbus'));
		
		//voire disponibiliter: calendrier ou tableau ?
		$edatedispo = new Zend_Form_Element_Select('datedispo');
		$edatedispo->setLabel('(tableau ou calendrier)');
		
		
		
		
		$edispopilote->addElement($ePilote);
		$edispopilote->addElement($eBrevet);
		$edispopilote->addElement($edatedispo);
		
		echo $edispopilote;
		
	}
	
	
	
	
	
	
		
	
	
}


?>