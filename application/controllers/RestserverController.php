<?php

class RestserverController extends Zend_Controller_Action
{

    protected $_server;
    
    public function init() {

        $this->_server = new Zend_Rest_Server();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->viewRenderer->setNeverRender(true);
    }

    public function indexAction() {

        $this->_server->setClass('VolsClass');
        $this->_server->handle();
    }
}

class VolsClass {

    public function allVols($search) {

        //  http://inssetairline.fr/restserver/index?method=allVols&name=test

        $tableVol = new TVols;
        $vols = $tableVol->fetchAll();

        $tab_vol = array();
        $count = 0;
        foreach ($vols as $vol) {
            $tableDestination = new TDestination;
            $destination = $tableDestination->find($vol->id_destination)->current();

            $tableAeroport = new TAeroport;
            $aeroport = $tableAeroport->find($destination->tri_aero_dep)->current();
            $aeroportBis = $tableAeroport->find($destination->tri_aero_arr)->current();

            $tableVille = new TVille;
            $ville = $tableVille->find($aeroport->id_ville)->current();
            $villeBis = $tableVille->find($aeroportBis->id_ville)->current();

            $tablePays = new TPays;
            $pays = $tablePays->find($ville->id_pays)->current();
            $paysBis = $tablePays->find($villeBis->id_pays)->current();

            $tablePilote = new TPilote;
            $pilote = $tablePilote->find($vol->id_pilote)->current();
            $copilote = $tablePilote->find($vol->id_copilote)->current();

            $tableUtilisateur = new TUtilisateur;
            $utilisateur = $tableUtilisateur->find($pilote->id_utilisateur)->current();
            $utilisateurBis = $tableUtilisateur->find($copilote->id_utilisateur)->current();

            $tableAvion = new TAvion;
            $avion = $tableAvion->find($vol->immatriculation)->current();

            $tableModelAvion = new TModelAvion;
            $modelAvion = $tableModelAvion->find($avion->id_model)->current();

            $tab_vol[$count]['numero_vol'] = $destination->numero_vol;
            $tab_vol[$count]['depart'] = 'Le '.date("d/m/Y à H:i",$vol->heure_dep).' de '.$aeroport->nom_aeroport.'<br/>'.$ville->nom_ville.' ('.$pays->nom_pays.')';
            $tab_vol[$count]['arrive'] = 'Le '.date("d/m/Y à H:i",$vol->heure_arr).' de '.$aeroportBis->nom_aeroport.'<br/>'.$villeBis->nom_ville.' ('.$paysBis->nom_pays.')';
            $tab_vol[$count]['avion'] = $modelAvion->nom_model.' ('.$avion->immatriculation.')';
            $tab_vol[$count]['pilotes'] = 'Pilote: '.$utilisateur->nom_utilisateur.' '.$utilisateur->prenom_utilisateur.'<br/>Co-Pilote: '.$utilisateurBis->nom_utilisateur.' '.$utilisateurBis->prenom_utilisateur;
            $tab_vol[$count]['remarque'] = $vol->remarque;

            $count++;
        }
        return $tab_vol;

    }

}