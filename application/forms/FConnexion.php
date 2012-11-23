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

		$eEmail = new Zend_Form_Element_Text('login_utilisateur');
		$eEmail		->setLabel('Identifiant')
		        	->setRequired(true)
		        	->setAttrib('required', 'required')->setAttrib('autofocus','true')
		            ->addFilter('StripTags')
		            ->addFilter('StringTrim')
		            ->setDecorators($decorators);


		$ePass = new Zend_Form_Element_Password('password_utilisateur');
		$ePass 		->setLabel('Mot de passe')
					->setRequired(true)
					->setAttrib('required', 'required')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('connexion');
		$eSubmit 	->setAttrib('id', 'connexion')
					->setLabel('Ok');

		$elements = array($eEmail,$ePass, $eSubmit);
		$this->addElements($elements);

		$this->setDecorators($decoratorsForm);
	}

}