<?php

class LogistiqueController extends Zend_Controller_Action
{
	public function indexAction(){

      	$date = time();

        $tableVol = new TVols;
        $selectvols = $tableVol->select()->where('heure_dep <= ?',$date);

        $vols = $tableVol->fetchAll($selectvols);

        $tab_vol = array();
        $count = 0;
        $nbr_repas_normal = 0;
        $nbr_repas_enfant = 0;
        $nbr_repas_hallal = 0;
        $nbr_repas_vegetarien = 0;
        $nbr_repas_casher = 0;

        //Boucle sur chaque vols
		foreach ($vols as $vol) {

            // Recupere chaque information du vols
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

                $tableAvion = new TAvion;
                $avion = $tableAvion->find($vol->immatriculation)->current();

                $tableModelAvion = new TModelAvion;
                $modelAvion = $tableModelAvion->find($avion->id_model)->current();

			$tab_vol[$count]['total_reservation'] = 0;

            $tableReservation = new TReservation;
            $selectReservation  = $tableReservation->select()->where('id_destination = ?' ,$vol->id_destination);
        	$reservations = $tableReservation->fetchAll($selectReservation);

			//Compe le nombre de passager sur le vol
            foreach ($reservations as $reservation) {
				$tab_vol[$count]['total_reservation'] = $tab_vol[$count]['total_reservation'] + $reservation['nbr_passager'];

                $tableClient = new TClient;
                $clientRequest = $tableClient->select()->where('id_reservation = ?', $reservation->id_reservation)->orwhere('id_reservation_retour = ?', $reservation->id_reservation);
                $clients = $tableClient->fetchAll($clientRequest);

                

                foreach ($clients as $client) {
                    if($client->repas == 'normal'){
                        $nbr_repas_normal++;
                    }elseif ($client->repas == 'enfant') {
                        $nbr_repas_enfant++;
                    }elseif ($client->repas == 'hallal') {
                        $nbr_repas_hallal++;
                    }elseif ($client->repas == 'végétarien') {
                        $nbr_repas_vegetarien++;
                    }elseif ($client->repas == 'casher') {
                        $nbr_repas_casher++;
                    }
                    
                }
            }

            // reunie toute les informations dans un seul meme tableau
            $tab_vol[$count]['numero_vol']              = $destination->numero_vol;

            $tab_vol[$count]['heur_depart']             = $vol->heure_dep;
            $tab_vol[$count]['aeroport_depart']         = $aeroport->nom_aeroport;
            $tab_vol[$count]['ville_depart']            = $ville->nom_ville;
            $tab_vol[$count]['pays_depart']             = $pays->nom_pays;
            $tab_vol[$count]['heur_arrive']             = $vol->heure_arr;
            $tab_vol[$count]['aeroport_arrive']         = $aeroportBis->nom_aeroport;
            $tab_vol[$count]['ville_arrive']            = $villeBis->nom_ville;
            $tab_vol[$count]['pays_arrive']             = $paysBis->nom_pays;
            $tab_vol[$count]['repas']                   = $nbr_repas_normal.' normal(aux) '.$nbr_repas_enfant.' enfant(s) '.$nbr_repas_hallal.' hallal(s) '.$nbr_repas_vegetarien.' végétarien(s) '.$nbr_repas_casher.' casher(s)';
            $tab_vol[$count]['avion_model']             = $modelAvion->nom_model;
            $tab_vol[$count]['nbr_place']             	= $modelAvion->nbr_place;

            $count++;
        }

        $this->view->listevol = $tab_vol;

	}
}