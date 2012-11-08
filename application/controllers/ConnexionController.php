<?php

class ConnexionController extends Zend_Controller_Action
{

    public function preDispatch() {
        // si l'utilisateur est connecté
        if (Zend_Auth::getInstance ()->hasIdentity ()) {
            // si l'action effectué est different de deconnexion ...
            if ('deconnexion' != $this->getRequest ()->getActionName ()) {
                // ... on le redirige vers la page d'accueil du site
                $this->_helper->redirector ( 'index', 'index' );
            }
        }
        else { // sinon (l'utilisateur n'est pas connecter)
            // si il veut se deconnecter ...
            if ('deconnexion' == $this->getRequest ()->getActionName ()) {
                // on le redirige vers le formulaire de deconnexion
                $this->_helper->redirector ( 'index' );
            }
        }
    }

	public function indexAction()
	{
		
        // creation de l'objet formulaire
        $form = new FConnexion;

        // affichage du formulaire
        $this->view->formConnexion = $form;

        // traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {
                // on recupere les données
                $email = $form->getValue ( 'email' );
                $password = $form->getValue ( 'password' );

                // creation d'un Zend_Auth
                $authAdapter = new Zend_Auth_Adapter_DbTable ( Zend_Db_Table::getDefaultAdapter () );
                // en lui donnant quelque information sur la base de donnée
                $authAdapter->setTableName('utilisateur')  // nom de la table
                            ->setIdentityColumn('email')   // colonne contenant de l'identifiant
                            ->setCredentialColumn('password')  // colonne contenant le mot de passe
                            ->setCredentialTreatment('MD5(?)')  // Type de hashage
                            ->setIdentity($email)   // valeur de l'identifiant
                            ->setCredential($password);  // valeur du mot de passe

                // identification de l'utilisateur
                $authAuthenticate = $authAdapter->authenticate ();

                //si l'authentification a reussi
                if ($authAuthenticate->isValid()) {
                    //on recupere l'espace de stockage de l'application
                    $storage = Zend_Auth::getInstance ()->getStorage ();

                    // on y ajoute les infos de l'utilisateur
                    $storage->write ( $authAdapter->getResultRowObject ( null, 'password' ) );

                    // on redirige l'utilisateur sur la page principal de l'application
                    $this->_helper->redirector ( 'index', 'index' );
                }
                else{ // sinon l'authentification a echoué

                    $form->addError ( 'l\'identifiant ou le mot de passe est incorrect' );
                }
            }
        } 
        $this->render('index');
	}

	public function deconnexionAction()
	{
        // on efface l'identification de l'utilisateur
		Zend_Auth::getInstance ()->clearIdentity ();
        // on redirige l'utilisateur sur la page principale
        $this->_helper->redirector ( 'index', 'index' );
	}

	public function inscriptionAction()
	{
		// creation de l'objet formulaire
        $form = new FInscription;

        // affichage du formulaire
        $this->view->formInscription = $form;

        // traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
        	// on recupere les éléments
            $formData = $this->_request->getPost();

            // si le formulaire passe au controle des validateurs
            if ($form->isValid($formData)) {

            	//on envoi la requete
                $users = new TUtilisateur;
                $row = $users->createRow();
                $row->nom = $form->getValue('nom');
                $row->prenom = $form->getValue('prenom');
                $row->email = $form->getValue('email');
                $row->password = md5($form->getValue('password'));
                $result = $row->save();
		
				// RAZ du formulaire
                $form->reset();
            }
        }
	}
}