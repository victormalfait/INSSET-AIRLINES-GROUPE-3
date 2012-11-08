<?php

class FConnexion extends Zend_Form
{
 
	public function init()
	{
		//parametrer le formulaire
		$this->setName('connexion');
		$this->setMethod('post');
		$this->setAction('index/index');
		$this->setAttrib('id', 'FConnexion');

		// Descativer les decorateurs par defaut
		$this->clearDecorators();

		// Creer les decorateurs
		$decorators = new InssetAirline_Form_Decorator_Connexion();
		$decorators = array($decorators);

		$eEmail = new Zend_Form_Element_Text('email');
		$eEmail		->setLabel('Identifiant')
		        	->setRequired(true)
		            ->addFilter('StripTags')
		            ->addFilter('StringTrim');


		$ePass = new Zend_Form_Element_Password('password');
		$ePass 		->setLabel('Mot de pase')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim');

		$eSubmit = new Zend_Form_Element_Submit('submit');
		$eSubmit 	->setAttrib('id', 'submitbutton')
					->setLabel('Se connecter');

		$elements = array($eEmail,$ePass, $eSubmit);
		$this->addElements($elements);

		$this->setDecorators(array(
		'FormElements',
		array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
		array('Errors', array('placement' => 'apend')),
		'Form'
		));
	}
 
}