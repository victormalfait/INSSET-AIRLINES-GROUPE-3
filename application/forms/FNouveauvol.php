<?php

class FNouveauvol extends Zend_Form
{
	private $numeroVol;

	public function init(){
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
		$eNumeroVol = new Zend_Form_Element_Text('numeroVol');
		$eNumeroVol	->setLabel('Numéro vol :')
					->setAttrib('size', '1')
					->setAttrib('maxlength', '5')
					->addFilter('StripTags')
		            ->addFilter('StringTrim')
					->setDecorators($decorators);
		// depart
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
			$ePaysDepart = new Zend_Form_Element_Select('paysDepart');
			$ePaysDepart	->setLabel('Pays : ')
							->setMultiOptions($paysTab)
							->setDecorators($decorators);
        //////////// fin de recuperation des pays pour liste //////////////



		//////////// recuperation des ville pour liste //////////////
				//on charge le model
				$tableVille = new TVille;
				//on recupere toutes les villes
		        $ville = $tableVille->fetchAll();
		        // on instancie le resultat en tableau de ville
		        $villeTab = array();

		        foreach ($ville as $v) {
		        	$villeTab[$v->id] = htmlentities($v->nom);
		        }

	        // creation de l'élément
			$eVilleDepart = new Zend_Form_Element_Select('villeDepart');
			$eVilleDepart	->setLabel('Ville : ')
							->setMultiOptions($villeTab)
							->setDecorators($decorators);
        //////////// fin de recuperation des ville pour liste //////////////


		//////////// recuperation des aeroports pour liste //////////////
				// on charge le model
				$tableAeroport = new TAeroport;
				//on recupere tout les aeroports
		        $aeroport = $tableAeroport->fetchAll();
		        // on instancie le resultat en tableau d'aeroport
		        $aeroportTab = array();

		        foreach ($aeroport as $a) {
		        	$aeroportTab[$a['trigramme']] = htmlentities($a->nom);
		        }

	        // Creation de l'élément
			$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
			$eAeroportDepart	->setLabel('Aeroport : ')
								->setMultiOptions($aeroportTab)
								->setDecorators($decorators);
		//////////// fin de recuperation des aeroports pour liste //////////////
		
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

		// //Arrivee
		$ePaysArrivee = new Zend_Form_Element_Select('paysArrivee');
		$ePaysArrivee	->setLabel('Pays : ')
						->setMultiOptions($paysTab)
						->setDecorators($decorators);

		$eVilleArrive = new Zend_Form_Element_Select('villeArrive');
		$eVilleArrive	->setLabel('Ville : ')
						->setMultiOptions($villeTab)
						->setDecorators($decorators);

		$eAeroportArrivee = new Zend_Form_Element_Select('aeroportArrivee');
		$eAeroportArrivee	->setLabel('Aeroport : ')
							->setMultiOptions($aeroportTab)
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
						->setMultiOptions(array('Vol unique'=>'Vol unique','Lundi'=>'Lundi', 'Mardi'=>'Mardi', 'Mercredi'=>'Mercredi', 'Jeudi'=>'Jeudi', 'Vendredi'=>'Vendredi', 'Samedi'=>'Samedi', 'Dimanche'=>'Dimanche'))
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
		$elements = array(	$eNumeroVol,
							$ePaysDepart,
							$eVilleDepart,
							$eAeroportDepart,
							$eDepartH,
							$eDepartM,
							$ePaysArrivee,
							$eVilleArrive,
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
								'villeDepart',
								'aeroportDepart',
								'timepickerdeb',
								'datepickerdeb'), 'depart', array("legend" => "Départ"));

		$this->addDisplayGroup(array(
								'paysArrivee',
								'villeArrive',
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

			// ... on charde le model de base de donnée Client,
			$tableDestination = new TDestination;
			// on envoi la requete pour recupere les informations de l'utilisateur
            $destination = $tableDestination  ->find($numeroVol)
                                    ->current();

			// si on a un retour
			if ($destination != null) {

                 
                
                $eAeroportDepart->setValue($destination->tri_aero_dep);
                $eAeroportArrivee->setValue($destination->tri_aero_arr);

                $tableAeroport = new TAeroport;

            	$aeroport_dep = $tableAeroport->find($destination->tri_aero_dep)->current();
            	$aeroport_arr = $tableAeroport->find($destination->tri_aero_arr)->current();

                $tableVille = new TVille;

            	$ville_dep = $tableVille->find($aeroport_dep->id_ville)->current();
            	$ville_arr = $tableVille->find($aeroport_arr->id_ville)->current();
            	$eVilleDepart->setValue($aeroport_dep->id_ville);
            	$eVilleArrive->setValue($aeroport_arr->id_ville);

            	// on peuple le formulaire avec les information demandé
                $destination = array(
                	'numeroVol' 	=> $destination->numero_vol,
                	'timepickerfin'	=> date('H:i',$destination->heure_arr),
                	'datepickerfin'	=> date('d-m-Y',$destination->date_arr),
                	'datepickerdeb'	=> date('d-m-Y',$destination->date_dep),
                	'timepickerdeb'	=> date('H:i',$destination->heure_dep)
                	);


            	$this->populate ( $destination );
			}
			
			// on change le label du bouton
			$eSubmit->setLabel ( 'Modifier' );
		}
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
}