<?php

class FClient extends Zend_Form
{
 
	public function init()
	{
	//===============Parametre du formulaire
		$this->setName('client');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FClient');


	//=============== creation des decorateurs
		// Descativer les decorateurs par defaut
		$this->clearDecorators();

		$decorators = array(
		    array('ViewHelper'),
		    array('Errors'),
		    array('Label', array('class' => 'label')),
		    array('HtmlTag', array('tag' => 'li'))
		);

		// decorateur d'element bouton
		$decoratorsBouton = array(
		    'ViewHelper',
		    'Errors',
		    array('Label', array('class' => 'label submit')),
		    array('HtmlTag', array('tag' => 'li'))
		);

		//decorateur de formulaire
		$decoratorsForm = array(
			'FormElements',
		    array('Errors', array('class' => "error")),
			array('HtmlTag', array('tag' => 'ul')),
			array(
				array('DivTag' => 'HtmlTag'),
				array('tag' => 'div')
			),
			'Form'
		);

		// on insere le decorateur de form au formulaire
		$this->setDecorators($decoratorsForm);


	//=============== Creation des element
		$tabCivilite = array('1' => 'M.', '2' => 'Mme');
		$eCivilite = new Zend_Form_Element_Select('civilite');
		$eCivilite ->setLabel('Civilite')
				   ->setMultiOptions($tabCivilite)
        		   ->addFilter('StripTags')
        		   ->addFilter('StringTrim')
		           ->setDecorators($decorators);


		$eNom = new Zend_Form_Element_Text('nom');
		$eNom ->setLabel('Nom')
			  ->setAttrib('required', 'required')
			  ->addValidator('notEmpty')
			  ->setDecorators($decorators);

		$ePrenom = new Zend_Form_Element_Text('prenom');
		$ePrenom ->setLabel('Prénom')
			   	 ->setAttrib('required', 'required')
			     ->addValidator('notEmpty')
			     ->setDecorators($decorators);

		$eJour = new Zend_Form_Element_Text('jour');
		$eJour ->setLabel('Date de naissance')
			   ->setAttrib('required', 'required')
			   ->addValidator('notEmpty')
			   ->setDecorators($decorators);

		$tabMois = array('-1'=>'mois', '1'=>'Janvier', '2'=>'Février', '3'=>'Mars', '4'=>'Avril', '5'=>'Mai', '6'=>'Juin', '7'=>'Juillet', '8'=>'Aout', '9'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');
		$eMois = new Zend_Form_Element_Select('mois');
		$eMois ->setMultiOptions($tabMois)
        	   ->addFilter('StripTags')
        	   ->addFilter('StringTrim')
		       ->setDecorators($decorators);

		$eAnnee = new Zend_Form_Element_Text('annee');
		$eAnnee ->setAttrib('required', 'required')
			    ->addValidator('notEmpty')
			    ->setDecorators($decorators);       

		$eMail = new Zend_Form_Element_Text('email');
		$eMail ->setLabel('Adresse email')
			   ->setAttrib('required', 'required')
			   ->addValidator('notEmpty')
			   ->setDecorators($decorators);

		$eConfMail = new Zend_Form_Element_Text('confemail');
		$eConfMail ->setLabel('Confirmation email')
			   	   ->setAttrib('required', 'required')
			       ->addValidator('notEmpty')
			       ->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('BTNValider');
		$eSubmit ->setAttrib('id', 'BTNValider')
				 ->setLabel('Valider')
				 ->setDecorators($decoratorsBouton);


		$elements = array($eCivilite, $eNom, $ePrenom, $eJour, $eMois, $eAnnee, $eMail, $eConfMail, $eSubmit);
		$this->addElements($elements);

		$this->addDisplayGroup(array(
								'jour',
								'mois',
								'annee'), 'trois', array("legend" => ""));
	}
}