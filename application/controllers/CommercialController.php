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
            if ($formCommercial->isValid($formData)) {

                $date_debut = $formCommercial->getValue('datepickerdeb');

                list($jourD, $moisD, $anneeD) = explode("-", $date_debut);          

                $date_depart = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);

                $date_depart_avant = $date_depart - 3*7*24*60*60;
                $date_depart_apres = $date_depart + 3*7*24*60*60;

                //on envoi la requete
                $tableAeroport = new TAeroport;

                $aeroportDepartRequest = $tableAeroport->select()->where('id_ville = ?', $formCommercial->getValue('aeroportDepart'));
                $aeroportArrivetRequest = $tableAeroport->select()->where('id_ville = ?', $formCommercial->getValue('aeroportArrive'));

                $aeroportDeparts = $tableAeroport->fetchAll($aeroportDepartRequest);
                $aeroportArrives = $tableAeroport->fetchAll($aeroportArrivetRequest);

                $table_content = array();
                $count = 0;

                foreach ($aeroportDeparts as $aeroportDepart) {
                    foreach ($aeroportArrives as $aeroportArrive) {
                        $tableDestination = new TDestination;
                        $destinationRequest = $tableDestination->select()->where('tri_aero_dep = ?', $aeroportDepart->trigramme)->where('tri_aero_arr = ?', $aeroportArrive->trigramme)->where('plannification = ?', 1);

                        $destinations = $tableDestination->fetchAll($destinationRequest);

                        foreach ($destinations as $destination) {
                            $tableVols = new TVols;
                            $volRequest = $tableVols->select()->where('id_destination = ?', $destination->id_destination);
                            $vols = $tableVols->fetchAll($volRequest);
                            foreach ($vols as $vol) {
                                if($vol->heure_dep >= $date_depart_avant && $vol->heure_dep <= $date_depart_apres){
                                    $table_content[$count]['numero_vol'] = $destination->numero_vol;
                                    $table_content[$count]['heure_dep'] = $vol->heure_dep;
                                    $table_content[$count]['heure_arr'] = $vol->heure_arr;
                                    $table_content[$count]['depart'] = $aeroportDepart->nom_aeroport;
                                    $table_content[$count]['arrive'] = $aeroportArrive->nom_aeroport;
                                    $count++;
                                }
                            }
                        }   
                    }
                }                

                $this->view->tabVol = $table_content;
                                                                          
                // RAZ du formulaire
                $formCommercial->reset();
            }
        }
    }

    public function catalogueAction ()
    {
    	
    }

}