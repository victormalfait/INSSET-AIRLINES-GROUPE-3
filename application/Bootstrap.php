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
	// Initialisation et stockage de la base de données
	public function _initDb()
	{
		
		$db = Zend_Db ::factory(Zend_Registry::get('config')->database);
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);
	}
	
}

