<?php

class CommercialController extends Zend_Controller_Action
{

	public function indexAction()
    {
    	// on charge le formulaire FCommercial
    	$formCommercial = new FCommercial;

    	// on l'envoi à la vu
    	$this->view->formCommercial = $formCommercial;

        if ($this->_request->isPost()) {
                // on recupere les éléments
                $formData = $this->_request->getPost();

                // si le formulaire passe au controle des validateurs
                if ($form->isValid($formData)) {

                    //on envoi la requete
                    $tableVols = new TVols;

                    $date_debut = $formCommercial->getValue('datepickerdeb');
                    $date_fin = $formCommercial->getValue('datepickerfin');
                    list($jourD, $moisD, $anneeD) = explode("-", $date_debut);
                    list($jourF, $moisF, $anneeF) = explode("-", $date_fin);           

                    $date_depart = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);
                    $jour_depart = date('N',$date_depart);
                    $date_fin = mktime(0, 0, 0, $moisF, $jourF, $anneeF); 
                    $ville_depart = $formCommercial->getValue('aeroportDepart');
                    $ville_arrivee = $formCommercial->getValue('aeroportArrivee');
                    $type_trajet = $formCommercial->getValue('typeTrajet');
                    $nbr_place = $formCommercial->getValue('nbrPassager');
                    $type_passager = $formCommercial->getValue('typePassager');
                    $type_classe = $formCommercial->getValue('classe');

                    $vols = $tableVols->fetchAll();

                    $tableDestination = new TDestination;
                    $table_content = array();
                    $count = 0;

                    foreach ($vols as $value) {
                        $destiantionRequest = $tableDestination->select()->from()
                                                                         ->where('id_destination ='.$value['id_destination'])
                                                                         ->where('date_dep >='.$date_depart.'or periodicite = '.$jour_depart);
                        $destination = $tableDestination->fetchAll($destiantionRequest); 
                        $table_content[$count]['etat'] = $event->etat_tache;
                         
                    }
                                                                 
                    // RAZ du formulaire
                    $formCommercial->reset();
                }
            }
    }

    public function catalogueAction ()
    {
    	
    }

}