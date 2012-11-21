<?php

class TMaintenance extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'maintenance';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		"Avion" => array(
			"columns" => "immatriculation_appareil",
			"refTableClass" => "TAvion")
		);
}
