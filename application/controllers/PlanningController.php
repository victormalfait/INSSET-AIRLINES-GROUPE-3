<?php

class PlanningController extends Zend_Controller_Action
{
	//fonction pour calculer le nombre de jour d'écart entre le jour d'aujourd'hui et le jour du vol
	public function find_date_vol($timestamp, $jour) {
        $target = $timestamp;
        //on recupere le jour d'aujourd'hui
        $day = date('D',$target);
        switch(strtoupper($day)) {
            case "MON":$first = 0;
                break;
            case "TUE":$first = 1;
                break;
            case "WED":$first = 2;
                break;
            case "THU":$first = 3;
                break;
            case "FRI":$first = 4;
                break;
            case "SAT":$first = 5;
                break;
            case "SUN":$first = 6;
                break;
            default:$first = 0;
                break;
        }

        switch($jour) {
            case "Lundi":$second = 0;
                break;
            case "Mardi":$second = 1;
                break;
            case "Mercredi":$second =2;
                break;
            case "Jeudi":$second = 3;
                break;
            case "Vendredi":$second = 4;
                break;
            case "Samedi":$second = 5;
                break;
            case "Dimanche":$second = 6;
                break;
            default:$second = 0;
                break;
        }
        //nombre en timestamp d'une journée
        $one_day = 24*60*60;
        //nombre de jour d'ecart entre le jour d'aujourdhui et le jour du vol
        $day_left = $second - $first;
        //si la différence est negative on ajoute 7 
        if($day_left < 0)
        	$day_left += 7;
        //on ajoute au timestamp d'aujourdhui le nombre de journee d'ecart
        $date = $target + ($one_day * $day_left);
        //on renvoi le timestamp
        return $date;
    }

	public function indexAction(){
		$tableVol = new TVols;
		$vols = $tableVol->fetchAll();

		$tab_vol = array();
		$count = 0;
		foreach ($vols as $vol) {
			$tableDestination = new TDestination;
			$destination = $tableDestination->find($vol->id_destination)->current();

			$tableAeroport = new TAeroport;
			$aeroport = $tableAeroport->find($destination->tri_aero_dep)->current();
			$aeroportBis = $tableAeroport->find($destination->tri_aero_arr)->current();

			$tableVille = new TVille;
			$ville = $tableVille->find($aeroport->id_ville)->current();
			$villeBis = $tableVille->find($aeroportBis->id_ville)->current();

			$tablePays = new TPays;
			$pays = $tablePays->find($ville->id_pays)->current();
			$paysBis = $tablePays->find($villeBis->id_pays)->current();

			$tablePilote = new TPilote;
			$pilote = $tablePilote->find($vol->id_pilote)->current();
			$copilote = $tablePilote->find($vol->id_copilote)->current();

			$tableUtilisateur = new TUtilisateur;
			$utilisateur = $tableUtilisateur->find($pilote->id_utilisateur)->current();
			$utilisateurBis = $tableUtilisateur->find($copilote->id_utilisateur)->current();

			$tableAvion = new TAvion;
			$avion = $tableAvion->find($vol->immatriculation)->current();

			$tableModelAvion = new TModelAvion;
			$modelAvion = $tableModelAvion->find($avion->id_model)->current();

			$tab_vol[$count]['numero_vol'] = $destination->numero_vol;
			$tab_vol[$count]['depart'] = 'Le '.date("d/m/Y à H:i",$vol->heure_dep).' de '.$aeroport->nom_aeroport.'<br/>'.$ville->nom_ville.' ('.$pays->nom_pays.')';
			$tab_vol[$count]['arrive'] = 'Le '.date("d/m/Y à H:i",$vol->heure_arr).' de '.$aeroportBis->nom_aeroport.'<br/>'.$villeBis->nom_ville.' ('.$paysBis->nom_pays.')';
			$tab_vol[$count]['avion'] = $modelAvion->nom_model.' ('.$avion->immatriculation.')';
			$tab_vol[$count]['pilotes'] = 'Pilote: '.$utilisateur->nom_utilisateur.' '.$utilisateur->prenom_utilisateur.'<br/>Co-Pilote: '.$utilisateurBis->nom_utilisateur.' '.$utilisateurBis->prenom_utilisateur;
			$tab_vol[$count]['remarque'] = $vol->remarque;

			$count++;
		}
		$this->view->vol = $tab_vol;
	}

	public function menuplanningAction(){
	}

