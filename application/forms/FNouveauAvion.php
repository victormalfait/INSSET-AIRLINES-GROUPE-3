<?php

class FNouveauAvion extends Zend_Form
{

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouveauAvion');
		$this->setMethod('post');
	
	//=============== creation des decorateurs
		// Descativation des decorateurs par defaut
		$this->clearDecorators();

		//decorateur d'element
		$decorators = array(
		    'ViewHelper',
		    'Errors',
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
		$immatriculation = new Zend_Form_Element_Text('immatriculation');
		$immatriculation	->setLabel('Pays :')
				->setRequired(true)
				->setAttrib('required', 'required')
				->setAttrib('size', '17')
				->addValidator('notEmpty')
				->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('ajouter');
		$eSubmit 	->setLabel('Ajouter')
					->setAttrib('class', 'button_pays')
					->setAttrib('id', 'submitbutton')
					->setDecorators($decoratorsBouton);


		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'closePays')
					->setDecorators($decoratorsBouton);

		// Ajout des éléments au formulaire
		$elements = array( $ePays, $eSubmit, $eFermer );
		$this->addElements ( $elements );
	}
}