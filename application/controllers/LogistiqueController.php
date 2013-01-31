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

            $tab_vol[$count]['avion_model']             = $modelAvion->nom_model;
            $tab_vol[$count]['nbr_place']             	= $modelAvion->nbr_place;

            $tab_vol[$count]['remarque']                = $vol->remarque;

            $count++;
        }

        $this->view->listevol = $tab_vol;

	}
}