<?php

class FPlannifier extends Zend_Form
{
	private $id_destination;
 
	public function init()
	{
		$id_destination = $this->getIdDestination();
		//===============Parametre du formulaire
		$this->setName('plannifier');
		$this->setMethod('post');
		$this->setAction('/planning/plannifier/id_destination/'.$id_destination);
		$this->setAttrib('id', 'FPlannifier');

	//=============== creation des decorateurs
		// Descativer les decorateurs par defaut
		$this->clearDecorators();

		$decorators = array(
		    array('ViewHelper'),
		    array('Errors'),
		    array('Label', array('class' => 'label')),
		    array('HtmlTag', array('tag' => 'li'))
		);

		// decorateur d'element bouton
		$decoratorsBouton = array(
		    'ViewHelper',
		    'Errors',
		    array('Label', array('class' => 'label submit')),
		    array('HtmlTag', array('tag' => 'li'))
		);

		//decorateur de formulaire
		$decoratorsForm = array(
			'FormElements',
		    array('Errors', array('class' => "error")),
			array('HtmlTag', array('tag' => 'ul')),
			array(
				array('DivTag' => 'HtmlTag'),
				array('tag' => 'div')
			),
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

		$eSubmit = new Zend_Form_Element_Submit('BTNPlannifier');
		$eSubmit 	->setAttrib('id', 'BTNPlannifier')
					->setLabel('Plannifier')
					->setDecorators($decoratorsBouton);

		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'close')
					->setDecorators($decoratorsBouton);

		$elements = array($eAvion, $ePilote, $eCoPilote, $eSubmit, $eFermer);
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