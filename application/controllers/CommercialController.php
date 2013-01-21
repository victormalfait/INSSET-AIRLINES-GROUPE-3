<?php

class CommercialController extends Zend_Controller_Action
{

    //fonction pour calculer le nombre de jour d'écart entre le jour d'aujourd'hui et le jour du vol
     function find_date_vol($timestamp, $jour) {
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
                //on recupere la date de départ
                $date_debut = $formCommercial->getValue('datepickerdeb');

                //on convertit au format timestamp
                list($jourD, $moisD, $anneeD) = explode("-", $date_debut);          
                $date_depart = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);

                $mois = date("m", time());
                $jour = date("d", time());
                $an = date("Y", time());

                //on cree un timestamp de la date d'aujourd'hui
                $date_jour = mktime(0,0,0, $mois, $jour, $an);

                //on initialise une variable pour avoir la date 3 semaines avant
                $date_depart_avant = $date_depart - 3*7*24*60*60;

                //on verifie que la date ne soit pas inferieure a la date du jour
                if($date_depart_apres < $date_jour){
                    $date_depart_avant = $date_jour
                }

                //on initialise une variable à 3 semaines apres la date demandée
                $date_depart_apres = $date_depart + 3*7*24*60*60;

                //on fait une requete pour trouver les aeroports possibles de départ et d'arrivé
                $tableAeroport = new TAeroport;

                $aeroportDepartRequest = $tableAeroport->select()->where('id_ville = ?', $formCommercial->getValue('aeroportDepart'));
                $aeroportArrivetRequest = $tableAeroport->select()->where('id_ville = ?', $formCommercial->getValue('aeroportArrive'));

                $aeroportDeparts = $tableAeroport->fetchAll($aeroportDepartRequest);
                $aeroportArrives = $tableAeroport->fetchAll($aeroportArrivetRequest);

                //on initialise un tableau pour enregistrer les informations sur les vols
                $table_content_aller = array();
                $count = 0;

                foreach ($aeroportDeparts as $aeroportDepart) {
                    foreach ($aeroportArrives as $aeroportArrive) {

                        //on cherche les destinations avec les aeroports de départ et d'arrivé 
                        $tableDestination = new TDestination;
                        $destinationRequest = $tableDestination->select()->where('tri_aero_dep = ?', $aeroportDepart->trigramme)->where('tri_aero_arr = ?', $aeroportArrive->trigramme);

                        $destinations = $tableDestination->fetchAll($destinationRequest);

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
                                    $count++;
                                }
                            //si le vol est periodique
                            }else{
                                //on boucle pour enregistrer les infos des vols périodiques
                                for ($i=0; $i < 7; $i++) { 
                                    $date = $date_depart + 7*24*60*60*$i;

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

                                    $table_content_aller[$count]['numero_vol']    = $destination->numero_vol;
                                    $table_content_aller[$count]['heure_dep']     = $date_vol_dep;
                                    $table_content_aller[$count]['heure_arr']     = $date_vol_arr;
                                    $table_content_aller[$count]['depart']        = $aeroportDepart->nom_aeroport;
                                    $table_content_aller[$count]['arrive']        = $aeroportArrive->nom_aeroport;
                                    $count++;

                                }
                            }
                        }
                    }
                }                
                //on envoie le tableau à la vue
                $this->view->tabVol = $table_content_aller;
                                                                          
                // RAZ du formulaire
                $formCommercial->reset();
            }
        }
        
    }

    public function catalogueAction ()
    {
    	
    }

}