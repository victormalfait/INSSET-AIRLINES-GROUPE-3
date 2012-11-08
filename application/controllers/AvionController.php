<?php

class AvionController extends Zend_Controller_Action
{
	//protected $_messenger = null;
	
    public function init()
    {
        /* Connection a la Base de Donnée en local */
    	// Va rechercher les info dans le application.ini
    	
    	//$dbavion = Zend_Db::factory(Zend_Registry::get('config')->database); 
    	
    }
    public function indexAction()
    {
    	/*
    	$this->view->messages = $this->_messenger->getMessage();
        $this->view->titre2 = "Gestion des Avions";
        $this->_forward('list');*/
        
    }
    public function listAction()
    {
    	
    	/*
    	$tableAvion = new TAvion;
    	
    	$avions = $tableAvion->fetchAll();
    	foreach ($avions as $avion)
    	{
    		echo $avion->id_name_modelle_avion . '<br/>';
    		echo $avion->immatriculation . '<br/>';
    		echo $avion->heures_vol_total . '<br/>';
    		echo $avion->heures_depuis_revision . '<br/>';
    		
    	}
    	
    	*/
    			
    }
    
    public function supprimerAction()
    {
    	/*
        $id = $this->_getParam('id');
        $avionMapper = new Application_Model_Mapper_Avion;

        if (($avion = $avionMapper->getavionById($id))) {
            try {
                $avionMapper->delete($avion);
                $this->_messenger->setMessage('avion supprimé');
            } catch (Zend_Db_Exception $e) {
                $this->_messenger->setMessage('Erreur');
            }
        } else {
            $this->_messenger->setMessage('Erreur id');
        }
        
        $this->_redirector->gotoSimple('index', null, null, array());*/
    }

    public function ajouterAction()
    {
        /*
    	$FormAjout = new Zend_Form;
    	
    	//paramétres du formulaire
    	$FormAjout->setMethod('post');
    	$FormAjout->setAction('/index/#');
    	$FormAjout->setAttrib('id', 'monformulaire');
    	
    	//creation élément formulaire
    	$eModelle = new Zend_Form_Element_Select('listmodelle');
    	$eModelle->setLabel('Liste des Modelles : ');
    	$eModelle->setMultiOptions(array('1' => 'Bohing 437', '2' => 'Bohing 499'));
    	
    	$eImmatriculation = new Zend_Form_Element_Text('Immatriculation');
    	$eImmatriculation->setLabel('Liste des Modelles : ');
    	$eImmatriculation->setValue(array('X678F'));

    	
    	$eHeuresTotal = new Zend_Form_Element_Text('HeuresTotal');
    	$eHeuresTotal->setLabel('Heures Total : ');
    	$eHeuresTotal->setValue('0');
    	
    	$eHeuresRevision = new Zend_Form_Element_Text('Immatriculation');
    	$eHeuresRevision->setLabel('Heures depuis la Revision : ');
    	$eHeuresRevision->setValue('0');
        
    	$eSubmit = new Zend_Form_Element_Submit('Envoyer');
    	
    	$FormAjout->addElement($eModelle);
    	$FormAjout->addElement($eImmatriculation);
    	$FormAjout->addElement($eHeuresTotal);
    	$FormAjout->addElement($eHeuresRevision);
    	$FormAjout->addElement($eSubmit);
    	
    	echo $FormAjout;

        // Vérification de l'envoi du formulaire
        if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
            $values = $form->getValues();
            $avion = new Application_Model_Avion;
            $avion->setIdModelle($values['id_name_modelle_avion']);
            $avion->setImmatriculation($values['Immatriculation']);
            $avion->setHeuresTotal($values['heures_vol_total']);
            $avion->setHeuresRevision($values['heures_depuis_revision']);
            $avionMapper = new Application_Model_Mapper_Avion;
            try {
                $avionMapper->save($avion);
                $this->_messenger->setMessage('Avion ajouté', 'succes');
            } catch (Zend_Db_Exception $e) {
                $this->_messenger->setMessage('Erreur');
            }
        }*/
    }


}

