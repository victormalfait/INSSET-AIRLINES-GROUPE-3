<?php

class CommercialController extends Zend_Controller_Action
{

	public function indexAction()
    {
        if(isset($sessionUser))
            Zend_Session::namespaceUnset('user');
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
                $date_depart = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);

                //on appelle la fonction pour obtenir la date du jour
                $date_jour = $time->timestamp_now();

                //on initialise une variable pour avoir la date 1 semaines avant
                $date_depart_avant = $date_depart - 7*24*60*60;

                //on verifie que la date ne soit pas inferieure a la date du jour
                if($date_depart_avant < $date_jour){
                    $date_depart_avant = $date_jour;
                }

                //on initialise une variable à 3 semaines apres la date demandée
                $date_depart_apres = $date_depart + 7*24*60*60;

                //si le vol est en aller retour
                if($formCommercial->getValue('typeTrajet') == 2){

                    //on recupere la date de retour
                    list($jourD, $moisD, $anneeD) = explode("-", $date_fin);          
                    $date_retour = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);
                    //on initialise une varible 1 semaine avant
                    $date_retour_avant = $date_retour - 7*24*60*60;
                    //on vérifie que la date de retour ne soit pas inférieure à la plus petite date d'aller
                    if($date_retour_avant < $date_depart_avant){
                        $date_retour_avant = $date_depart_avant;
                    }
                    $date_retour_apres = $date_retour + 7*24*60*60;
                }                

                //on recupere les informations des aeroports
                $tableAeroport = new TAeroport;
                $aeroportDepartsRequest = $tableAeroport->select()->where('trigramme = ?',$formCommercial->getValue('aeroportDepart'));
                $aeroportArrivesRequest = $tableAeroport->select()->where('trigramme = ?',$formCommercial->getValue('aeroportArrive'));
                $aeroportDeparts = $tableAeroport->fetchAll($aeroportDepartsRequest);
                $aeroportArrives = $tableAeroport->fetchAll($aeroportArrivesRequest);

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

                        if($formCommercial->getValue('typeTrajet') == 2){
                            $date_test = $date_retour_avant + 1;
                        }

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
                                    $table_content_aller[$count]['prix']          = number_format(($destination->distance / 10000) * $coefPrix, 2);
                                    $count++;

                                    if($formCommercial->getValue('typeTrajet') == 2){
                                        if($date_retour_avant < $destination->heure_dep && $date_retour_avant < $date_test){
                                            $date_retour_avant = $destination->heure_dep;
                                            $date_test = $destination->heure_dep;
                                        }
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
                                    $table_content_aller[$count]['prix']          = number_format(($destination->distance / 10000) * $coefPrix, 2);
                                    $count++;

                                    if($formCommercial->getValue('typeTrajet') == 2){
                                        if($date_retour_avant < $date_vol_dep && $date_retour_avant < $date_test){
                                            $date_retour_avant = $date_vol_dep;
                                            $date_test = $date_vol_dep;
                                        }
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
                                        $table_content_retour[$countRetour]['prix']          = number_format(($destinationRetour->distance / 10000) * $coefPrix, 2);
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
                                        $table_content_retour[$countRetour]['prix']          = number_format(($destinationRetour->distance / 10000) * $coefPrix, 2);
                                        $countRetour++;
                                    }
                                }
                            }
                        }
                    }
                }                
                //on envoie le tableau à la vue
                $this->view->tabVol = $table_content_aller;
                if($formCommercial->getValue('typeTrajet') == 2){
                    $this->view->tabVolRetour = $table_content_retour; 
                }
                          
            }                                                                    
            // RAZ du formulaire
            $formCommercial->reset();
        }        
    }

    public function detailsrechercheAction ()
    {
        if ($this->_request->isPost()) {

            //on initialise une session
            $sessionUser = new Zend_Session_Namespace('user');

            //on recupere les informations envoyées dans le radio selectionné
            list($numero_vol,$heure_dep,$prix) = explode('/', $_POST['volAller']);

            $this->view->nbrPassager = $sessionUser->nbrPassager;
            $typePassager = $sessionUser->typePassager;

            //on recupere les différentes informations sur les passagers
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
            //on calcul le prix unitaire d'un vol pour un adulte
            $tarif = $prix/($nbrAdultes+$nbrEnfants*0.5+$nbrSeniors*0.75);

            $this->view->nbrAdultes = $nbrAdultes;
            $this->view->nbrEnfants = $nbrEnfants;
            $this->view->nbrSeniors = $nbrSeniors;
            $this->view->tarif = $tarif;

            //on initialise des variables de vitesse par defaut
            $vitesse = 800*1000;
            $vitesseRetour = 800*1000;

            $id_vol = 0;
            $id_vol_retour = 0;

            //on recherche la destination par rapport au numéro de vol
            $tableDestination = new TDestination;
            $destinationRequest = $tableDestination->select()->where('numero_vol = ?', $numero_vol);
            $destination = $tableDestination->fetchRow($destinationRequest);   

            //on recherche le vol par rapport a sa destination et son heure de depart
            $tableVol = new TVols;
            $volRequest = $tableVol->select()->where('id_destination = ?',$destination->id_destination)->where('heure_dep = ?', $heure_dep);
            $vol = $tableVol->fetchRow($volRequest);

            //si le vol est planifié on recupere le model d'avion
            if(isset($vol) && $vol != ''){
                $id_vol = $vol->id_vols;
                
                $tableAvion = new TAvion;
                $avion = $tableAvion->find($vol->immatriculation)->current();

                $tableModelAvion = new TModelAvion;
                $modelAvion = $tableModelAvion->find($avion->id_model)->current();

                $this->view->avion = $modelAvion->nom_model;
                //on enreigistre la vitesse de l'avion
                $vitesse = $modelAvion->vitesse * 1000;
            }

            //on calcul la durée de vol en fonction de la distance et de la vitesse de l'avion
            $division = $destination->distance / $vitesse;
            $minute = ($division*60)%60;
            $heure = intval($division);
            $duree = $heure.'h'.$minute.'min';

            $this->view->duree = $duree;

            $heure_arr = $destination->heure_arr - $destination->heure_dep + $heure_dep;


            //on recupere le nom des villes par rapport aux aeroports correspondant
            $tableAeroport = new TAeroport;
            $aeroportDepart = $tableAeroport->find($destination->tri_aero_dep)->current();
            $aeroportArrive = $tableAeroport->find($destination->tri_aero_arr)->current();

            $tableVille = new TVille;
            $villeDepart = $tableVille->find($aeroportDepart->id_ville)->current();
            $villeArrive = $tableVille->find($aeroportArrive->id_ville)->current();

            //on enregistre les informations necessaires pour l'affichage
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

            //si il y a un vol retour
            if(isset($_POST['volRetour']) && $_POST['volRetour'] != ''){

                list($numero_vol_retour,$heure_dep_retour,$prixRetour) = explode('/', $_POST['volRetour']);

                $destinationRetourRequest = $tableDestination->select()->where('numero_vol = ?', $numero_vol_retour);
                $destinationRetour = $tableDestination->fetchRow($destinationRetourRequest);
                $volRetourRequest = $tableVol->select()->where('id_destination = ?',$destinationRetour->id_destination)
                                                       ->where('heure_dep = ?', $heure_dep_retour);
                $volRetour = $tableVol->fetchRow($volRetourRequest);

                if(isset($volRetour) && $volRetour != ''){
                    $id_vol_retour = $volRetour->id_vols;

                    $tableAvion = new TAvion;
                    $avionRetour = $tableAvion->find($volRetour->immatriculation)->current();

                    $tableModelAvion = new TModelAvion;
                    $modelAvionRetour = $tableModelAvion->find($avionRetour->id_model)->current();

                    $this->view->avionRetour = $modelAvionRetour->nom_model;

                    $vitesseRetour = $modelAvionRetour->vitesse * 1000;

                }

                $divisionRetour = $destinationRetour->distance / $vitesse;
                $minuteRetour   = ($divisionRetour*60)%60;
                $heureRetour    = intval($divisionRetour);
                $dureeRetour    = $heureRetour.'h'.$minuteRetour.'min';

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
            //on enregistre des variables en session 
            $this->view->volRetour = $volRetour;
            $sessionUser->id_destination_retour = $destinationRetour->id_destination;
            $sessionUser->id_vol_retour         = $id_vol_retour;
            $sessionUser->heure_dep_retour      = $heure_dep_retour;
            $sessionUser->tarif_retour          = $tarifRetour;
            $sessionUser->prix_retour           = $prixRetour;
            }

            $sessionUser->id_destination_aller  = $destination->id_destination;
            $sessionUser->prix_aller            = $prix;
            $sessionUser->id_vol_aller          = $id_vol;
            
            $sessionUser->heure_dep_aller       = $heure_dep;
            
            $sessionUser->tarif_aller           = $tarif;
            
            $sessionUser->nombre_adultes        = $nbrAdultes;
            $sessionUser->nombre_enfants        = $nbrEnfants;
            $sessionUser->nombre_senior         = $nbrSeniors;
        }
    }

    public function reservationAction(){
        //on initialise la session
        $sessionUser = new Zend_Session_Namespace('user');
        $nbrAdultes = $sessionUser->nombre_adultes;
        $nbrSeniors = $sessionUser->nombre_senior;
        $nbrEnfants = $sessionUser->nombre_enfants;
        $nbr_passager = $nbrAdultes + $nbrSeniors + $nbrEnfants;
        $count=1;

        //on crée le formulaire pour les informations des clients
        $formClient = new FClient;

        if ($this->_request->isPost()) {
            //on recupere les variables envoyées
            $formData = $this->_request->getPost();

            if ($formClient->isValid($formData)) {

                //on crée une nouvelle ligne dans la table reservation
                $tableReservation = new TReservation;
                $reservation = $tableReservation->createRow();

                $id_reservation_retour = 0;
                //on regarde si un vol retour existe
                if(isset($sessionUser->tarif_retour) && $sessionUser->tarif_retour != ''){
                    $trajet = 'aller retour';
                    //on crée une nouvelle ligne dans la table reservation pour enregistrer les informations du vol retour
                    $reservation_retour = $tableReservation->createRow();

                    $reservation_retour->id_destination = $sessionUser->id_destination_retour;
                    $reservation_retour->id_vol         = $sessionUser->id_vol_retour;
                    $reservation_retour->heure_dep      = $sessionUser->heure_dep_retour;
                    $reservation_retour->tarif          = $sessionUser->prix_retour;
                    $reservation_retour->nbr_passager   = $nbr_passager;
                    $reservation_retour->trajet         = $trajet;

                    $reservation_retour->save();
                
                    $id_reservation_retour = $reservation_retour->id_reservation;

                }else{
                    $trajet = 'aller simple';
                }   
                //on enregistre les infos du vol aller
                $reservation->id_destination = $sessionUser->id_destination_aller;
                $reservation->id_vol         = $sessionUser->id_vol_aller;
                $reservation->heure_dep      = $sessionUser->heure_dep_aller;
                $reservation->tarif          = $sessionUser->prix_aller;
                $reservation->nbr_passager   = $nbr_passager;
                $reservation->trajet         = $trajet;

                $reservation->save();
                
                $id_reservation = $reservation->id_reservation;

                $tableClient = new TClient;

                //on enregistre les infos des clients adultes
                if(isset($nbrAdultes) && $nbrAdultes != ''){
                    for ($i=0; $i < $nbrAdultes ; $i++) {
                        $adultes = $tableClient->createRow();

                        $adultes->nom_client            = $_POST['nom'.$count];
                        $adultes->prenom_client         = $_POST['prenom'.$count];
                        $adultes->email_client          = $_POST['email'.$count];
                        $adultes->date_naissance        = $_POST['jour'.$count].'/'.$_POST['mois'.$count].'/'.$_POST['annee'.$count];
                        $adultes->civilite              = $_POST['civilite'.$count];
                        $adultes->id_reservation        = $id_reservation;
                        $adultes->id_reservation_retour = $id_reservation_retour;
                        $adultes->type                  = 'adulte';
                        $adultes->repas                 = $_POST['repasrestriction'.$count];
        
                        $adultes->save();
                        $count++;
                    }
                }
                //on enregistre les infos des clients seniors
                if(isset($nbrSeniors) && $nbrSeniors != ''){
                    for ($i=0; $i < $nbrSeniors ; $i++) { 
                        $senior = $tableClient->createRow();

                        $senior->nom_client             = $_POST['nom'.$count];
                        $senior->prenom_client          = $_POST['prenom'.$count];
                        $senior->email_client           = $_POST['email'.$count];
                        $senior->date_naissance         = $_POST['jour'.$count].'/'.$_POST['mois'.$count].'/'.$_POST['annee'.$count];
                        $senior->civilite               = $_POST['civilite'.$count];
                        $senior->id_reservation         = $id_reservation;
                        $senior->id_reservation_retour  = $id_reservation_retour;
                        $senior->type                   = 'sénior';
                        $senior->repas                  = $_POST['repasrestriction'.$count];
        
                        $senior->save();
                        $count++;
                    }
                }
                //on enregistre les infos des clients enfnats
                if(isset($nbrEnfants) && $nbrEnfants != ''){
                    for ($i=0; $i < $nbrEnfants ; $i++) { 
                        $enfant = $tableClient->createRow();

                        $enfant->nom_client             = $_POST['nom'.$count];
                        $enfant->prenom_client          = $_POST['prenom'.$count];
                        $enfant->email_client           = $_POST['email'.$count];
                        $enfant->date_naissance         = $_POST['jour'.$count].'/'.$_POST['mois'.$count].'/'.$_POST['annee'.$count];
                        $enfant->civilite               = $_POST['civilite'.$count];
                        $enfant->id_reservation         = $id_reservation;
                        $enfant->id_reservation_retour  = $id_reservation_retour;
                        $enfant->type                   = 'enfant';
                        $enfant->repas                  = $_POST['repasrestriction'.$count];
        
                        $enfant->save();
                        $count++;
                    }
                }
                // on redirige la page avec deux parametres dans l'url
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl("commercial/resumereservation/id_reservation/".$id_reservation."/id_reservation_retour/".$id_reservation_retour);
            }
        }else{
            //en fonction du nombre d'adulte on ajoute des champs au formulaire
            if(isset($nbrAdultes) && $nbrAdultes != ''){
                for ($i=0; $i < $nbrAdultes ; $i++) {
                    $formClient->setId($count);
                    $count++;
                }
            }
            //on fait pareil pour les seniors
            if(isset($nbrSeniors) && $nbrSeniors != ''){
                for ($i=0; $i < $nbrSeniors ; $i++) { 
                    $formClient->setId($count);
                    $count++;
                }
            }
            //et pour les enfants
            if(isset($nbrEnfants) && $nbrEnfants != ''){
                for ($i=0; $i < $nbrEnfants ; $i++) { 
                
                    $formClient->setId($count);
                    $count++;
                }
            }
            $this->view->formClient = $formClient;
        }
    }

    public function resumereservationAction ()
    {
        //on initialise la session
        $sessionUser = new Zend_Session_Namespace('user');
        $nbrAdultes = $sessionUser->nombre_adultes;
        $nbrSeniors = $sessionUser->nombre_senior;
        $nbrEnfants = $sessionUser->nombre_enfants;

        $vitesse = 800*1000;
        $vitesseRetour = 800*1000;

        //on recupere les varaibles passées dans l'URL
        $id_reservation = $this->_getParam('id_reservation');
        $id_reservation_retour = $this->_getParam('id_reservation_retour');

        //on recherche la reservation
        $tableReservation = new TReservation;
        $reservation = $tableReservation->find($id_reservation)->current();

        //on recupere la destination par rapport a la reservation
        $tableDestination = new TDestination;
        $destination = $tableDestination->find($reservation->id_destination)->current();

        //on recherche un vol correspondant
        $tableVol = new TVols;
        $volRequest = $tableVol->select()->where('id_destination = ?',$destination->id_destination)
                                         ->where('heure_dep = ?', $reservation->heure_dep);
        $vol = $tableVol->fetchRow($volRequest);

        //si il existe on recupere la vitesse de l'avion
        if(isset($vol) && $vol != ''){
            $id_vol = $vol->id_vols;
            
            $tableAvion = new TAvion;
            $avion = $tableAvion->find($vol->immatriculation)->current();

            $tableModelAvion = new TModelAvion;
            $modelAvion = $tableModelAvion->find($avion->id_model)->current();

            $vitesse = $modelAvion->vitesse * 1000;
        }

        //on recupere les infos des aeroports
        $tableAeroport = new TAeroport;
        $aeroportDepart = $tableAeroport->find($destination->tri_aero_dep)->current();
        $aeroportArrive = $tableAeroport->find($destination->tri_aero_arr)->current();

        //nous permettra d'avoir le nom de la ville ou se situeny les aeroports
        $tableVille = new TVille;
        $villeDepart = $tableVille->find($aeroportDepart->id_ville)->current();
        $villeArrive = $tableVille->find($aeroportArrive->id_ville)->current();

        //on recupere aussi le pays
        $tablePays = new TPays;
        $paysDepart = $tablePays->find($villeDepart->id_pays)->current();
        $paysArrive = $tablePays->find($villeArrive->id_pays)->current();

        //on calcul la duree du vol
        $division = $destination->distance / $vitesse;
        $minute = ($division*60)%60;
        $heure = intval($division);
        $duree = $heure.'h'.$minute.'min';

        //infos envoyés a la vue
        $data_aller = array(
            'depart' => $aeroportDepart->nom_aeroport.' ('.$villeDepart->nom_ville.', '.$paysDepart->nom_pays.')',
            'arrive' => $aeroportArrive->nom_aeroport.' ('.$villeArrive->nom_ville.', '.$paysArrive->nom_pays.')',
            'heure_dep' => $reservation->heure_dep,
            'duree' => $duree,
            'trajet' => $reservation->trajet,
            'nbrPassager' => $reservation->nbr_passager,
            'prix' => $reservation->tarif
            );

        //si il y a un vol retour on recherche et enregistre les information necessaire
        if($id_reservation_retour != 0){
            $reservationRetour = $tableReservation->find($id_reservation_retour)->current();

            $destinationRetour = $tableDestination->find($reservationRetour->id_destination)->current();

            $volRetourRequest = $tableVol->select()->where('id_destination = ?',$destinationRetour->id_destination)
                                                   ->where('heure_dep = ?', $reservationRetour->heure_dep);
            $volRetour = $tableVol->fetchRow($volRequest);

            if(isset($volRetour) && $volRetour != ''){
                $id_vol = $volRetour->id_vols;
                
                $avionRetour = $tableAvion->find($volRetour->immatriculation)->current();

                $modelAvionRetour = $tableModelAvion->find($avionRetour->id_model)->current();

                $vitesseRetour = $modelAvionRetour->vitesse * 1000;
            }

            $aeroportRetourDepart = $tableAeroport->find($destinationRetour->tri_aero_dep)->current();
            $aeroportRetourArrive = $tableAeroport->find($destinationRetour->tri_aero_arr)->current();

            $villeRetourDepart = $tableVille->find($aeroportRetourDepart->id_ville)->current();
            $villeRetourArrive = $tableVille->find($aeroportRetourArrive->id_ville)->current();

            $paysRetourDepart = $tablePays->find($villeRetourDepart->id_pays)->current();
            $paysRetourArrive = $tablePays->find($villeRetourArrive->id_pays)->current();

            $divisionRetour = $destinationRetour->distance / $vitesseRetour;
            $minuteRetour = ($divisionRetour*60)%60;
            $heureRetour = intval($divisionRetour);
            $dureeRetour = $heureRetour.'h'.$minuteRetour.'min';

            $data_retour = array(
            'depart' => $aeroportRetourDepart->nom_aeroport.' ('.$villeRetourDepart->nom_ville.', '.$paysRetourDepart->nom_pays.')',
            'arrive' => $aeroportRetourArrive->nom_aeroport.' ('.$villeRetourArrive->nom_ville.', '.$paysRetourArrive->nom_pays.')',
            'heure_dep' => $reservationRetour->heure_dep,
            'duree' => $dureeRetour,
            'trajet' => $reservationRetour->trajet,
            'nbrPassager' => $reservationRetour->nbr_passager,
            'prix' => $reservationRetour->tarif
            );
            $this->view->reservationRetour = $data_retour;
        }

        $this->view->reservation = $data_aller;

        //on recupere les clients liés a la reservation
        $tableClient = new TClient;
        $clientRequest = $tableClient->select()->where('id_reservation = ?', $id_reservation);
        $client = $tableClient->fetchAll($clientRequest);

        $this->view->clients = $client;
    }

    public function catalogueAction ()
    {
    	$time = new time;
        $date = $time->timestamp_now();

        $count = 0;

        $tableDestination = new TDestination;
        $destinationRequest = $tableDestination->select()->where('heure_dep >= ?', $date)->orwhere('periodicite != ?', 'Vol unique');
        $destinations = $tableDestination->fetchAll($destinationRequest);

        foreach ($destinations as $destination) {
            $vitesse = 800 * 1000;

            //si il existe on recupere la vitesse de l'avion
            if(isset($vol) && $vol != ''){
                $id_vol = $vol->id_vols;
                
                $tableAvion = new TAvion;
                $avion = $tableAvion->find($vol->immatriculation)->current();

                $tableModelAvion = new TModelAvion;
                $modelAvion = $tableModelAvion->find($avion->id_model)->current();

                $vitesse = $modelAvion->vitesse * 1000;
            }

            //on recupere les infos des aeroports
            $tableAeroport = new TAeroport;
            $aeroportDepart = $tableAeroport->find($destination->tri_aero_dep)->current();
            $aeroportArrive = $tableAeroport->find($destination->tri_aero_arr)->current();

            //nous permettra d'avoir le nom de la ville ou se situeny les aeroports
            $tableVille = new TVille;
            $villeDepart = $tableVille->find($aeroportDepart->id_ville)->current();
            $villeArrive = $tableVille->find($aeroportArrive->id_ville)->current();

            //on recupere aussi le pays
            $tablePays = new TPays;
            $paysDepart = $tablePays->find($villeDepart->id_pays)->current();
            $paysArrive = $tablePays->find($villeArrive->id_pays)->current();

            //on calcul la duree du vol
            $division = $destination->distance / $vitesse;
            $minute = ($division*60)%60;
            $heure = intval($division);
            $duree = $heure.'h'.$minute.'min';

            $tab_catologue[$count]['depart'] = $aeroportDepart->nom_aeroport.' ('.$villeDepart->nom_ville.', '.$paysDepart->nom_pays.')';
            $tab_catologue[$count]['arrive'] = $aeroportArrive->nom_aeroport.' ('.$villeArrive->nom_ville.', '.$paysArrive->nom_pays.')';
            $tab_catologue[$count]['heure_dep'] = $destination->heure_dep;
            $tab_catologue[$count]['duree'] = $duree;
            $tab_catologue[$count]['periodicite'] = $destination->periodicite;

            $count++;
        }
        $this->view->vols = $tab_catologue;
    }

    public function listereservationAction(){

        $time = new time;
        $date = $time->timestamp_now();

        $tableReservation = new TReservation;
        $reservationRequest = $tableReservation->select()->where('heure_dep >= ?',$date)->order('heure_dep');
        $reservations = $tableReservation->fetchAll($reservationRequest);

        $tab_reservation = array();
        $count = 0;

        foreach ($reservations as $reservation) {
            $vitesse = 800 * 1000;
            $tableDestination = new TDestination;
            $destination = $tableDestination->find($reservation->id_destination)->current();

            //on recherche un vol correspondant
            $tableVol = new TVols;
            $volRequest = $tableVol->select()->where('id_destination = ?',$destination->id_destination)
                                             ->where('heure_dep = ?', $reservation->heure_dep);
            $vol = $tableVol->fetchRow($volRequest);

            //si il existe on recupere la vitesse de l'avion
            if(isset($vol) && $vol != ''){
                $id_vol = $vol->id_vols;
                
                $tableAvion = new TAvion;
                $avion = $tableAvion->find($vol->immatriculation)->current();

                $tableModelAvion = new TModelAvion;
                $modelAvion = $tableModelAvion->find($avion->id_model)->current();

                $vitesse = $modelAvion->vitesse * 1000;
            }

            //on recupere les infos des aeroports
            $tableAeroport = new TAeroport;
            $aeroportDepart = $tableAeroport->find($destination->tri_aero_dep)->current();
            $aeroportArrive = $tableAeroport->find($destination->tri_aero_arr)->current();

            //nous permettra d'avoir le nom de la ville ou se situeny les aeroports
            $tableVille = new TVille;
            $villeDepart = $tableVille->find($aeroportDepart->id_ville)->current();
            $villeArrive = $tableVille->find($aeroportArrive->id_ville)->current();

            //on recupere aussi le pays
            $tablePays = new TPays;
            $paysDepart = $tablePays->find($villeDepart->id_pays)->current();
            $paysArrive = $tablePays->find($villeArrive->id_pays)->current();

            //on calcul la duree du vol
            $division = $destination->distance / $vitesse;
            $minute = ($division*60)%60;
            $heure = intval($division);
            $duree = $heure.'h'.$minute.'min';

            $tab_reservation[$count]['numero_vol'] = $destination->numero_vol;
            $tab_reservation[$count]['depart'] = $aeroportDepart->nom_aeroport.' ('.$villeDepart->nom_ville.', '.$paysDepart->nom_pays.')';
            $tab_reservation[$count]['arrive'] = $aeroportArrive->nom_aeroport.' ('.$villeArrive->nom_ville.', '.$paysArrive->nom_pays.')';
            $tab_reservation[$count]['heure_dep'] = $reservation->heure_dep;
            $tab_reservation[$count]['duree'] = $duree;
            $tab_reservation[$count]['nbr_passager'] = $reservation->nbr_passager;
            $tab_reservation[$count]['tarif'] = $reservation->tarif;

            $count++;

        }
        $this->view->reservation = $tab_reservation;
    }

}