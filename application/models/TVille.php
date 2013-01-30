<?php

class TVille extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'ville';
	protected $_primary = 'id_ville';

	protected $_referenceMap = array(
		"Pays" => array(
			"columns" => "id_pays",
			"refTableClass" => "TPays")
		);
}
