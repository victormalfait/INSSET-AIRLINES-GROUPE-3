<?php

class FPlannifier extends Zend_Form
{
 
	public function init()
	{
		//===============Parametre du formulaire
		$this->setName('plannifier');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FPlannifier');


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

		$eBrevet = new Zend_Form_Element_Select('brevet');
		$eBrevet	->setLabel('Brevet')
					->setMultiOptions($brevetTab)
        			->addFilter('StripTags')
        			->addFilter('StringTrim')
		            ->setDecorators($decorators);


		$eDate = new Zend_Form_Element_Text('datepicker');
		$eDate 		->setLabel('Date d\'obtention')
					->setAttrib('required', 'required')
					->addValidator('notEmpty')
					->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('BTNAttribuer');
		$eSubmit 	->setAttrib('id', 'BTNAttribuer')
					->setLabel('Attribuer')
					->setDecorators($decoratorsBouton);

		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'close')
					->setDecorators($decoratorsBouton);

		$elements = array($eBrevet, $eDate, $eSubmit, $eFermer);
		$this->addElements($elements);
	}
}