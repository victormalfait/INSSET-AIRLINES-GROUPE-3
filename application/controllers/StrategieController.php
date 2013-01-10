<?php

class StrategieController extends Zend_Controller_Action
{   
    public function init()
    {
        // mise en place du contexte ajax
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('nouveaupays', 'html')
                    ->addActionContext('nouvelleville', 'html')
                    ->addActionContext('nouvelaeroport', 'html')
                    ->initContext();

        // si on a une requete ajax
        if ($this->_request->isXmlHttpRequest())
        {
            // on desactive le layout
            $this->_helper->layout->disableLayout();
            $this->_helper->removeHelper('viewRenderer');
        }
    }

    //calcul de la distance 3D conçue par partir-en-vtt.com
    function distance($lat1, $lon1, $lat2, $lon2, $alt1, $alt2) 
    {

        $a= 6378137;
        $b = 6356752.314245;
        $f = 1/298.257223563;
        $L = ($lon2-$lon1)*pi()/180;
        $U1 = atan((1-$f) * tan($lat1*pi()/180));
        $U2 = atan((1-$f) * tan($lat2*pi()/180));
        $sinU1 = sin($U1); 
        $cosU1 = cos($U1);
        $sinU2 = sin($U2); 
        $cosU2 = cos($U2);

        $lambda = $L; 
        $iterLimit = 100;
        do {
            $sinLambda = sin($lambda);
            $cosLambda = cos($lambda);
            $sinSigma = sqrt(($cosU2*$sinLambda) * ($cosU2*$sinLambda) + ($cosU1*$sinU2-$sinU1*$cosU2*$cosLambda) * ($cosU1*$sinU2-$sinU1*$cosU2*$cosLambda));
            $cosSigma = $sinU1*$sinU2 + $cosU1*$cosU2*$cosLambda;
            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
            $cosSqAlpha = 1 - $sinAlpha*$sinAlpha;
            $cos2SigmaM = $cosSigma - 2*$sinU1*$sinU2/$cosSqAlpha;
            $C = $f/16*$cosSqAlpha*(4+$f*(4-3*$cosSqAlpha));
            $lambdaP = $lambda;
            $lambda = $L + (1-$C) * $f * $sinAlpha *($sigma + $C*$sinSigma*($cos2SigmaM+$C*$cosSigma*(-1+2*$cos2SigmaM*$cos2SigmaM)));
        } while (abs($lambda-$lambdaP)>1^⁻12 && $iterLimit>0);
        
        $uSq = $cosSqAlpha * ($a*$a - $b*$b) / ($b*$b);
        $A = 1 + $uSq/16384*(4096+$uSq*(-768+$uSq*(320-175*$uSq)));
        $B = $uSq/1024 * (256+$uSq*(-128+$uSq*(74-47*$uSq)));
        $deltaSigma = $B*$sinSigma*($cos2SigmaM+$B/4*($cosSigma*(-1+2*$cos2SigmaM*$cos2SigmaM)-$B/6*$cos2SigmaM*(-3+4*$sinSigma*$sinSigma)*(-3+4*$cos2SigmaM*$cos2SigmaM)));
        $s = $b*$A*($sigma-$deltaSigma);

      return $s;
    }

    public function indexAction()
    {
        //on recupere la date du jour
        $jour  = date("d",time());
        $mois  = date("m",time());
        $annee = date("Y",time());

        //on convertie la date en timestamp
        $date = mktime(0, 0, 0, $mois, $jour, $annee);
        // on charge le model TDestination
        $tableDestination = new TDestination;

        // on recherche toutes les destination ordonner par date de depart
        $destinationRequest = $tableDestination ->select()->where('heure_dep >= ?',$date)->orwhere('periodicite != "Vol unique"');

        // on le resultat de la requete envoi à la vue
        $this->view->destinations = $tableDestination->fetchAll($destinationRequest);

        // Message du detail client lorsqu'aucun client n'a été choisi
        $this->view->messages = $this->_helper->FlashMessenger->getMessages();
    }


    public function detailAction()
    {
         // on recupere l'id 
        $idDestination = $this->_getParam('id');

        // on charge le model
        $tableDestination = new TDestination;

        // on recherche dans la table si l'id existe
        $destination = $tableDestination    ->find($idDestination)
                                            ->current();

        // Si l'id existe
        if ($destination!= null)
        {
            $tableAeroport = new TAeroport;

            $aeroportdep = $tableAeroport   ->find($destination->tri_aero_dep)
                                            ->current();
            $aeroportarr = $tableAeroport   ->find($destination->tri_aero_arr)
                                            ->current();
                                            
            $villedep = $aeroportdep->findParentRow('TVille');
            $villearr = $aeroportarr->findParentRow('TVille');

            // envoi du resultat a la vue
            $this->view->destination = $destination;
            $this->view->aeroportdep = $aeroportdep;
            $this->view->aeroportarr = $aeroportarr;
            $this->view->villedep = $villedep;
            $this->view->villearr = $villearr;
        }
        else { // Sinon (l'id n'existe pas)
            // Message d'erreur si aucun id n'a été trouvé
            $message = "La destination selectionnée n'existe pas";
            $this->_helper->FlashMessenger($message);

            $redirector = $this->_helper->getHelper('Redirector');
            $redirector->gotoUrl("strategie/index");
        }
    }


