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
                $list ['aeroport'][$row->trigramme] = $row->nom_ville . ' - ' . $row->nom_aeroport;
            }
 
            // Je renvoie ce tableau à ma vue au format JSON
            $this->_helper->json($list, array( 'enableJsonExprFinder' => true ));
    }

    public function piloteplanningAction() 
    {
            // Récupération des paramètres passés dans la requête
            $params = $this->_request->getParams();
            
            // Appel de mon modèle de table
            $tableAvion = new TAvion;
            $avion = $tableAvion->find($params)->current();

            $tableModelAvion = new TModelAvion;
            $modelAvion = $tableModelAvion->find($avion->id_model)->current();

            $tablePiloteBrevet = new TPiloteBrevet;
            $piloteBrevetRequest = $tablePiloteBrevet->select()->where('id_brevet = ?', $modelAvion->id_brevet);
            $piloteBrevets = $tablePiloteBrevet->fetchAll($piloteBrevetRequest);

            // Transforme mes données en tableau PHP 
            $list = array();
            foreach ($piloteBrevets as $piloteBrevet) {
                $tablePilote = new TPilote;
                $pilote = $tablePilote->find($piloteBrevet->id_pilote)->current();
                $utilisateur = $pilote->findParentRow('TUtilisateur');
                $list ['pilote'][$pilote->id_pilote] = $utilisateur->nom_utilisateur . ' ' . $utilisateur->prenom_utilisateur;
            }
 
            // Je renvoie ce tableau à ma vue au format JSON
            $this->_helper->json($list, array( 'enableJsonExprFinder' => true ));
    }


    public function copiloteplanningAction() 
    {
            // Récupération des paramètres passés dans la requête
            $params = $this->_request->getParams();

            // Appel de mon modèle de table
            $tableAvion = new TAvion;
            $avion = $tableAvion->find($params['immatriculation'])->current();

            $tableModelAvion = new TModelAvion;
            $modelAvion = $tableModelAvion->find($avion->id_model)->current();

            $tablePiloteBrevet = new TPiloteBrevet;
            $piloteBrevetRequest = $tablePiloteBrevet->select()->where('id_brevet = ?', $modelAvion->id_brevet)->where('id_pilote != ?',$params['id_pilote']);
            $piloteBrevets = $tablePiloteBrevet->fetchAll($piloteBrevetRequest);

            // Transforme mes données en tableau PHP 
            $list = array();
            foreach ($piloteBrevets as $piloteBrevet) {
                $tablePilote = new TPilote;
                $piloteRequest = $tablePilote->select()->where('id_pilote = ?',$piloteBrevet->id_pilote);
                $pilote = $tablePilote->fetchRow($piloteRequest);
                $utilisateur = $pilote->findParentRow('TUtilisateur');
                if(isset($pilote->id_pilote) && $pilote->id_pilote != ''){
                    $list ['copilote'][$pilote->id_pilote] = $utilisateur->nom_utilisateur . ' ' . $utilisateur->prenom_utilisateur;
                }
            }
 
            // Je renvoie ce tableau à ma vue au format JSON
            $this->_helper->json($list, array( 'enableJsonExprFinder' => true ));
    }
 
}