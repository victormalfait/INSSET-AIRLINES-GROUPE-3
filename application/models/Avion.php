<?php

/**
 * Classe métier des Avions
 *  
 * @package Application_Model
 * @copyright INSSET Projet 
 * 
 */
class Application_Model_Avion
{
    protected $_id_name_modelle_avion;
    protected $_immatriculation;
    protected $_heures_vol_total;
    protected $_heures_depuis_revision;
    
    public function getIdModelle()
    {
        return $this->_id_name_modelle_avion;
    }
    public function getImmatriculation()
    {
        return $this->_immatriculation;
    }
    public function getHeuresTotal()
    {
        return $this->_heures_vol_total;
    }
    public function getHeuresRevision()
    {
    	return $this->_heures_depuis_revision;
    }

    public function setIdModelle($idmodelle = null)
    {
        $this->_id_name_modelle_avion = (string) $idmodelle;
        return $this;
    }

    public function setImmatriculation($immatriculation = null)
    {
        $this->_immatriculation = (string) $immatriculation ;
        return $this;
    }
    
    
    public function setHeuresTotal($heurestotal = null)
    {
    	$this->_heures_vol_total = (int) $heurestotal ;
    	return $this;
    }
    public function setHeuresRevision($heuresrevision = null)
    {
    	$this->_heures_depuis_revision = (int) $heuresrevision ;
    	return $this;
    }  

     public function getArrayFromItem()
    {
        return array(
            'idmodelle'=> $this->_id_name_modelle_avion,
            'immatriculation' => $this->_immatriculation,
            'heurestotal' => $this->_heures_vol_total,
            'heuresrevision' => $this->_heures_depuis_revision
        );
    }
}