<?php

class FNouveauvol extends Zend_Form
{
	private $numeroVol;

	public function init() {
	//===============Parametre du formulaire
		// on recupere la valeur de l'id utilisateur
		$numeroVol = $this->getNumeroVol();

		//$this->setName('nouveauVol');
		$this->setMethod('post');
		$this->setAction('/strategie/nouveau/numero_vol/'.$numeroVol);
		$this->setAttrib('id', 'FNouveauvol'.$numeroVol);

		
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
		// depart
			$ePaysDepart = new Zend_Form_Element_Select('paysDepart');
			$ePaysDepart	->setLabel('Pays : ')
							->setAttrib('class', 'choixPaysDepart')
							->setMultiOptions($this->listPays())
							->setDecorators($decorators);


			$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
			$eAeroportDepart	->setLabel('Aeroport : ')
								->setMultiOptions($this->listAeroport())
								->setDecorators($decorators);
			

			$eDepartH = new Zend_Form_Element_Text('timepickerdeb');
			$eDepartH	->setLabel('Heure :')
						->setAttrib('required', 'required')
						->setAttrib('size', '1')
						->setAttrib('class','timepickerdeb'.$numeroVol)
						->addValidator('notEmpty')
						->setDecorators($decorators);


			$eDepartM = new Zend_Form_Element_Text('datepickerdeb');
			$eDepartM	->setLabel('Date :')
						->setAttrib('required', 'required')
						->setAttrib('size', '3')
						->setAttrib('class','datepickerdeb'.$numeroVol)
						->addValidator('notEmpty')
						->setDecorators($decorators);


		//Arrivee
			$ePaysArrivee = new Zend_Form_Element_Select('paysArrivee');
			$ePaysArrivee	->setLabel('Pays : ')
							->setAttrib('class', 'choixPaysArrivee')
							->setMultiOptions($this->listPays())
							->setDecorators($decorators);


			$eAeroportArrivee = new Zend_Form_Element_Select('aeroportArrivee');
			$eAeroportArrivee	->setLabel('Aeroport : ')
								->setMultiOptions($this->listAeroport())
								->setDecorators($decorators);

			
			$eArriveeH = new Zend_Form_Element_Text('timepickerfin');
			$eArriveeH	->setLabel('Heure :')
						->setAttrib('required', 'required')
						->setAttrib('size', '1')
						->setAttrib('class','timepickerfin'.$numeroVol)
						->addValidator('notEmpty')
						->setDecorators($decorators);


			$eArriveeM = new Zend_Form_Element_Text('datepickerfin');
			$eArriveeM	->setLabel('Date :')
						->setAttrib('required', 'required')
						->setAttrib('size', '3')
						->setAttrib('class','datepickerfin'.$numeroVol)
						->addValidator('notEmpty')
						->setDecorators($decorators);


		// Périodicité
			$ePeriodicite = new Zend_Form_Element_Select('periodicite');
			$ePeriodicite	->setLabel('periodicite : ')
							->setMultiOptions( array( 'Vol unique'	=> 'Vol unique'
													, 'Lundi'		=> 'Lundi'
													, 'Mardi'		=> 'Mardi'
													, 'Mercredi'	=> 'Mercredi'
													, 'Jeudi'		=> 'Jeudi'
													, 'Vendredi'	=> 'Vendredi'
													, 'Samedi'		=> 'Samedi'
													, 'Dimanche'	=> 'Dimanche'
													)
												)
							->setDecorators($decorators);

		// Terminer
			$eSubmit = new Zend_Form_Element_Submit('Enregistrer');
			$eSubmit 	->setLabel('Enregistrer')
						->setAttrib('id', 'submitbutton')
						->setDecorators($decoratorsBouton);


			$eFermer = new Zend_Form_Element_Reset('fermer');
			$eFermer 	->setLabel('Fermer')
						->setAttrib('id', 'fermerbutton')
						->setAttrib('class', 'close')
						->setDecorators($decoratorsBouton);

		// Ajout des éléments au formulaire
			$elements = array(	$ePaysDepart,
								$eAeroportDepart,
								$eDepartH,
								$eDepartM,
								$ePaysArrivee,
								$eAeroportArrivee,
								$eArriveeH,
								$eArriveeM,
								$ePeriodicite,
								$eSubmit,
								$eFermer
							);
			$this->addElements ( $elements );


	//=============== creation des groupes de formulaire
		$this->addDisplayGroup(array(
								'paysDepart',
								'aeroportDepart',
								'timepickerdeb',
								'datepickerdeb'), 'depart', array("legend" => "Départ"));

		$this->addDisplayGroup(array(
								'paysArrivee',
								'aeroportArrivee',
								'timepickerfin',
								'datepickerfin'), 'arrivee', array("legend" => "Arrivée"));

		$this->addDisplayGroup(array('periodicite'), 'periode', array("legend" => "Périodicité"));
		
		$this->addDisplayGroup(array(
								'Enregistrer',
								'fermer'), 'terminer', array("legend" => "Terminer"));



/*===========================PRÉ REMPLISSAGE DU FORMULAIRE==============================*/

		// si on a une valeur ...
		if (isset ( $numeroVol ) && $numeroVol != "") {
			$eDepartM->setName('datepickerdeb'.$numeroVol);
			$eDepartH->setName('timepickerdeb'.$numeroVol);
			$eArriveeM->setName('datepickerfin'.$numeroVol);
			$eArriveeH->setName('timepickerfin'.$numeroVol);

			// ... on charde le model,
			$tableDestination = new TDestination;
			// on envoi la requete pour recupere les informations
            $destination = $tableDestination  	->find($numeroVol)
                                    			->current();

			// si on a un retour
			if ($destination != null) { 

				// on peuple les selects d'aeroport concernées
                $eAeroportDepart->setValue($destination->tri_aero_dep);
                $eAeroportArrivee->setValue($destination->tri_aero_arr);

                // on charge le model aeroport...
                $tableAeroport = new TAeroport;
                // ... on cherche les information concernant les aeroports de la destination concernée
            	$aeroport_dep = $tableAeroport	->find($destination->tri_aero_dep)->current();
            	$aeroport_arr = $tableAeroport	->find($destination->tri_aero_arr)->current();

            	// on charge le model ville ...
                $tableVille = new TVille;
                // ...on cherche les informations des ville des aeroport concerner
            	$ville_dep = $tableVille->find($aeroport_dep->id_ville)->current();
            	$ville_arr = $tableVille->find($aeroport_arr->id_ville)->current();

            	// on peuple les selects de pays concernée
            	$ePaysDepart->setValue($ville_dep->id_pays);
            	$ePaysArrivee->setValue($ville_arr->id_pays);


            	// on peuple la périodicité
            	$ePeriodicite->setValue($destination->periodicite);

            	// on peuple le formulaire avec les information demandé
                $destination = array(
                	'timepickerfin'	=> date('H:i',$destination->date_arr),
                	'datepickerfin'	=> date('d-m-Y',$destination->date_arr),
                	'datepickerdeb'	=> date('d-m-Y',$destination->date_dep),
                	'timepickerdeb'	=> date('H:i',$destination->date_dep)
                	);


            	$this->populate ( $destination );
			}
			
			// on change le label du bouton
			$eSubmit->setLabel ( 'Modifier' );
		}
/*===========================PRÉ REMPLISSAGE DU FORMULAIRE==============================*/

	}


