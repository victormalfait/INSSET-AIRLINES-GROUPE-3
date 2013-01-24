<?php

class CommercialController extends Zend_Controller_Action
{

	public function indexAction()
    {
    	// on charge le formulaire FCommercial
    	$formCommercial = new FCommercial;

    	// on l'envoi à la vu
    	$this->view->formCommercial = $formCommercial;             
    }

    public function rechercheAction ()
    {
        $formCommercial = new FCommercial;

        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($formCommercial->isValid($formData)) {
                //on recupere la date de départ, le npmbre de passager et le type
                $date_debut = $formCommercial->getValue('datepickerdeb');
                $nbrPassager = $formCommercial->getValue('nbrPassager');
                $coefPrix = $formCommercial->getValue('typePassager');
                $date_fin = $formCommercial->getValue('datepickerfin');

                //on initialise un tableau pour stocker le type de chaque passager
                $tabTypePassager = array();

                //initialisation d'une session
                $sessionUser = new Zend_Session_Namespace('user');

                //initialisation de la classe des fonctions pour gérer le temps
                $time = new time;

                //on enregistre en session le nombre de passager
                $sessionUser->nbrPassager = $formCommercial->getValue('nbrPassager');
                $tabTypePassager['1'] = $formCommercial->getValue('typePassager');

                //on boucle pour pouvoir enregistrer dans le tableau et on incrémente une variable qui permettra d'obtenir un prix
                for ($i=2; $i <= $nbrPassager; $i++) {
                    $coefPrix += $formCommercial->getValue('typePassager'.$i);
                    $tabTypePassager[$i] = $formCommercial->getValue('typePassager'.$i);
                }
                //on enregistre en session le tableau et la classe
                $sessionUser->typePassager = $tabTypePassager;
                $sessionUser->classe = $formCommercial->getValue('classe');
                //on multiplie par le coef de la classe choisie
                $coefPrix = $coefPrix * $formCommercial->getValue('classe');

                //on convertit au format timestamp
                list($jourD, $moisD, $anneeD) = explode("-", $date_debut);          
                list($jourD, $moisD, $anneeD) = explode("-", $date_fin);          
                $date_retour = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);
                $date_depart = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);

                //on appelle la fonction pour obtenir la date du jour
                $date_jour = $time->timestamp_now();

                //on initialise une variable pour avoir la date 1 semaines avant
                $date_depart_avant = $date_depart - 7*24*60*60;
                $date_retour_avant = $date_retour - 7*24*60*60;

                //on verifie que la date ne soit pas inferieure a la date du jour
                if($date_depart_avant < $date_jour){
                    $date_depart_avant = $date_jour;
                }

                if($date_retour_avant < $date_depart_avant){
                    $date_retour_avant = $date_depart_avant;
                }

                //on initialise une variable à 3 semaines apres la date demandée
                $date_depart_apres = $date_depart + 7*24*60*60;
                $date_retour_apres = $date_retour + 7*24*60*60;

                $tableAeroport = new TAeroport;

                $aeroportDepartRequest = $tableAeroport->select()->where('id_ville = ?', $formCommercial->getValue('aeroportDepart'));
                $aeroportArrivetRequest = $tableAeroport->select()->where('id_ville = ?', $formCommercial->getValue('aeroportArrive'));

                $aeroportDeparts = $tableAeroport->fetchAll($aeroportDepartRequest);
                $aeroportArrives = $tableAeroport->fetchAll($aeroportArrivetRequest);

                //on initialise un tableau pour enregistrer les informations sur les vols
                $table_content_aller = array();
                $table_content_retour = array();
                $count = 0;
                $countRetour = 0;

