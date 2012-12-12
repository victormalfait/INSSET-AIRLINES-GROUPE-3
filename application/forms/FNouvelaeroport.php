<?php

class FNouvelaeroport extends Zend_Form
{

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouvelAeroport');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FNouvelaeroport');

	//=============== Creation des element
		//////////// recuperation des ville pour liste //////////////
			// on charge le model
			$tableVille = new TVille;
			// on recupere tout les ville
	        $ville = $tableVille->fetchAll();
	        // on instancie le resulta en tableau de ville
	        $villeTab = array();

	        foreach ($ville as $v) {
	        	$villeTab[$v->id] = htmlentities($v->nom);
	        }
	        
			// creation de l'élément
			$eVille = new Zend_Form_Element_Select('ville_aeroport');
			$eVille	->setLabel('Ville : ')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setMultiOptions($villeTab)
					->addValidator('notEmpty');
        //////////// fin de recuperation des ville pour liste //////////////

		$eAeroport = new Zend_Form_Element_Text('nouvelAeroport');
		$eAeroport	->setLabel('Aeroport :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->addFilter('StripTags')
		        	->addFilter('StringTrim')
					->addValidator('notEmpty');


		$eIATA = new Zend_Form_Element_Text('trigramme');
		$eIATA	->setLabel('Code IATA :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->addFilter('StripTags')
		        	->addFilter('StringTrim')
					->addValidator('notEmpty');


		$eLongueurPiste = new Zend_Form_Element_Text('longueurpiste');
		$eLongueurPiste	->setLabel('Longueur de Piste :')
						->setRequired(true)
						->setAttrib('required', 'required')
						->addFilter('StripTags')
		        		->addFilter('StringTrim')
						->addValidator('notEmpty');


		$eSubmit = new Zend_Form_Element_Submit('ajouter');
		$eSubmit 	->setLabel('Ajouter')
					->setAttrib('class', 'button_aeroport')
					->setAttrib('id', 'submitbutton');


		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'closeAeroport');

		// Ajout des éléments au formulaire
		$elements = array( $eVille, $eAeroport, $eIATA, $eLongueurPiste, $eSubmit, $eFermer );
		$this->addElements ( $elements );
	}
}