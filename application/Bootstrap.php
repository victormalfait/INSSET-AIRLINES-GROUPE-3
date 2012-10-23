<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function run()
	{
		Zend_Registry::set('config', new Zend_Config($this->getOptions()));
		parent::run();
	}
}

