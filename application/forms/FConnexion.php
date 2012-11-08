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
		// $decorators = new InssetAirline_Form_Decorator_Connexion();
		// $decorators = array($decorators);

		$decorators = array(
		    'ViewHelper',
		    'Errors',
		    array('Label', array('class' => 'label')),
		    array('HtmlTag', array('tag' => 'li'))
		);

		$decoratorsForm = array(
			'FormElements',
			array('HtmlTag', array('tag' => 'ul')),
			array(
				array('DivTag' => 'HtmlTag'),
				array('tag' => 'div')
			),
			'Form'
		);

		$eEmail = new Zend_Form_Element_Text('email');
		$eEmail		->setLabel('Identifiant')
		        	->setRequired(true)
		            ->addFilter('StripTags')
		            ->addFilter('StringTrim')
		            ->setDecorators($decorators);


		$ePass = new Zend_Form_Element_Password('password');
		$ePass 		->setLabel('Mot de pase')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('submit');
		$eSubmit 	->setAttrib('id', 'submitbutton')
					->setLabel('Se connecter')
					->setDecorators($decorators);

		$elements = array($eEmail,$ePass, $eSubmit);
		$this->addElements($elements);

		$this->setDecorators(array(
		'FormElements',
		array('HtmlTag', array('tag' => 'ul', 'class' => 'zend_form')),
		array('Errors', array('placement' => 'apend')),
		'Form'
		));
	}
 
}