<?php

class TVols extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'vols';
	protected $_primary = 'id_vols';

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
