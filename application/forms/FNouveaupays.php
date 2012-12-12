<?php

class FNouveaupays extends Zend_Form
{

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouveauPays');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FNouveaupays');

	//=============== Creation des element
		$ePays = new Zend_Form_Element_Text('nouveauPays');
		$ePays	->setLabel('Pays :')
				->setRequired(true)
				->setAttrib('required', 'required')
				->addFilter('StripTags')
		        ->addFilter('StringTrim')
				->addValidator('notEmpty');


		$eSubmit = new Zend_Form_Element_Submit('ajouter');
		$eSubmit 	->setLabel('Ajouter')
					->setAttrib('class', 'button_pays')
					->setAttrib('id', 'submitbutton');


		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'closePays');

		// Ajout des éléments au formulaire
		$elements = array( $ePays, $eSubmit, $eFermer );
		$this->addElements ( $elements );
	}
}