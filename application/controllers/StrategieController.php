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


                $date_debut = $_POST['datepickerdeb'.$id_destination];
                $date_fin = $_POST['datepickerfin'.$id_destination];
                list($jourD, $moisD, $anneeD) = explode("-", $date_debut);
                list($jourF, $moisF, $anneeF) = explode("-", $date_fin); 

                $date_depart = mktime($heureD, $minuteD, 0,  $moisD, $jourD, $anneeD);
                $date_fin = mktime($heureF, $minuteF, 0, $moisF, $jourF, $anneeF);

                if(isset($id_destination) && $id_destination!=""){
                    $row = $tableDestination->find($id_destination)->current();
                    $numeroVol = $row->numero_vol;
                }else{
                    
                    $nbr_enr = count($destination);
                    $numeroVol = 'AI'.($nbr_enr+1);
                }

                   
                $tri_aero_dep = $formVol->getValue('aeroportDepart');
                $tri_aero_arr = $formVol->getValue('aeroportArrivee');
                $periodicite = $formVol->getValue('periodicite');

                if($formVol->getValue('periodicite')=='Vol unique'){
                    $row = $tableDestination->createRow();

                    $row->numero_vol = $numeroVol;
                    $row->tri_aero_dep = $tri_aero_dep;
                    $row->tri_aero_arr = $tri_aero_arr;
                    $row->date_dep = $date_depart;
                    $row->date_arr = $date_fin;
                    $row->periodicite = $periodicite;
                    $row->plannification = 0;

                    //sauvegarde de la requete
                    $result = $row->save();

                }else{

                     for($i=0;$i<10;$i++){
                        $date_depart += 7*24*60*60;
                        $date_fin += 7*24*60*60;                      

                        $data = array(
                            'numero_vol' => $numeroVol,
                        'tri_aero_dep' => $tri_aero_dep,
                        'tri_aero_arr' => $tri_aero_arr,
                        'date_dep' => $date_depart,
                        'date_arr' => $date_fin,
                        'periodicite' => $periodicite,
                        'plannification' => 0
                        );

                        $row = $tableDestination->createRow($data);
                        //sauvegarde de la requete
                        $row->save();
                    }
                }
        
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

