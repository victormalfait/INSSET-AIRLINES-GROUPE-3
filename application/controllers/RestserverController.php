<?php

class RestserverController extends Zend_Controller_Action
{

    protected $_server;
    
    public function init() {

        //Initialise Le REST
        $this->_server = new Zend_Rest_Server();

        // Desactive tout le HTML pour qui ne reste que le XML en sortie
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->viewRenderer->setNeverRender(true);
    }

    public function indexAction() {

        // Ajoute la Classe VolsClass au service REST
        $this->_server->setClass('VolsClass');
        $this->_server->handle();
    }
}

class VolsClass {

    //Recupere tous les vols qui son programmer 
    public function allVols() {

        //  http://inssetairline.fr/restserver/index?method=allVols

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

            $tableAvion = new TAvion;
            $avion = $tableAvion->find($vol->immatriculation)->current();

            $tableModelAvion = new TModelAvion;
            $modelAvion = $tableModelAvion->find($avion->id_model)->current();

            $tab_vol[$count]['numero_vol']              = $destination->numero_vol;
            $tab_vol[$count]['heur_depart']             = $vol->heure_dep;
            $tab_vol[$count]['aeroport_depart']         = $aeroport->nom_aeroport;
            $tab_vol[$count]['vill_depart']             = $ville->nom_ville;
            $tab_vol[$count]['pays_depart']             = $pays->nom_pays;
            $tab_vol[$count]['heur_arrive']             = $vol->heure_arr;
            $tab_vol[$count]['aeroport_arrive']         = $aeroportBis->nom_aeroport;
            $tab_vol[$count]['vill_arrive']             = $villeBis->nom_ville;
            $tab_vol[$count]['pays_arrive']             = $paysBis->nom_pays;
            $tab_vol[$count]['avion_model']             = $modelAvion->nom_model;
            $tab_vol[$count]['avion_immatriculation']   = $avion->immatriculation;
            $tab_vol[$count]['remarque']                = $vol->remarque;

            $count++;
        }
        return $tab_vol;

    }

    //Retourne une lsite des Reservation avec une heure de depart (timestamp) , si la date et null ou 0 alors il commence a la date actuel
    public function listReservation($date) {

        //  http://inssetairline.fr/restserver/index?method=listReservation&date=0

        if ($date == 0){
            $time = new time;
            $date = $time->timestamp_now();
        }
       

        $tableReservation = new TReservation;
        $reservationRequest = $tableReservation->select()->where('heure_dep >= ?',$date)->order('heure_dep');
        $reservations = $tableReservation->fetchAll($reservationRequest);

        $tab_reservation = array();
        $count = 0;

        foreach ($reservations as $reservation) {

            $vitesse = 800 * 1000;
            $tableDestination = new TDestination;
            $destination = $tableDestination->find($reservation->id_destination)->current();

            $tableVol = new TVols;
            $volRequest = $tableVol->select()->where('id_destination = ?',$destination->id_destination)
                                             ->where('heure_dep = ?', $reservation->heure_dep);
            $vol = $tableVol->fetchRow($volRequest);

            if(isset($vol) && $vol != ''){
                $id_vol = $vol->id_vols;
                
                $tableAvion = new TAvion;
                $avion = $tableAvion->find($vol->immatriculation)->current();

                $tableModelAvion = new TModelAvion;
                $modelAvion = $tableModelAvion->find($avion->id_model)->current();

                $vitesse = $modelAvion->vitesse * 1000;
            }

            $tableAeroport = new TAeroport;
            $aeroportDepart = $tableAeroport->find($destination->tri_aero_dep)->current();
            $aeroportArrive = $tableAeroport->find($destination->tri_aero_arr)->current();

            $tableVille = new TVille;
            $villeDepart = $tableVille->find($aeroportDepart->id_ville)->current();
            $villeArrive = $tableVille->find($aeroportArrive->id_ville)->current();

            $tablePays = new TPays;
            $paysDepart = $tablePays->find($villeDepart->id_pays)->current();
            $paysArrive = $tablePays->find($villeArrive->id_pays)->current();

            $division = $destination->distance / $vitesse;
            $minute = ($division*60)%60;
            $heure = intval($division);
            $duree = $heure.'h'.$minute.'min';

            $tab_reservation[$count]['numero_vol']      = $destination->numero_vol;
            $tab_reservation[$count]['aeroport_depart'] = $aeroportDepart->nom_aeroport;
            $tab_reservation[$count]['ville_depart']    = $villeDepart->nom_ville;
            $tab_reservation[$count]['pays_depart']     = $paysDepart->nom_pays;
            $tab_reservation[$count]['aeroport_arrive'] = $aeroportArrive->nom_aeroport;
            $tab_reservation[$count]['ville_arrive']    = $villeArrive->nom_ville;
            $tab_reservation[$count]['pays_arrive']     = $paysArrive->nom_pays;
            $tab_reservation[$count]['heure_dep']       = $reservation->heure_dep;
            $tab_reservation[$count]['duree']           = $duree;
            $tab_reservation[$count]['nbr_passager']    = $reservation->nbr_passager;
            $tab_reservation[$count]['tarif']           = $reservation->tarif;

            $count++;

        }
        return $tab_reservation;

    }

}