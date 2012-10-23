<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function indexAction()
    {
        $monform = new Zend_Form;
		
		//paramétres du formulaire
		$monform->setMethod('post');
		$monform->setAction('/index/#');
		$monform->setAttrib('id', 'monformulaire');
		
		//creation élément formulaire
		$ePilot = new Zend_Form_Element_Select('listpilot');
		$ePilot->setLabel('Liste des pilotes : ');
		$ePilot->setMultiOptions(array('1' => 'Jean Charle', '2' => 'Amstrong Frederic'));
		
		$monform->addElement($ePilot);
		
		echo $monform;
    }


}

