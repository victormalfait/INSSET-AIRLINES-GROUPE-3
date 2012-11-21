<?php

class TDestination extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'destination';
	protected $_primary = 'numero_vol';
	protected $_sequence = false;

	protected $_referenceMap = array(
		"Aeroport" => array(
			"columns" => "tri_aero_dep",
			"columns" => "tri_aero_arr",
			"columns" => "tri_aero_eff",
			"refTableClass" => "TAeroport")
		);
}
