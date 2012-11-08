<?php

class Festival_Debug extends Zend_Debug
{
	public static function dump($var) 
	{
		$logger = new Zend_Log();
		$writer2 = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../var/debugs/debug');
		$writer3 = new Zend_Log_Writer_Firebug();

		$logger->setEventItem( 'IP_cli', $_SERVER['REMOTE_ADDR']);
		$logger->setEventItem( 'date', date("m.d.y - H:i:s"));

		$formatter = new Zend_Log_Formatter_Simple('%priorityName%(%priority%)- %date% - %IP_cli% : %message%'. PHP_EOL);
		$writer2->setFormatter($formatter);
		$writer3->setFormatter($formatter);

		$logger->addWriter($writer2);
		$logger->addWriter($writer3);

		$logger->log("Mon Debug  : ".parent::dump($var), 7);


		//$logger->info("DEBUG  : ".parent::dump($var) );

		;
	}
}