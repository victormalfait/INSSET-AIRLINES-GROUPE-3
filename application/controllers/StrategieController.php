<?php

class StrategieController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $tableDestination = new TDestination;
        $destinationRequest = $tableDestination->select()->from($tableDestination);
        $destinationRequest->setIntegrityCheck(false);
        $destinationRequest->order('date_dep');
        $destination = $tableDestination->fetchAll($destinationRequest);
        $this->view->destination = $destination;

    }

    public function nouveauAction()
    {
        if(isset($_POST['pays'])){
            $tablePays = new TPays;
            $row = $tablePays->createRow();
            $row->nom = $_POST['pays'];
            $row->save();
            echo $row->id;exit;

        }elseif (isset($_POST['ville'])) {
            $tableVille = new TVille;
            $row = $tableVille->createRow();
            $row->nom = $_POST['ville'];
            $row->id_pays = $_POST['pays_ville'];
            $row->save();
            echo $row->id;exit;
        }
        elseif (isset($_POST['aeroport'])) {
            $tableAeroport = new TAeroport;
            $row = $tableAeroport->createRow();
            $row->nom = $_POST['aeroport'];
            $row->id_ville = $_POST['ville_aeroport'];
            $row->trigramme = $_POST['trigramme'];
            $row->save();
        }else{
            // creation de l'objet formulaire
            $form = new FNouveauvol;
            $numero_vol = $this->_getparam('numero_vol');
            $this->view->numero_vol = $numero_vol;

            // affichage du formulaire
            $this->view->formNouveauVol = $form;

            //On envoie les valeurs d'ID dans le formulaire
            $form->setnumeroVol($numero_vol);

            // traitement du formulaire
            // si le formulaire a été soumis
            if ($this->_request->isPost()) {
                // on recupere les éléments
                $formData = $this->_request->getPost();

                // si le formulaire passe au controle des validateurs
                if ($form->isValid($formData)) {

                    //on envoi la requete
                    $destination = new TDestination;

                    if(isset($numero_vol) && $numero_vol!=""){
                        $row = $destination->find($numero_vol)->current();  
                        $row->numero_vol = $form->getValue('numeroVol');
                    }else{
                        $row = $destination->createRow();
                        $nbr_enr = count($destination->fetchAll());
                        $row->numero_vol = 'AI'.$nbr_enr;
                    }

                    $heure_dep = $_POST['timepickerdeb'.$numero_vol];
                    $heure_arr = $_POST['timepickerfin'.$numero_vol];

                    //On explose le format envoyé par les datepicker
                    list($heureD, $minuteD) = explode(":", $heure_dep);
                    list($heureF, $minuteF) = explode(":", $heure_arr);

                    if($form->getValue('periodicite')=='Vol unique'){
                        $date_debut = $_POST['datepickerdeb'.$numero_vol];
                        $date_fin = $_POST['datepickerfin'.$numero_vol];
                        list($jourD, $moisD, $anneeD) = explode("-", $date_debut);
                        list($jourF, $moisF, $anneeF) = explode("-", $date_fin);           
                    }else{
                        $jourD = 0;$jourF = 0;
                        $moisD = 0;$moisF = 0;
                        $anneeD = 0;$anneeF = 0;
                    }
                    $date_depart = mktime($heureD, $minuteD, 0,  $moisD, $jourD, $anneeD);
                    $date_fin = mktime($heureF, $minuteF, 0, $moisF, $jourF, $anneeF); 

                    $row->tri_aero_dep = $form->getValue('aeroportDepart');
                    $row->tri_aero_arr = $form->getValue('aeroportArrivee');
                    $row->heure_dep = $date_depart;
                    $row->heure_arr = $date_arrivee;
                    $row->date_dep = $date_depart;
                    $row->date_arr = $date_arrivee;
                    $row->periodicite = $form->getValue('periodicite');
                    
                    //sauvegarde de la requete
                    $result = $row->save();
            
                    // RAZ du formulaire
                    $form->reset();
                }
            }
        }
    }

    public function modifierAction()
    {

    }


    public function supprimerAction()
    {
        
    }
}

