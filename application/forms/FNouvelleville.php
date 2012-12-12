<?php

class FNouvelleville extends Zend_Form
{

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouvelleVille');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FNouvelleville');

	//=============== Creation des element
		//////////// recuperation des pays pour liste //////////////
			// on charge le model
			$tablePays = new TPays;
			// on recupere tout les pays
	        $pays = $tablePays->fetchAll();
	        // on instancie le resulta en tableau de pays
	        $paysTab = array();

	        foreach ($pays as $p) {
	        	$paysTab[$p->id] = htmlentities($p->nom);
	        }
			// creation de l'élément
			$ePays = new Zend_Form_Element_Select('pays_ville');
			$ePays	->setLabel('Pays : ')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setMultiOptions($paysTab)
					->addValidator('notEmpty');
        //////////// fin de recuperation des pays pour liste //////////////

		$eVille = new Zend_Form_Element_Text('nouveauVille');
		$eVille	->setLabel('Ville :')
				->setRequired(true)
				->setAttrib('required', 'required')
				->addFilter('StripTags')
		        ->addFilter('StringTrim')
				->addValidator('notEmpty');


		$eSubmit = new Zend_Form_Element_Submit('ajouter');
		$eSubmit 	->setLabel('Ajouter')
					->setAttrib('class', 'button_ville')
					->setAttrib('id', 'submitbutton');


		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'closeVille');

		// Ajout des éléments au formulaire
		$elements = array( $ePays, $eVille, $eSubmit, $eFermer );
		$this->addElements ( $elements );
	}
}