<?php

class TModelAvion extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'model_avion';
	protected $_primary = 'nom_model';
	protected $_sequence = false;

	protected $_referenceMap = array(
		"Brevet" => array(
			"columns" => "nom_brevet",
			"refTableClass" => "TBrevet")
		);
}
