<?php

class RestserverController extends Zend_Controller_Action
{

    protected $_server;
    //  http://inssetairline.fr/restserver/index?method=allVols&name=test
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

    public function allVols($name) {

        
        $tableVol = new TVols;
        $vol = $tableVol->fetchAll()->toArray();

        foreach ($vol as $UnVol) {
            $listvol$UnVol[$UnVol['[id_vols']] = array('immatriculation' => $UnVol['immatriculation'],
                             'heure_dep' => $UnVol['heure_dep'],
                             'heure_arr' => $UnVol['heure_arr'],
                             'remarque' => $UnVol['remarque'],
                             );
        }

        return   $listvol  ;

    }

}