                foreach ($aeroportDeparts as $aeroportDepart) {
                    foreach ($aeroportArrives as $aeroportArrive) {

                        //on cherche les destinations avec les aeroports de départ et d'arrivé 
                        $tableDestination = new TDestination;
                        $destinationRequest = $tableDestination->select()
                                                               ->where('tri_aero_dep = ?', $aeroportDepart->trigramme)
                                                               ->where('tri_aero_arr = ?', $aeroportArrive->trigramme);
                        $destinationRetourRequest = $tableDestination->select()
                                                                     ->where('tri_aero_dep = ?', $aeroportArrive->trigramme)
                                                                     ->where('tri_aero_arr = ?', $aeroportDepart->trigramme);
                        $destinationsRetour = $tableDestination->fetchAll($destinationRetourRequest);
                        $destinations = $tableDestination->fetchAll($destinationRequest);

                        $date_test = $date_retour_avant + 1;

                        foreach ($destinations as $destination) {
                            //si le vol est unique ...
                            if($destination->periodicite == 'Vol unique'){

                                //on vérifie que le vol soit entre les dates voulu
                                if($destination->heure_dep >= $date_depart_avant && $destination->heure_dep <= $date_depart_apres){
                                    //on enregistre les valeur dans le tableau
                                    $table_content_aller[$count]['numero_vol']    = $destination->numero_vol;
                                    $table_content_aller[$count]['heure_dep']     = $destination->heure_dep;
                                    $table_content_aller[$count]['heure_arr']     = $destination->heure_arr;
                                    $table_content_aller[$count]['depart']        = $aeroportDepart->nom_aeroport;
                                    $table_content_aller[$count]['arrive']        = $aeroportArrive->nom_aeroport;
                                    $table_content_aller[$count]['prix']          = ($destination->distance / 10000) * $coefPrix;
                                    $count++;
                                    if($date_retour_avant < $destination->heure_dep && $date_retour_avant < $date_test){
                                        $date_retour_avant = $destination->heure_dep;
                                        $date_test = $destination->heure_dep;
                                    }
                                }
                            //si le vol est periodique
                            }else{
                                //on boucle pour enregistrer les infos des vols périodiques
                                for ($i=0; $i < 3; $i++) { 

                                    $date = $date_depart_avant + 7*24*60*60*$i;

                                    //appel à la fonction pour trouver le nombre de jour d'ecart
                                    $date_vol = $time->find_date_vol($date, $destination->periodicite);

                                    //on cree un timestamp pour la date de depart du vol
                                    $date_vol_dep = $time->timestamp_fly($date_vol, $destination->heure_dep);

                                    $date_vol_arr = $time->timestamp_fly($date_vol_dep, $destination->heure_arr);

                                    $table_content_aller[$count]['numero_vol']    = $destination->numero_vol;
                                    $table_content_aller[$count]['heure_dep']     = $date_vol_dep;
                                    $table_content_aller[$count]['heure_arr']     = $date_vol_arr;
                                    $table_content_aller[$count]['depart']        = $aeroportDepart->nom_aeroport;
                                    $table_content_aller[$count]['arrive']        = $aeroportArrive->nom_aeroport;
                                    $table_content_aller[$count]['prix']          = ($destination->distance / 10000) * $coefPrix;
                                    $count++;

                                    if($date_retour_avant < $date_vol_dep && $date_retour_avant < $date_test){
                                        $date_retour_avant = $date_vol_dep;
                                        $date_test = $date_vol_dep;
                                    }
                                }
                            }
                        }
                        if($formCommercial->getValue('typeTrajet') == 2){
                            foreach ($destinationsRetour as $destinationRetour) {
                                //si le vol est unique ...
                                if($destinationRetour->periodicite == 'Vol unique'){

                                    //on vérifie que le vol soit entre les dates voulu
                                    if($destinationRetour->heure_dep >= $date_retour_avant && $destinationRetour->heure_dep <= $date_retour_apres){
                                        //on enregistre les valeur dans le tableau
                                        $table_content_retour[$countRetour]['numero_vol']    = $destinationRetour->numero_vol;
                                        $table_content_retour[$countRetour]['heure_dep']     = $destinationRetour->heure_dep;
                                        $table_content_retour[$countRetour]['heure_arr']     = $destinationRetour->heure_arr;
                                        $table_content_retour[$countRetour]['depart']        = $aeroportArrive->nom_aeroport;
                                        $table_content_retour[$countRetour]['arrive']        = $aeroportDepart->nom_aeroport;
                                        $table_content_retour[$countRetour]['prix']          = ($destinationRetour->distance / 10000) * $coefPrix;
                                        $countRetour++;
                                    }
                                //si le vol est periodique
                                }else{
                                    //on boucle pour enregistrer les infos des vols périodiques
                                    for ($i=0; $i < 3; $i++) { 

                                        $date = $date_retour_avant + 7*24*60*60*$i;

                                        //appel à la fonction pour trouver le nombre de jour d'ecart
                                        $date_vol = $time->find_date_vol($date, $destinationRetour->periodicite);

                                        //on cree un timestamp pour la date de depart du vol
                                        $date_vol_dep = $time->timestamp_fly($date_vol, $destinationRetour->heure_dep);

                                        $date_vol_arr = $time->timestamp_fly($date_vol_dep, $destinationRetour->heure_arr);

                                        $table_content_retour[$countRetour]['numero_vol']    = $destinationRetour->numero_vol;
                                        $table_content_retour[$countRetour]['heure_dep']     = $date_vol_dep;
                                        $table_content_retour[$countRetour]['heure_arr']     = $date_vol_arr;
                                        $table_content_retour[$countRetour]['depart']        = $aeroportArrive->nom_aeroport;
                                        $table_content_retour[$countRetour]['arrive']        = $aeroportDepart->nom_aeroport;
                                        $table_content_retour[$countRetour]['prix']          = ($destination->distance / 10000) * $coefPrix;;
                                        $countRetour++;
                                    }
                                }
                            }
                        }
                    }
                }                
                //on envoie le tableau à la vue
                $this->view->tabVol = $table_content_aller;
                $this->view->tabVolRetour = $table_content_retour; 
                          
            }                                                                    
            // RAZ du formulaire
            $formCommercial->reset();
        }        
    }

    public function detailsrechercheAction ()
    {
        if ($this->_request->isPost()) {

            $sessionUser = new Zend_Session_Namespace('user');

            list($numero_vol,$heure_dep,$prix) = explode('/', $_POST['volAller']);

            $this->view->nbrPassager = $sessionUser->nbrPassager;
            $typePassager = $sessionUser->typePassager;

            $nbrAdultes = 0; $nbrEnfants = 0; $nbrSeniors = 0;
            for ($i=1; $i <= $sessionUser->nbrPassager ; $i++) { 
                if($typePassager[$i] == 1){
                    $nbrAdultes++;
                }elseif($typePassager[$i] == 0.5){
                    $nbrEnfants++;
                }elseif($typePassager[$i] == 0.75){
                    $nbrSeniors++;
                }
            }

            $this->view->classe = $sessionUser->classe;
            $tarif = $prix/($nbrAdultes+$nbrEnfants*0.5+$nbrSeniors*0.75);

            $this->view->nbrAdultes = $nbrAdultes;
            $this->view->nbrEnfants = $nbrEnfants;
            $this->view->nbrSeniors = $nbrSeniors;
            $this->view->tarif = $tarif;
            $vitesse = 800*1000;
            $vitesseRetour = 800*1000;
            $id_vol = 0;
            $id_vol_retour = 0;

            $tableDestination = new TDestination;
            $destinationRequest = $tableDestination->select()->where('numero_vol = ?', $numero_vol);
            $destination = $tableDestination->fetchRow($destinationRequest);   

            $tableVol = new TVols;
            $volRequest = $tableVol->select()->where('id_destination = ?',$destination->id_destination)
                                             ->where('heure_dep = ?', $heure_dep);
            $vol = $tableVol->fetchRow($volRequest);

            if(isset($vol) && $vol != ''){
                $id_vol = $vol->id_vol;
                
                $tableAvion = new TAvion;
                $avion = $tableAvion->find($vol->immatriculation)->current();

                $tableModelAvion = new TModelAvion;
                $modelAvion = $tableModelAvion->find($avion->id_model)->current();

                $this->view->avion = $modelAvion->nom_model;

                $vitesse = $modelAvion->vitesse * 1000;
            }

            $division = $destination->distance / $vitesse;
            $minute = ($division*60)%60;
            $heure = intval($division);
            $duree = $heure.'h'.$minute.'min';

            $this->view->duree = $duree;

            $heure_arr = $destination->heure_arr - $destination->heure_dep + $heure_dep;

            $tableAeroport = new TAeroport;
            $aeroportDepart = $tableAeroport->find($destination->tri_aero_dep)->current();
            $aeroportArrive = $tableAeroport->find($destination->tri_aero_arr)->current();

            $tableVille = new TVille;
            $villeDepart = $tableVille->find($aeroportDepart->id_ville)->current();
            $villeArrive = $tableVille->find($aeroportArrive->id_ville)->current();

            $volAller = array(
                'numero_vol'     => $numero_vol,
                'depart'         => $aeroportDepart->nom_aeroport,
                'arrive'         => $aeroportArrive->nom_aeroport,
                'heure_dep'      => $heure_dep,
                'heure_arr'      => $heure_arr,
                'villeDepart'    => $villeDepart->nom_ville,
                'villeArrive'    => $villeArrive->nom_ville
                );

            $this->view->volAller = $volAller;

            if(isset($_POST['volRetour']) && $_POST['volRetour'] != ''){

                list($numero_vol_retour,$heure_dep_retour,$prixRetour) = explode('/', $_POST['volRetour']);

                $destinationRetourRequest = $tableDestination->select()->where('numero_vol = ?', $numero_vol_retour);
                $destinationRetour = $tableDestination->fetchRow($destinationRetourRequest);
                $volRetourRequest = $tableVol->select()->where('id_destination = ?',$destinationRetour->id_destination)
                                                       ->where('heure_dep = ?', $heure_dep_retour);
                $volRetour = $tableVol->fetchRow($volRetourRequest);

                if(isset($volRetour) && $volRetour != ''){
                    $id_vol_retour = $volRetour->id_vol;

                    $avionRetour = $tableAvion->find($volRetour->immatriculation)->current();

                    $modelAvionRetour = $tableModelAvion->find($avionRetour->id_model)->current();

                    $this->view->avionRetour = $modelAvionRetour->nom_model;

                    $vitesseRetour = $modelAvionRetour->vitesse * 1000;

                }

                $divisionRetour = $destinationRetour->distance / $vitesse;
                $minuteRetour = ($divisionRetour*60)%60;
                $heureRetour = intval($divisionRetour);
                $dureeRetour = $heureRetour.'h'.$minuteRetour.'min';

                $this->view->dureeRetour = $dureeRetour;

                $tarifRetour = $prixRetour/($nbrAdultes+$nbrEnfants*0.5+$nbrSeniors*0.75);

                $this->view->tarifRetour = $tarifRetour;

                $heure_arr_retour = $destinationRetour->heure_arr - $destinationRetour->heure_dep + $heure_dep_retour;

                $aeroportDepartRetour = $tableAeroport->find($destinationRetour->tri_aero_dep)->current();
                $aeroportArriveRetour = $tableAeroport->find($destinationRetour->tri_aero_arr)->current();

                $volRetour = array(
                    'numero_vol'     => $numero_vol_retour,
                    'depart'         => $aeroportDepartRetour->nom_aeroport,
                    'arrive'         => $aeroportArriveRetour->nom_aeroport,
                    'heure_dep'      => $heure_dep_retour,
                    'heure_arr'      => $heure_arr_retour
                );

            $this->view->volRetour = $volRetour;
            }

            $sessionUser->id_destination_aller = $destination->id_destination;
            $sessionUser->id_destination_retour = $destinationRetour->id_destination;
            $sessionUser->id_vol_aller = $id_vol;
            $sessionUser->id_vol_retour = $id_vol_retour;
            $sessionUser->heure_dep_aller = $heure_dep;
            $sessionUser->heure_dep_retour = $heure_dep_retour;
            $sessionUser->tarif_aller = $tarif;
            $sessionUser->tarif_retour = $tarifRetour;
            $sessionUser->nombre_adultes = $nbrAdultes;
            $sessionUser->nombre_enfants = $nbrEnfants;
            $sessionUser->nombre_senior = $nbrSeniors;
        }
    }

    public function reservationAction(){
        $sessionUser = new Zend_Session_Namespace('user');
        $this->view->nbrAdultes = $sessionUser->nombre_adultes;
        $this->view->nbrSeniors = $sessionUser->nombre_senior;
        $this->view->nbrEnfants = $sessionUser->nombre_enfants;
        $formClient = new FClient;
        $this->view->form = $formClient;

    }

    public function catalogueAction ()
    {
    	
    }

}