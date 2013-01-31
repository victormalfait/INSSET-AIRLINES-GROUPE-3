<?php

class TTempsTravail extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'temps_travail';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		"Pilote" => array(
			"columns" => "id_pilote",
			"refTableClass" => "TPilote")
		);
}
