<?php

class Festival_Controller extends Zend_Controller_Action 
{
	public function preDispatch() 
	{
		$logger = new Zend_Log();
		$writer2 = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../var/logs/monlog');

		$logger->setEventItem( 'IP_cli', $_SERVER['REMOTE_ADDR']);
		$logger->setEventItem( 'date', date("m.d.y - H:i:s"));

		$formatter = new Zend_Log_Formatter_Simple('%priorityName%(%priority%)- %date% - %IP_cli%'. PHP_EOL);
		$writer2->setFormatter($formatter);

		$logger->addWriter($writer2);

		$logger->info("INFO  : ");
	}
}