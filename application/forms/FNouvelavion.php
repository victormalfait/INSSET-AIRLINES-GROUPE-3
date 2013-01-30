<?php

class FNouvelavion extends Zend_Form
{

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouvelavion');
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

		//Liste des models d'avion .
		$model = new Zend_Form_Element_Select('model');
		$model	->setLabel('Model : ')
				->setRequired(true)
				->setAttrib('required', 'required')
				->setMultiOptions($this->listModel())
				->addValidator('notEmpty')
				->setDecorators($decorators);

		$Submit = new Zend_Form_Element_Submit('ajouter');
		$Submit 	->setLabel('Ajouter')
					->setAttrib('class', 'button_avion')
					->setAttrib('id', 'submitbutton')
					->setDecorators($decoratorsBouton);


		$Fermer = new Zend_Form_Element_Reset('fermer');
		$Fermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'close')
					->setDecorators($decoratorsBouton);

		// Ajout des éléments au formulaire
		$elements = array( $model, $Submit, $Fermer );
		$this->addElements ( $elements );
	}

	/**
    * Liste des model d'avions
    */
    private function listModel () {
		// on charge le model
		$tableModel = new TModelAvion;
		// on recupere tout les pays
        $reqModel = $tableModel	->select()
    							->from($tableModel)
    							->order("nom_model");

	    $model = $tableModel->fetchAll($reqModel);

        // on instancie le resultat en tableau de pays
        $modelTab = array();

        $modelTab["-1"] = "-- Choisissez --"; 
        foreach ($model as $m) {
        	$modelTab[$m->id_model] = utf8_encode($m->nom_model);
        }
 
        return $modelTab;
    }

}