    public function nouveauAction()
    {
        // on recupere le numero de vol passer en parametre
        $id_destination = $this->_request->getParam('id_destination');

        // on l'envoi a la vue
        $this->view->id_destination = $id_destination;

        // creation de l'objet formulaire
        $formVol = new FNouveauvol;

        //On envoie les valeurs d'ID dans le formulaire
        $formVol->setIdDestination($id_destination);
        $formVol->init();

        $this->view->formNouveauVol = $formVol;

        // traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();
            // var_dump( $formVol->isValid($formData));

            // si le formulaire passe au controle des validateurs
            if ($formVol->isValid($formData)) {

                //on envoi la requete
                $tableDestination = new TDestination;
                $destination = $tableDestination->fetchAll();

                $heure_dep = $_POST['timepickerdeb'.$id_destination];
                $heure_arr = $_POST['timepickerfin'.$id_destination];

                //On explose le format envoyé par les datepicker
                list($heureD, $minuteD) = explode(":", $heure_dep);
                list($heureF, $minuteF) = explode(":", $heure_arr);

                if(isset($_POST['datepickerdeb'.$id_destination]) && $_POST['datepickerdeb'.$id_destination] != ''){
                    $date_debut = $_POST['datepickerdeb'.$id_destination];
                    list($jour, $mois, $annee) = explode("-", $date_debut);
                }else{
                    $jour   = 0;
                    $mois   = 0;
                    $annee  = 0;
                }

                $heure_depart = mktime($heureD, $minuteD, 0, $mois, $jour, $annee);
                $heure_arrive = mktime($heureF, $minuteF, 0, $mois, $jour, $annee);

                if(isset($id_destination) && $id_destination!=""){
                    $row = $tableDestination->find($id_destination)->current();
                    $numeroVol = $row->numero_vol;
                }else{                    
                    $nbr_enr = count($destination);
                    $numeroVol = 'AI'.($nbr_enr+1);
                    $row = $tableDestination->createRow();
                }

                $tableAeroport = new TAeroport;
                $dep_d = $tableAeroport->find($formVol->getValue('aeroportDepart'))->current();
                $arr_d = $tableAeroport->find($formVol->getValue('aeroportArrivee'))->current();

                $distance = $this->distance($dep_d->lattitude,$dep_d->longitude,$arr_d->lattitude,$arr_d->longitude);//

                

                $row->numero_vol        = $numeroVol;
                $row->tri_aero_dep      = $formVol->getValue('aeroportDepart');
                $row->tri_aero_arr      = $formVol->getValue('aeroportArrivee');
                $row->heure_dep         = $heure_depart;
                $row->heure_arr         = $heure_arrive;
                $row->periodicite       = $formVol->getValue('periodicite');
                $row->plannification    = 0;
                $row->distance          = $distance;

                //sauvegarde de la requete
                $result = $row->save();
    
                // RAZ du formulaire
                $formVol->reset();

                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl('strategie/index');
            }
        }
    }

    public function supprimerAction()
    {
        // Récuperation de l'id de la tache
        $numero_vol = $this->_getParam('id');

        // Chargement du model TTache
        $tableDestination = new TDestination;

        //Requetage par clé primaire
        $destination = $tableDestination->find($numero_vol)->current();

        //suppression de la destination
        $destination->delete();

        // on recharge la page
        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoUrl("strategie");
    }

    public function nouveaupaysAction()
    {
        // creation de l'objet formulaire
        $form = new FNouveaupays;

        // on envoi le formulaire a la vue
        $this->view->formNouveauPays = $form;

        //=========== traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
                //on charge le model TDestination
                $tablePays = new TPays;

                // on creer une nouvelle ligne
                $row = $tablePays->createRow();

                // on envoi les données 
                $row->nom_pays  = utf8_decode($_POST['nouveauPays']);

                //sauvegarde de la requete
                $result = $row->save();
        
                echo $row->id_pays;
                //echo $_POST['nouveauPays'];
             
                // RAZ du formulaire
                $form->reset();
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl('strategie/index');
            }
        }
    }

    public function nouvellevilleAction()
    {
        // creation de l'objet formulaire
        $form = new FNouvelleville;

        // on envoi le formulaire a la vue
        $this->view->formNouvelleVille = $form;

        //=========== traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
                //on charge le model TDestination
                $tableVille = new TVille;

                // on creer une nouvelle ligne
                $row = $tableVille->createRow();

                // on envoi les données 
                $row->nom_ville = utf8_decode($_POST['nouveauVille']);
                $row->id_pays   = $_POST['pays_ville'];

                //sauvegarde de la requete
                $result = $row->save();

                echo $row->id_ville;
             
                // RAZ du formulaire
                $form->reset(); 
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl('strategie/index');            
            }
        }
    }

    public function nouvelaeroportAction()
    {
        // creation de l'objet formulaire
        $form = new FNouvelaeroport;

        // on envoi le formulaire a la vue
        $this->view->formNouvelAeroport = $form;

        //=========== traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
                //on charge le model TDestination
                $tableVille = new TAeroport;

                // on creer une nouvelle ligne
                $row = $tableVille->createRow();

                // on envoi les données 
                $row->nom_aeroport      = utf8_decode($_POST['nouvelAeroport']);
                $row->id_ville          = $_POST['ville_aeroport'];
                $row->trigramme         = $_POST['trigramme'];
                $row->longueur_piste    = $_POST['longueurpiste'];
                $row->longitude         = $_POST['longitude'];
                $row->lattitude         = $_POST['lattitude'];

                //sauvegarde de la requete
                $result = $row->save();             
                // RAZ du formulaire
                $form->reset();
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl('strategie/index');
            }
        }
    }
}

