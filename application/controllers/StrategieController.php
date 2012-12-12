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
        $this->view->destination = $tableDestination->fetchAll($destinationRequest);
    }

    public function nouveauAction()
    {

        // on recupere le numero de vol
        $numero_vol = $this->_getparam('numero_vol');
    
        // creation de l'objet formulaire
        $form = new FNouveauvol;

        // On envoie le numero de vol au le formulaire
        $form->setNumeroVol($numero_vol);
        $form->init();

        // on envoi le formulaire a la vue
        $this->view->formNouveauVol = $form;

        //=========== traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
                //on charge le model TDestination
                $tableDestination = new TDestination;

                // si on a un numero de vol
                if(isset($numero_vol) && $numero_vol!=""){
                    // on recupere la ligne a mettre a jour
                    $row = $tableDestination    ->find($numero_vol)
                                                ->current();  
=======
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
>>>>>>> e3c6af58b2e9d67f3f9749155aad9ccd6cf7b795
                }
                else{ // sinon (pas de numero de vol)
                    // on creer une nouvelle ligne
                    $row = $tableDestination->createRow();
                }

                // on envoi les données 
                $row->tri_aero_dep  = $form->getValue('aeroportDepart');
                $row->heure_dep     = $form->getValue('departH') . 'h' . $form->getValue('departM');
                $row->tri_aero_arr  = $form->getValue('aeroportArrivee');
                $row->heure_arr     = $form->getValue('arriveeH') . 'h' . $form->getValue('arriveeM');
                $row->periodicite   = $form->getValue('periodicite');
                $row->date_dep      = $form->getValue('dateDep');

                //sauvegarde de la requete
                $result = $row->save();
        
                // RAZ du formulaire
                $form->reset();
            }
        }
    }


    public function supprimerAction()
    {
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
             
                // RAZ du formulaire
                $form->reset();
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

