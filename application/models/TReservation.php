<?php

class TReservation extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'reservation';
	protected $_primary = 'id_reservation';
	//protected $_sequence = false;

	protected $_referenceMap = array(
		"Vol" => array(
			"columns" => "id_vol",
			"refTableClass" => "TVols"),
		"Destination" => array(
			"columns" => "id_destination",
			"refTableClass" => "TDestination")
		);
}
