<?php

class FConnexion extends Zend_Form
{
 
	public function init()
	{
		//parametrer le formulaire
		$this->setName('connexion');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FConnexion');

		// Descativer les decorateurs par defaut
		$this->clearDecorators();

		$decorators = array(
		    array('ViewHelper'),
		    array('Errors'),
		    array('Label', array('class' => 'label')),
		    array('HtmlTag', array('tag' => 'li'))
		);

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

		$eEmail = new Zend_Form_Element_Text('login_utilisateur');
		$eEmail		->setLabel('Identifiant')
		        	->setRequired(true)
		        	->setAttrib('required', 'required')->setAttrib('autofocus','true')
		            ->addFilter('StripTags')
		            ->addFilter('StringTrim')
		            ->addValidator('notEmpty')
		            ->setDecorators($decorators);


		$ePass = new Zend_Form_Element_Password('password_utilisateur');
		$ePass 		->setLabel('Mot de passe')
					->setRequired(true)
					->setAttrib('required', 'required')
					->addFilter('StripTags')
					->addFilter('StringTrim')
		            ->addValidator('notEmpty')
					->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('SBTconnexion');
		$eSubmit 	->setAttrib('id', 'SBTconnexion')
					->setLabel('Ok')
					->setDecorators($decorators);

		$elements = array($eEmail, $ePass, $eSubmit);
		$this->addElements($elements);

		$this->setDecorators($decoratorsForm);
	}

}