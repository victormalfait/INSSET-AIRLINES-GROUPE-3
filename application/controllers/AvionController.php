<?php

class AvionController extends Zend_Controller_Action
{
	protected $_messenger = null;
	
    public function init()
    {
        /* Connection a la Base de Donnée en local */
    	// Va rechercher les info dans le application.ini
    	
    	echo "AvionCntroller.php";
    	
    	$dbavion = Zend_Db::factory(Zend_Registry::get('config')->database); 
    }
    public function indexAction()
    {
    	$this->view->messages = $this->_messenger->getMessage();
        $this->view->titre2 = "Gestion des Avions";
        $this->_forward('list');
    }
    public function listAction()
    {
    	$avionMapper = new Application_Model_Mapper_Avion();
    	
    	// création du paginator
    	Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator.phtml');
    	$listeAvion = $avionMapper->getAllAvion();
    	
    	$this->view->liste = $listeAvion;	
    	
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
        $this->view->titre2 = "Ajouter un avion";

        $listeService = $this->getListeService();
        $form = new Application_Form_avion(array(
                    'listeService' => $listeService
                ));
        $form->setMethod('post');

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

            // Message d'erreur à faire
            $this->_redirector->gotoSimple('index', null, null, array());
        }

        $this->view->form = $form;
    }*/


}

