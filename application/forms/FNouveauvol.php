<?php

class FNouveauvol extends Zend_Form
{
	private $numeroVol;

	public function init(){
	//===============Parametre du formulaire
		$this->setName('nouveauVol');
		$this->setMethod('post');
		$this->setAction('');
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
	        $n = 0;
	        $aeroportTab = array();

	        foreach ($aeroport as $a) {
	        	$aeroportTab[$n] = htmlentities($a->nom);
	        	$n++;
	        }
	        // Creation de l'élément
			$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
			$eAeroportDepart	->setLabel('Aeroport : ')
								->setRequired(true)
								->setAttrib('required', 'required')
								->setMultiOptions($aeroportTab)
								->addValidator('notEmpty');
		//////////// fin de recuperation des aeroports pour liste //////////////
		
		$eDepartH = new Zend_Form_Element_Text('timepickerdeb');
		$eDepartH	->setLabel('Heure :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('class','timepickerdeb'.$numero_vol)
					->addValidator('notEmpty');

		$eDepartM = new Zend_Form_Element_Text('datepickerdeb');
		$eDepartM	->setLabel('Date :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('class','datepickerdeb'.$numero_vol)
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
					->setAttrib('class','timepickerfin'.$numero_vol)
					->addValidator('notEmpty');

		$eArriveeM = new Zend_Form_Element_Text('datepickerfin');
		$eArriveeM	->setLabel('Date :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->setAttrib('class','datepickerfin'.$numero_vol)
					->addValidator('notEmpty');

		// Périodicité
		$ePeriodicite = new Zend_Form_Element_Select('periodicite');
		$ePeriodicite	->setLabel('periodicite : ')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setMultiOptions(array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche', 'Unique'))
						->addValidator('notEmpty');

		// Terminer
		$eSubmit = new Zend_Form_Element_Submit('Enregistrer');
		$eSubmit 	->setLabel('Enregistrer')
					->setAttrib('id', 'submitbutton');

		// Ajout des éléments au formulaire
		$elements = array( $eNumeroVol, $ePaysDepart, $eVilleDepart,$eAeroportDepart, $eDepartH, $eDepartM, $ePaysArrivee, $eVilleArrive, $eAeroportArrivee, $eArriveeH, $eArriveeM, $ePeriodicite, $eSubmit );
		$this->addElements ( $elements );


/*===========================PRÉ REMPLISSAGE DU FORMULAIRE==============================*/
		// on recupere la valeur de l'id utilisateur
		$numeroVol = $this->getNumeroVol();

		// si on a une valeur ...
		if (isset ( $numeroVol ) && $numeroVol != "") {

			// ... on charde le model de base de donnée Client,
			$tableDestination = new TDestination ( );
			// on envoi la requete pour recupere les informations de l'utilisateur
            $destination = $tableDestination  ->find($numeroVol)
                                    ->current();

			// si on a un retour
			if ($destination != null) {
                // on peuple le formulaire avec les information demandé
                $destination = array(
                	'numeroVol' 	=> $destination->numero_vol,
                	'arriveeH'		=> $destination->numero_vol,
                	'datepickerfin'	=> $destination->numero_vol,
                	'datepickerdeb'	=> $destination->numero_vol,
                	'departH'		=> $destination->numero_vol,
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