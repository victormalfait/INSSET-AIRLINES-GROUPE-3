<?php

class AjaxController extends Zend_Controller_Action 
{
	public function init ()
	{
        //si requete ajax on désactive les layouts et la vue pour une réponse JSON
        if($this->_request->isXmlHttpRequest()){
	        $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->viewRenderer->setNeverRender(true);
        }
	}


    public function remplirAction() 
    {
        	// Récupération des paramètres passés dans la requête
            $params = $this->_request->getParams();

            // Appel de mon modèle de table
            $tableAeroport = new TAeroport;
            // Extraction selon les critères de recherche des différents paramètres
            $Rows = $tableAeroport->selectRecherche($params);

            // Transforme mes données en tableau PHP 
            $list = array();
            foreach ($Rows as $row) {
            	$list ['pays'][$row->id_pays] = $row->nom;
                $list ['ville'][$row->id_ville]  = $row->nom;
                $list ['aeroport'][$row->trigramme] = $row->nom;
            }
 
            echo $Rows;
            // Je renvoie ce tableau à ma vue au format JSON
            $this->_helper->json($list, array( 'enableJsonExprFinder' => true ));
    }
 
}