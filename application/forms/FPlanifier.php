<?php

class FPlanifier extends Zend_Form
{
	private $id_destination;
 
	public function init()
	{
		$id_destination = $this->getIdDestination();
		//===============Parametre du formulaire
		$this->setName('planifier');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FPlanifier');

	//=============== creation des decorateurs
		// Descativer les decorateurs par defaut
		$this->clearDecorators();

		$decorators = array(
		    array('ViewHelper', array('tag' => 'div')),
		    array('Errors'),
		    array('Label', array('tag' => 'div', 'class' => 'label')),
		    array('HtmlTag', array('tag' => 'div', 'class' => 'identite'))
		);

		// decorateur d'element bouton
		$decoratorsBouton = array(
		    'ViewHelper',
		    'Errors',
		    array('Label', array('tag' => 'div', 'class' => 'label submit')),
		    array('HtmlTag', array('tag' => 'div', 'class' => 'identite'))
		);

		//decorateur de formulaire
		$decoratorsForm = array(
			'FormElements',
		    array('Errors', array('class' => "error")),
			'Form'
		);

		// on insere le decorateur de form au formulaire
		$this->setDecorators($decoratorsForm);

		$piloteTab["-1"] = "-- Choisissez --";


	//=============== Creation des element

		$eAvion = new Zend_Form_Element_Select('avion');
		$eAvion	->setLabel('Avion')
        		->setMultiOptions($this->listAvion())
	            ->setDecorators($decorators);

		$ePilote = new Zend_Form_Element_Select('pilote');
		$ePilote->setLabel('Pilote')
				->setMultiOptions($piloteTab)
				->setRegisterInArrayValidator(false)
				->setDecorators($decorators);

		$eCoPilote = new Zend_Form_Element_Select('copilote');
		$eCoPilote->setLabel('Co-Pilote')
				  ->setMultiOptions($piloteTab)
				  ->setRegisterInArrayValidator(false)
				  ->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('BTNPlanifier');
		$eSubmit 	->setLabel('Terminer')
					->setAttrib('class', 'bgRedBtn')
					->setAttrib('style', 'float:none;')
					->setDecorators($decoratorsBouton);

		$elements = array($eAvion, $ePilote, $eCoPilote, $eSubmit);
		$this->addElements($elements);
	}

	public function setIdDestination($id_destination) {
		$this->id_destination = $id_destination;
	}

	public function getIdDestination() {
		return $this->id_destination;
	}

	private function listAvion () {
		$id_destination = $this->getIdDestination();
		$avionTab = array();
		$avionTab["-1"] = "-- Choisissez --"; 
		if(isset($id_destination) && $id_destination!=''){
			// on charge le model
			$tableDestination = new TDestination;
			$destination = $tableDestination->find($id_destination)->current();

			$tableAeroport = new TAeroport;
			$aeroport_dep = $tableAeroport->find($destination->tri_aero_dep)->current();
			$aeroport_arr = $tableAeroport->find($destination->tri_aero_arr)->current();

			$tableModelAvion = new TModelAvion;
			$modelAvionRequest = $tableModelAvion->select()->where('rayon_action > ?', $destination->distance)
														   ->where('longueur_piste < ?',$aeroport_dep->longueur_piste)
														   ->where('longueur_piste < ?',$aeroport_arr->longueur_piste);

			$modelAvion = $tableModelAvion->fetchAll($modelAvionRequest);

			$tableAvion = new TAvion;			

			foreach ($modelAvion as $modelAvions) {
				$avionRequest = $tableAvion->select()->where('id_model = ?', $modelAvions->id_model);
				$avions = $tableAvion->fetchAll($avionRequest);

				foreach ($avions as $avion) {
					$avionTab[$avion->immatriculation] = $modelAvions->nom_model;
				}
				
			}
		}
        return $avionTab;
    }
}