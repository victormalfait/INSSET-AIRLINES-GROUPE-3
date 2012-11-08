<?php

class FNouveauVol extends Zend_Form
{
	public function init()
	{
	
	//===============Parametre du formulaire
		$this->setMethod('post');
		$this->setAction('/strategie/index');
		$this->setAttrib('id', 'ConnexionNouveauVol');

	//===============Creation des element
		//Depart
		$ePaysDepart = new Zend_Form_Element_Select('paysDepart');
		$ePaysDepart	->setLabel('Pays : ')
						->setRequired(true)
						->addValidator('notEmpty');

		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
		$eAeroportDepart	->setLabel('Aeroport : ')
							->setRequired(true)
							->addValidator('notEmpty');
		
		$eDepartH = new Zend_Form_Element_Text('departH');
		$eDepartH	->setLabel('H')
					->setRequired(true)
					->addValidator('notEmpty');

		$eDepartM = new Zend_Form_Element_Text('departM');
		$eDepartM	->setLabel('M')
					->setRequired(true)
					->addValidator('notEmpty');

		//Arrivee
		$ePaysArrivee = new Zend_Form_Element_Select('paysArrivee');
		$ePaysArrivee	->setLabel('Pays : ')
						->setRequired(true)
						->addValidator('notEmpty');

		$eAeroportArrivee = new Zend_Form_Element_Select('aeroportArrivee');
		$eAeroportArrivee	->setLabel('Aeroport : ')
							->setRequired(true)
							->addValidator('notEmpty');
		
		$eArriveeH = new Zend_Form_Element_Text('arriveeH');
		$eArriveeH	->setLabel('H')
					->setRequired(true)
					->addValidator('notEmpty');

		$eArriveeM = new Zend_Form_Element_Text('arriveeM');
		$eArriveeM	->setLabel('M')
					->setRequired(true)
					->addValidator('notEmpty');

		// Périodicité
		$ePeriodicite = new Zend_Form_Element_Select('periodicite');
		$ePeriodicite	//->setLabel('Aeroport : ')
							->setRequired(true)
							->addValidator('notEmpty');

		// Terminer
		$eSubmit = new Zend_Form_Element_Submit('Enregistrer');

		// Ajout des éléments au formulaire
		$elements = array ( $ePaysDepart, $eAeroportDepart, $eDepartH, $eDepartM, $ePaysArrivee, $eAeroportArrivee, $eArriveeH, $eArriveeM, $ePeriodicite, $eSubmit );
		$this->addElements ( $elements );

		$this->addDisplayGroup(array(
			'ePaysDepart',
			'eAeroportDepart',
			'eDepartH',
			'eDepartM'
            ),'Depart',array('legend' => 'Départ'));
        
        $Depart = $this->getDisplayGroup('Depart');
        $Depart->setDecorators(array(
        
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
        ));

        $this->addDisplayGroup(array(
			'ePaysArrivee',
			'eAeroportArrivee',
			'eArriveeH',
			'eArriveeM'
            ),'Arrivee',array('legend' => 'Arrivée'));
        
        $Arrivee = $this->getDisplayGroup('Arrivee');
        $Arrivee->setDecorators(array(
        
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
        ));

        $this->addDisplayGroup(array(
			'periodicite'
            ),'periodicite',array('legend' => 'Périodicité'));
        
        $periodicite = $this->getDisplayGroup('periodicite');
        $periodicite->setDecorators(array(
        
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
        ));

		$this->addDisplayGroup(array(
			'Enregistrer'
            ),'Enregistrer',array('legend' => 'Terminer'));
        
        $Enregistrer = $this->getDisplayGroup('Enregistrer');
        $Enregistrer->setDecorators(array(
        
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
        ));

	}
}