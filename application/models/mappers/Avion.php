<?php

class Application_Model_Mapper_Avion extends Application_Model_Mapper
{

    protected function _createItemFromRow(Zend_Db_Table_Row $row)
    {
        $item = new Application_Model_Avion();
        $item->setIdModelle($row->idmodelle)
             ->setImmatriculation($row->immatriculation)
             ->setHeuresTotal($row->heurestotal)
             ->setHeuresRevision($row->heuresrevision);
        return $item;
    }

    protected function _getDataArrayFromItem($item)
    {
        return array(
        		'idmodelle'=> $item->getIdModelle(),
        		'immatriculation' => $item->getImmatriculation(),
        		'heurestotal' => $item->getHeuresTotal(),
        		'heuresrevision' => $item->getHeuresRevision()
        );
    }

    public function getAllAvion()
    {
        return($this->fetchAll());
    }

}