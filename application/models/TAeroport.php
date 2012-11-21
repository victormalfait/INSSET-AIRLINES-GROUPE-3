<?php

class TAeroport extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'aeroport';
	protected $_primary = 'trigramme';
	protected $_sequence = false;

	protected $_referenceMap = array(
		"Ville" => array(
			"columns" => "id_ville",
			"refTableClass" => "TVille")
		);
}
