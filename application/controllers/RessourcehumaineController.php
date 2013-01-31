<?php

class RessourcehumaineController extends Zend_Controller_Action
{

     public function init()
    {
        // mise en place du contexte ajax
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('creerbrevet', 'html')
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
        // on charge les model
        $tableUtilisateur = new TUtilisateur;
        

        // Récuperation le filtre
        $filtre = $this->_getParam('filtre');
        // si un filtre a été demandé
        if (isset($filtre)) {
            $filtrepc = $filtre . '%';
            // on recupere les clients correspondant au filtre demandé
            $reqUtilisateurs = $tableUtilisateur    ->select()
                                                    ->setIntegrityCheck(false)
                                                    ->from('utilisateur')
                                                    ->where('id_service = ?', $filtre)
                                                    ->orwhere('ville_utilisateur LIKE ?', $filtrepc);
            $utilisateurs = $tableUtilisateur->fetchAll($reqUtilisateurs);
        }
        else { //sinon        
            // on recupere tout les utilisateur 
            $utilisateurs = $tableUtilisateur->fetchAll();
        }

        // on envoi le resultat a la vue
        $this->view->utilisateurs = $utilisateurs;

        // Message du detail client lorsqu'aucun client n'a été choisi
        $this->view->messages = $this->_helper->FlashMessenger->getMessages();
	}

