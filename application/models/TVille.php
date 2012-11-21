<?php

class TVille extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'ville';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		"Pays" => array(
			"columns" => "id_ville",
			"refTableClass" => "TVille")
		);
}
