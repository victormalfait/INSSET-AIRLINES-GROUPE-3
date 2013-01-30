<?php

class FNouvelaeroport extends Zend_Form
{

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouvelAeroport');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FNouvelaeroport');
	
	//=============== creation des decorateurs
		// Descativation des decorateurs par defaut
		$this->clearDecorators();

		// decorateur d'element
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

		// decorateur de formulaire
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
		//////////// recuperation des ville pour liste //////////////
			// on charge le model
			$tableVille = new TVille;
			// on recupere tout les ville
	        $ville = $tableVille->fetchAll();
	        // on instancie le resulta en tableau de ville
	        $villeTab = array();

	        foreach ($ville as $v) {
	        	$villeTab[$v->id_ville] = utf8_encode($v->nom_ville);
	        }
	        
			// creation de l'élément
			$eVille = new Zend_Form_Element_Select('ville_aeroport');
			$eVille	->setLabel('Ville : ')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setMultiOptions($villeTab)
					->addValidator('notEmpty')
					->setDecorators($decorators);
        //////////// fin de recuperation des ville pour liste //////////////

		$eAeroport = new Zend_Form_Element_Text('nouvelAeroport');
		$eAeroport	->setLabel('Aeroport :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('size', '14')
					->addFilter('StripTags')
		        	->addFilter('StringTrim')
					->addValidator('notEmpty')
					->setDecorators($decorators);


		$eIATA = new Zend_Form_Element_Text('trigramme');
		$eIATA	->setLabel('Code IATA :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('size', '12')
					->addFilter('StripTags')
		        	->addFilter('StringTrim')
					->addValidator('notEmpty')
					->setDecorators($decorators);


		$eLongueurPiste = new Zend_Form_Element_Text('longueurpiste');
		$eLongueurPiste	->setLabel('Longueur de Piste :')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setAttrib('size', '6')
						->addFilter('StripTags')
		        		->addFilter('StringTrim')
						->addValidator('notEmpty')
						->setDecorators($decorators);

		$eLattitude = new Zend_Form_Element_Text('lattitude');
		$eLattitude	->setLabel('Lattitude :')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setAttrib('size', '6')
						->addFilter('StripTags')
		        		->addFilter('StringTrim')
						->addValidator('notEmpty')
						->setDecorators($decorators);

		$eLongitude = new Zend_Form_Element_Text('longitute');
		$eLongitude	->setLabel('Longitude :')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setAttrib('size', '6')
						->addFilter('StripTags')
		        		->addFilter('StringTrim')
						->addValidator('notEmpty')
						->setDecorators($decorators);


		$eSubmit = new Zend_Form_Element_Submit('ajouter');
		$eSubmit 	->setLabel('Ajouter')
					->setAttrib('class', 'button_aeroport')
					->setAttrib('id', 'submitbutton')
					->setDecorators($decoratorsBouton);


		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'closeAeroport')
					->setDecorators($decoratorsBouton);

		// Ajout des éléments au formulaire
		$elements = array( $eVille, $eAeroport, $eIATA, $eLongueurPiste, $eLattitude, $eLongitude, $eSubmit, $eFermer );
		$this->addElements ( $elements );
	}
}