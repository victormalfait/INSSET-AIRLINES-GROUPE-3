<?php
class FAjoutheuredevolavion extends zend_form

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


		//=============== Creation des elements

		//on recupére la totaliter tous les avions referencer dans la BDD
		$tableavion = new TAvion();
		$listeAvion = $tablavionl->fetchAll();
        $listeAvion = array();


        //Liste des avions
		$avion = new Zend_Form_Element_Select('avions');
		$avion	->setLabel('Avion : ')
				->setRequired(true)
				->setAttrib('required', 'required')
				->setMultiOptions($modelTab)
				->addValidator('notEmpty')
				->setDecorators($decorators);


		//ajout heure de vol sur l'avion choisi
		$heuredevol = new Zend_Form_Element_Text('heuredevol')
		$heuredevol ->setLabel("Nombre d'heure de vol : ")
					->setRequired(true)
					->setAttrib('required', 'required')
					->setMultiOptions($modelTab)
					->addValidator('notEmpty')
					->setDecorators($decorators);



}

?>