    /** filtrer les utilisateurs */
    public function filtreAction()
    {
        // creation de l'objet formulaire
        $form = new FFiltre;

        // affichage du formulaire
        $this->view->formFiltre = $form;

        // traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
                // On recupere les données du formulaire
                $service = $form->getValue('services');
                $ville   = $form->getValue('ville');
                
                if ($ville != 'first') {
                    $filtre = $ville;
                }

                if ($service != 'first') {
                    $filtre = $service;
                }

                if (($service == 'first') && ($ville == 'first')){
                    // Message d'erreur si aucun id n'a été trouvé
                    $message = "Aucun filtre n'a été sélectionné";
                    $this->_helper->FlashMessenger($message);

                    $redirector = $this->_helper->getHelper('Redirector');
                    $redirector->gotoUrl("ressourcehumaine/index");
                }

                // on redirige la page
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl("ressourcehumaine/index/filtre/" . $filtre);

            }
        }
    }

	public function editerAction()
	{
        // on recupere le numero de vol passer en parametre
        $idUtilisateur = $this->_request->getParam('idUtilisateur');

        // on l'envoi a la vue
        $this->view->idUtilisateur = $idUtilisateur;

        // creation de l'objet formulaire
        $form = new FNouvelUtilisateur;

        //On envoie les valeurs d'ID dans le formulaire
        $form->setIdUtilisateur($idUtilisateur);
        $form->init();

        $this->view->formNouvelUtilisateur = $form;

        // traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();
            // var_dump( $form->isValid($formData));

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {

                //on envoi la requete
                $utilisateur = new TUtilisateur;

                if(isset($idUtilisateur) && $idUtilisateur!=""){
                    $row = $utilisateur->find($idUtilisateur)->current();
                }else{
                    $row = $utilisateur->createRow();
                    
                }

                $row->login_utilisateur = $form->getValue('login');
                $row->nom_utilisateur = $form->getValue('nom');
                $row->prenom_utilisateur = $form->getValue('prenom');
                $row->adresse_utilisateur = $form->getValue('adresse');
                $row->cp_utilisateur = $form->getValue('codePostal');
                $row->ville_utilisateur = $form->getValue('ville');
                $row->password_utilisateur = md5($form->getValue('password'));
                $row->id_service = $form->getValue('service');
                
                //sauvegarde de la requete
                $result = $row->save();

                if(!isset($idUtilisateur) && $idUtilisateur==""){
                    if($form->getValue('service') == '9'){
                        $pilote = new TPilote;
                        $rowPilote = $pilote->createRow();
                        $rowPilote->id_utilisateur = $row->id_utilisateur;
                        $rowPilote->save();
                    }
                }

        
                // RAZ du formulaire
                $form->reset();

                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl('ressourcehumaine/index');
            }
        }
	}

	public function supprimerAction()
	{
		// Récuperation de l'id de la tache
        $idUtilisateur = $this->_getParam('id');

        // Chargement du model TTache
        $tableUtilisateur = new TUtilisateur;

        //Requetage par clé primaire
        $user = $tableUtilisateur->find($idUtilisateur)->current();

        //suppression de la destination
        $user->delete();

        // on recharge la page
        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoUrl("ressourcehumaine");
	}

    public function detailAction() {
        // on recupere l'id 
        $idUtilisateur = $this->_getParam('id');

        // on charge le model
        $tableUtilisateur = new TUtilisateur;

        // requete par clé primaire
        $utilisateur = $tableUtilisateur    ->find($idUtilisateur)
                                            ->current();

        // Si l'id existe
        if ($utilisateur!= null)
        {
            // Recherche des infos sur la service de l'utilisateur
            $service = $utilisateur->findParentRow('TService');

            // envoi du resultat a la vue
            $this->view->utilisateur = $utilisateur;
            $this->view->service = $service;

            if ($service->id_service == 9) {
                // on charge les models
                $tablePilote        = new TPilote;
                $tablePiloteBrevet  = new TPiloteBrevet;
                $tableTempsTravail  = new TTempsTravail;

                // requete par clé primaire
                $reqPilote = $tablePilote   ->select()
                                            ->from($tablePilote)
                                            ->where('id_utilisateur = ?', $idUtilisateur);

                $pilote = $tablePilote->fetchRow($reqPilote);

                $reqPiloteBrevet = $tablePiloteBrevet   ->select()
                                                        ->from($tablePiloteBrevet)
                                                        ->where('id_pilote = ?', $pilote->id_pilote)
                                                        ->order('date_obtention');

                $piloteBrevet = $tablePiloteBrevet ->fetchAll($reqPiloteBrevet);

                $selectTempsTravail = $tableTempsTravail->select()
                                                        ->where('id_pilote = ?', $pilote->id_pilote);
                $TempsTravails = $tableTempsTravail->fetchAll($selectTempsTravail);

               // Zend_Debug::dump($TempsTravails);
                $TempsUser_Total = 0;
                $TempsUser_Semaine[ltrim(date('W', time()), "0")] = 0;
                $variable[0] = 0;

                foreach ($TempsTravails as $TempsTravail){

                    //Temps Total
                    $TempsUser_Total = $TempsUser_Total + $TempsTravail['temps'];
                    
                    //Temps dans la semaine
                    (int)$numeor_semaine = ltrim(date('W',$TempsTravail['date']) , "0");

                    if (!isset($TempsUser_Semaine[$numeor_semaine])){
                       $TempsUser_Semaine[$numeor_semaine] = 0; 
                    }
                    $TempsUser_Semaine[$numeor_semaine] = $TempsTravail['temps'] + $TempsUser_Semaine[$numeor_semaine];
                   // $valeur_avant[$numeor_semaine] = $TempsUser_Semaine[$numeor_semaine] =  $TempsTravail['temps'] ;
                    
                }
               
                
                // envoi du resultat a la vue
                $this->view->pilote              = $pilote;
                $this->view->piloteBrevet        = $piloteBrevet;
                $this->view->TempsUser_Total     = $TempsUser_Total;
                $this->view->TempsUser_Semaine   = $TempsUser_Semaine;
             
    
            }
        }
        else { // Sinon (l'id n'existe pas)
            // Message d'erreur si aucun id n'a été trouvé
            $message = "L'employé selectionné n'existe pas";
            $this->_helper->FlashMessenger($message);

            $redirector = $this->_helper->getHelper('Redirector');
            $redirector->gotoUrl("ressourcehumaine/index");
        }
    }

    public function attribuerAction() {
        $form = new FAttribuer;
        $this->view->formAttribuer = $form;
        $idPilote = $this->_getParam('idPilote');
        $idUtilisateur = $this->_getParam('idUtilisateur');

        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
                //on charge le model TDestination
                $tablePiloteBrevet = new TPiloteBrevet;

                // on creer une nouvelle ligne
                $row = $tablePiloteBrevet->createRow();

                list($jourD, $moisD, $anneeD) = explode("-", $_POST['datepicker']);
                $date_obtention = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);

                // on envoi les données 
                $row->id_pilote = $idPilote;
                $row->id_brevet = $_POST['brevet'];
                $row->date_obtention = $date_obtention;

                //sauvegarde de la requete
                $row->save();
             
                // RAZ du formulaire
                $form->reset(); 
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl('ressourcehumaine/detail/id/'.$idUtilisateur);            
            }
        }
    }

    public function creerbrevetAction(){
        $form = new FCreerBrevet;
        $this->view->formCreer = $form;


        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
                //on charge le model TDestination
                $tableBrevet = new TBrevet;

                // on creer une nouvelle ligne
                $row = $tableBrevet->createRow();

                // on envoi les données 
                $row->nom_brevet = utf8_decode($_POST['nomBrevet']);
                $row->temps_validite = $_POST['duree'];

                //sauvegarde de la requete
                $row->save();

                echo $row->id_brevet;
             
                // RAZ du formulaire
                $form->reset();          
            }
        }
    }

    public function supprimerbrevetAction(){
        $brevet = $this->_getParam('idBrevet');
        $utilisateur = $this->_getParam('idUtilisateur');
        
        // Chargement du model TTache
        $tablePiloteBrevet = new TPiloteBrevet;

        //Requetage par clé primaire
        $pilotebrevet = $tablePiloteBrevet->find($brevet)->current();

        //suppression de la destination
        $pilotebrevet->delete();

        // on recharge la page
        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoUrl("ressourcehumaine/detailspilote/idPilote/".$utilisateur);
    }

    public function menurhAction(){
        // on envoi le nom de afficher dans la vue
        $ctrl=Zend_Controller_Front::getInstance();
        $this->view->action = $ctrl->getRequest()->getActionName();
        
    }
}