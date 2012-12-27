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

    public function indexAction()
    {
        // on charge le model TDestination
        $tableDestination = new TDestination;

        // on recherche toutes les destination ordonner par date de depart
        $destinationRequest = $tableDestination ->select()
                                                ->from($tableDestination)
                                                ->order('date_dep');

        // on le resultat de la requete envoi à la vue
        $this->view->destinations = $tableDestination->fetchAll($destinationRequest);
    }

    public function nouveauAction()
    {
        // on recupere le numero de vol passer en parametre
        $numero_vol = $this->_request->getParam('numero_vol');

        // on l'envoi a la vue
        $this->view->numero_vol = $numero_vol;

        // creation de l'objet formulaire
        $formVol = new FNouveauvol;

        //On envoie les valeurs d'ID dans le formulaire
        $formVol->setNumeroVol($numero_vol);
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
                $destination = new TDestination;

                if(isset($numero_vol) && $numero_vol!=""){
                    $row = $destination->find($numero_vol)->current();
                }else{
                    $row = $destination->createRow();
                    $nbr_enr = count($destination->fetchAll());
                    $row->numero_vol = 'AI'.($nbr_enr+1);
                }

                $heure_dep = $_POST['timepickerdeb'.$numero_vol];//$formVol->getValue('timepickerdeb'.$numero_vol);
                $heure_arr = $_POST['timepickerfin'.$numero_vol];//$formVol->getValue('timepickerfin'.$numero_vol);

                //On explose le format envoyé par les datepicker
                list($heureD, $minuteD) = explode(":", $heure_dep);
                list($heureF, $minuteF) = explode(":", $heure_arr);

                if($formVol->getValue('periodicite')=='Vol unique'){
                    $date_debut = $_POST['datepickerdeb'.$numero_vol];//$formVol->getValue('datepickerdeb'.$numero_vol);
                    $date_fin = $_POST['datepickerfin'.$numero_vol];//$formVol->getValue('datepickerfin'.$numero_vol);
                    list($jourD, $moisD, $anneeD) = explode("-", $date_debut);
                    list($jourF, $moisF, $anneeF) = explode("-", $date_fin);     
                }else{
                    $jourD = 0;$jourF = 0;
                    $moisD = 0;$moisF = 0;
                    $anneeD = 0;$anneeF = 0;
                }
                $date_depart = mktime($heureD, $minuteD, 0,  $moisD, $jourD, $anneeD);
                $date_fin = mktime($heureF, $minuteF, 0, $moisF, $jourF, $anneeF);

                $row->tri_aero_dep = $formVol->getValue('aeroportDepart');
                $row->tri_aero_arr = $formVol->getValue('aeroportArrivee');
                $row->heure_dep = $date_depart;
                $row->heure_arr = $date_fin;
                $row->date_dep = $date_depart;
                $row->date_arr = $date_fin;
                $row->periodicite = $formVol->getValue('periodicite');
                
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
        $destination = $tableDestination    ->find($numero_vol)
                                            ->current();

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
                $row->nom  = $_POST['nouveauPays'];

                //sauvegarde de la requete
                $result = $row->save();
        
                echo $row->id;
                //echo $_POST['nouveauPays'];
             
                // RAZ du formulaire
                $form->reset();
                exit;
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
                $row->nom       = $_POST['nouveauVille'];
                $row->id_pays   = $_POST['pays_ville'];

                //sauvegarde de la requete
                $result = $row->save();
             
                // RAZ du formulaire
                $form->reset();
        
                echo $row->id;
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
                $row->nom               = $_POST['nouvelAeroport'];
                $row->id_ville          = $_POST['ville_aeroport'];
                $row->trigramme         = $_POST['trigramme'];
                $row->longueur_piste    = $_POST['longueurpiste'];

                //sauvegarde de la requete
                $result = $row->save();
        
                echo $row->id;
             
                // RAZ du formulaire
                $form->reset();
            }
        }
    }
}

