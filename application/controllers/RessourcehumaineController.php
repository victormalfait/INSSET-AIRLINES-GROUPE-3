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
		// on charge le model
        $tableUtilisateur = new TUtilisateur;

        // on envoi a la vue tout les pilote
        $this->view->utilisateurs = $tableUtilisateur->fetchAll();
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

    public function piloteAction(){
        $tableUtilisateur = new TUtilisateur;
        $utilisateurRequest = $tableUtilisateur->select()->where('id_service = 9');
        $user = $tableUtilisateur->fetchAll($utilisateurRequest);

        $this->view->utilisateur = $user;
    }

    public function detailspiloteAction(){
        $idPilote = $this->_getParam('idPilote');

        $tablePilote = new TPilote;

        $piloteRequest = $tablePilote->select()->where('id_utilisateur ='.$idPilote);
        $pilote = $tablePilote->fetchRow($piloteRequest);
        $utilisateur = $pilote->findParentRow('TUtilisateur');

        $tableBrevet = new TPiloteBrevet;

        $brevetRequest = $tableBrevet->select()->where('id_pilote ='.$pilote->id_pilote);
        $brevet = $tableBrevet->fetchAll($brevetRequest);
        
        $this->view->pilote = $pilote;
        $this->view->utilisateur = $utilisateur;
        $this->view->brevet = $brevet;
    }

    public function attribuerAction(){
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
                $redirector->gotoUrl('ressourcehumaine/pilote/idPilote/'.$idUtilisateur);            
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

    public function menurhAction(){

    }
}