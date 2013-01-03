<?php

class RessourcehumaineController extends Zend_Controller_Action
{

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
        $idPilote = $this->_getParam('idPilote');

        $tablePilote = new TPilote;

        $piloteRequest = $tablePilote->select()->where('id_utilisateur ='.$idPilote);
        $pilote = $tablePilote->fetchRow($piloteRequest);
        $utilisateur = $pilote->findParentRow('TUtilisateur');

        $tableBrevet = new TPiloteBrevet;

        $brevetRequest = $tableBrevet->select()->where('id_pilote ='.$idPilote);
        $brevet = $tableBrevet->fetchAll($brevetRequest);
        
        $this->view->pilote = $pilote;
        $this->view->utilisateur = $utilisateur;
        $this->view->brevet = $brevet;
    }
}