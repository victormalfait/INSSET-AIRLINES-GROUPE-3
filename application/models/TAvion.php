<?php

class TAvion extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'avion';
	protected $_primary = 'immatriculation';
	protected $_sequence = false;

	protected $_referenceMap = array(
		"Model" => array(
			"columns" => "id_model",
			"refTableClass" => "TModelAvion")
		);
}
