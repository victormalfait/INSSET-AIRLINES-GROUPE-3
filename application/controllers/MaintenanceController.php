<?php

class MaintenanceController extends Zend_Controller_Action
{
	public function indexAction()
    {
    	$tableAvion = new TAvion;
		$avion = $tableAvion->fetchAll();
		$this->view->avion = $avion;
		
    }
    public function afficherAction()
    {

    }
    public function ajouterAction()
    {
		$immatriculation = $this->_getParam('immatriculation');

		$form = new FMaintenance;
		$this->view->formMaintenance= $form;

		if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();

            if ($form->isValid($formData)) {

					$Time_Revision 	= $form->getValue('Time_Revision');
					$Maint_Debut 	= $form->getValue('Maint_Debut');

	            	$tableMaintenance = new TMaintenance;
	            	$row = $tableMaintenance->createRow(); 

	            		/* Router => une verif si pas un autre avion deja maintenace  en cour a cette date*/
	            		
					$row->immatriculation  	= $immatriculation;
					$row->date_prevue 	 	= $Maint_Debut;
					$row->duree_prevue  	= $Time_Revision;
					$row->date_eff  		= 0;
					$row->duree_eff 	 	= 0;

	                $result = $row->save();
	                $form->reset();

	                $redirector = $this->_helper->getHelper('Redirector');
	                $redirector->gotoUrl('maintenance/index');
	            }else{
	            	echo 'Veillez remplire tout les champs , merci !';
	            }
        }
    }
    public function delAction()
    {

    }
    
}