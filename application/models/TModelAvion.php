<?php

class TModelAvion extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'model_avion';
	protected $_primary = 'id_model';

	protected $_referenceMap = array(
		"Brevet" => array(
			"columns" => "id_brevet",
			"refTableClass" => "TBrevet")
		);
}
