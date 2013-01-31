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
		                    // ->setHttpEquiv('Refresh', '2')
		                    ->setName('language', 'fr');

	    // css
		$view->headLink()	->appendStylesheet('/css/resetcss.css')
                            ->appendStylesheet('/css/smoothness/jquery-ui-1.9.2.custom.css')
                            ->appendStylesheet('/css/style.css')
                            ->appendStylesheet('/css/rouleau.css')
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


    /** liaison entre Zend_Acl et Zend_Navigation */
    private $_acl = null;
    private $_auth = null;
    private $_role = null;
    
    protected function _initAutoload()
    {
        
        new Zend_Application_Module_Autoloader(array(
                'namespace' => '',
                'basePath'  => APPLICATION_PATH,
        ));
        
        $this->_acl = new acl(APPLICATION_PATH . '/configs/acl.ini');
        $this->_auth = Zend_Auth::getInstance();
        
        // si l'utilisateur est connecté ...
        if ($this->_auth->hasIdentity()) {
			// ... on recupere l'identifiant de son service
			$this->_role = $this->_auth->getIdentity()->id_service;
        	// $this->_role = $this->_auth->getStorage()->read()->role;
        }
        else { // sinon (l'utilisateur n'est pas connecté)
        	// c'est un visiteur
        	$this->_role = 'visiteur';
        }
    }
    
    /** initialisation des acls */
    protected function _initAcl()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new auth($this->_acl, $this->_auth));
    }
    
    /** initialisation de la navigation */
    protected function _initNavigation()    
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        $view->navigation(new Zend_Navigation($config))->setAcl($this->_acl)->setRole($this->_role);
    }
}