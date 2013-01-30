<?php

class TPilote extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'pilote';
	protected $_primary = 'id_pilote';

	protected $_referenceMap = array(
		"utilisateur" => array(
			"columns" => "id_utilisateur",
			"refTableClass" => "TUtilisateur")
		);

}
