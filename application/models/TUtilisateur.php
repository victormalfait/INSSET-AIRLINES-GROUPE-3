<?php

class TUtilisateur extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'utilisateur';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		"Service" => array(
			"columns" => "id_service",
			"refTableClass" => "TService")
		);
}
