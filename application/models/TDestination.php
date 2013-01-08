<?php

class TDestination extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'destination';
	protected $_primary = 'id_destination';

	protected $_referenceMap = array(
		"Aeroport" => array(
			"columns" => "tri_aero_dep",
			"columns" => "tri_aero_arr",
			"refTableClass" => "TAeroport")
		);
}
