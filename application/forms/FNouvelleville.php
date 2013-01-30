<?php

class FNouvelleville extends Zend_Form
{

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouvelleVille');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FNouvelleville');

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
		//////////// recuperation des pays pour liste //////////////
			// on charge le model
			$tablePays = new TPays;
			// on recupere tout les pays
	        $pays = $tablePays->fetchAll();
	        // on instancie le resulta en tableau de pays
	        $paysTab = array();

	        foreach ($pays as $p) {
	        	$paysTab[$p->id_pays] = utf8_encode($p->nom_pays);
	        }
			// creation de l'élément
			$ePays = new Zend_Form_Element_Select('pays_ville');
			$ePays	->setLabel('Pays : ')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setMultiOptions($paysTab)
					->addValidator('notEmpty')
					->setDecorators($decorators);
        //////////// fin de recuperation des pays pour liste //////////////

		$eVille = new Zend_Form_Element_Text('nouveauVille');
		$eVille	->setLabel('Ville :')
				->setRequired(true)
				->setAttrib('required', 'required')
				->setAttrib('size', '17')
				->addFilter('StripTags')
		        ->addFilter('StringTrim')
				->addValidator('notEmpty')
				->setDecorators($decorators);


		$eSubmit = new Zend_Form_Element_Submit('ajouter');
		$eSubmit 	->setLabel('Ajouter')
					->setAttrib('class', 'button_ville')
					->setAttrib('id', 'submitbutton')
					->setDecorators($decoratorsBouton);


		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'closeVille')
					->setDecorators($decoratorsBouton);

		// Ajout des éléments au formulaire
		$elements = array( $ePays, $eVille, $eSubmit, $eFermer );
		$this->addElements ( $elements );
	}
}