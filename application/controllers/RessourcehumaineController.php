<?php

class RessourcehumaineController extends Zend_Controller_Action
{

	public function indexAction()
	{
		// on charge le model
        $tableUtilisateur = new TUtilisateur;

        // on envoi a la vue tout les pilote
        $this->view->utilisateurs = $tableUtilisateur->fetchAll();
	}

	public function editerAction()
	{
        // on recupere le numero de vol passer en parametre
        $idUtilisateur = $this->_request->getParam('idUtilisateur');

        // on l'envoi a la vue
        $this->view->idUtilisateur = $idUtilisateur;

        // creation de l'objet formulaire
        $formVol = new FNouveauvol;

        //On envoie les valeurs d'ID dans le formulaire
        $formVol->setNumeroVol($numero_vol);
        $formVol->init();

        $this->view->formNouveauVol = $formVol;

        // traitement du formulaire
        // si le formulaire a été soumis
        if ($this->_request->isPost()) {
            // on recupere les éléments
            $formData = $this->_request->getPost();
            // var_dump( $formVol->isValid($formData));

            // si le formulaire passe au controle des validateurs
            if ($formVol->isValid($formData)) {

                //on envoi la requete
                $destination = new TDestination;

                if(isset($numero_vol) && $numero_vol!=""){
                    $row = $destination->find($numero_vol)->current();
                }else{
                    $row = $destination->createRow();
                    $nbr_enr = count($destination->fetchAll());
                    $row->numero_vol = 'AI'.($nbr_enr+1);
                }

                $heure_dep = $formVol->getValue('timepickerdeb'.$numero_vol);
                $heure_arr = $formVol->getValue('timepickerfin'.$numero_vol);

                //On explose le format envoyé par les datepicker
                list($heureD, $minuteD) = explode(":", $heure_dep);
                list($heureF, $minuteF) = explode(":", $heure_arr);

                if($formVol->getValue('periodicite')=='Vol unique'){
                    $date_debut = $formVol->getValue('datepickerdeb'.$numero_vol);
                    $date_fin = $formVol->getValue('datepickerfin'.$numero_vol);
                    list($jourD, $moisD, $anneeD) = explode("-", $date_debut);
                    list($jourF, $moisF, $anneeF) = explode("-", $date_fin);      
                }else{
                    $jourD = 0;$jourF = 0;
                    $moisD = 0;$moisF = 0;
                    $anneeD = 0;$anneeF = 0;
                }
                $date_depart = mktime($heureD, $minuteD, 0,  $moisD, $jourD, $anneeD);
                $date_fin = mktime($heureF, $minuteF, 0, $moisF, $jourF, $anneeF);

                $row->tri_aero_dep = $formVol->getValue('aeroportDepart');
                $row->tri_aero_arr = $formVol->getValue('aeroportArrivee');
                $row->heure_dep = $date_depart;
                $row->heure_arr = $date_fin;
                $row->date_dep = $date_depart;
                $row->date_arr = $date_fin;
                $row->periodicite = $formVol->getValue('periodicite');
                
                //sauvegarde de la requete
                $result = $row->save();
        
                // RAZ du formulaire
                $formVol->reset();

                // $redirector = $this->_helper->getHelper('Redirector');
                // $redirector->gotoUrl('strategie/index');
            }
        }
	}

	public function supprimerAction()
	{
		
	}
}