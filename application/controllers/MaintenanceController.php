<?php

class MaintenanceController extends Zend_Controller_Action
{
	public function indexAction()
    {
    	$tableAvion = new TAvion;
		$fetchAllavion = $tableAvion->fetchAll();

		foreach ($fetchAllavion as $avion) {
			$tableMaintenance = new TMaintenance;
			$selectMaintenance= $tableMaintenance->select()
                                         ->from(array('m' => 'maintenance'),
                                                array('m.immatriculation','m.date_prevue','m.date_eff','m.duree_prevue','m.duree_eff','m.note'))
                                          ->where('m.immatriculation = ?',$avion['immatriculation'] );
            $maintenance = $tableMaintenance->fetchRow($selectMaintenance);

			$tableModelAvion = new TModelAvion;
  			$selectModelAvion= $tableModelAvion->select()
                                         ->from(array('a' => 'model_avion'),
                                                array('a.id_model','a.nom_model'))
                                          ->where('a.id_model = ?',$avion['id_model'] );
            $modelavion = $tableModelAvion->fetchRow($selectModelAvion);          

            if (!empty($maintenance))
            {
	            $listeavion[] = array('nom_model' =>  $modelavion['nom_model'],'immatriculation' =>  $avion['immatriculation']
					            ,'heure_vol_total' =>  $avion['heure_vol_total'],'heure_depuis_revision' =>  $avion['heure_depuis_revision']
					            ,'date_prevue' =>  $maintenance['date_prevue'],'date_eff' =>  $maintenance['date_eff']
					            ,'duree_prevue' =>  $maintenance['duree_prevue'],'duree_eff' =>  $maintenance['duree_eff'], );
            }else{
	            $listeavion[] = array('nom_model' =>  $modelavion['nom_model'],'immatriculation' =>  $avion['immatriculation']
		            ,'heure_vol_total' =>  $avion['heure_vol_total'],'heure_depuis_revision' =>  $avion['heure_depuis_revision']
		            ,'date_prevue' =>  0,'date_eff' =>  0
		            ,'duree_prevue' =>  0,'duree_eff' =>  0, );		
            }

		}
		$this->view->avion = $listeavion;
		
    }
    public function afficherAction()
    {
    	$tableMaintenance = new TMaintenance;
		$maintenance = $tableMaintenance->fetchAll();

		$jour_now  = $this->view->jour_actuel = date('j', time());
		$mois_now  = $this->view->mois_actuel = date('m', time());
		$annee_now  = $this->view->annee_actuel = date('Y', time());

		$nb_jour_now = date('t', time());

		$this->view->DayNames = array( "Dim","Lun","Mar","Mer","Jeu","Ven","Sam");

		$this->view->NameMois = array( "01" => "Janvier","02" => "Fevrier","03" => "Mars","04" => "Avril","05" => "Mai","06" => "Juin",
                   "07" => "Juillet","08" => "Aout","09" => "Septembre","10" =>"Octobre", "11" =>"Novembre", "12" =>"Decembre");

		

		foreach ($maintenance as $listmaintenance) { 

			// Date de chaque maintenance
			$date_prevue = $listmaintenance['date_prevue'];
			$jour_maintenance = date('j', $date_prevue);
			$mois_maintenance = date('m', $date_prevue);
			$annee_maintenance = date('Y', $date_prevue);
			$nb_jour_maintenance = date('t', $date_prevue);
			$duree_maintenance = $listmaintenance['duree_prevue'];

			$calendrier[$annee_maintenance][$mois_maintenance]['nbjours'][0] = $nb_jour_maintenance;
			$conteur = 0;

			$jourcalendrier = $jour_maintenance;//30

			for ($i=1; $i <= $duree_maintenance; $i++) { //10

				// Redefinie le jour , mois et l'année si on depasser la fin du mois
				if ( $jourcalendrier > $nb_jour_maintenance ){
						$mois_maintenance = $mois_maintenance+1;
						$jourcalendrier = 1;
						if ($mois_maintenance == 13){
							$mois_maintenance = 0;
							$annee_maintenance = $annee_maintenance+1;
						}
				}

				$mois_maintenance = str_pad($mois_maintenance, 2, "0", STR_PAD_LEFT); // Transforme le 2 en 02 ;
				$jourcalendrier = str_pad($jourcalendrier, 2, "0", STR_PAD_LEFT);
				$calendrier[$annee_maintenance][$mois_maintenance][$jourcalendrier]['note'] = $listmaintenance['note'];
				$calendrier[$annee_maintenance][$mois_maintenance][$jourcalendrier]['immatriculation'] = $listmaintenance['immatriculation'];
				$jourcalendrier++;
			}
		}
		// Si il n'y a pas de maintenance programmer ( table vide ) 
		if ( !isset($calendrier) ){
			$calendrier[$annee_now][$mois_now]['nbjours'][0] = $nb_jour_now;
		}
		$this->view->ligne = $calendrier;

    }

