<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function run()
	{
		parent::run();
	}

	// Initialisation et stockage de la configuration
	public function _initConfig()
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

	/** initialisation des sessions et de l'espace de nom festival pour les sessions */
	protected function _initSession()
	{
		$session = new Zend_Session_Namespace('crm', true);
		return $session;
	}

	// Initialisation et stockage de la base de données
	public function _initDb()
	{
		$db = Zend_Db ::factory(Zend_Registry::get('config')->database);
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);
	}
	
}

