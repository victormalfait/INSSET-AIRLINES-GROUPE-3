<?php

class TTempsTravail extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'temps_travail';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		"Pilote" => array(
			"columns" => "id_pilote",
			"columns" => "id_copilote",
			"refTableClass" => "TPilote"),
		"Avion" => array(
			"columns" => "immatriculation",
			"refTableClass" => "TAvion"),
		"Destination" => array(
			"columns" => "id_destination",
			"refTableClass" => "TDestination")
		);
}
