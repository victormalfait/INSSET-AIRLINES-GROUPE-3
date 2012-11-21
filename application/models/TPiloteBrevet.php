<?php

class TPiloteBrevet extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'pilote_brevet';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		"Pilote" => array(
			"columns" => "id_pilote",
			"refTableClass" => "TPilote"),
		"Brevet" => array(
			"columns" => "nom_brevet",
			"refTableClass" => "TBrevet")
		);
}
