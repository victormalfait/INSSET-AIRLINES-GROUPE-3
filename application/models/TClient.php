<?php

class TClient extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'client';
	protected $_primary = 'id_client';
	//protected $_sequence = false;

	protected $_referenceMap = array(
		"Model" => array(
			"columns" => "id_reservation",
			"refTableClass" => "TReservation")
		);
}
