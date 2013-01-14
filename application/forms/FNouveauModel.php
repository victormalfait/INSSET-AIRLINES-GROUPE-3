<?php

class FNouveauModel extends Zend_Form
{

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouveauModel');
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
		$nom_model = new Zend_Form_Element_Text('nom_model');
		$nom_model	->setLabel('Nom du model :')
				->setRequired(true)
				->setAttrib('required', 'required')
				->setAttrib('size', '17')
				->addValidator('notEmpty')
				->setDecorators($decorators);


		$rayon_action = new Zend_Form_Element_Text('rayon_action');
		$rayon_action	->setLabel('Rayon d\'action :')
				->setRequired(true)
				->setAttrib('required', 'required')
				->setAttrib('size', '17')
				->addValidator('notEmpty')
				->setDecorators($decorators);

		$longueur_piste = new Zend_Form_Element_Text('longueur_piste');
		$longueur_piste	->setLabel('Longeur de piste minimum :')
				->setRequired(true)
				->setAttrib('required', 'required')
				->setAttrib('size', '17')
				->addValidator('notEmpty')
				->setDecorators($decorators);

		$nbr_place = new Zend_Form_Element_Text('nbr_place');
		$nbr_place	->setLabel('Nombre de places :')
				->setRequired(true)
				->setAttrib('required', 'required')
				->setAttrib('size', '17')
				->addValidator('notEmpty')
				->setDecorators($decorators);

		$vitesse = new Zend_Form_Element_Text('vitesse');
		$vitesse	->setLabel('Vitesse de croisiere :')
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
		$elements = array( $nom_model, $rayon_action, $longueur_piste, $nbr_place, $vitesse, $eSubmit, $eFermer );
		$this->addElements ( $elements );
	}
}