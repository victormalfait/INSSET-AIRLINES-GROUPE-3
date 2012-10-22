<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Connection a la Base de Donnée en local */
    	
    	// Va rechercher les info dans le application.ini
    	$db = Zend_Db::factory(Zend_Registry::get('config')->database); 
    	
    	
    	$sql = "select * from pilote";
    	$result = $db->fetchAll($sql);
    	Zend_Debug::dump($result); // affiche la liste des pilotes
    	
    	
    }

    public function indexAction()
    {
        // action body
    }


}

