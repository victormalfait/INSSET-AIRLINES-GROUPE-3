<?php

class FConnexion extends Zend_Form
{
 
	public function init()
	{
	//===============Parametre du formulaire
		$this->setName('connexion');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FConnexion');


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
					->setDecorators($decoratorsBouton);

		$elements = array($eEmail, $ePass, $eSubmit);
		$this->addElements($elements);

	}

}