	/**
	 * @param $numeroVol the $numeroVol to set
	 */
	public function setNumeroVol($numeroVol) {
		$this->numeroVol = $numeroVol;
	}

	/**
	 * @return the $numeroVol
	 */
	public function getNumeroVol() {
		return $this->numeroVol;
	}


	/**
     * Liste des Pays
     */
    private function listPays () {
		// on charge le model
		$tablePays = new TPays;
		// on recupere tout les pays
        $reqPays = $tablePays	->select()
    							->from($tablePays)
    							->order("nom_pays");

	    $pays = $tablePays->fetchAll($reqPays);

        // on instancie le resultat en tableau de pays
        $paysTab = array();

        $paysTab["-1"] = "-- Choisissez --"; 
        foreach ($pays as $p) {
        	$paysTab[$p->id_pays] = utf8_encode($p->nom_pays);
        }
 
        return $paysTab;
    }
 

    /**
     * Liste des Aeroports
     */
    private function listAeroport () {
		// on charge les models
		$tableAeroport = new TAeroport;
 
		// on recupere tout les pays
        $reqAeroport = $tableAeroport	->select()
		    							->from($tableAeroport)
		    							->order("nom_aeroport");

	    $aeroport = $tableAeroport->fetchAll($reqAeroport);

        // on instancie le resultat en tableau de pays
        $aeroportTab = array();

        $aeroportTab[""] = "-- Choisissez --"; 
        foreach ($aeroport as $a) {
        	$tableAeroport = new TAeroport;
        	// on recherche l'aeroport par clé primaire
        	$aeroport = $tableAeroport	->find($a->trigramme)
        								->current();
        	$ville = $aeroport->findParentRow('TVille');

        	$aeroportTab[$a->trigramme] = utf8_encode($ville->nom_ville . ' - ' . $a->nom_aeroport);
        }
 
        return $aeroportTab;
    }
 
}