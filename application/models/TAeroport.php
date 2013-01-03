<?php

class TAeroport extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'aeroport';
	protected $_primary = 'trigramme';
	protected $_sequence = false;

	protected $_referenceMap = array(
		"Ville" => array(
			"columns" => "id_ville",
			"refTableClass" => "TVille")
		);


	public function selectRecherche ($critere)
	{
        $tableAeroport = new TAeroport;

		// pays
		if (!$critere['paysDepart'] == "") {
			$reqAeroport = $tableAeroport   ->select()
	                                        ->setIntegrityCheck(false)
	                                        ->from(array('a' => 'aeroport'))
	                                        ->join(array('v' => 'ville'), 'a.id_ville = v.id_ville')
	                                        ->join(array('p' => 'pays'), 'v.id_pays = p.id_pays')
	                                        ->where('p.id_pays = ?', $critere['paysDepart']);
		}
 
		// ville
		if (!$critere['villeDepart'] == "") {
    	    $reqAeroport = $tableAeroport   ->select()
	                                        ->setIntegrityCheck(false)
	                                        ->from(array('a' => 'aeroport'))
	                                        ->join(array('v' => 'ville'), 'a.id_ville = v.id_ville')
	                                        ->join(array('p' => 'pays'), 'v.id_pays = p.id_pays')
	                                        ->where('v.id_ville = ?', $critere['villeDepart']);
		}
 
		// aeroport
		if (!$critere['aeroportDepart'] == "") {
        	$reqAeroport = $tableAeroport   ->select()
	                                        ->setIntegrityCheck(false)
	                                        ->from(array('a' => 'aeroport'))
	                                        ->join(array('v' => 'ville'), 'a.id_ville = v.id_ville')
	                                        ->join(array('p' => 'pays'), 'v.id_pays = p.id_pays')
	                                        ->where('a.trigramme = ?', $critere['aeroportDepart']);
		}

        return $aeroport = $tableAeroport->fetchAll($reqAeroport);
	}
}
