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
	    $view = new Zend_View();

	    //... code de paramétrage de votre vue : titre, doctype ...
	    $view->doctype('HTML5');
	}
}

