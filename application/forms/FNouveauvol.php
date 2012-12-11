<?php

class FNouveauvol extends Zend_Form
{
	private $numeroVol;

	public function init(){
		$numero_vol = $this->getNumeroVol();
	//===============Parametre du formulaire

		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'ConnexionNouveauVol');

	//===============Creation des element

		$eNumeroVol = new Zend_Form_Element_Text('numeroVol');
		$eNumeroVol	->setLabel('Numéro vol :')
					->setRequired(true)
					->setAttrib('required', 'required')
					->addValidator('notEmpty');


		$tablePays = new TPays;
        $pays = $tablePays->fetchAll();
        $paysTab = array();

        foreach ($pays as $p) {
        	$paysTab[$p['id']] = $p['nom'];
        }

		//Depart
		$ePaysDepart = new Zend_Form_Element_Select('paysDepart');
		$ePaysDepart	->setLabel('Pays : ')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setMultiOptions($paysTab)
						->addValidator('notEmpty');

		$tableVille = new TVille;
        $ville = $tableVille->fetchAll();
        $villeTab = array();

        foreach ($ville as $v) {
        	$villeTab[$v['id']] = $v['nom'];
        }

		$eVilleDepart = new Zend_Form_Element_Select('villeDepart');
		$eVilleDepart	->setLabel('Ville : ')
						->setRequired(true)
						->setAttrib('required', 'required')
						->setMultiOptions($villeTab)
						->addValidator('notEmpty');

		$tableAeroport = new TAeroport;
        $aeroport = $tableAeroport->fetchAll();
        $n = 0;
        $aeroportTab = array();

        foreach ($aeroport as $a) {
        	$aeroportTab[$n] = $a['nom'];
        	$n++;
        }

		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
		$eAeroportDepart	->setLabel('Aeroport : ')
							->setRequired(true)
							->setAttrib('required', 'required')
							->setMultiOptions($aeroportTab)
							->addValidator('notEmpty');
		
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
		$ePeriodicite	//->setLabel('Aeroport : ')
							->setRequired(true)
							->setAttrib('required', 'required')
							->setMultiOptions(array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'))
							->addValidator('notEmpty');

		// Terminer
		$eSubmit = new Zend_Form_Element_Submit('Enregistrer');

		// Ajout des éléments au formulaire
		$elements = array ( $eNumeroVol, $ePaysDepart, $eVilleDepart,$eAeroportDepart, $eDepartH, $eDepartM, $ePaysArrivee, $eVilleArrive, $eAeroportArrivee, $eArriveeH, $eArriveeM, $ePeriodicite, $eSubmit );
		$this->addElements ( $elements );
	}

	public function setNumeroVol($id){
		$this->numeroVol = $id;
	}

	public function getNumeroVol(){
		echo $this->numeroVol;
		return $this->numeroVol;
		
	}

}

		 // si on a une valeur ...
   //      if (isset ( $numero_vol ) && $numero_vol != "") {
        	

   //          // ... on charde le model de base de donnée Client,
   //          $tableDestination = new TDestination ( );
   //          // on envoi la requete pour recupere les informations de l'utilisateur
   //          $destination = $tableDestination  ->find($numero_vol)
   //                                ->current();
   //          // si on a un retour
   //          if ($destination != null) {
   //              // on peuple le formulaire avec les information demandé
   //              $destination = array(
   //              	'numeroVol' => $destination->numero_vol,
   //              	'arriveeH'
   //              	'datepickerfin'
   //              	'datepickerdeb'
   //              	'departH'
   //              	);

   //              $this->populate ( $destination );

   //          }
           
        //     // on change le label du bouton
        //     $eSubmit->setLabel ( 'Modifier' );
        // }

		// $this->addDisplayGroup(array(
		// 	'ePaysDepart',
		// 	'eAeroportDepart',
		// 	'eDepartH',
		// 	'eDepartM'
  //           ),'Depart',array('legend' => 'Départ'));
        
        // $Depart = $this->getDisplayGroup('Depart');
        // $Depart->setDecorators(array(
        
        //             'FormElements',
        //             'Fieldset',
        //             array('HtmlTag',array('tag'=>'div','style'=>'width:50%;float:left;'))
        // ));

  //       $this->addDisplayGroup(array(
		// 	'ePaysArrivee',
		// 	'eAeroportArrivee',
		// 	'eArriveeH',
		// 	'eArriveeM'
  //           ),'Arrivee',array('legend' => 'Arrivée'));
        
  //       $Arrivee = $this->getDisplayGroup('Arrivee');
  //       $Arrivee->setDecorators(array(
        
  //                   'FormElements',
  //                   'Fieldset',
  //                   array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
  //       ));

  //       $this->addDisplayGroup(array(
		// 	'periodicite'
  //           ),'periodicite',array('legend' => 'Périodicité'));
        
  //       $periodicite = $this->getDisplayGroup('periodicite');
  //       $periodicite->setDecorators(array(
        
  //                   'FormElements',
  //                   'Fieldset',
  //                   array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
  //       ));

		// $this->addDisplayGroup(array(
		// 	'Enregistrer'
  //           ),'Enregistrer',array('legend' => 'Terminer'));
        
  //       $Enregistrer = $this->getDisplayGroup('Enregistrer');
  //       $Enregistrer->setDecorators(array(
        
  //                   'FormElements',
  //                   'Fieldset',
  //                   array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
  //       ));