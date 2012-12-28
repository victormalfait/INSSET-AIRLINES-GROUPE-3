<?php

class FNouvelUtilisateur extends Zend_Form
{
	private $idUtilisateur;

	public function init()
	{
	//===============Parametre du formulaire
		$this->setName('nouvelUtilisateur');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FNouvelUtilisateur');

		
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
        $eLogin         = new Zend_Form_Element_Text('login');
        $eLogin         ->setLabel('Login : ')
        				->setAttrib('required', 'required')
						->addValidator('notEmpty')
        				->addFilter('StripTags')
        				->addFilter('StringTrim')
        				->setDecorators($decorators);

        $eNom           = new Zend_Form_Element_Text('nom');
        $eNom           ->setLabel('Nom : ')
        				->setAttrib('required', 'required')
						->addValidator('notEmpty')
        				->addFilter('StripTags')
        				->addFilter('StringTrim')
        				->setDecorators($decorators);

        $ePrenom        = new Zend_Form_Element_Text('prenom');
        $ePrenom        ->setLabel('Prénom : ')
        				->setAttrib('required', 'required')
						->addValidator('notEmpty')
        				->addFilter('StripTags')
        				->addFilter('StringTrim')
        				->setDecorators($decorators);

        $eAdresse       = new Zend_Form_Element_Text('adresse');
        $eAdresse       ->setLabel('Adresse : ')
        				->setAttrib('required', 'required')
						->addValidator('notEmpty')
        				->addFilter('StripTags')
        				->addFilter('StringTrim')
        				->setDecorators($decorators);

        $eCodePostal    = new Zend_Form_Element_Text('codePostal');
        $eCodePostal    ->setLabel('Code Postal : ')
        				->setAttrib('required', 'required')
						->addValidator('notEmpty')
						->setAttrib('size', '1')
						->setAttrib('maxlength', '5')
        				->addFilter('StripTags')
        				->addFilter('StringTrim')
        				->setDecorators($decorators);

        $eVille         = new Zend_Form_Element_Text('ville');
        $eVille         ->setLabel('Ville : ')
        				->setAttrib('required', 'required')
						->addValidator('notEmpty')
        				->addFilter('StripTags')
        				->addFilter('StringTrim')
        				->setDecorators($decorators);

        $ePassword      = new Zend_Form_Element_Password('password');
        $ePassword      ->setLabel('Mot de passe : ')
        				->setAttrib('required', 'required')
						->addValidator('notEmpty')
        				->addFilter('StripTags')
        				->addFilter('StringTrim')
        				->setDecorators($decorators);


		//////////// recuperation des service pour liste //////////////
			// on charge le model
			$tableService 	= new TService;
			// on recupere tout les service
	        $services 		= $tableService->fetchAll();
	        // on instancie le resulta en tableau
	        $serviceTab 	= array();

	        foreach ($services as $s) {
	        	$serviceTab[$s->id_service] = utf8_encode($s->nom);
	        }
			// creation de l'élément
        $eService       = new Zend_Form_Element_Select('service');
        $eService       ->setLabel('Service : ')
						->setMultiOptions($serviceTab)
        				->addFilter('StripTags')
        				->addFilter('StringTrim')
        				->setDecorators($decorators);
        //////////// fin de recuperation des service pour liste //////////////

	
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
		$elements = array(	
							$eLogin,
							$eNom,
							$ePrenom,
							$eAdresse,
							$eCodePostal,
							$eVille,
							$ePassword,
							$eService,
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
	 * @param $idUtilisateur the $idUtilisateur to set
	 */
	public function setIdUtilisateur($idUtilisateur) {
		$this->idUtilisateur = $idUtilisateur;
	}

	/**
	 * @return the $idUtilisateur
	 */
	public function getIdUtilisateur() {
		return $this->idUtilisateur;
	}
}