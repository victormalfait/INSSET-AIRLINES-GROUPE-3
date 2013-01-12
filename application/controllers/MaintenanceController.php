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
                                                array('m.immatriculation','m.date_prevue','m.date_eff','m.duree_prevue','m.duree_eff'))
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

		$jour_actuel = date('j', time());
		$mois_actuel = date('m', time());
		$annee_actuel = date('Y', time());

		$this->view->DayNames = array("Dim","Lun","Mar","Mer","Jeu","Ven","Sam");

		$NameMois = array( 01 => "Janvier","Fevrier","Mars","Avril","Mai","Juin",
                   "Juillet","Aout","Septembre","Octobre","Novembre","Decembre");

		foreach ($maintenance as $listmaintenance) { 

			// Date de chaque maintenance
			$date_prevue = $listmaintenance['date_prevue'];
			$jour_maintenance = date('j', $date_prevue);
			$mois_maintenance = date('m', $date_prevue);
			$annee_maintenance = date('Y', $date_prevue);



			$Jmaintenance = $listmaintenance['duree_prevue'];

			// Genere un tableau avec les maintenance du mois en cour
			//$this->view->NameOne = $NameMois[$mois_actuel].' - '.$annee_actuel;

			if ($annee_maintenance == $annee_actuel && $mois_maintenance == $mois_actuel )
			{
				$ligne[0][0] = date('t',time()); 
				for ($i = 0; $i <= $Jmaintenance-1; $i++) {
					$ligne[0][$jour_maintenance+$i+1] = $listmaintenance['immatriculation'];
				}
			}

			// Si le prochain mois et l'annné prochiane , je cahnge d'année
			if ($mois_actuel == 12 ) 
			{
				$mois_actuel = 0;
				$annee_actuel++;
			}

			//$this->view->NameTwo = $NameMois[$mois_actuel+1].' - '.$annee_actuel;
			// Genere un tableau avec les maintenance du mois en cour+1
			if ($annee_maintenance ==  $annee_actuel && $mois_maintenance == $mois_actuel+1 )
			{
				$ligne[1][0] = date('t',time()+(31*3600*24)); // Rajoute  mois a la date actuele
				for ($i = 0; $i <= $Jmaintenance-1; $i++) {
					$ligne[1][$jour_maintenance+$i+1] = $listmaintenance['immatriculation'];
				}
			}

		}
		$this->view->ligne = $ligne;

    }
    public function ajouterAction()
    {
		$immatriculation = $this->_getParam('immatriculation');

		$formMaintenance = new FNouvelleMaintenance;
		$this->view->formMaintenance = $formMaintenance;

		if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();

            if ($form->isValid($formData)) {

					$Time_Revision 	= $form->getValue('Time_Revision');
					$Maint_Debut 	= $form->getValue('Maint_Debut');

	            	$tableMaintenance = new TMaintenance;
	            	$row = $tableMaintenance->createRow(); 

	            	//Router => une verif si pas un autre avion deja maintenace  en cour a cette date
	            		
					$row->immatriculation  	= $immatriculation;
					$row->date_prevue 	 	= $Maint_Debut;
					$row->duree_prevue  	= $Time_Revision;
					$row->date_eff  		= 0;
					$row->duree_eff 	 	= 0;

	                $result = $row->save();
	                $form->reset();

	                $redirector = $this->_helper->getHelper('Redirector');
	                $redirector->gotoUrl('maintenance/index');
	            }else{
	            	echo 'Veillez remplire tout les champs , merci !';
	            }
        }
    }
    public function delAction()
    {

    }
    
}