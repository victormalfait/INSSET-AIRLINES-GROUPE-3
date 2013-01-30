<?php

class FFiltre extends Zend_Form
{
 
	public function init()
	{
	//================= parametrer le formulaire
		$this->setName('filtre');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FFiltre');

	//================= Creation des decorateurs
		// Descativer les decorateurs par defaut
		$this->clearDecorators();

		// creation du décorateur pour les elements
		$decorators = array(
		    array('ViewHelper'),
		    array('Errors'),
		    array('Label', array('style' => 'display:none;')),
		    array('HtmlTag', array('tag' => 'li'))
		);

		// creation d'un decorateur pour le formulaire
		$decoratorsForm = array(
			'FormElements',
			array('HtmlTag', array('tag' => 'ul')),
			array(
				array('DivTag' => 'HtmlTag'),
				array('tag' => 'div', 'class' => 'div-form')
			),
			'Form'
		);
		// Ajout du decorateur au formulaire
		$this->setDecorators($decoratorsForm);

	//================== Creation des elements
		$eService = new Zend_Form_Element_Select('services');
		$eService	->setLabel('Services')
			            ->addMultiOptions($this->listService())
			        	->setRequired(true)
			        	->setAttrib('required', 'required')
			        	->setAttrib('class', 'select')
			            ->addFilter('StripTags')
			            ->addFilter('StringTrim')
			            ->setDecorators($decorators);


		$eVille = new Zend_Form_Element_Select('ville');
		$eVille		->setLabel('Ville')
					->addMultiOptions($this->listVille())
		        	->setRequired(true)
		        	->setAttrib('required', 'required')
		            ->addFilter('StripTags')
		            ->addFilter('StringTrim')
			        ->setDecorators($decorators);


		$eSubmit = new Zend_Form_Element_Submit('submit');
		$eSubmit 	->setAttrib('id', 'submitbutton')
					->setLabel('Valider')
			        ->setDecorators($decorators);


		$eAnnuler = new Zend_Form_Element_Reset('annuler');
		$eAnnuler 	->setLabel('Annuler')
					->setAttrib('id', 'annulerbutton')
					->setAttrib('class', 'close')
			        ->setDecorators($decorators);

		$elements = array($eService, $eVille, $eSubmit, $eAnnuler);
		$this->addElements($elements);

	}

	/**
     * Liste des Services
     */
    private function listService () {
		// on charge le model
		$tableService = new TService;
		// on recupere tout les pays
        $reqService = $tableService	->select()
    							->from($tableService)
    							->order("nom");

	    $services = $tableService->fetchAll($reqService);

        // on instancie le resultat en tableau de pays
        $serviceTab = array();

        $serviceTab["first"] = "-- Choisissez --"; 
        foreach ($services as $s) {
        	$serviceTab[$s->id_service] = utf8_encode($s->nom);
        }
 
        return $serviceTab;
    }

    /**
     * Liste des Villes
     */
    private function listVille () {
		// on charge le model
		$tableVille = new TUtilisateur;
		// on recupere tout les pays
        $reqVille = $tableVille	->select()
    							->from($tableVille)
    							->order("ville_utilisateur");

	    $villes = $tableVille->fetchAll($reqVille);

        // on instancie le resultat en tableau de pays
        $villeTab = array();

        $villeTab["first"] = "-- Choisissez --"; 
        foreach ($villes as $v) {
        	$villeTab[$v->ville_utilisateur] = utf8_encode($v->ville_utilisateur);
        }
 
        return $villeTab;
    }

    /**
     * Liste des Villes
     */
  //   private function listVille () {
		// // on charge le model
		// $tableVille = new TVille;
		// // on recupere tout les pays
  //       $reqVille = $tableVille	->select()
  //   							->from($tableVille)
  //   							->order("nom_ville");

	 //    $villes = $tableVille->fetchAll($reqVille);

  //       // on instancie le resultat en tableau de pays
  //       $villeTab = array();

  //       $villeTab["first"] = "-- Choisissez --"; 
  //       foreach ($villes as $v) {
  //       	$villeTab[$v->id_ville] = utf8_encode($v->nom_ville);
  //       }
 
  //       return $villeTab;
  //   }
 
}