	public function planificationAction(){

		//requete dans la BDD
		$tableDestination = new TDestination;
		$destinationRequest = $tableDestination	->select()
												->where('plannification = 0')
												->orwhere('periodicite != "Vol unique"');
		$destinations = $tableDestination->fetchAll($destinationRequest);

		$tabDestination = array();

		$mois = date("m", time());
        $jour = date("d", time());
        $an = date("Y", time());

        //on cree un timestamp de la date d'aujourd'hui
		$date = mktime(0,0,0, $mois, $jour, $an);

		//nous sert à indexer le tableau
		$i=0;

		foreach ($destinations as $destination) {

			if($destination->periodicite != "Vol unique"){

				//appel à la fonction pour trouver le nombre de jour d'ecart
				$date_vol = $this->find_date_vol($date, $destination->periodicite);

				$minuteD = date("i", $destination->heure_dep);
				$heureD = date("H", $destination->heure_dep);
				$mois = date("m", $date_vol);
		        $jour = date("d", $date_vol);
		        $an = date("Y", $date_vol);

		        //on cree un timestamp pour la date de depart du vol
				$date_vol_dep = mktime($heureD,$minuteD,0, $mois, $jour, $an);

				$minuteA = date("i", $destination->heure_arr);
				$heureA = date("H", $destination->heure_arr);

				$heure = $heureA - $heureD;
				if($heure < 0)
					$heure += 24;
				$minute = $minuteA - $minuteD;
				if($heure < 0)
					$heure += 60;

				//on cree le timestamp de la date d'arrivee du vol
				$date_vol_arr = $date_vol_dep + $heure*60*60 + $minute*60;

				//pour afficher sur 4 semaines le vol periodique
				for ($j=0; $j < 4; $j++) {

					$test = 0;
					//nous permet de verifier que le vol n'est pas deja planifier
					$tableVol = new TVols;
					$volRequest = $tableVol	->select()
											->where('id_destination = ?',$destination->id_destination);
					$vols = $tableVol->fetchAll($volRequest);

					foreach ($vols as $vol) {
						if($vol->heure_dep == $date_vol_dep)
							$test += 1;
					}

					if($test == 0){
						$tabDestination[$i]['id_destination'] = $destination->id_destination;
						$tabDestination[$i]['numero_vol'] = $destination->numero_vol;
						$tabDestination[$i]['tri_aero_dep'] = $destination->tri_aero_dep;
						$tabDestination[$i]['tri_aero_arr'] = $destination->tri_aero_arr;
						$tabDestination[$i]['heure_dep'] = $date_vol_dep;
						$tabDestination[$i]['heure_arr'] = $date_vol_arr;
						
						$i++;
					}
					//on incremente d'une semaine pour pouvoir avoir les dates sur 4 semaines
					$date_vol_dep += 7*24*60*60;
					$date_vol_arr += 7*24*60*60;
				}
			//si le vol n'est pas periodique
			}else{
				$tabDestination[$i]['id_destination'] = $destination->id_destination;
				$tabDestination[$i]['numero_vol'] = $destination->numero_vol;
				$tabDestination[$i]['tri_aero_dep'] = $destination->tri_aero_dep;
				$tabDestination[$i]['tri_aero_arr'] = $destination->tri_aero_arr;
				$tabDestination[$i]['heure_dep'] = $destination->heure_dep;
				$tabDestination[$i]['heure_arr'] = $destination->heure_arr;
				$i++;
			}
		}

		$this->view->destination = $tabDestination;
	}

	public function planifierAction(){
		$id_destination = $this->_getParam('id');
		$heureD 		= $this->_getParam('heureD');
		$heureA 		= $this->_getParam('heureA');

		$form = new FPlanifier;

		$this->view->id_destination = $id_destination;
		$this->view->formPlannifier = $form;
		$this->view->heureD 		= $heureD;
		$this->view->heureA 		= $heureA;

		
		$form->setIdDestination($id_destination);
		$form->init();

		$tableDestination = new TDestination;
		$destination = $tableDestination->find($id_destination)->current();
		$this->view->destination = $destination;

		if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();
            // var_dump( $formVol->isValid($formData));

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
            	if($form->getValue('pilote') != -1 && $form->getValue('copilote') != -1 && $form->getValue('avion') != -1){

	            	$tableVol = new TVols;
	            	$row = $tableVol->createRow();                

	                $row->id_pilote        = $form->getValue('pilote');
	                $row->id_copilote      = $form->getValue('copilote');
	                $row->immatriculation  = $form->getValue('avion');
	                $row->id_destination   = $id_destination;
	                $row->remarque         = 'RAS';
	                $row->heure_dep        = $heureD;
	                $row->heure_arr        = $heureA;

	                //sauvegarde de la requete
	                $result = $row->save();
	                $destination->plannification = 1;
	                $destination->save();
	    
	                // RAZ du formulaire
	                $form->reset();

	                $redirector = $this->_helper->getHelper('Redirector');
	                $redirector->gotoUrl('planning/index');
	            }else{
	            	echo 'choisissez un avion, un pilote et un copilote pour ce vol';
	            }
            }
        }
	}
}