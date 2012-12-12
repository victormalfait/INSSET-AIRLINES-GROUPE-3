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
            $form->setNumeroVol($numero_vol);

            $form->init();

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
                    }else{
                        $row = $destination->createRow();

                    }
                    $row->tri_aero_dep = $form->getValue('aeroportDepart');
                    $row->heure_dep = $form->getValue('departH') . 'h' . $form->getValue('departM');
                    $row->tri_aero_arr = $form->getValue('aeroportArrivee');
                    $row->heure_arr = $form->getValue('arriveeH') . 'h' . $form->getValue('arriveeM');
                    $row->periodicite = $form->getValue('periodicite');
                    $row->date_dep = $form->getValue('dateDep');

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