    public function ajouterAction()
    {
		



		$formMaintenance = new FNouvelleMaintenance;
		$this->view->formMaintenance = $formMaintenance;
		$verif = true;
		if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();

            if ($formMaintenance->isValid($formData)) {

					// Convertie la date "dd-mm-yy" en Timestamps

            		$datepickerdeb 	= $formMaintenance->getValue('datepickerdeb');

	            	list($jour, $mois, $annee) = explode("-", $datepickerdeb);
	            	$datedeb = mktime(0 , 0, 0, $mois, $jour, $annee);

					$datefin = $datedeb+($_POST['Time_Revision']*86400)-86400;

	            	$tableMaintenance = new TMaintenance;
	            	
	            	// Recupere toute la liste des maintenances deja prevue.
					$listmaintenance = $tableMaintenance->fetchAll();	

					// Boucle sur chaque maintenance
					foreach ($listmaintenance as $maintenance) { 

						$debut = $maintenance['date_prevue'];
						$fin = $maintenance['date_prevue']+($maintenance['duree_prevue']*86400)-86400;

						//verifie si une maintenance n'existe pas deja a la date prevue.
						if (  !($debut <=  $datedeb  && $datedeb <= $fin) ){
							if ( !($debut <=  $datefin && $datefin <= $fin )){
								$verif = true;
								// echo '1<br/>';
								// echo $debut.' - '.$datedeb.' <= '.$fin.' <br/> '.$debut.' <= '.$datefin.' <= '.$fin;
								// echo '<br/>';
							}else{
								$verif = false;
							}
						}else{
							$verif = false;

						}
					}

					if ($verif == true)
					{

		            	$immatriculation = $this->_getParam('immatriculation');

		            	$row = $tableMaintenance->createRow(); 
						$row->immatriculation  	= $immatriculation;
						$row->date_prevue 	 	= $datedeb;
						$row->duree_prevue  	= $_POST['Time_Revision'];
						$row->date_eff  		= 0;
						$row->duree_eff 	 	= 0;
						$row->note 	 	= $_POST['note'];

		                $row->save();
		                $formMaintenance->reset();
		                $redirector = $this->_helper->getHelper('Redirector');
		                $redirector->gotoUrl('maintenance/index');
					}else{
	            		echo 'Il a deja une maintance a cette date !';
	            	}

	            }else{
	            	echo 'Veillez remplire tout les champs , merci !';
	            }
        }
    }
    public function supprimerAction()
    {
        $immatriculation = $this->_getParam('immatriculation');

        $tableMaintenance = new TMaintenance;
        $whereMaintenance = $tableMaintenance->getAdapter()->quoteInto('immatriculation = ?', $immatriculation);
        $tableMaintenance->delete($whereMaintenance);

        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoUrl("maintenance/afficher");
    }

	public function ajouterAvionAction(){

	}

	public function menumaintenanceAction(){

	}

    
}