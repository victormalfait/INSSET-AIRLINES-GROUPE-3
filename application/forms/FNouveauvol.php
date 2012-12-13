<?php

class FNouveauvol extends Zend_Form
{
	private $numeroVol;

	public function init(){
	//===============Parametre du formulaire
		// on recupere la valeur de l'id utilisateur
		$numeroVol = $this->getNumeroVol();
		
		$this->setName('nouveauVol');
		$this->setMethod('post');
		$this->setAction('/strategie/nouveau/numero_vol/'.$numeroVol);
		$this->setAttrib('id', 'FNouveauvol');

		

	//=============== Creation des element
		$eNumeroVol = new Zend_Form_Element_Text('numeroVol');
		$eNumeroVol	->setLabel('Numéro vol :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->addFilter('StripTags')
		            ->addFilter('StringTrim')
					->addValidator('notEmpty');
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
							->setRequired(true)
							->setAttrib('required', 'required')
							->setMultiOptions($paysTab)
							->addValidator('notEmpty');
        //////////// fin de recuperation des pays pour liste //////////////



		//////////// recuperation des ville pour liste //////////////
			// on charge le model
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
							->setRequired(true)
							->setAttrib('required', 'required')
							->setMultiOptions($villeTab)
							->addValidator('notEmpty');
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
								->setRequired(true)
								->setAttrib('required', 'required')
								->setMultiOptions($aeroportTab)
								->addValidator('notEmpty')
								->setValue('MRS');
		//////////// fin de recuperation des aeroports pour liste //////////////
		
		$eDepartH = new Zend_Form_Element_Text('timepickerdeb');
		$eDepartH	->setLabel('Heure :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('class','timepickerdeb'.$numeroVol)
					->addValidator('notEmpty');

		$eDepartM = new Zend_Form_Element_Text('datepickerdeb');
		$eDepartM	->setLabel('Date :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('class','datepickerdeb'.$numeroVol)
					->addValidator('notEmpty');

		//Arrivee
		$ePaysArrivee = new Zend_Form_Element_Select('paysArrivee');
		$ePaysArrivee	->setLabel('Pays : ')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setMultiOptions($paysTab)
						->addValidator('notEmpty');

		$eVilleArrive = new Zend_Form_Element_Select('villeArrive');
		$eVilleArrive	->setLabel('Ville : ')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setMultiOptions($villeTab)
						->addValidator('notEmpty');

		$eAeroportArrivee = new Zend_Form_Element_Select('aeroportArrivee');
		$eAeroportArrivee	->setLabel('Aeroport : ')
							->setRequired(true)
							->setAttrib('required', 'required')
							->setMultiOptions($aeroportTab)
							->addValidator('notEmpty');
		
		$eArriveeH = new Zend_Form_Element_Text('timepickerfin');
		$eArriveeH	->setLabel('Heure :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('class','timepickerfin'.$numeroVol)
					->addValidator('notEmpty');

		$eArriveeM = new Zend_Form_Element_Text('datepickerfin');
		$eArriveeM	->setLabel('Date :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('class','datepickerfin'.$numeroVol)
					->addValidator('notEmpty');

		// Périodicité
		$ePeriodicite = new Zend_Form_Element_Select('periodicite');
		$ePeriodicite	->setLabel('periodicite : ')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setMultiOptions(array('Vol unique'=>'Vol unique','Lundi'=>'Lundi', 'Mardi'=>'Mardi', 'Mercredi'=>'Mercredi', 'Jeudi'=>'Jeudi', 'Vendredi'=>'Vendredi', 'Samedi'=>'Samedi', 'Dimanche'=>'Dimanche'))
						->addValidator('notEmpty');

		// Terminer
		$eSubmit = new Zend_Form_Element_Submit('Enregistrer');
		// $eSubmit 	->setLabel('Enregistrer')
		// 			->setAttrib('id', 'submitbutton');


		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'close');

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


/*===========================PRÉ REMPLISSAGE DU FORMULAIRE==============================*/
		

		// si on a une valeur ...
		if (isset ( $numeroVol ) && $numeroVol != "") {
			$eDepartM->setName('datepickerdeb'.$numeroVol);
			$eDepartH->setName('timepickerdeb'.$numeroVol);
			$eArriveeM->setName('datepickerfin'.$numeroVol);
			$eArriveeH->setName('timepickerfin'.$numeroVol);

			// ... on charde le model de base de donnée Client,
			$tableDestination = new TDestination ( );
			// on envoi la requete pour recupere les informations de l'utilisateur
            $destination = $tableDestination  ->find($numeroVol)
                                    ->current();

			// si on a un retour
			if ($destination != null) {

                 
                
                $eAeroportDepart->setValue($destination->tri_aero_dep);
                $eAeroportArrivee->setValue($destination->tri_aero_arr);

                $tableAeroport = new TAeroport ( );

            	$aeroport_dep = $tableAeroport->find($destination->tri_aero_dep)->current();
            	$aeroport_arr = $tableAeroport->find($destination->tri_aero_arr)->current();

                $tableVille = new TVille ( );

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