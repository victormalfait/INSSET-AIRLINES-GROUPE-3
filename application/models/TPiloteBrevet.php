<?php

class TPiloteBrevet extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'pilote_brevet';
	protected $_primary = 'id_pilote_brevet';

	protected $_referenceMap = array(
		"Pilote" => array(
			"columns" => "id_pilote",
			"refTableClass" => "TPilote"),
		"Brevet" => array(
			"columns" => "id_brevet",
			"refTableClass" => "TBrevet")
		);
}
