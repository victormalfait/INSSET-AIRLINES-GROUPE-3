<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function run()
	{
		parent::run();
	}

	// Initialisation et stockage de la configuration
	protected function _initConfig()
	{
		Zend_Registry::set('config', new Zend_Config($this->getOptions()));
	}

	/** initialisation de l'API personnalisée "INSSETAIRLINE" */
	protected function _initLoaderINSSETAIRLINE()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('INSSETAIRLINE_');
		$autoloader->setFallbackAutoloader(true);
	}

	/** initialisation des sessions et de l'espace de nom inssetairline pour les sessions */
	protected function _initSession()
	{
		$sessionConfig = Zend_Registry::get('config')->session;
    	Zend_Session::setOptions($sessionConfig->toArray());
		$session = new Zend_Session_Namespace('inssetairline', true);
		return $session;
	}

	// Initialisation et stockage de la base de données
	protected function _initDb()
	{
		$db = Zend_Db::factory(Zend_Registry::get('config')->database);
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);
	}
	
	/** initialiser Zend_View */
	protected function _initView()
	{
		// Chargement de Zend_View
	    $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();

	    //... code de paramétrage de votre vue : titre, doctype ...
	    $view->doctype('HTML5');

	    // meta
	    $view->headMeta()	->setHttpEquiv('Content-Type', 'text/html;X charset=utf-8')
		                    ->setHttpEquiv('Content-Style-Type', 'text/css')
		                    ->setHttpEquiv('lang', 'fr')
		                    ->setHttpEquiv('Refresh', '2')
		                    ->setName('language', 'fr');

	    // css
		$view->headLink()	->appendStylesheet('/css/resetcss.css')
                            ->appendStylesheet('/css/smoothness/jquery-ui-1.9.2.custom.css')
                            ->appendStylesheet('/css/style.css')
                            ->appendStylesheet('/css/rouleau.css')
                            ->appendStylesheet('/css/commercial.css')
                            ->appendStylesheet('/css/overlay.css');
	    // javascript
		$view->headScript()	->appendFile('/js/Jquery/jquery-1.8.3.js')					/////////////
							->appendFile('/js/Jquery/jquery-ui-1.9.2.custom.min.js')	//	JQuery
							->appendFile('/js/Jquery/jquery.easing.1.3.js')				/////////////
		                    ->appendFile('/js/custom.js')                               /////////////////
		                    ->appendFile('/js/rouleau.js')                              // 
		                    ->appendFile('/js/overlay.js')                              // 
		                    ->appendFile('/js/ajax.js')                                 // fichier perso
							->appendFile('/js/tutorial.js')                             //
		                    ->appendFile('/js/ajaxRessourcesHumaines.js')
		                    ->appendFile('/js/ajaxCommercial.js')
		                    ->appendFile('/js/ajaxPlanning.js');				/////////////////
	}

	/** Initialisation des Acls */
	protected function _initAcl()
	{

		// chargement de Zend_Acl
		$acl = new Zend_Acl;

		// Mise en place des rôles
		$acl->addRole('visiteur')
			->addRole('1')  // Administration
			->addRole('2')  // Direction Strategie
			->addRole('3')  // Ressources humaines
			->addRole('4')  // Maintenance
			->addRole('5')  // Planning
			->addRole('6')  // Commercial
			->addRole('7')  // Exploitation
			->addRole('8'); // Logistique Commercial

		// Mise en place des resources
		$acl->addResource('commercial')
			->addResource('connexion')
			->addResource('exploitation')
			->addResource('index')
			->addResource('maintenance')
			->addResource('menu')
			->addResource('planning')
			->addResource('ressourcehumaine')
			->addResource('logistique')
			->addResource('strategie');

		// Mise en place des regles
		// Visiteur
			$acl->deny('visiteur', null)
				->allow('visiteur', 'connexion', 'index')
				->allow('visiteur', 'index', 'index')
				->allow('visiteur', 'menu', 'menuadmin')
				->allow('visiteur', 'menu', 'menuvisiteur')
				->allow('visiteur', 'commercial', 'index')
				->allow('visiteur', 'commercial', 'catalogue');

		// Administration
			$acl->allow('1', null);

		// Direction Strategie
			$acl->deny('2', null)
				->allow('2', 'menu', 'menuvisiteur')
				->allow('2', 'strategie');

		// Ressources Humaines
			$acl->deny('3', null)
				->allow('3', 'menu', 'menuvisiteur')
				->allow('3', 'ressourcehumaine');

		// Maintenance
			$acl->deny('4', null)
				->allow('4', 'menu', 'menuvisiteur')
				->allow('4', 'maintenance');

		// Planning
			$acl->deny('5', null)
				->allow('5', 'menu', 'menuvisiteur')
				->allow('5', 'planning');

		// Commercial
			$acl->deny('6', null)
				->allow('6', 'menu', 'menuvisiteur')
				->allow('6', 'commercial');

		// Exploitation
			$acl->deny('7', null)
				->allow('7', 'menu', 'menuvisiteur')
				->allow('7', 'exploitation');

		// Logistique Commercial
			$acl->deny('8', null)
				->allow('8', 'menu', 'menuvisiteur')
				->allow('8', 'logistique');


		// on enregistre notre acl
		Zend_Registry::set('acl', $acl);

		// on creer un espace de session pour les roles 
		$session = new Zend_Session_Namespace('role');
		
		// si l'utilisateur est connecté ...
		if (Zend_Auth::getInstance ()->hasIdentity ()) {

			// ... on recupere l'identifiant de son service
			$storage = Zend_Auth::getInstance()->getIdentity()->id_service;

			// on lui attribue le role de membre
		 	$session->role = $storage;
		}
		else { // sinon (pas connecté)
			// on lui attribut le role de visiteur
			$session->role = "visiteur";
		}
	}

	/** initialisation de la navigation */
	protected function _initNavigation()    
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        // $view->navigation(new Zend_Navigation($config))->setAcl($this->_acl)->setRole($this->_role);
	 
		$navigation = new Zend_Navigation($config);
		$view->navigation($navigation);
    }
}